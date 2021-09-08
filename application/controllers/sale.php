<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('person');
        $this -> load -> model('sale_model');
        //$this -> is_logged_in();
    }
    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}
    function get_products(){
        $result=$this->sale_model->select_inforce_products()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array);
    }


    public function angular_view_sale(){
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
                        margin-top: 10px;
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
                    #custoner-details{
                        list-style-type: none;
                    }
                    growl-notification{
                        color: red;
                    }
                    .td-input{
                        padding: 2px;
                        margin-left: 0px;
                        margin-right: 0px;
                    }
                    table tr td{
                        padding: 0 !important;
                        margin: 0 !important;
                        margin-left: 2px;
                    }



    </style>
    <div class="container-fluid">
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="customer-div">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><span class="glyphicon glyphicon-shopping-cart"></span>&nbspStart Sale</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><span class="glyphicon glyphicon-file"></span>Show Sale Details</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="my-tab-1">
                            <form name="purchaseForm" class="form-horizontal" id="purchaseForm">
                                <div class="row">
                                    <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6col-md-6 col-sm-6 col-xs-6" style="background-color: papayawhip">
                                        <div class="form-group">
                                            <label  class="control-label  col-lg-2 col-md-2 col-sm-2 col-xs-2 ">Customer</label>
                                            <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                <select
                                                    ng-change="setGstFactor()"
                                                    ng-model="saleMaster.customer"
                                                    ng-options="customer as customer.person_name for customer in customerList" ng-change="">
                                                </select>
                                            </div>
                                        </div>

                                        <ul id="custoner-details" ng-show="saleMaster.customer">
                                            <li>Name: {{saleMaster.customer.billing_name}}</li>
                                            <li>{{saleMaster.customer.address1}}</li>
                                            <li>{{saleMaster.customer.city}}, {{purchaseMaster.vendor.post_office}}</li>
                                            <li>Dist. - {{saleMaster.customer.district_name}}, {{saleMaster.customer.state_name}}</li>
                                            <li>GST: {{saleMaster.customer.gst_number}}</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="background-color: lavender">

                                            <div class="form-group">

                                                <label  class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3">Memo number</label>
                                                <div class="controls col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                    <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="purchaseMaster.invoice_no" />
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <label  class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3">Date</label>
                                                <div class="controls col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                    <input type="date" id=FromDate" name="purchaseDate"  ng-model="saleMaster.sale_date" date-format="yyyy-MM-dd" ng-change="saleMaster.sale_date=(saleMaster.sale_date | date:'yyyy-MM-dd')"/>
                                                </div>
                                            </div>
                                        <div class="form-group">
                                            <label  class="control-label col-lg-3 col-md-3 col-sm-3 col-xs-3">Order Number</label>
                                            <div class="controls col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="saleMaster.orderNumber" />
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                </div>
                                <div class="row" id="purchase-details-div" ng-show="saleMaster.customer">
                                    <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" style="background-color: #b2dba1;">
                                             <table class="table table-responsive" id="sale-table">
                                                <thead>
                                                    <tr>
                                                        <th>P.Group</th>
                                                        <th>Product</th>
                                                        <th>Quality</th>
                                                        <th>Quantity</th>
                                                        <th>G.wt</th>
                                                        <th>Net.wt</th>
                                                        <th>Rate</th>
                                                        <th>Amount</th>
                                                        <th>Mk.ch.Type</th>
                                                        <th>Mk.Rt</th>
                                                        <th>Mk.Ch</th>
                                                        <th>Oth.Ch</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                <!--                                            <select ng-model="purchaseDetails.product_id" ng-change="setUnit()" id="product-name">-->
                <!--                                                <option value="{{product.product_id}}"  ng-repeat="product in prductList" unit-id="{{product.unit_id}}" unit-name="{{product.unit_name}}">  {{product.product_name}} </option>-->
                <!--                                            </select>-->
                                                            <select
                                                                ng-model="saleDetails.productGroup"
                                                                ng-options="pGroup as pGroup.group_name for pGroup in productGroupList" ng-change="getProductByGroup();setGstRate()">
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" >
                                                                    <select
                                                                            ng-model="saleDetails.product"
                                                                            ng-options="pName as pName.product_name for pName in productByGroup">
                                                                    </select>


                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <a href="#"  ng-click="showQuality=!showQuality" ng-init="showQuality=false" >{{(saleDetails.quality==null)?(saleDetails.product.quality):(saleDetails.quality.quality)}}</a>
                                                            <div class="form-group" ng-show="showQuality">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" >
                                                                    <select
                                                                            ng-change="showQuality = !showQuality"
                                                                            ng-model="saleDetails.quality"
                                                                            ng-options="quality as quality.quality for quality in productQualityList ">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <input  type="text" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 td-input text-right" ng-keyup="" ng-model="saleDetails.quantity" ng-change="">
                                                        </td>
                                                        <td>
                                                            <input  type="text" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 td-input text-right" ng-keyup="" ng-model="saleDetails.gross_weight" ng-change="setGst()">
                                                        </td>
                                                        <td>
                                                            <input  type="text" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 td-input text-right" ng-keyup="setAmount(); getMakingCharge()" ng-model="saleDetails.net_weight">
                                                        </td>
                                                        <td>
                                                            <input  type="text" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 td-input text-right" ng-keyup="setAmount()" ng-model="saleDetails.rate">
                                                        </td>
                                                        <td>
                                                            <input  type="text" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 td-input text-right" disabled  value="{{saleDetails.amount | number : 2}}">
                                                        </td>
                                                        <td>
                                                            <select ng-model="saleDetails.making_charge_type" ng-change="getMakingCharge()">
                                                                <option value="1">Normal</option>
                                                                <option value="2">Fixed</option>
                                                            </select>

                                                        </td>
                                                        <td>
                                                            <input  type="text" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 td-input text-right" ng-keyup="" ng-model="saleDetails.making_rate" ng-change="getMakingCharge()">
                                                        </td>
                                                        <td>
                                                            <input  type="text" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 td-input text-right" ng-keyup="" ng-model="saleDetails.making_charge" disabled ng-change="setGst()">
                                                        </td>
                                                        <td>
                                                            <input  type="text" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 td-input text-right" ng-keyup="" ng-model="saleDetails.other_charge">
                                                        </td>

                                                        <td><input type="button" value="Add" ng-click="addSaleDetailsData(saleDetails)"></td>
                                                    </tr>
                                                </tbody>
                                                 <tfoot>
                                                 <tr>
                                                     <td colspan="8" class="text-right"><growl-notification ng-if="showNotification">!! Duplicate entry</growl-notification></td>
                                                 </tr>
                                                 </tfoot>

                                            </table>
                                        </div>
                                        <div class="table-responsive" style="background-color: #b8b8b8;">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Rate</th>
                                                <th>Discount</th>
                                                <th>amt.disc</th>
                                                <th>SGST</th>
                                                <th>CGST</th>
                                                <th>IGST</th>
                                                <th>Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody ng-repeat="s in saleDetailsList">
                                            <tr>
                                                <td>{{$index+1}}</td>
                                                <td>{{s.product.product_name}}</td>
                                                <td>{{s.quantity}}&nbsp;</td>
                                                <td><i class="fa fa-inr"></i> {{p.rate | number}} Per {{p.product.unit_name}}</td>
                                                <td>{{p.discount}}</td>
                                                <td>{{getDiscount()}}</td>
                                                <td>{{p.sgst}}</td>
                                                <td>{{p.cgst}}</td>
                                                <td>{{p.igst}}</td>
                                                <td class="text-right"><i class="fa fa-inr"></i> {{p.amount | number}}</td>
                                                <td> <a href="#" data-ng-click="removeRow($index)"><span class="glyphicon glyphicon-remove"></span> Remove </a></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td>Total:</td>
                                                <td colspan="9" class="text-right">{{totalPurchaseAmount | number}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                        <div class="form-group">
                                            <div class="controls col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="button" class="btn pull-right" id="update-purchas-data" ng-click="savePurchase(purchaseMaster,purchaseDetailsDataList)" value="Save"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row" style="background-color: cornsilk;">

<!--                                <pre>productByGroup = {{productByGroup | json}}</pre>-->
<!--                                <pre>saleMaster = {{saleMaster | json}}</pre>-->
                                <pre>saleDetails = {{saleDetails | json}}</pre>
<!--                                <pre>saleDetailsList = {{saleDetailsList | json}}</pre>-->

                            </div>
                        </div>
                    </div>
                    <!--/.Panel 1-->
                    <!--Panel 2-->
                    <div ng-show="isSet(2)">
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
    function save_new_purchase(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        print_r($post_data['purchase_master']);
        print_r($post_data['purchse_details']);
        //print_r($post_data['purchase_data']);
       //$result=$this->purchase_model->insert_new_purchase((object)$post_data['purchase_data']);
        //$report_array['records']=$result;
       // echo json_encode($report_array);

    }


}
?>