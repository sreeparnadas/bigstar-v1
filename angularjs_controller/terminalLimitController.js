app.controller("terminalLimitCtrl", function ($scope,$http,$filter,$rootScope,dateFilter,$timeout,$interval,$window) {
    $scope.msg = "This is terminalLimitCtrl controller";
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


    $scope.saveTerminalRechargeData=function (limit) {
        var amount=limit.amount;
        var stockist_cur_bal=limit.terminal.stockist_current_balance;

        if (amount>stockist_cur_bal){
            alert("Sorry! not enough balance");
            return;
        }
        var terminal_id=limit.terminal.terminal_id;
        var stockist_id=limit.terminal.stockist_id;
        var request = $http({
            method: "post",
            url: site_url+"/TerminalLimit/save_terminal_recharge_details",
            data: {
                terminal_id: terminal_id
                ,stockist_id: stockist_id
                ,amount : amount
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.terminalRechargeReport=response.data.records;
            if($scope.terminalRechargeReport.success==1){
                $scope.submitStatus = true;
                $scope.isUpdateable=true;
                alert("Current Balance is " + $scope.terminalRechargeReport.current_balance);
                $scope.limit.terminal.terminal_current_balance=$scope.terminalRechargeReport.current_balance;
                $scope.limit.terminal.stockist_current_balance=$scope.limit.terminal.stockist_current_balance - amount;
                $timeout(function() {
                    $scope.submitStatus = false;
                }, 4000);
                // $scope.stockistList.unshift($scope.stockist);
                $scope.terminalForm.$setPristine();
            }

        });
    };

    $scope.defaultLimit={};
    $scope.limit=angular.copy($scope.defaultLimit);

    $scope.getTerminalList=function () {
        var request = $http({
            method: "post",
            url: site_url+"/TerminalLimit/get_all_terminal",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.terminalList=response.data.records;
        });
    };
    $scope.getTerminalList();


    $scope.resetRechargeDetails=function () {
        $scope.limit=angular.copy($scope.defaultLimit);
        $scope.isUpdateable=false;
        $scope.submitStatus = false;
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

