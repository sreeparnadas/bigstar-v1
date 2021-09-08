app.controller("payoutSettingsCtrl", function ($scope,$http,$filter,$rootScope,dateFilter,$timeout,$interval,$window) {
    $scope.msg = "This is payoutSettingsCtrl controller";
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

    $scope.getDefaultUserId=function(){

    };


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


    var request = $http({
        method: "post",
        url: site_url+"/PayoutSettings/get_game1_payout",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.TwoDigitPayOut=response.data.records;
    });


    var request = $http({
        method: "post",
        url: site_url+"/PayoutSettings/get_game2_payout",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.cardPayOut=response.data.records[0];
    });


    $scope.resetRechargeDetails=function () {
        $scope.limit=angular.copy($scope.defaultLimit);
        $scope.isUpdateable=false;
        $scope.submitStatus = false;
    };

    $scope.submitGamePayout=function(twoDigitPayOut,cardPayOut){
        var tdp=angular.copy(twoDigitPayOut);
        var cp=angular.copy(cardPayOut);
        var request = $http({
            method: "post",
            url: site_url+"/PayoutSettings/set_game_payout",
            data: {
                twoDigitPayOut: tdp
                ,cardPayOut: cp
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.payOutReport=response.data.records;
            if($scope.payOutReport.success==1){
                $scope.payoutStatus=true;
                $timeout(function() {
                    $scope.payoutStatus = false;
                }, 5000);
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

