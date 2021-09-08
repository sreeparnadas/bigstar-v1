<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terminal extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Terminal_model');
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



    public function angular_view_terminal(){
        ?>
        <style type="text/css">

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
                        <a class="nav-link" data-toggle="tab" ng-style="tab==1 && selectedTab" href="#" role="tab" ng-click="setTab(1)"><i class="fas fa-user-graduate"></i></i>Create Terminal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" ng-style="tab==2 && selectedTab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-envelope"></i>Terminal list</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="row my-tab-1">
                            <form name="terminalForm" class="form-horizontal">
                                <div class="d-flex justify-content-center ">
                                    <div class="col">
                                        <div class="d-flex mt-1">
                                            <div class="col-3">Select Stockist</div>
                                            <div class="col-3">
                                                <select class="form-control" ng-change="getNextUserId(terminal.stockist.serial_no,terminal.stockist.stockist_id)"
                                                        data-ng-model="terminal.stockist"
                                                        data-ng-options="st as st.user_id for st in stockistList">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex mt-1">
                                            <label  class="col-3">Terminal Name<span class="text-danger"></span></label>
                                            <div class="col-3">
                                                <input type="text" class="form-control" ng-model="terminal.person_name" ng-change="terminal.person_name=(terminal.person_name | capitalize)" required/>
                                            </div>
                                        </div>
                                        <div class="d-flex  mt-1">
                                            <label  class="col-3">Login Id<span class="text-danger"></span></label>
                                            <div class="col-3">
                                                <input type="text" class="form-control" ng-model="terminal.user_id"  readonly/>
                                            </div>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <label  class="col-3">Password<span class="text-danger"></span></label>
                                            <div class="col-3">
                                                <input type="text" class="form-control" ng-model="terminal.user_password" required/>
                                            </div>
                                            <div class="col-3">
                                                <input type="button" class="btn btn-success"  ng-click="randomPass(8,true,true,true)" value="Generate password" />
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center mt-2">
                                            <div class="col-4">
                                                <input type="button" class="btn btn-secondary"  ng-click="saveTerminalData(terminal)" ng-disabled="terminalForm.$invalid" value="Save" ng-show="!isUpdateable"/>
                                                <input type="button" class="btn btn-secondary"  ng-click="resetTerminalDetails()" value="Reset" ng-disabled="terminalForm.$pristine"/>
                                                <input type="button" class="btn btn-secondary ml-2"  ng-click="updateTerminalByTerminalId(terminal)" value="Update" ng-show="isUpdateable" ng-disabled="terminalForm.$pristine"/>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center mt-2">
                                            <div class="">
                                                <span ng-show="submitStatus" class="text-success">Record successfully added</span>
                                                <span ng-show="updateStatus" class="text-success">Update successful</span>
                                            </div>
                                        </div>
<!--                                        <div class="d-flex mt-1">-->
<!--                                            <div class="col-3">-->
<!--                                                <pre>terminal={{terminal | json}}</pre>-->
<!--                                                <pre>terminalReport={{terminalReport | json}}</pre>-->
<!--                                                <pre>stockistList={{stockistList | json}}</pre>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                    </div>
                                </div>
                            </form>
                        </div> <!--//End of my tab1//-->
                    </div>

                    <div ng-show="isSet(2)">
                        <div id="my-tab-2">
                            <style type="text/css">
                                .bee{
                                    background-color: #d9edf7;
                                }
                                .banana{
                                    background-color: #c4e3f3;
                                }
                                #stockist-table-div table th{
                                    background-color: #1b6d85;
                                    color: #a6e1ec;
                                    cursor: pointer;
                                }
                                a[ng-click]{
                                    cursor: pointer;
                                }

                            </style>
                            <p><input type="text" ng-model="searchItem"><span class="glyphicon glyphicon-search"></span> Search </p>
                            <div id="stockist-table-div" class="d-flex">
                                <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                    <tr>
                                        <th>SL></th>
                                        <th ng-click="changeSorting('terminal_name')">Terminal<i class="glyphicon" ng-class="getIcon('terminal_name')"></i></th>
                                        <th ng-click="changeSorting('user_id')">Login Id<i class="glyphicon" ng-class="getIcon('user_id')"></i></th>
                                        <th ng-click="changeSorting('user_password')">Password<i class="glyphicon" ng-class="getIcon('user_password')"></i></th>
                                        <th ng-click="changeSorting('stockist_name')">Stockist<i class="glyphicon" ng-class="getIcon('stockist_name')"></i></th>
                                        <th>Edit</th>
                                    </tr>
                                    <tbody ng-repeat="x in terminalList | filter : searchItem  | orderBy:sort.active:sort.descending">
                                    <tr ng-class-even="'banana'" ng-class-odd="'bee'">
                                        <td>{{ $index+1}}</td>
                                        <td>{{x.person_name}}</td>
                                        <td>{{x.user_id}}</td>
                                        <td>{{x.user_password}}</td>
                                        <td>{{x.stockist_name}}</td>
                                        <td ng-click="updateTerminalFromTable(x)"><a href="#"><i class="fa fa-edit"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    function save_new_terminal(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Terminal_model->insert_new_terminal((object)$post_data['terminal'],$post_data['stockist_sl_no'],$post_data['stockist_id']);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }

    public function get_all_stockist(){
        $result=$this->Terminal_model->select_all_stockist()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array);
    }

    public function get_all_terminal(){
        $result=$this->Terminal_model->select_all_terminal()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array);
    }


    function update_terminal_by_terminal_id(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Terminal_model->update_terminal_details((object)$post_data['terminal']);
        $report_array['records']=$result;
        echo json_encode($report_array);
    }


    public function get_current_user_id(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Terminal_model->select_next_user_id_for_terminal($post_data['serialNo'],$post_data['stockistId']);
        echo $result;
    }


}
?>