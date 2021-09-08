app.controller("saleCtrl", function ($scope,$http,$filter) {
    $scope.saleMsg = "This is sale controller";
    $scope.test=function(){
        alert(1);
    };
    $scope.tab = 1;
    $scope.showQuality=true;

    $scope.setTab = function(newTab){
        $scope.tab = newTab;
    };

    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
    };

    $scope.saleDetails={
        quality:null,
        sgstFactor: 0,
        cgstFactor: 0,
        taxableAmount: 0,
        making_rate: 0,
        making_charge: 0,
        other_charge: 0,
        amount: 0,
        sgst: 0,
        cgst: 0,
        igst: 0

    };
    $scope.customerList={};
    $scope.loadAllCustomers=function(){
        var request = $http({
            method: "post",
            url: site_url+"/customer/get_customers",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customerList=response.data.records;
        });
    };//end of loadCustomer
    $scope.loadAllCustomers();

    $scope.productList={};
    $scope.loadAllProducts=function(){
        var request = $http({
            method: "post",
            url: site_url+"/sale/get_products",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.productList=response.data.records;
            $scope.productGroupList=alasql('SELECT distinct group_id,group_name,gst_rate  from ? ',[$scope.productList]);
            $scope.productQualityList=alasql('SELECT distinct quality  from ? ',[$scope.productList]);
        });
    };//end of loadProducts
    $scope.loadAllProducts();

    $scope.getProductByGroup=function () {
        $scope.productByGroup=alasql('SELECT distinct product_id,product_name,quality  from ? where group_id=?',[$scope.productList,$scope.saleDetails.productGroup.group_id]);
    };

    $scope.setAmount=function () {
            $scope.saleDetails.amount=parseFloat(($scope.saleDetails.net_weight*$scope.saleDetails.rate).toFixed(2));

    };

    $scope.getMakingCharge=function () {
        if($scope.saleDetails.making_charge_type==1){
           // $scope.saleDetails.making_charge=parseFloat(($scope.saleDetails.net_weight*$scope.saleDetails.making_rate).toFixed(2));
            $scope.saleDetails.making_charge=parseFloat($scope.saleDetails.net_weight*$scope.saleDetails.making_rate).toFixed(2);
        }else{
            $scope.saleDetails.making_charge=parseFloat($scope.saleDetails.making_rate).toFixed(2);
        }
    };

    $scope.saleDetailsList=[];
    $scope.addSaleDetailsData=function(sale){
        //$scope.showNotification=false;
        var test=0;
        angular.forEach($scope.saleDetailsList, function(value, key) {

            if(angular.equals(value,sale))
                test++;

        });
        if(test==0){
            var tempSale=angular.copy(sale);
            var total=0;
            $scope.saleDetailsList.unshift(tempSale);
        }else{
            $scope.showNotification=true;

        }

    };

    $scope.setGstFactor=function () {
        var stateId=$scope.saleMaster.customer.state_id;
        if(stateId=='19'){
            $scope.saleDetails.cgstFactor=0.5;
            $scope.saleDetails.sgstFactor=0.5;
            $scope.saleDetails.igstFactor=0.0;
        }else{
            $scope.saleDetails.cgstFactor=0.0;
            $scope.saleDetails.sgstFactor=0.0;
            $scope.saleDetails.igstFactor=1;
        }
    };
    //Get sgst cgst igst rate
    $scope.setGstRate=function(){

        var gst=$scope.saleDetails.productGroup.gst_rate;
        $scope.saleDetails.cgstRate=(gst * $scope.saleDetails.cgstFactor)/100;
        $scope.saleDetails.sgstRate=(gst * $scope.saleDetails.sgstFactor)/100;
        $scope.saleDetails.igstRate=(gst * $scope.saleDetails.igstFactor)/100;
    };


   $scope.$watch("[saleDetails.amount,saleDetails.making_charge,saleDetails.other_charge]", function(newValue, oldValue){
        if(newValue != oldValue){
            var taxableAmount=0;
            for(i=0;i<newValue.length;i++){
                taxableAmount+=parseFloat(newValue[i]);
            }
            $scope.saleDetails.taxableAmount=parseFloat(taxableAmount.toFixed(2));
            $scope.saleDetails.sgst=parseFloat($scope.saleDetails.taxableAmount * $scope.saleDetails.sgstRate).toFixed(2);
            $scope.saleDetails.cgst=parseFloat($scope.saleDetails.taxableAmount * $scope.saleDetails.cgstRate).toFixed(2);
            $scope.saleDetails.igst=parseFloat($scope.saleDetails.taxableAmount * $scope.saleDetails.igstRate).toFixed(2);
        }
    });

   /* $scope.$watchCollection('saleDetails', function(newValues) {
                var taxableAmount=0;
                 console.log(newValues);
                taxableAmount=parseFloat(newValues.amount)+parseFloat(newValues.making_charge);
                console.log(taxableAmount);

    });*/


});

