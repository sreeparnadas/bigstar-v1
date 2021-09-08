<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockistLimit extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Stockist_limit_model');
        $this -> is_logged_in();
    }
   
   function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		$person_cat_id = $this -> session -> userdata('person_cat_id');
		if (!isset($is_logged_in) || $is_logged_in != 1 || $person_cat_id!=1) {
			echo 'you have no permission to use admin area'. '<a href="#!" ng-click="goToFrontPage()">Login</a>';
			die();
		}
	}
    function get_products(){
        $result=$this->sale_model->select_inforce_products()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array);
    }


    public function angular_view_limit(){
        ?>
        <style type="text/css">
            #search-results {
                max-height: 200px;
                border: 1px solid #dedede;
                border-radius: 3px;
                box-sizing: border-box;
                overflow-y: auto;
            }
        </style>
        <div class="d-flex">
            <div class="p-2 my-flex-item col-12">
                                <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                    <!-- Brand -->
                    <a class="navbar-brand" href="#">Big Star</a>
                        <!-- Links -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" ng-href="{{base_url + '#!cpanel'}}">Home</a>
                            </li>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon bg-light"></span>
                            </button>
                            <!-- Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                    Master
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#!stockist"> Stockist</a>
                                    <a class="dropdown-item" href="#!terminal">Terminal</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                    Limits
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#!stlim"> Stockist</a>
                                    <a class="dropdown-item" href="#!trlim">Terminal</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                    Game Setting
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#!payout"></i> Payout Setting</a>
                                    <a class="dropdown-item" href="#!manualresult"></i> Manual Result</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                    Report
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#!custSalesReportCtrl"></i>Customer Sales Report</a>
                                    <a class="dropdown-item" href="#!barcodereport"></i>Barcode Report</a>
                                </div>
                            </li>
                            
                             <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                    Close Session
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#!termsession"></i>Terminal</a>
                                    
                                </div>
                            </li>
                            
                            
                        </ul>
                        <div class="navbar-collapse">
					        <ul class="navbar-nav ml-auto">
					            <li class="nav-item">
					                <a class="nav-link btn btn-info text-white" href="#" ng-click="logoutCpanel()"><b>Logout</b></a>
					                
					            </li>
					        </ul>
					    </div>
                        
                </nav>
            </div>

        </div>
        <div class="d-flex col-12">
            <div class="col-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" ng-style="tab==1 && selectedTab" href="#" role="tab" ng-click="setTab(1)"><i class="fas fa-user-graduate"></i></i>Recharge</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="row my-tab-1">
                            <form name="stockistForm" class="form-horizontal">
                                <div class="d-flex justify-content-center ">
                                    <div class="col">
                                        <div class="d-flex  mt-1">
                                            <label  class="col-3">Select Stockist<span class="text-danger"></span></label>
                                            <div class="col-3">
                                                <select ng-disabled="isUpdateable"
                                                        class="form-control "
                                                        data-ng-model="limit.stockist"
                                                        data-ng-options="x as x.user_id for x in stockistList" ng-change="">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex  mt-1">
                                            <label  class="col-3">Current Balance<span class="text-danger"></span></label>
                                            <div class="col-3">
                                                <input type="text" class="form-control text-right" ng-model="limit.stockist.current_balance"  readonly/>
                                            </div>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <label  class="col-3">Amount<span class="text-danger"></span></label>
                                            <div class="col-3">
                                                <input type="text" class="form-control text-right" ng-model="limit.amount" required/>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center mt-2">
                                            <div class="col-4">
                                                <input type="button" class="btn btn-secondary"  ng-click="saveStockistRechargeData(limit)" ng-disabled="stockistForm.$invalid" value="Save"/>
                                                <input type="button" class="btn btn-secondary"  ng-click="resetRechargeDetails()" value="Clear"/>
<!--                                                <input type="button" class="btn btn-secondary ml-2"  ng-click="updateStockistByStockistId(stockist)" value="Update" ng-show="isUpdateable" ng-disabled="stockistForm.$pristine"/>-->
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center mt-2">
                                            <div class="">
                                                <span ng-show="submitStatus" class="text-success">Recharge successful</span>
                                                <span ng-show="updateStatus" class="text-success">Update successful</span>
                                            </div>
                                        </div>

                                        <!-- Search -->
<!--                                        <input type="text" class="form-control col-3" placeholder="Search" ng-model="query" ng-focus="focus=true">-->
<!--                                        <div id="search-results" ng-show="focus">-->
<!--                                            <div class="search-result col-3" ng-repeat="item in stockistList | search:query"  ng-click="setQuery(item.stockist_name)">{{item.stockist_name}}</div>-->
<!--                                        </div>-->

                                        <div class="d-flex mt-1">
                                            <div class="col-3">
<!--                                                <pre>stockistList={{stockistList | json}}</pre>-->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> <!--//End of my tab1//-->
                    </div>




                    <div ng-show="isSet(3)">
                        <style type="text/css">

                        </style>

                    </div>

                    <div ng-show="isSet(4)">
                        <div id="row my-tab-4">


                        </div> <!--//End of my tab1//-->
                    </div>

                    <!--                    Show Mustard Oil Bill-->

                </div>
            </div>
        </div>

        <?php
    }
    function save_stockist_recharge_details(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Stockist_limit_model->insert_stockist_recharge($post_data['stockist_id'],$post_data['amount']);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }

    public function get_all_stockist(){
        $result=$this->Stockist_limit_model->select_all_stockist()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array);
    }

    function update_stockist_by_stockist_id(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Stockist_model->update_stockist_details((object)$post_data['stockist']);
        $report_array['records']=$result;
        echo json_encode($report_array);
    }

    public function get_current_user_id(){
        $result=$this->Stockist_model->select_next_user_id_for_stockist();
        echo $result;
    }


}
?>