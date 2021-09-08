app.controller("manualResultCtrl", function ($scope,$http,$filter,$rootScope,dateFilter,$timeout,$interval,$window) {
    $scope.msg = "This is manualResultCtrl controller";
    $scope.tab = 1;
    $scope.sort = {
        active: '',
        descending: undefined
    };
    $scope.findObjectByKey = function(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return array[i];
            }
        }
        return null;
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
        "background-color" : "coral",
        "font-size" : "15px",
        "padding" : "5px"
    };

   $scope.gameList=[{game_id: 1,game_name: "2 DIGIT"},{game_id: 2,game_name: "12 CARD"}];



    $scope.saveStockistRechargeData=function (limit) {
        var stockist_id=limit.stockist.stockist_id;
        var amount= limit.amount;
        var request = $http({
            method: "post",
            url: site_url+"/StockistLimit/save_stockist_recharge_details",
            data: {
                stockist_id: stockist_id
                ,amount : amount
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.stockistRechargeReport=response.data.records;

            if($scope.stockistRechargeReport.success==1){
                $scope.updateableStockistIndex=0;
                $scope.submitStatus = true;
                $scope.isUpdateable=true;
                alert("Current Balance is " + $scope.stockistRechargeReport.current_balance);
                $timeout(function() {
                    $scope.submitStatus = false;
                }, 4000);
                // $scope.stockistList.unshift($scope.stockist);
                $scope.stockistForm.$setPristine();
            }

        });
    };

    $scope.defaultLimit={};
    $scope.limit=angular.copy($scope.defaultLimit);

    $scope.getPlaySeries=function () {
        var request = $http({
            method: "post",
            url: site_url+"/ManualResult/get_all_series",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.seriesList=response.data.records;
        });
    };
    $scope.getPlaySeries();

    $scope.getDigitDrawTime=function () {
        var request = $http({
            method: "post",
            url: site_url+"/ManualResult/get_all_digit_draw_time",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.digitDrawTime=response.data.records;
        });
    };
    $scope.getDigitDrawTime();

    $scope.getCardDrawTime=function () {
        var request = $http({
            method: "post",
            url: site_url+"/ManualResult/get_all_card_draw_time",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.cardDrawTime=response.data.records;
        });
    };
    $scope.getCardDrawTime();



    var request = $http({
        method: "post",
        url: site_url+"/PayoutSettings/get_game2_payout",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.cardPayOut=response.data.records[0];
    });

    $scope.manualData={};

    $scope.cardCombination=["JC","JD","JS","JH","QC","QD","QS","QH","KC","KD","KS","KH"];


    $scope.setMrp=function(manualData){
        if(manualData.game.game_id==1){
            $scope.manualData.mrp=manualData.series.mrp;
        }
        if(manualData.game.game_id==2){
            $scope.manualData.mrp=$scope.cardPayOut.mrp;
        }
    };

    $scope.showTimeList={};
    $scope.setTime=function(manualData){
        if(manualData.game.game_id==1){
        	$scope.getDigitDrawTime();
            $scope.showTimeList=angular.copy($scope.digitDrawTime);
        }
        if(manualData.game.game_id==2){
        	$scope.getCardDrawTime();
            $scope.showTimeList=angular.copy($scope.cardDrawTime);
        }
    };




    $scope.submitManualResult=function(manualResult){
        var master={};
        if(manualResult.game.game_id==1){
            master.game_id=manualResult.game.game_id;
            master.play_series_id=manualResult.series.play_series_id;
            master.draw_master_id=manualResult.time.draw_master_id;
            master.result=manualResult.result;
        }
        if(manualResult.game.game_id==2){
            master.game_id=manualResult.game.game_id;
            master.card_draw_master_id=manualResult.time.card_draw_master_id;
            master.result=manualResult.result.toLowerCase();
            alert(master.result);
        }

        if(master.game_id==1){
            var request = $http({
                method: "post",
                url: site_url+"/ManualResult/get_digit_manual_result",
                data: {
                    master: master
                }
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.manualResultReport=response.data.records;
                if($scope.manualResultReport.success==1){
                    $scope.submitStatus=true;
                    $timeout(function() {
                        $scope.submitStatus = false;
                    }, 5000);
                }
            });
        }

        // insert result maually for 12 card
        if(master.game_id==2){
            var request = $http({
                method: "post",
                url: site_url+"/ManualResult/get_twelve_card_manual_result",
                data: {
                    master: master
                }
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.manualResultReport=response.data.records;
                if($scope.manualResultReport.success==1){
                    $scope.submitStatus=true;
                    $timeout(function() {
                        $scope.submitStatus = false;
                    }, 5000);
                }
            });
        }

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

