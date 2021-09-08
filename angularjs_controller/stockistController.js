app.controller("stockistCtrl", function ($scope,$http,$filter,$rootScope,dateFilter,$timeout,$interval,$window) {
    $scope.msg = "This is admin controller";
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


    $scope.saveStockistData=function (stockist) {
        var request = $http({
            method: "post",
            url: site_url+"/Stockist/save_new_stockist",
            data: {
                stockist: stockist
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.stockistReport=response.data.records;
            if($scope.stockistReport.success==1){
                $scope.stockist.stockist_id = $scope.stockistReport.stockist_id;
                $scope.stockist.user_id = $scope.stockistReport.user_id;
                $scope.updateableStockistIndex=0;
                $scope.submitStatus = true;
                $scope.isUpdateable=true;
                $timeout(function() {
                    $scope.submitStatus = false;
                }, 4000);
                $scope.stockistList.unshift($scope.stockist);
                $scope.stockistForm.$setPristine();
            }

        });
    };

    $scope.defaultStockist={
        stockist_name: ""
        ,user_id: ""
        ,user_password: ""
    };
    $scope.stockist=angular.copy($scope.defaultStockist);
    $scope.randomPass=function(length, addUpper, addSymbols, addNums) {
        var lower = "abcdefghijklmnopqrstuvwxyz";
        var upper = addUpper ? lower.toUpperCase() : "";
        var nums = addNums ? "0123456789" : "";
        var symbols = addSymbols ? "!#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~" : "";

        var all = lower + upper + nums + symbols;
        while (true) {
            var pass = "";
            for (var i=0; i<length; i++) {
                pass += all[Math.random() * all.length | 0];
            }

            // criteria:
            if (!/[a-z]/.test(pass)) continue; // lowercase is a must
            if (addUpper && !/[A-Z]/.test(pass)) continue; // check uppercase
            if (addSymbols && !/\W/.test(pass)) continue; // check symbols
            if (addNums && !/\d/.test(pass)) continue; // check nums

            $scope.stockist.user_password=pass;
            return $scope.stockist.user_password;
        }
    }


    var request = $http({
        method: "post",
        url: site_url+"/Stockist/get_all_stockist",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.stockistList=response.data.records;
    });



    $scope.updateStockistFromTable = function(stockist) {
        $scope.tab=1;
        $scope.stockist = angular.copy(stockist);
        $scope.isUpdateable=true;
        var index=$scope.stockistList.indexOf(stockist);
        $scope.updateableStockistIndex=index;
        $scope.stockistForm.$setPristine();
    };

    $scope.updateStockistByStockistId=function(stockist){
        $scope.master = angular.copy(stockist);
        var request = $http({
            method: "post",
            url: site_url+"/Stockist/update_stockist_by_stockist_id",
            data: {
                stockist: $scope.master
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.stockistReport=response.data.records;
            if($scope.stockistReport.success==1){
                $scope.updateStatus = true;
                $scope.isUpdateable=true;
                $timeout(function() {
                    $scope.updateStatus = false;
                }, 4000);
                $scope.stockistList[$scope.updateableStockistIndex]=$scope.stockist;
                $scope.stockistForm.$setPristine();
            }

        });
    };


    $scope.resetStockistDetails=function () {
        $scope.stockist=angular.copy($scope.defaultStockist);
        $scope.isUpdateable=false;
        $scope.getNextUserId();
    };

    $scope.getNextUserId=function () {
        var request = $http({
            method: "post",
            url: site_url+"/Stockist/get_current_user_id",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.stockist.user_id=response.data;
            console.log($scope.stockist.user_id);
        });
    };

    $scope.getNextUserId();
    
    
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

