<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('person');
        $this -> load -> model('product_model');
        $this -> is_logged_in();
    }
    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}

    function get_inforce_products(){
        $result=$this->product_model->select_inforce_products()->result_array();
        $test=array();
        $k[]=array('id'=>100,'name'=>'xyz');
        $k[]=array('id'=>101,'name'=>'pqr');
        foreach($result as $row){

            $row['units']=$this->db->query('select * from unit_to_product inner join units on unit_to_product.unit_id = units.unit_id where product_id=?',array($row['product_id']))->result_array();
            array_push($test,$row);
        }
        $report_array['records']=$test;
        echo json_encode($report_array);
    }
    public function angular_view_product(){
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
    </style>
    <div class="container-fluid">
        <div class="row">
            {{msg}}
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="vendor-div">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-user" ></i> New Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-heart"></i> Product List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fa fa-envelope"></i>About Product</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="my-tab-1">

                            <form name="vendorForm" class="form-horizontal" id="vendorForm">
                                <div class="form-group">
                                    <label  class="control-label col-md-2">ID</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input name="vendorName" class="textinput textInput form-control capitalizeWord" type="text" ng-disabled="true"  ng-model="vendor.person_id" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Name</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input name="vendorName" class="textinput textInput form-control capitalizeWord" type="text"  required ng-model="vendor.person_name" ng-blur="vendor.mailingName=vendor.vendorName" ng-change="vendor.person_name= (vendor.person_name | capitalize)" />
                                    </div>
                                </div>
                                <!-- Mailing name-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Mailing Name</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-click="copyVendorName()" ng-model="vendor.mailing_name" ng-change="vendor.person_name= (vendor.mailing_name | capitalize)" />
                                    </div>
                                </div>
                                <!-- Email id-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Email id</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="vendor.email" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="control-label col-md-2">Contacts</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" name="mobileNo" class="textinput textInput form-control capitalizeWord" ng-model="vendor.mobile_no"  placeholder="MOBILE" />
                                        <span ng-show="vendorForm.mobileNo.$error.pattern" style="color:red">Please enter correct Mobile No.</span>
                                    </div>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="vendor.phone_no" ng-blur="" placeholder="PHONE" />
                                    </div>
                                </div>
                                <!-- Address-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Address</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="vendor.address1" ng-change="vendor.address1=(vendor.address1 | capitalize)"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="control-label col-md-2">City</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="vendor.city" ng-change="vendor.city=(vendor.city | capitalize)"/>
                                    </div>
                                </div>
                                <!-- Post office-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Post office</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="vendor.post_office" />
                                    </div>
                                    <!-- Pin-->
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="vendor.pin" placeholder="PIN" />
                                    </div>
                                </div>
                                <!-- Aadhsr-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">Aadhar number</label>
                                    <div class="controls col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" name="aadharNumber" class="textinput textInput form-control capitalizeWord" ng-model="vendor.aadhar_no" />
                                    </div>
                                </div>
                                <!-- Gst number-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">GST & PAN</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" name="gst" class="textinput textInput form-control capitalizeWord" ng-model="vendor.gst_number" ng-pattern="" placeholder="GST" />
                                        <span ng-show="vendorForm.gst.$error.pattern" style="color:red">Please enter correct gst No.</span>
                                    </div>
                                    <!-- pan number-->
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="vendor.pan_no" placeholder="PAN" />
                                    </div>
                                </div>
                                <!-- state-->
                                <div class="form-group">
                                    <label  class="control-label col-md-2">State & District</label>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <select ng-model="vendor.state_id" ng-change="selectStates(vendor.state_id)" ng-init="selectStates(vendor.state_id)">
                                            <option value="{{state.state_id}}"  ng-repeat="state in states">  {{state.state_name}} </option>
                                        </select>
                                    </div>
                                    <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <select ng-model="vendor.district_id">
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
                                        <input type="button" ng-click="resetVendor()" value="Reset" />
                                        <input type="button" id="submit-vendor" ng-click="saveVendor(vendor)" value="Save" ng-disabled="vendorForm.$invalid" ng-show="!isUpdateable"/>
                                        <input type="button" id="update-vendor" ng-click="updateVendorByVendorId(vendor)" value="Update" ng-show="isUpdateable" ng-disabled="vendorForm.$pristine"/>
                                    </div>
                                </div>
                            </form>
<!--                            <pre>master = {{master | json}}</pre>-->
<!--                            <pre>vendor = {{vendor | json}}</pre>-->
<!--                            <pre>master = {{master | json}}</pre>-->
<!--                            <pre>database Report = {{reportArray | json}}</pre>-->
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
                            #vendor-table-div table th{
                                background-color: #1b6d85;
                                color: #a6e1ec;
                                cursor: pointer;
                            }
                            a[ng-click]{
                                cursor: pointer;
                            }

                        </style>
                        <p><input type="text" ng-model="searchItem"><span class="glyphicon glyphicon-search"></span> Search </p>
                        <div id="vendor-table-div">
                            <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                <tr>
                                    <th>SL></th>
                                    <th ng-click="changeSorting('person_id')">ID<i class="glyphicon" ng-class="getIcon('person_id')"></i></th>
                                    <th ng-click="changeSorting('person_name')">Name<i class="glyphicon" ng-class="getIcon('person_name')"></i></th>
                                    <th ng-click="changeSorting('mobile_no')">Mobile<i class="glyphicon" ng-class="getIcon('mobile_no')"></i></th>
                                    <th ng-click="changeSorting('address1')">Address<i class="glyphicon" ng-class="getIcon('address1')"></i></th>
                                    <th ng-click="changeSorting('gst_number')">GST no<i class="glyphicon" ng-class="getIcon('gst_number')"></i></th>
                                    <th ng-click="changeSorting('aadhar_no')">AAdhar No<i class="glyphicon" ng-class="getIcon('aadhar_no')"></i></th>
                                    <th ng-click="changeSorting('pan_no')">PAN No<i class="glyphicon" ng-class="getIcon('pan_no')"></i></th>
                                    <th>Edit</th>
                                </tr>
                                <tbody ng-repeat="vendor in vendorList | filter : searchItem  | orderBy:sort.active:sort.descending">
                                <tr ng-class-even="'banana'" ng-class-odd="'bee'">
                                    <td>{{ $index+1}}</td>
                                    <td>{{vendor.person_id}}</td>
                                    <td>{{vendor.person_name}}</td>
                                    <td>{{vendor.mobile_no}}</td>
                                    <td>{{vendor.address1}}</td>
                                    <td>{{vendor.gst_number}}</td>
                                    <td>{{vendor.aadhar_no}}</td>
                                    <td>{{vendor.pan_no}}</td>
                                    <td ng-click="updateVendorFromTable(vendor)"><a href="#"><i class="glyphicon glyphicon-edit"></i></a></td>
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


}
?>