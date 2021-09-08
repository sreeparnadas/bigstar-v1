app.controller("productCtrl", function ($scope,$http) {
    $scope.msg = "This is Product controller";
    $scope.tab = 1;

    $scope.setTab = function(newTab){
        $scope.tab = newTab;
    };

    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
    };
});

