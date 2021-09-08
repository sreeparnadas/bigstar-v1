app.controller("sessionCloseTerm", function ($scope,$http,$filter,$rootScope,dateFilter,$timeout,$interval,$window) {
	
    $scope.msg = "This is Close Session Controller";
    
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

   
	$scope.term_session={};

    $scope.getActiveTerminalList=function () {
        var request = $http({
            method: "post",
            url: site_url+"/SessionCloseTerminal/get_terminal",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.terminalList=response.data.records;
        });
    };
    $scope.getActiveTerminalList();
    
    $scope.getTerminalLoginTime=function(logintime){
    	$scope.showlogindate=logintime.substr(0,10);
    	var ddarray=$scope.showlogindate.split("-");
    	$scope.logintime=logintime.substr(10,9);
    	var dd=ddarray[2];
    	var mm=ddarray[1];
    	var yyyy=ddarray[0];
    	$scope.logindate=dd+'/'+mm+'/'+yyyy;
    	console.log(dd);
        
    };//end of loadVendors
    
    
    $scope.closeTerminalSession=function(term_session){
    	var user_id=term_session.terminal.user_id;
    	var is_logout=term_session.is_logout;
    	if(is_logout==1){
			alert('do');
		}
		
	};
	
	
	
      $scope.logoutRequestedTerminal=function (term_session){
	  	    var userid=term_session.terminal.user_id;
    		var is_logout=term_session.is_logout;
    			if(is_logout==1){
				
				var request = $http({
		        method: "post",
		        url: site_url+"/SessionCloseTerminal/logout_cpanel",
		        data: {
		        	user_id: userid
		        }
		        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		        }).then(function(response){
		        	$scope.sessionReport=response.data;
		        	console.log($.parseJSON($scope.sessionReport));
		        	if($scope.sessionReport){
						alert("Session closed");
					}
		   		 });
			}
	  }
	  
	  
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

