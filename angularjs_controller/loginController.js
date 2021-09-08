app.controller("loginCtrl", function ($scope,$http,$filter,md5,$window) {
    $scope.msg = "Log in controller";
    $scope.loginData={};
    $scope.login=function (loginData) {
        //var psw=md5.createHash($scope.loginData.user_password || '');
        var psw=$scope.loginData.user_password;
        var request = $http({
            method: "post",
            url: site_url+"/base/validate_credential",
            data: {
                    userId: loginData.user_id
                ,userPassword: psw
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.loginDatabaseResponse=response.data;
            if($scope.loginDatabaseResponse.person_cat_id==3){
                $window.location.href = base_url+'#!play';
            }else if($scope.loginDatabaseResponse.person_cat_id==1){
                $window.location.href = base_url+'#!cpanel';
            }else if($scope.loginDatabaseResponse.is_currently_loggedin==1){
                alert("This account is already loggedin");
            }else{
				
			}
            
            if($scope.loginDatabaseResponse.user_id==0){
				alert("Check User id or Password");
			}
        });
    };

    // $window.onbeforeunload = function() { return "Your data will be lost!"; };z



});

