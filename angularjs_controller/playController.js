app.controller("playCtrl", function ($scope,$http,$filter,$timeout,dateFilter,$interval,$rootScope,$window) {
    $scope.msg = "This is play controller";
    $scope.submitStatus=false;
    $scope.showResultSheet=false;
    $scope.game100=true;
    $scope.game12=false;
    $scope.tenDigitDrawTime=true;
    $scope.gameNumber=1;
    $scope.selectCardGameDate=false;        //show div to select date for card result//
    $scope.defaultCardResult=true;
    
   
    $scope.getUserData=function(){
    	
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_sessiondata",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.huiSessionData=response.data;		
            $scope.person_cat_id=$scope.huiSessionData.person_cat_id;
            $scope.is_logged_in=$scope.huiSessionData.is_logged_in;
            if($scope.is_logged_in==''){
				$window.location.href = base_url+'#!';
			}
        });
    };
	$scope.getUserData();	
	
	$scope.$watch('huiSessionData',$scope.getUserData, true);
	


    // $window.onbeforeunload = function() { return "Your data will be lost!"; };
    $scope.selectGame100=function(){
        $scope.game100=true;
        $scope.game12=false;
        $scope.luky3=false;
        $scope.gameNumber=1;
    };
    $scope.selectGame12=function(){
        $scope.game12=true;
        $scope.game100=false;
        $scope.luky3=false;
        $scope.gameNumber=2;
    };
    
     $scope.selectLuky3=function(){
       $scope.game12=false;
        $scope.game100=false;
        $scope.luky3=true;
        $scope.gameNumber=3;
        
    };

    // active terminal balance
    $scope.getActiveTerminalBalance=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_active_terminal_balance",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.activeTerminalDetails=response.data.records;
            //console.log($scope.activeTerminalDetails);
        });
    };
    $scope.getActiveTerminalBalance();

    $scope.refreshTerminalBalance=function(){
		$scope.getActiveTerminalBalance();
	};




    //CREATE DATE//
    $scope.dd = new Date().getDate();
    $scope.mm = new Date().getMonth()+1;
    $scope.yy = new Date().getFullYear();
    $scope.day= ($scope.dd<10)? '0'+$scope.dd : $scope.dd;
    $scope.month= ($scope.mm<10)? '0'+$scope.mm : $scope.mm;
    $scope.gameStartingDate=($scope.day+"/"+$scope.month+"/"+$scope.yy);
    $scope.result_date=($scope.month+"/"+$scope.day+"/"+$scope.yy);



    $scope.changeDateFormat=function(userDate){
        return moment(userDate).format('YYYY-MM-DD');
    };

    $scope.getResultbyDate=function(gameDate,gameNumber){
        if(gameNumber==1){
            var request = $http({
                method: "post",
                url: site_url+"/Play/get_result_by_date",
                data: {
                    result_date: gameDate
                }
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.resultSheetRecord=response.data;
            });
        }else{
            $scope.defaultCardResult=false;
            var request = $http({
                method: "post",
                url: site_url+"/Play/get_card_result_by_date",
                data: {
                    result_date: gameDate
                }
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.preCardRecord=response.data;
            });
        }


    };
    
    		//GET TIMER	GET TIMER	GET TIMER	GET TIMER	GET TIMER//
    
     $scope.getCurrentTime=function(){
    	
		 var request = $http({
            method: "post",
            url: site_url+"/Play/get_timestamp",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
           //console.log(response.data);
           $scope.show_time=response.data;
           $scope.theclock=$scope.show_time.substr(0,8);
        });
	};
        
    
     $scope.updateTime = function(){
        $timeout(function(){
            //$scope.theclock = (dateFilter(new Date(), 'hh:mm:ss'));
            $scope.getCurrentTime();
            $scope.updateTime();
        },1000);
    };
    $scope.updateTime();



    //CONVERT CURRENT TIME INTO MILLISECONDS//
    $scope.getCurrentTimeIntoMilliseconds=function(){
    	if($scope.theclock){
			$scope.holahour=parseInt($scope.theclock.substr(0,2));
			$scope.holamin=parseInt($scope.theclock.substr(3,2));
			$scope.holasec=parseInt($scope.theclock.substr(6,2));
			$scope.meridiem=$scope.show_time.substr(8,2);
			/*if($scope.meridiem=='PM'){
				$scope.x=$scope.x+12;
			}*/
			$scope.milliSec=(($scope.holahour * 60 + $scope.holamin) * 60 + $scope.holasec) * 1000;
			$scope.stopwatch=$scope.drawMilliSec-$scope.milliSec;
		}
    
       // $scope.holaDate = new Date();
        //$scope.holahour = new Date().getHours();
        //$scope.holamin = new Date().getMinutes();
        //$scope.holasec = new Date().getSeconds();
        //$scope.milliSec=(($scope.holahour * 60 + $scope.holamin) * 60 + $scope.holasec) * 1000;
        
        $scope.am_pm = new Date().getHours() >= 12 ? "PM" : "AM";
    };

    $scope.getCurrentTimeIntoMilliseconds();
    $interval(function () {
        $scope.getCurrentTimeIntoMilliseconds();
        if($scope.holahour>=9 &&$scope.meridiem=='PM' &&$scope.holahour!=12){
            $scope.remainingTime=(43200000 -$scope.milliSec)+$scope.drawMilliSec;
            $scope.cardRemainingTime=(43200000 -$scope.milliSec)+$scope.cardDrawMilliSec;
        }else{
            $scope.remainingTime=$scope.drawMilliSec-$scope.milliSec;
            $scope.cardRemainingTime=$scope.cardDrawMilliSec-$scope.milliSec;
        }
        
        

    },1000);

    //HAPPY ENDING OF CURRENT TIME INTO MILLISECONDS and TIMER//

    $scope.noSeries = false;
    $scope.lastSum=0;
    $scope.show=0;
    $scope.lpValue="";
    $scope.totalBoxSum=0;
    $scope.totalTicketBuy=0;
    $scope.ticketPrice=0.00;

    //CREATE TIMER
    /*$scope.updateTime = function(){
        $timeout(function(){
            $scope.theclock = (dateFilter(new Date(), 'hh:mm:ss'));
            $scope.updateTime();
        },1000);
    };
    $scope.updateTime();*/
    
    
   
    
    
    
    
    
    
    


    $scope.verticalBoxCss = {
        "color" : "black",
        "background-color" : "plum"
    }

    $scope.horizontalBoxCss = {
        "color" : "black",
        "background-color" : "coral"
    }
    
    $scope.game3box = {
        "background-color" : "#ccffcc",
    }

    $scope.checkList=[];
    $scope.checkList[0]={
        mrp: 1.1
        ,play_series_id: 1
        ,series_name: "A"
    };
    $scope.playInput={};
    $scope.defaultPlayInput={0:{},1:{},2:{},3:{},4:{},5:{},6:{},7:{},8:{},9:{}};

    $scope.playInput={0:{},1:{},2:{},3:{},4:{},5:{},6:{},7:{},8:{},9:{}};
    
    $scope.clearDigitInputBox=function(){
		$scope.playInput=angular.copy($scope.defaultPlayInput);
	};


    $scope.row=10;
    $scope.coloumn=12;
    $scope.getRow = function(num) {
        return new Array(num);
    }
    $scope.getCol = function(num) {
        return new Array(num);
    }

    $scope.verticallyHorizontallyPushValue=function(index,x){
        var i=0;
        if(index==10) {
            for(i=0;i<10;i++){
                $scope.playInput[i][x]=$scope.playInput[x][10];
            }

        }
        if(index==11) {
            for(i=0;i<10;i++){
                $scope.playInput[x][i]=$scope.playInput[x][11];
            }

        }

    };


    $scope.generateTableColumn=function(){
        var cl=Math.floor((Math.random()*9)+1);
        return cl;
    };

    $scope.generateTableRow=function(){
        var r=Math.floor((Math.random()*9)+1);
        return r;
    };


    $scope.generateNumberByLp=function(){
        $scope.playInput=[{},{},{},{},{},{},{},{},{},{}];
        $scope.sum=0;
        var lpValue=parseInt($scope.lpValue);
        var min=(lpValue/100);
        if(min<1){
            min=1;
        }
        var max=min+5;
        var range=(max-min)+1;
        if(lpValue==undefined){
            return 0;
        }
        do{
            var row=$scope.generateTableRow();
            var col=$scope.generateTableColumn();
            $scope.lastSum=$scope.sum;
            if($scope.lastSum==lpValue){
                $scope.sum=0;
                return;
            }
            //$scope.playInput[row][col]=Math.floor((Math.random()*range)+1);
            if($scope.playInput[row][col]==undefined){
                $scope.playInput[row][col]=Math.floor((Math.random()*range)+min);
                $scope.sum=$scope.sum+$scope.playInput[row][col];
            }
            //alert($scope.sum);
        }while($scope.sum<lpValue);
        if($scope.sum>lpValue){
            $scope.playInput[row][col]=lpValue-$scope.lastSum;
        }

    };



    $scope.getTotalBuyTicket=function(playInput){
        var mrp=0;
        var sum=0;
        for(var row=0;row<10;row++){
            for(var col=0;col<10;col++){
                if(playInput[row][col]!=undefined){
                    sum=sum+parseInt(playInput[row][col]);
                }
            }
        }
        $scope.totalBoxSum=sum;

        for(var a=0;a<$scope.checkList.length;a++){
            mrp=mrp+$scope.checkList[a].mrp;
            $scope.ticketPrice=mrp;
        }
        $scope.totalTicketBuy=$scope.totalBoxSum * $scope.ticketPrice;

    };

    $scope.$watch('playInput', $scope.getTotalBuyTicket, true);

    //GET TOTAL TICKET PURCHASE IN CHANGE OF SERIES

    $scope.getTotalBuyTicketByClickSeries=function(position){
        var mrp=0;
        var sum=0;
        for(var row=0;row<10;row++){
            for(var col=0;col<10;col++){
                if($scope.playInput[row][col]!=undefined){
                    sum=sum+parseInt($scope.playInput[row][col]);
                }
            }
        }
        $scope.totalBoxSum=sum;

        if($scope.seriesList[position].Selected){
            $scope.ticketPrice=$scope.seriesList[position].mrp;
            $scope.totalTicketBuy=$scope.totalBoxSum * $scope.ticketPrice;

        }else if($scope.selectAll){
            for(var a=0;a<$scope.checkList.length;a++){
                mrp=mrp+$scope.checkList[a].mrp;
            }
            $scope.ticketPrice=mrp;
            $scope.totalTicketBuy=$scope.totalBoxSum * $scope.ticketPrice;
        } else{
            $scope.ticketPrice=0.0;
            $scope.totalTicketBuy=$scope.totalBoxSum * $scope.ticketPrice;
        }
    };

		
	$scope.disable2d=false;
	$scope.printContent={};
	
    $scope.submitGameValues=function (playInput,checkList) {
    	$scope.disable2d=true;
    	
        var isUndefined=0;
        var balance=$scope.activeTerminalDetails.current_balance;
        // checking is empty filled
        for(i=0;i<10;i++){
            for(j=0;j<10;j++){
                if(playInput[i][j]!=undefined){
                    var isUndefined=isUndefined+1;
                }
            }
        }
        if(isUndefined==0){
            alert("Input is not valid");
            $scope.disable2d=false;
            return;
        }
        // Check Terminal Balance
        var purchasedTicket=$rootScope.roundNumber($scope.totalTicketBuy,2);

        if(purchasedTicket > balance) {
            alert("Sorry low balance");
            $scope.disable2d=false;
            $scope.clearDigitInputBox();
            $scope.lpValue="";
            return;
        }

        // end of checking empty filled
        var series=[];
        $scope.barcodeList={};
        var countSeries=0;
        angular.forEach(checkList,function(value, key){
            series.push({ "play_series_id": value.play_series_id, "series_name": value.series_name});
            countSeries+=1;
        });
        if($scope.checkList.length>0){
            var i= 0,j=0;
            $scope.term=[];
            for(i=0;i<10;i++){
                for(j=0;j<10;j++){
                    if(playInput[i][j]!=undefined){
                        $scope.term.push({ "row_num": i, "col_num": j, "game_value": playInput[i][j]});
                    }
                }
            }
            var request = $http({
                method: "post",
                url: site_url+"/Play/insert_game_values",
                data: {
                    playDetails: $scope.term
                    ,checkList: series
                    ,drawId: $scope.drawTimeList[0].draw_master_id
                    ,purchasedTicket: purchasedTicket
                }
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.reportArray=response.data.records;
                console.log($scope.reportArray);
                if($scope.reportArray.success==1){
                    alert("Print Done");
                    
                    $scope.getActiveTerminalBalance();
                    $scope.submitStatus=true;
                    $timeout(function() {
                        $scope.submitStatus = false;
                    }, 4000);

                    $scope.playInput=angular.copy($scope.defaultPlayInput);
                    $scope.lpValue="";
                    $scope.barcodeList=[];
                    $scope.showSeries=[];
           			 if(countSeries==1){
					 	 //$scope.barcodeList.push($scope.reportArray.barcode0);
					 	 $scope.barcodeList.push({ "bcd": $scope.reportArray.barcode0, "series_name": 'A',"game_name": '2DIGIT'});
					 }
					  if(countSeries==5){
					 	$scope.barcodeList.push({ "bcd": $scope.reportArray.barcode0, "series_name": 'A',"game_name": '2DIGIT'});
					 	$scope.barcodeList.push({ "bcd": $scope.reportArray.barcode1, "series_name": 'B',"game_name": '2DIGIT'});
					 	$scope.barcodeList.push({ "bcd": $scope.reportArray.barcode2, "series_name": 'C',"game_name": '2DIGIT'});
					 	$scope.barcodeList.push({ "bcd": $scope.reportArray.barcode3, "series_name": 'D',"game_name": '2DIGIT'});
					 	$scope.barcodeList.push({ "bcd": $scope.reportArray.barcode4, "series_name": 'E',"game_name": '2DIGIT'});
					 	 
					 }
					 $scope.ongoing_draw=$scope.drawTimeList[0].end_time+''+$scope.drawTimeList[0].meridiem;
					 $scope.purchase_time=$scope.reportArray.purchase_time;
					 $scope.purchase_date=$scope.reportArray.purchase_date;
					 $scope.currentGameMrp=1.10;
					 
					 $scope.allGameValue=[];
						var ln=$scope.term.length;
						var i;
						
						/*for(i=0;i<ln;i=+5){
							$scope.allGameValue.push({ "1": $scope.term[i].row_num+''+$scope.term[i].col_num+'-'+$scope.term[i].game_value});
							$scope.allGameValue.push({ "2": $scope.term[i].row_num+''+$scope.term[i].col_num+'-'+$scope.term[i].game_value});
							
						}*/
						
						$scope.totalticket_qty=0;
					  angular.forEach($scope.term,function(value, key){
				            $scope.allGameValue.push(value.row_num+''+value.col_num+'-'+value.game_value);
				            $scope.totalticket_qty+=parseInt(value.game_value);
			        	});
			        	$scope.totalticket_purchase=$scope.totalticket_qty*1.10;
					 
                    $timeout(function() {
					   //$rootScope.huiPrintDiv('receipt-div','my_printing_style.css',1);
					   $rootScope.huiPrintDiv('receipt-div','my_printing_style.css',1);
					   $scope.disable2d=false;
					}, 3000);
					
                	
                }
            });
        }else{
            alert("Please select any series");
            $scope.disable2d=false;
            return;
        }
        
    };

    //GET SERIES DATA FROM DATABASE//
    var request = $http({
        method: "post",
        url: site_url+"/Play/get_all_play_series",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){

        $scope.seriesList=response.data.records;
        $scope.seriesList[0].Selected = true;
    });


    //CHECK ONLY ANY ONE OF SERIES AND GET IT'S DETAILS//

    $scope.checkOneSeries = function (position) {
        $scope.selectAll=false;
        $scope.checkList=[];
        if($scope.seriesList[position].Selected){
            var play_series_id = $scope.seriesList[position].play_series_id;
            var series_name = $scope.seriesList[position].series_name;
            var mrp = $scope.seriesList[position].mrp;
            $scope.checkList.push({ "play_series_id":  play_series_id,"series_name": series_name,"mrp": mrp});
        }

        for(var i=0;i<$scope.seriesList.length;i++){
            if(i !=position){
                $scope.seriesList[i].Selected = false;
            }
        }

    };
    //CHECK ALL SERIES AND GATE SERIES DETAILS//
    $scope.checkSeriesAll = function () {
        if($scope.selectAll){
            for(var i=0;i<$scope.seriesList.length;i++){
                $scope.seriesList[i].Selected = false;
            }
            $scope.checkList=angular.copy($scope.seriesList);
        }else{
            $scope.checkList=[];
        }


    };

    // activation of playing game
    $scope.isDeactivate2D=false;
    $scope.isDeactivateCard=false;


    $scope.drawTimeList=[];
    $scope.getCurrentDrawTime=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_all_draw_time",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
        	
            $scope.drawTimeList=response.data.records;
            $scope.endTime=$scope.drawTimeList[0].end_time;
            
            //alert($scope.theclock);
            $scope.serialNumber=$scope.drawTimeList[0].serial_number;

            // CONVERT DRAW TIME TO MILLISECOND//
            $scope.dateArray = $scope.endTime.split(":");
            $scope.myDate = new Date(1970, 0, 1, $scope.dateArray[0], $scope.dateArray[1], $scope.dateArray[2]);
            $scope.drawHour=$scope.myDate.getHours();
            $scope.drawMin=$scope.myDate.getMinutes();
            $scope.drawSec=$scope.myDate.getSeconds();
              if($scope.serialNumber==16){
                //$scope.drawMilliSec=$scope.drawMilliSec+43200000;
                $scope.drawHour=$scope.drawHour+12;
            }
            $scope.drawMilliSec=(($scope.drawHour * 60 + $scope.drawMin) * 60 + $scope.drawSec) * 1000;
            
            //****OLD CODE FOR TIMER***//
            
            

            if($scope.holahour==0 && $scope.drawHour==12){
                $scope.drawMilliSec=$scope.drawMilliSec-43200000;
            }
        });

        // get information to know the game activation
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_game_activation_details",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.getActivationDetails=response.data.records;
            if($scope.getActivationDetails[0].deactivate==1){
                $scope.isDeactivate2D=true;
            }else{
                $scope.isDeactivate2D=false;
            }

            if($scope.getActivationDetails[1].deactivate==1){
                $scope.isDeactivateCard=true;
            }else{
                $scope.isDeactivateCard=false;
            }

        });
    };
    
  
    
        
    
    
    
    
    

    $scope.previousDraw={};


    $scope.getCurrentDrawTime();



    $scope.getEachDrawTime=function(){
        if($scope.theclock==$scope.drawTimeList[0].end_time && $scope.am_pm==$scope.drawTimeList[0].meridiem){
            $scope.previousDraw=angular.copy($scope.drawTimeList);
            $scope.getCurrentDrawTime();
            //$scope.getResultEndOfDrawTime($scope.previousDraw[0].draw_master_id);

        }
    };

    $scope.$watch('theclock', $scope.getEachDrawTime, true);



    $scope.getResultEndOfDrawTime=function (drawId) {
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_draw_result",
            data: {
                drawId: drawId
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.winningValue=response.data.records;

        });
    };

    //$scope.getResultEndOfDrawTime(24);


    $scope.getPreviousResult=function(){

        var request = $http({
            method: "post",
            url: site_url+"/Play/get_previous_result",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.winningValue=response.data;

        });
    };


    $scope.getPreviousResult();

    //update draw_time by calling database every 1 second
    $interval(function () {
        $scope.hideDate=false;
        $scope.getCurrentDrawTime();
        $scope.getCardDrawTime();
        $scope.getPreviousResult();

        if($scope.theclock >= '09:00:00' && $scope.am_pm=='PM'){
            $scope.hideDate=true;
        }else{
            $scope.hideDate=false;
        }
    },1000);



    //show all result

    $scope.getResultSheetToday=function(gameNumber){
        if(gameNumber==1){
            $scope.showResultSheet=true;
            var request = $http({
                method: "post",
                url: site_url+"/Play/get_result_sheet_today",
                data: {}
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.resultSheetRecord=response.data;

            });
        }
        if(gameNumber==2){
            $scope.selectCardGameDate=true;        //show div to select date for card result//
        }
    };

    $scope.cardImage=true;


    $scope.closeResultSheet=function(gameNumber){
        if(gameNumber==1) {
            $scope.showResultSheet = false;
        }else{
            $scope.defaultCardResult=true;
            $scope.selectCardGameDate=false;
        }
    };



    // twelve card
    $scope.defaultJack=[{row:'j',col:'c',val:0},{row:'j',col:'d',val:0},{row:'j',col:'s',val:0},{row:'j',col:'h',val:0}];
    $scope.defaultQueen=[{row:'q',col:'c',val:0},{row:'q',col:'d',val:0},{row:'q',col:'s',val:0},{row:'q',col:'h',val:0}];
    $scope.defaultSaheb=[{row:'k',col:'c',val:0},{row:'k',col:'d',val:0},{row:'k',col:'s',val:0},{row:'k',col:'h',val:0}];

    $scope.jack=angular.copy($scope.defaultJack);
    $scope.queen=angular.copy($scope.defaultQueen);
    $scope.saheb=angular.copy($scope.defaultSaheb);

    $scope.cardSubmitStatus = false;

    // get card game price details
    $scope.getCardPrice=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_card_game_price",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.cardPrice=response.data.records[0];
        });
    };
    $scope.getCardPrice();
    //end

    // get total input value into card box
    $scope.totalCardInput=0;
    $scope.totalTicketBuyByCard=0;
    $scope.getTotalCardInput=function (cardValue) {
        var total=0;
        angular.forEach(cardValue,function(value, key){
            if(value.val==undefined){
                value.val=0;
            }
            total=total+parseInt(value.val);
        });
        return total;
    };


    $scope.$watch('[jack,queen,saheb]', function (newValue, oldValue) {
        var jack=$scope.getTotalCardInput(newValue[0]);
        var queen=$scope.getTotalCardInput(newValue[1]);
        var saheb=$scope.getTotalCardInput(newValue[2]);
        $scope.totalCardInput=jack+queen+saheb;
        $scope.getCardPrice();
        $scope.totalTicketBuyByCard=$scope.cardPrice.mrp * $scope.totalCardInput;
    }, true);

    // end of get total input value into card box

    //get draw time for card game
    $scope.cardDrawTimeList=[];
    $scope.getCardDrawTime=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_current_card_game_draw_time",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.cardDrawTimeList=response.data.records;
            $scope.lastDraw=$scope.cardDrawTimeList[0].serial_number;
            if($scope.cardDrawTimeList){
				//console.log($scope.cardDrawTimeList);
				//console.log($scope.lastDraw);
			}
            
            $scope.cardEndTime=$scope.cardDrawTimeList[0].end_time;
            $scope.cardSerialNumber=$scope.cardDrawTimeList[0].serial_number;

            // CONVERT DRAW TIME TO MILLISECOND//
            $scope.dateArray = $scope.cardEndTime.split(":");
            $scope.myDate = new Date(1970, 0, 1, $scope.dateArray[0], $scope.dateArray[1], $scope.dateArray[2]);
            $scope.cardDrawHour=$scope.myDate.getHours();
            $scope.cardDrawMin=$scope.myDate.getMinutes();
            $scope.cardDrawSec=$scope.myDate.getSeconds();
            if($scope.cardSerialNumber==24){
                //$scope.cardDrawMilliSec=$scope.cardDrawMilliSec+43200000;
                $scope.cardDrawHour=$scope.cardDrawHour+12;
            }
            $scope.cardDrawMilliSec=(($scope.cardDrawHour * 60 + $scope.cardDrawMin) * 60 + $scope.cardDrawSec) * 1000;
            

            if($scope.holahour==0 && $scope.cardDrawHour==12){
                $scope.cardDrawMilliSec=$scope.cardDrawMilliSec-43200000;
            }
        });
    };
    $scope.getCardDrawTime();


	
    // save game input into database
    $scope.disableCard=false;
    
    $scope.submitCardGameValue=function () {
    	$scope.disableCard=true;
        var balance=$scope.activeTerminalDetails.current_balance;
        var purchasedTicket=$rootScope.roundNumber($scope.totalTicketBuyByCard,2);
        if(purchasedTicket>balance){
            alert("Sorry low balance");
            $scope.disableCard=false;
            $scope.clearAll();
            return;
        }

        var drawId=$scope.cardDrawTimeList[0].card_draw_master_id;
        var cardPriceDetailsId=$scope.cardPrice.card_price_details_id;
        var cardValues=[];
        
        angular.forEach($scope.jack,function(value, key){
            cardValues.push({ "row_num": value.row, "col_num": value.col, "game_value": value.val});
            
        });

        angular.forEach($scope.queen,function(value, key){
            cardValues.push({ "row_num": value.row, "col_num": value.col, "game_value": value.val});
        });

        var count=0;


        angular.forEach($scope.saheb,function(value, key){
            cardValues.push({ "row_num": value.row, "col_num": value.col, "game_value": value.val});

        });
        angular.forEach(cardValues,function(value, key){
            if(value.game_value>0){
                count=+1;
            }
        });


        if(count<1){
            alert('Invalid input');
            $scope.disableCard=false;
            $scope.clearAll();
            return;
        }
        $scope.testArray=angular.copy(cardValues);



        var request = $http({
            method: "post",
            url: site_url+"/Play/insert_card_values",
            data: {
                cardValues: cardValues
                ,drawId: drawId
                ,cardPriceDetailsId: cardPriceDetailsId
                ,purchasedTicket: purchasedTicket
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.cardDataReportArray=response.data.records;
            if($scope.cardDataReportArray.success==1){
                alert("Print Done");
                $scope.disableCard=false;
                $scope.getActiveTerminalBalance();
                $scope.cardSubmitStatus=true;
                $timeout(function() {
                    $scope.cardSubmitStatus = false;
                }, 4000);

                $scope.jack=angular.copy($scope.defaultJack);
                $scope.queen=angular.copy($scope.defaultQueen);
                $scope.saheb=angular.copy($scope.defaultSaheb);
                
                $scope.twelveCardForm.$setPristine();
                $scope.lpValueCard='';
                
                $scope.barcodeList=[];
                $scope.barcodeList.push({ "bcd": $scope.cardDataReportArray.barcode,"series_name": '',"game_name": 'CARD'});
            	
            	$scope.ongoing_draw=$scope.cardDrawTimeList[0].end_time+''+$scope.cardDrawTimeList[0].meridiem;
           		$scope.purchase_time=$scope.cardDataReportArray.purchase_time;
				$scope.purchase_date=$scope.cardDataReportArray.purchase_date;
				$scope.currentGameMrp=5.00;
				$scope.allGameValue=[];
				$scope.totalticket_qty=0;
				angular.forEach(cardValues,function(value, key){
					if(value.game_value>0){
						$scope.allGameValue.push(value.row_num+''+value.col_num+'-'+value.game_value);
						$scope.totalticket_qty+=parseInt(value.game_value);
					}
       			 });
       			 $scope.totalticket_purchase=$scope.totalticket_qty * $scope.currentGameMrp;
				
				
				$timeout(function() {
					   $rootScope.huiPrintDiv('receipt-div','my_printing_style.css',1);
				}, 3000);
            }
        });
    };

    $interval(function () {
        $scope.getCardDrawTime();
        $scope.getLucky3DrawTime();
        $scope.getTodayCardResult();
        $scope.number = parseInt($scope.winningCard.length/6);

        if($scope.number && $scope.winningCard.length % 6){
            $scope.number+=1;
        }
        if(!$scope.number){
            $scope.number=1;
        }

    },1000);

    $scope.path='img/winning-card/';
    $scope.getTodayCardResult=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_today_card_result",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.winningCard=response.data.records;
        });
    };
    $scope.getTodayCardResult();


    $scope.getNumber = function(num) {
        return new Array(num);
    };

    $scope.testFunc=function(x){
        $scope.myVar=x * 6;
        return ($scope.myVar);
    };


    $scope.gotToTerminalReportSection=function () {
        $window.location.href ='#!reportterm';
        $window.location.reload();
    };

    $scope.clearAll=function(){
        $scope.jack=angular.copy($scope.defaultJack);
        $scope.queen=angular.copy($scope.defaultQueen);
        $scope.saheb=angular.copy($scope.defaultSaheb);
        //$scope.lpValueCard='';
    };

    $scope.lpValueCard;
    $scope.getCardLpValue=function (num) {
        if(num==undefined || num<1){
            alert("Incorrect input");
            $scope.clearAll();
            return;
        }
        $scope.clearAll();
        $scope.cardValues=[];

        var sum=0;
        var lastSum=0;
        var min=parseInt(num/12);
        if(min<1){
            min=1;
        }
        // var max=min+5;
        var max=min+10;
        var range=(max-min)+1;

        do{
           var x=Math.floor((Math.random()*range)+min);
           lastSum=sum;
           sum=sum+x;
           if(sum>num){
               x=(num -lastSum );
               sum=lastSum+(num -lastSum );
           }
            $scope.cardValues.push(x);


        }while(sum!=num);
        var cardLn=$scope.cardValues.length;
        // var x=Math.floor(Math.random() * 13);

        var i=0,x=0;


        for(i=0;i<cardLn;i++){

            if($scope.cardValues[x]!=undefined){
                $scope.jack[i].val=$scope.cardValues[x];
            }else{
                return;
            }
            if(x==9){
                $scope.queen[0].val=$scope.cardValues[x+1];
                $scope.saheb[1].val=$scope.cardValues[x+2];
                return;
            }

            if($scope.cardValues[x+1]!=undefined){
                $scope.queen[i+1].val=$scope.cardValues[x+1];
            }else{
                return;
            }
            if($scope.cardValues[x+2]!=undefined){
                if(i+2>3){
                    $scope.saheb[0].val=$scope.cardValues[x+2];
                    // if($scope.saheb[0].val>0){
                    //     $scope.saheb[1].val=$scope.cardValues[x+2];
                    // }
                }else {
                    $scope.saheb[i+2].val=$scope.cardValues[x+2];
                }
            }else{
                return;
            }

            x=x+3;

            // x is used to indicate new lp values
            // i is used to indicate card boxes
        }
        $scope.twelveCardForm.$setDirty();


    };



	/*START THIRD GAME LUCKY3     */
	$scope.row1={cat:'ST',val1:'',val2:'',val3:'',qty:1,back_dis:0,frnt_dis:0};
	$scope.row2={cat:'BX',val1:'',val2:'',val3:'',qty:1,back_dis:0,frnt_dis:0};
	$scope.row3={cat:'FP',val1:'',val2:'',val3:'',qty:1,back_dis:0,frnt_dis:0};
	$scope.row4={cat:'BP',val1:'',val2:'',val3:'',qty:1,back_dis:0,frnt_dis:0};
	
	
	$scope.disableBack=false;
	$scope.disableFront=false;
	$scope.catWiseBehave=function(targetval){
		
		if(targetval.cat=='FP'){
			targetval.val1=targetval.val2=targetval.val3=targetval.val4='';
			targetval.back_dis=1;
			targetval.frnt_dis=0;
		}
		if(targetval.cat=='BP'){
			targetval.val1=targetval.val2=targetval.val3=targetval.val4='';
			targetval.back_dis=0;
			targetval.frnt_dis=1;
		}
		if(targetval.cat=='ST' || targetval.cat=='BX'){
			targetval.back_dis=0;
			targetval.frnt_dis=0;
		}
	};
	$scope.catWiseBehave($scope.row1);
	$scope.catWiseBehave($scope.row2);
	$scope.catWiseBehave($scope.row3);
	$scope.catWiseBehave($scope.row4);
	
	
	$scope.getLpForLucky3=function(rowValue){
		rowValue.val1=rowValue.val2=rowValue.val3=rowValue.val4='';
		var x=Math.floor(Math.random() * 10);
		var y=Math.floor(Math.random() * 10);
		var z=Math.floor(Math.random() * 10);
		if(x==0){
			x=1;
		}
		if(rowValue.cat=='FP'){
			rowValue.val1=x;
			rowValue.val2=y;
		}else if(rowValue.cat=='BP'){
			
			rowValue.val2=x;
			rowValue.val3=y;
		}else{
			
			rowValue.val1=x;
			rowValue.val2=y;
			rowValue.val3=z;
		}
		
	};
	
	
	$scope.getLucky3DrawTime=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_lucky3_draw_time",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.luckyDrawTimeList=response.data.records[0];
            $scope.luckyDrawEndTime=$scope.luckyDrawTimeList[0].end_time;
            $scope.luckyDrawserialno=$scope.drawTimeList[0].serial_number;

            // CONVERT DRAW TIME TO MILLISECOND//
           /* $scope.dateArray = $scope.endTime.split(":");
            $scope.myDate = new Date(1970, 0, 1, $scope.dateArray[0], $scope.dateArray[1], $scope.dateArray[2]);
            $scope.drawHour=$scope.myDate.getHours();
            $scope.drawMin=$scope.myDate.getMinutes();
            $scope.drawSec=$scope.myDate.getSeconds();
              if($scope.serialNumber==16){
                //$scope.drawMilliSec=$scope.drawMilliSec+43200000;
                $scope.drawHour=$scope.drawHour+12;
            }
            $scope.drawMilliSec=(($scope.drawHour * 60 + $scope.drawMin) * 60 + $scope.drawSec) * 1000;

            if($scope.holahour==0 && $scope.drawHour==12){
                $scope.drawMilliSec=$scope.drawMilliSec-43200000;
            }*/
        });

    };
	
    $scope.getLucky3DrawTime();
    
    
        $scope.submitLuckyValue=function () {
    	
        var balance=$scope.activeTerminalDetails.current_balance;
        alert(balance);return;
        var purchasedTicket=$rootScope.roundNumber($scope.totalTicketBuyByCard,2);
        if(purchasedTicket>balance){
            alert("Sorry low balance");
            $scope.disableCard=false;
            $scope.clearAll();
            return;
        }

        var drawId=$scope.cardDrawTimeList[0].card_draw_master_id;
        var cardPriceDetailsId=$scope.cardPrice.card_price_details_id;
        var cardValues=[];
        
        angular.forEach($scope.jack,function(value, key){
            cardValues.push({ "row_num": value.row, "col_num": value.col, "game_value": value.val});
            
        });

        angular.forEach($scope.queen,function(value, key){
            cardValues.push({ "row_num": value.row, "col_num": value.col, "game_value": value.val});
        });

        var count=0;


        angular.forEach($scope.saheb,function(value, key){
            cardValues.push({ "row_num": value.row, "col_num": value.col, "game_value": value.val});

        });
        angular.forEach(cardValues,function(value, key){
            if(value.game_value>0){
                count=+1;
            }
        });


        if(count<1){
            alert('Invalid input');
            $scope.disableCard=false;
            $scope.clearAll();
            return;
        }
        $scope.testArray=angular.copy(cardValues);



        var request = $http({
            method: "post",
            url: site_url+"/Play/insert_card_values",
            data: {
                cardValues: cardValues
                ,drawId: drawId
                ,cardPriceDetailsId: cardPriceDetailsId
                ,purchasedTicket: purchasedTicket
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.cardDataReportArray=response.data.records;
            if($scope.cardDataReportArray.success==1){
                alert("Print Done");
                $scope.disableCard=false;
                $scope.getActiveTerminalBalance();
                $scope.cardSubmitStatus=true;
                $timeout(function() {
                    $scope.cardSubmitStatus = false;
                }, 4000);

                $scope.jack=angular.copy($scope.defaultJack);
                $scope.queen=angular.copy($scope.defaultQueen);
                $scope.saheb=angular.copy($scope.defaultSaheb);
                
                $scope.twelveCardForm.$setPristine();
                $scope.lpValueCard='';
                
                $scope.barcodeList=[];
                $scope.barcodeList.push({ "bcd": $scope.cardDataReportArray.barcode,"series_name": '',"game_name": 'CARD'});
            	
            	$scope.ongoing_draw=$scope.cardDrawTimeList[0].end_time+''+$scope.cardDrawTimeList[0].meridiem;
           		$scope.purchase_time=$scope.cardDataReportArray.purchase_time;
				$scope.purchase_date=$scope.cardDataReportArray.purchase_date;
				$scope.currentGameMrp=5.00;
				$scope.allGameValue=[];
				$scope.totalticket_qty=0;
				angular.forEach(cardValues,function(value, key){
					if(value.game_value>0){
						$scope.allGameValue.push(value.row_num+''+value.col_num+'-'+value.game_value);
						$scope.totalticket_qty+=parseInt(value.game_value);
					}
       			 });
       			 $scope.totalticket_purchase=$scope.totalticket_qty * $scope.currentGameMrp;
				
				
				$timeout(function() {
					   $rootScope.huiPrintDiv('receipt-div','my_printing_style.css',1);
				}, 3000);
            }
        });
    };
    
    /*	GET	LUCKY3 CATEGORY MRP DETAILS		*/
    $scope.getLuckyGameMrpDetails=function(){
		var request = $http({
            method: "post",
            url: site_url+"/Play/get_lucky_cat_price_details",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.luckyGamePriceList=response.data.records;
            $scope.luckyMrp=$scope.luckyGamePriceList[0].mrp;
            
        });
	};
	$scope.getLuckyGameMrpDetails();
	
    
    
    
    
      $scope.logoutCpanel=function () {
        
        var request = $http({
            method: "post",
            url: site_url+"/Play/logout_cpanel",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $window.location.href = base_url+'#!';
        });
    };
    
    
    
    /***********************      BARCODE   ********** */
    
    //$scope.alerter = CommonCode;
    //$scope.alerter.show("Hello World");
    
    $scope.barcodeOilBill = {
        format: 'CODE128',
        lineColor: '#000000',
        width: 2,
        height: 25,
        displayValue: false,
        fontOptions: '',
        font: 'monospace',
        textAlign: 'center',
        textPosition: 'bottom',
        textMargin: 2,
        fontSize: 20,
        background: '#ffffff',
        margin: 0,
        marginTop: undefined,
        marginBottom: undefined,
        marginLeft: undefined,
        marginRight: undefined,
        valid: function (valid) {
        }
    };
    
//     $scope.barcodeTest="A";
    
    
    
    





});

