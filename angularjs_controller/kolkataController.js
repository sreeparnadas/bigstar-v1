app.controller("kolkataCtrl", function ($scope,$window,$http) {
            $http.get("customer.php").then(function(response) {
              $scope.Customers = response.data.records;
            });

            $scope.Add = function () {
                //Add the new item to the Array.
                var customer = {};
                customer.Name = $scope.Name;
                customer.Mobile = $scope.Mobile;
                $scope.Customers.push(customer);

                //Clear the TextBoxes.
                $scope.Name = "";
                $scope.Mobile = "";
            };

            $scope.Remove = function (index) {
                //Find the record using Index from Array.
                var name = $scope.Customers[index].Name;
                if ($window.confirm("Do you want to delete: " + name)) {
                    //Remove the item from Array using Index.
                    $scope.Customers.splice(index, 1);
                }
            }
});

