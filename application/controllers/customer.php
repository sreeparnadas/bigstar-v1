<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Customer_model');
        $this -> is_logged_in();
    }
    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}


    public function get_areas(){
        $result=$this->person->select_areas()->result_array();
        //$report_array['records']=$result;
        echo json_encode($result);
    }

    public function get_cities(){
        $result=$this->person->select_cities()->result_array();
        //$report_array['records']=$result;
        echo json_encode($result);
    }

    public function get_post_office(){
        $result=$this->person->select_post_office()->result_array();
        //$report_array['records']=$result;
        echo json_encode($result);
    }

    public function get_address_one(){
        $result=$this->person->select_address_one()->result_array();
        //$report_array['records']=$result;
        echo json_encode($result);
    }



    public function get_states(){
        $result=$this->person->select_states()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array);
    }
    public function get_districts(){
        $post_data =(object)json_decode(file_get_contents("php://input"), true);
        $state_id=$post_data->stateID;
        $result=$this->person->select_districts($state_id)->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array);
    }


    public function angular_view_customer(){
        ?>
                <style type="text/css">
                    .navbar-fixed-top {
                        border: none;
                        background: #ac2925;

                        margin-top: -20px;
                    }
                    .navbar-fixed-top a{
                        color: #a6e1ec;
                    }
                    #vendor-div{
                        margin-top: 0px;
                    }
                    h1{
                        color: blue;
                    }
                    #mySidenav a[ng-click]{
                        cursor: pointer;
                        position: absolute;
                        left: -20px;
                        transition: 0.3s;
                        padding: 15px;
                        width: 140px;
                        text-decoration: none;
                        font-size: 15px;
                        color: white;
                        border-radius: 0 5px 5px 0;
                        background-color: #ac2925;
                    }

                    #mySidenav a[ng-click]:hover {
                        left: 0;
                    }

                    #mySidenav a:hover {
                        left: 0;
                    }
                    #new-vendor {
                        top: 20px;
                        background-color: #4CAF50;
                    }

                    #update-vendor {
                        top: 78px;
                        background-color: #2196F3;
                    }

                    #show-vendor{
                        top: 136px;
                        background-color: #f44336;
                    }
                    #main-working-div h1{
                        color: darkblue;
                    }
                    #vendor-form input{
                        border-radius: 5px;
                    }
                    #vendorForm{
                        margin-top: 10px;
                     }
                     input.ng-invalid {
                        background-color: pink;
                    }



                    .ui-autocomplete {
                        /* will be fixed by options.outHeight */
                        max-height: 300px;
                        min-height: 50px;
                        max-width: 200px;
                        overflow-y: auto;
                        /* prevent horizontal scrollbar */
                        overflow-x: hidden;
                        /* add padding to account for vertical scrollbar */
                        padding-right: 0;
                        padding-left: 5px;
                        z-index: 9999;
                    }
                    .ui-helper-hidden-accessible {
                        display: none;
                    }
                    .ui-menu-add {
                        text-align: center;
                    }
                    .ui-menu-group {
                        margin-right: 10px;
                        text-align: right;
                        color: red;
                    }
                    .ui-state-hover,
                    .ui-state-focus,
                    .ui-state-active {
                        border: none;
                        background: none;
                        cursor: pointer;
                    }
                    /* Scrollbar Styles */
                    ::-webkit-scrollbar {
                        background-color: transparent;
                        width: 15px;
                    }
                    ::-webkit-scrollbar-thumb {
                        background: #aaa;
                        min-height: 20px;
                    }
                    ::-webkit-scrollbar-track {
                        background: #ddd;
                    }
                    .text-demo {
                        color: red;
                    }
                    .ui-menu-item{
                        background-color: ivory;
                        color: blue;
                    }
                    #ui-id-1 li{
                        list-style: none;
                        padding-left: 10px;
                    }




    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="customer-div">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-user" ></i> New Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-heart"></i> Customer List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fa fa-envelope"></i>Update Customers</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="my-tab-1">

                            <form name="customerForm" class="form-horizontal" id="customerForm">
                                <div class="form-group">
                                    <label  class="control-label col-md-2">ID</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input name="customerName" class="textinput textInput form-control capitalizeWord" type="text" ng-disabled="true"  ng-model="customer.person_id" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Name</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input name="customerName" class="textinput textInput form-control capitalizeWord" type="text"  required ng-model="customer.person_name" ng-blur="customer.billing_name=customer.person_name" ng-change="customer.person_name= (customer.person_name | capitalize)" />
                                    </div>
                                </div>
                                <!-- Mailing name-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Billing Name</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-click="copyCustomerName()" ng-model="customer.billing_name" ng-change="customer.billing_name=(customer.billing_name | capitalize)"/>
                                    </div>
                                </div>
