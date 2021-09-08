app.controller("barcodeReportCtrl", function ($scope,$http,$filter,$rootScope,dateFilter,$timeout,$interval,$window) {
    $scope.msg = "This is all barcode report controller";

    $scope.tab = 1;
    $scope.sort = {
        active: '',
        descending: undefined
    };



    $scope.changeSorting = function(column) {
        var sort = $scope.sort;

        if (sort.active == column) {
            sort.descending = !sort.descending;
        }
        else {
            sort.active = column;
            sort.descending = false;
        }
    };
    $scope.getIcon = function(column) {
        var sort = $scope.sort;

        if (sort.active == column) {
            return sort.descending
                ? 'glyphicon-chevron-up'
                : 'glyphicon-chevron-down';
        }

        return 'glyphicon-star';
    };

    $scope.setTab = function(newTab){
        $scope.tab = newTab;
    };

    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
        if(newTab==1){
            $scope.isUpdateableFr=false;
        }
    };

    $scope.selectedTab = {
        "color" : "white",
        "background-color" : "#655D5D",
        "font-size" : "15px",
        "padding" : "5px"
    };

    $scope.getTerminalList=function () {
        var request = $http({
            method: "post",
            url: site_url+"/CustomerSalesReport/get_terminal_list",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.terminalList=response.data.records;
        });
    };
    $scope.getTerminalList();




    $scope.selectDate=true;
    $scope.winning_date=$filter('date')(new Date(), 'dd.MM.yyyy');
    $scope.start_date=new Date();
    $scope.end_date=new Date();
    $scope.barcode_report_date=new Date();
    $scope.changeDateFormat=function(userDate){
        return moment(userDate).format('YYYY-MM-DD');
    };

    $scope.isLoading=false;
    $scope.isLoading2=false;

    // get total sale report for 2d game
    $scope.alertMsg2=true;
    $scope.alertMsgCard=true;





    $scope.$watch("barcodeWiseReport", function(newValue, oldValue){

        if(newValue != oldValue){
            var result=alasql('SELECT sum(amount) as total_amount,sum(prize_value) as total_prize_value  from ? ',[newValue]);
            $scope.barcodeWiseReportFooter=result[0];
        }
    });

    $scope.$watch("cardSaleReport", function(newValue, oldValue){

        if(newValue != oldValue){
            var result=alasql('SELECT sum(amount) as total_amount,sum(commision) as total_commision,sum(prize_value) as total_prize_value,sum(net_payable) as total_net_payable  from ? ',[newValue]);
            $scope.cardSaleReportFooter=result[0];
        }
    });


    $scope.gameList = [
        {id : 1, name : "2D"},
        {id : 2, name : "Card"}
    ];
    $scope.select_game=$scope.gameList[0];

    // get two digit draw time list
    $scope.getDrawList=function (gameNo) {
        if(gameNo==1){
            var request = $http({
                method: "post",
                url: site_url+"/BarcodeReport/get_2d_draw_time",
                data: {}
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.drawTime=response.data.records;
            });
        }
        if(gameNo==2){
            var request = $http({
                method: "post",
                url: site_url+"/BarcodeReport/get_card_draw_time",
                data: {}
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.drawTime=response.data.records;
            });
        }
    };
    $scope.getDrawList($scope.select_game.id);
    $scope.select_draw_time=0;


    $scope.barcodeType = [
        {id : 1, type : "All barcode"},
        {id : 2, type : "Winning barcode"}
    ];
    $scope.select_barcode_type=$scope.barcodeType[0];
    $scope.select_terminal=0;



    // get terminal report order by barcode
    $scope.showbarcodeReport=[];
    $scope.getAllBarcodeDetailsByDate=function (start_date,select_game,select_terminal,select_draw_time) {

        $scope.isLoading2=true;
        var start_date=$scope.changeDateFormat(start_date);
        $scope.x=select_draw_time;
        if(select_game==1){
            var address="/BarcodeReport/get_2d_report_order_by_barcode";
        }
        if(select_game==2){
            var address="/BarcodeReport/get_card_report_order_by_barcode";
        }
        var request = $http({
            method: "post",
            url: site_url + address,
            data: {
                start_date: start_date
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.barcodeWiseReport=response.data.records;
            $scope.isLoading2=false;


            if(select_draw_time>0){
                $scope.x=parseInt($scope.x);
                $scope.barcodeWiseReport=alasql("SELECT *  from ? where draw_master_id=?",[$scope.barcodeWiseReport,$scope.x]);
            }



            if(select_terminal!=0){
                $scope.barcodeWiseReport=alasql("SELECT *  from ? where user_id=?",[$scope.barcodeWiseReport,select_terminal]);
            }

            // checking for data
            if($scope.barcodeWiseReport.length==0){
                $scope.alertMsg2=true;
            }else{
                $scope.alertMsg2=false;
            }

        });

    };

    
   // $scope.getAllBarcodeDetailsByDate($scope.start_date,$scope.gameList[0].id,0,0);


    $scope.showParticulars=function (target) {
        $scope.target=target;
    };

    $scope.claimedBarcodeForPrize=function (barcodeDetails,game_id) {
        var request = $http({
            method: "post",
            url: site_url+"/ReportTerminal/insert_claimed_barcode_details",
            data: {
                barcode: barcodeDetails.barcode
                ,game_id:game_id
                ,prize_value:barcodeDetails.prize_value
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.claimReport=response.data.records;
            if($scope.claimReport.success==1){
                alert("Thanks for the  claim");
                barcodeDetails.is_claimed=1;
            }
        });
    };
     $scope.logoutCpanel=function () {
     	
        var request = $http({
            method: "post",
            url: site_url+"/Admin/logout_cpanel",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $window.location.href = base_url+'#!';
        });
    };

});

