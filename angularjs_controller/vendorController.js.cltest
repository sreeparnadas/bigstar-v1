app.controller("vendorCtrl", function ($scope,$http,uploadService) {
    $scope.msg = "This is vendor controller";

    $scope.sort = {
        active: '',
        descending: undefined
    };

    $scope.isUpdateable=false;
    $scope.defaultVendor={
        "person_id": "",
        "person_name": "",
        "mailing_name": "",
        "mobile_no": "",
        "phone_no": "",
        "email": "",
        "aadhar_no": "",
        "pan_no": "",
        "address1": "",
        "city": "",
        "district_id": "0",
        "post_office": "",
        "pin": "",
        "gst_number": "",
        "state_id": "19"
    };
    $scope.vendor={
        person_id: "",
        person_name: " ",
        mailing_name: "",
        mobile_no: "",
        phone_no: "",
        email: "",
        aadhar_no: "",
        pan_no: "",
        address1: "",
        city: "",
        district_id: "",
        post_office: "",
        pin: "",
        gst_number: "",
        state_id: "19"
    };
    var request = $http({
        method: "post",
        url: site_url+"/vendor/get_states",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.states=response.data.records;
    });

    var request = $http({
        method: "post",
        url: site_url+"/vendor/get_vendors",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.vendorList=response.data.records;
    });


    $scope.tab = 1;

    $scope.setTab = function(newTab){
        $scope.vendor = angular.copy($scope.defaultVendor);
        $scope.tab = newTab;
        if(newTab==1)
            $scope.isUpdateable=false;
    };

    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
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


    $scope.selectStates=function(stateID){
        var request = $http({
            method: "post",
            url: site_url+"/vendor/get_districts",
            data: {
                stateID: stateID
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            //$('#feature').html(response);
            $scope.districts=response.data.records;
        });
    };

    $scope.updateableVendorIndex=-1;

    $scope.updateVendorFromTable = function(vendor) {
        $scope.vendor = angular.copy(vendor);
        var index=$scope.vendorList.indexOf(vendor);
        $scope.updateableVendorIndex=index;
        $scope.tab=1;
        $scope.isUpdateable=true;
        $scope.selectStates($scope.vendor.state_id);

        $scope.vendorForm.$setPristine();
    };
    $scope.updateVendorByVendorId = function(vendor) {
        $scope.master = angular.copy(vendor);
        var request = $http({
            method: "post",
            url: site_url+"/vendor/update_vendor_by_vendor_id",
            data: {
                vendor: $scope.master
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.reportArray=response.data.records;
            if($scope.reportArray.success==1){
                $scope.isUpdateable=true;
                $scope.vendorList[$scope.updateableVendorIndex]=$scope.vendor;
                $scope.vendorForm.$setPristine();
            }

        });

    };
    $scope.saveVendor = function(vendor) {
        $scope.master = angular.copy(vendor);
        var request = $http({
            method: "post",
            url: site_url+"/vendor/insert_vendor",
            data: {
                vendor: $scope.master
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.reportArray=response.data.records;
            if($scope.reportArray.success==1){
                $scope.isUpdateable=true;
                $scope.vendor.person_id=$scope.reportArray.person_id;
                $scope.vendorList.unshift($scope.vendor);
            }
        });


    };

    $scope.reportArray={message:'New Vendor',success:"0"};
    $scope.reMobile = /^(\+\d{1,3}[- ]?)?\d{10}$/;
    $scope.reGST = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
    $scope.reset = function() {
        $scope.vendor = angular.copy($scope.master);
    };

    //$scope.reset();
    $scope.districts=[
        {district_id: "0", district_name: "--Select--"}
    ];


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

    $scope.copyVendorName = function () {
        $scope.vendor.mailing_name = $scope.vendor.person_name;
    }

    $scope.newVendor = function () {
        $scope.msg = "New Vendor Creation";
        var request=$.ajax({
            type:'get',
            url: site_url+"/vendor/new_vendor_form",
            data:  {},
            success: function(data, textStatus, xhr) {
                $('#main-working-div').html(data)
            }

        });// end of ajax

    };
    $scope.resetVendor=function(){
        $scope.vendor=angular.copy($scope.defaultVendor);
    };

    $scope.$watch('file', function(newfile, oldfile) {
        if(angular.equals(newfile, oldfile) ){
            return;
        }

        uploadService.upload(newfile).then(function(res){
            // DO SOMETHING WITH THE RESULT!
            console.log("result", res);
        })
    });
});