<!--                                sex-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Sex</label>
                                    <div class="controls col-lg-1 col-md-1 col-sm-1 col-xs-1">
<!--                                        <input type="text" class="textinput textInput form-control capitalizeWord"  ng-model="customer.sex" />-->
                                        <select class="form-control" ng-model="customer.sex">
                                            <option value="Male" selected>Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Email id-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Email id</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="customer.email" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="control-label col-md-2">Contacts</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" name="mobileNo" class="textinput textInput form-control capitalizeWord" ng-model="customer.mobile_no"  placeholder="MOBILE" />
                                        <span ng-show="customerForm.mobileNo.$error.pattern" style="color:red">Please enter correct Mobile No.</span>
                                    </div>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="customer.phone_no" ng-blur="" placeholder="PHONE" />
                                    </div>
                                </div>
                                <!-- Address-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Address 1</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ui-autocomplete="myOptionAddressOne" ng-model="customer.address1" ng-change="customer.address1=(customer.address1 | capitalize)"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Address 2</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="customer.address2" ng-change="customer.address2=(customer.address2 | capitalize)"  />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="control-label col-md-2">Area & City</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control" ng-model="customer.area" ui-autocomplete="myOption"  ng-change="customer.area=(customer.area | capitalize)" placeholder="AREA" />
                                    </div>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord"  ng-model="customer.city" ui-autocomplete="myOptionCity" ng-change="customer.city=(customer.city | capitalize)" placeholder="CITY"/>
                                    </div>
                                </div>
                                <!-- Post office-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Post office</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="customer.post_office" ui-autocomplete="myOptionPostOffice" ng-change="customer.post_office=(customer.post_office | capitalize)"/>
                                    </div>
                                    <!-- Pin-->
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="customer.pin" placeholder="PIN" />
                                    </div>
                                </div>
                                <!-- Aadhsr-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Aadhar number</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" name="aadharNumber" class="textinput textInput form-control capitalizeWord" ng-model="customer.aadhar_no" />
                                    </div>
                                </div>
                                <!-- Gst number-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">GST & PAN</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" name="gst" class="textinput textInput form-control capitalizeWord" ng-model="customer.gst_number" ng-pattern="" placeholder="GST" />
                                        <span ng-show="customerForm.gst.$error.pattern" style="color:red">Please enter correct gst No.</span>
                                    </div>
                                    <!-- pan number-->
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="customer.pan_no"  placeholder="PAN" />
                                    </div>
                                </div>
                                <!-- state-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">State & District</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <select ng-model="customer.state_id" ng-change="selectState(customer.state_id)" ng-init="selectState(customer.state_id)">
                                            <option value="{{state.state_id}}"  ng-repeat="state in states">  {{state.state_name}} </option>
                                        </select>
                                    </div>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <select ng-model="customer.district_id">
                                            <option value="{{district.district_id}}"  ng-repeat="district in districts">  {{district.district_name}} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    </div>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    </div>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" ng-click="resetCustomer()" value="Reset" />
                                        <input type="button" id="submit-customer" ng-click="saveCustomer(customer)" value="Save" ng-disabled="customerForm.$invalid" ng-show="!isUpdateable"/>
                                        <input type="button" id="update-customer" ng-click="updateCustomerByCustomerId(customer)" value="Update" ng-show="isUpdateable" ng-disabled="customerForm.$pristine"/>
                                        <input type="button"  ng-click="testState()" value="Test" />
                                    </div>
                                </div>
                            </form>

                          <pre>customer = {{customer | json}}</pre>
                            <pre>master = {{master | json}}</pre>
                            <pre>database Report = {{reportArray | json}}</pre>
                        </div>
                    </div>
                    <!--/.Panel 1-->
                    <!--Panel 2-->
                    <div ng-show="isSet(2)">
                        <style type="text/css">
                            .bee{
                                background-color: #d9edf7;
                            }
                            .banana{
                                background-color: #c4e3f3;
                            }
                            #customer-table-div table th{
                                background-color: #1b6d85;
                                color: #a6e1ec;
                                cursor: pointer;
                            }
                            a[ng-click]{
                                cursor: pointer;
                            }

                        </style>
                        <p>
                            <input type="text" ng-model="searchItem"><span class="glyphicon glyphicon-search"></span> Search
                            <button ng-click="saveToExcel('customers.xls')">Save to excel</button>
                            <button ng-click="saveToCSV('customers.csv')">Save to CSV</button>
                        </p>
                        <div id="customer-table-div">
                            <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                <tr>
                                    <th>SL></th>
                                    <th ng-click="changeSorting('person_id')">ID<i class="glyphicon" ng-class="getIcon('person_id')"></i></th>
                                    <th ng-click="changeSorting('person_name')">Name<i class="glyphicon" ng-class="getIcon('person_name')"></i></th>
                                    <th ng-click="changeSorting('mobile_no')">Mobile<i class="glyphicon" ng-class="getIcon('mobile_no')"></i></th>
                                    <th ng-click="changeSorting('address1')">Address<i class="glyphicon" ng-class="getIcon('address1')"></i></th>
                                    <th ng-click="changeSorting('Area')">Area<i class="glyphicon" ng-class="getIcon('area')"></i></th>
                                    <th ng-click="changeSorting('gst_number')">GST no<i class="glyphicon" ng-class="getIcon('gst_number')"></i></th>
                                    <th ng-click="changeSorting('aadhar_no')">AAdhar No<i class="glyphicon" ng-class="getIcon('aadhar_no')"></i></th>
                                    <th ng-click="changeSorting('pan_no')">PAN No<i class="glyphicon" ng-class="getIcon('pan_no')"></i></th>
                                    <th>Edit</th>
                                </tr>
                                <tbody ng-repeat="customer in customerList | filter : searchItem  | orderBy:sort.active:sort.descending">
                                <tr ng-class-even="'banana'" ng-class-odd="'bee'">
                                    <td>{{ $index+1}}</td>
                                    <td>{{customer.person_id}}</td>
                                    <td>{{customer.person_name}}</td>
                                    <td>{{customer.mobile_no}}</td>
                                    <td>{{customer.address1}}</td>
                                    <td>{{customer.area}}</td>
                                    <td>{{customer.gst_number}}</td>
                                    <td>{{customer.aadhar_no}}</td>
                                    <td>{{customer.pan_no}}</td>
                                    <td ng-click="updateCustomerFromTable(customer)"><a href="#"><i class="glyphicon glyphicon-edit"></i></a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

<!--                        <pre>vendor List = {{vendorList | json}}</pre>-->
<!--                        <pre>vendor = {{vendor | json}}</pre>-->
<!--                        <pre>vendors = {{vendorList | json}}</pre>-->
                    </div>
                    <!--/.Panel 2-->
                    <!--Panel 3-->
                    <div ng-show="isSet(3)">
                        This is our help area
                    </div>
                    <!--/.Panel 3-->
                </div>
            </div>

        </div>
    </div>







        <?php
    }
    public function insert_customer(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->customer_model->insert_new_customer((object)$post_data['customer']);
        $report_array['records']=$result;
        echo json_encode($report_array);
    }
    public function get_customers(){
        $result=$this->customer_model->select_customers()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array);
    }
    public function update_customer_by_customer_id(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->customer_model->update_customer_by_customer_id((object)$post_data['customer']);
        $report_array['records']=$result;
        echo json_encode($report_array);
    }


}
?>