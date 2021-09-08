<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BarcodeReport extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Barcode_report_model');
        $this -> load -> model('cust_sale_report_model');
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




    public function angular_view_terminal_report(){
        ?>
        <style type="text/css">
            #search-results {
                max-height: 200px;
                border: 1px solid #dedede;
                border-radius: 3px;
                box-sizing: border-box;
                overflow-y: auto;
            }
            .report-table tr th,.report-table tr td{
                border: 1px solid black !important;
                font-size: 12px;
                line-height: 0px;
            }
            .tfoot-style tr td{
                font-size: 18px;
                background-color: #ACD2DD;
                line-height: 0px;
                border: 1px solid black !important;

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
                        <a class="nav-link" data-toggle="tab" ng-style="tab==1 && selectedTab" href="#" role="tab" ng-click="setTab(1)">Barcode Wise</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="row my-tab-1">
                            <div class="d-flex justify-content-center mb-1">
                                <div class="col-2" ng-show="!selectDate"><input type="text" class="form-control" ng-model="winning_date" ng-change="changeDateFormat(start_date)" readonly></div>
                                <div class="col-2" ng-show="selectDate"><input type="date" class="form-control" ng-model="barcode_report_date" ng-change="changeDateFormat(start_date)"></div>

                                <div class="col-1">
                                    <select class="form-control " ng-change="getDrawList(select_game.id)"
                                            data-ng-model="select_game"
                                            data-ng-options="x as x.name for x in gameList">
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select ng-model="select_terminal" class="form-control"> 
                                        <option value="0" selected disabled>Select Terminal</option>
                                        <option value="0" selected="All">All</option>
                                        <option ng-repeat="x in terminalList" value="{{x.user_id}}">
                                            {{x.user_id}}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-1">
                                    <select ng-model="select_draw_time" class="form-control">
                                        <option value="0" selected disabled>Select Draw Time</option>
                                        <option value="0" selected="All">All</option>
                                        <option ng-repeat="x in drawTime" value="{{x.draw_master_id}}">
                                            {{(x.end_time |limitTo: 5) + ' '+ (x.meridiem)}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control" ng-model="select_barcode" placeholder="Enter Barcode">
                                </div>

                                <div class="ml-2"><input type="button" class="btn btn-info form-control" value="Show" ng-click="getAllBarcodeDetailsByDate(barcode_report_date,select_game.id,select_terminal,select_draw_time)"></div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="loader mt-1" ng-show="isLoading2"></div>
                            </div>


                            <div class="d-flex justify-content-between" ng-show="!isLoading2">

                                <div class="col">
                                    <table cellpadding="0" cellspacing="0" class="table table-hover   text-justify">
                                        <thead class="report-table">
                                            <tr>
                                                <th>SL</th>
                                                <th>Terminal Id</th>
                                                <th>D.Time</th>
                                                <th>T.Time</th>
                                                <th>Barcode</th>
                                                <th>Qty</th>
                                                <th>Amount</th>
                                                <th>Prize</th>
                                                <th>Claimed</th>
                                                <!--                                            <th>Particulars</th>-->
                                                <th>Particulars</th>
                                                <th ng-show="select_barcode_type.id==2"></th>
                                            </tr>
                                        </thead>

                                        <tbody ng-repeat="x in barcodeWiseReport | filter : select_barcode" class="report-table">
                                            <tr>
                                                <td>{{ $index+1}}</td>
                                                <td>{{x.user_id}}</td>
                                                <td>{{x.draw_time +' '+ x.meridiem}}</td>
                                                <td>{{x.ticket_taken_time}}</td>
                                                <td>{{x.barcode}}</td>
                                                <td>{{x.quantity |number:2}}</td>
                                                <td>{{x.amount |number:2}}</td>
                                                <td>{{x.prize_value |number:2}}</td>
                                                <td>{{x.claimed}}</td>
                                                <!--                                            <td style="word-wrap: break-word;min-width: 500px;max-width: 500px;">{{x.particulars}}</td>-->
                                                <td>
                                                    <!--                                                <button type="button" data-toggle="modal" data-target="#flipFlop" ng-click="showParticulars($index)">-->
                                                    <!--                                                    Click here-->
                                                    <!--                                                </button>-->

                                                    <a href="#" type="button" data-toggle="modal" data-target="#flipFlop" ng-click="showParticulars($index)">
                                                        Click here
                                                    </a>
                                                </td>
                                                <td ng-show="select_barcode_type.id==2">
                                                    <input type="button" value="Claim" class="btn btn-secondary" ng-click="claimedBarcodeForPrize(x,select_game.id)" ng-show="x.is_claimed == 0">
                                                    <input type="button" value="Claimed" class="btn btn-success" ng-disabled="true" ng-show="x.is_claimed == 1">
                                                    <!--                                                <i class="fa fa-check fa-lg" aria-hidden="true" style="color:green" ng-show="x.is_claimed == 1"></i>-->
                                                </td>
                                            </tr>
                                        </tbody>

                                        <tfoot ng-show="barcodeWiseReport.length" class="tfoot-style">
                                            <tr>
                                                <td class="text-center" colspan="4">Grand Total</td>
                                                <td class="text-right" colspan="3">{{(barcodeWiseReportFooter.total_amount) | number:2}}</td>
                                                <td class="" colspan="3">{{(barcodeWiseReportFooter.total_prize_value) | number:2}}</td>

                                            </tr>
                                            <tr class="text-center">
                                                <td colspan="12" class="font-weight-bold">Net Balance &nbsp;
                                                    {{((barcodeWiseReportFooter.total_amount) - (barcodeWiseReportFooter.total_prize_value)) | number:2}}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="d-flex justify-content-center" ng-show="alertMsg2">
                                        <div>No records found</div>
                                    </div>
                                </div>

                            </div>


                            <!-- The modal -->
                            <div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="modalLabel">View details</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="word-wrap: break-word">
                                            {{barcodeWiseReport[target].particulars}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--                                                        <div class="d-flex">-->
                            <!--                                                            <div class="col">{{select_game}}jh-->
                            <!--                                                                <i class="fa fa-check fa-lg" ng-show="select_game.id == 1"></i>-->
<!--                                                                                            <pre>barcodeWiseReportFooter={{barcodeWiseReportFooter | json}}</pre>-->
                            <!--                                                            </div>-->
                            <!--                                                            <div class="col"><pre>showbarcodeReport={{showbarcodeReport | json}}</pre></div>-->
                            <!--                                                            <div class="col"></div>-->
                            <!--                                                        </div>-->
                        </div> <!--//End of my tab1//-->

<!--                                                <pre>terminalList={{terminalList | json}}</pre>-->
                        <!--                        <pre>saleReportFooter={{saleReportFooter | json}}</pre>-->
                    </div>


                </div>
            </div>
        </div>

        <?php
    }





    public function get_2d_report_order_by_barcode(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Barcode_report_model->get_all_barcode_report_by_date($post_data['start_date'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }

    public function get_2d_draw_time(){
        $result=$this->Barcode_report_model->get_all_2d_draw_time_list()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_card_draw_time(){
        $result=$this->Barcode_report_model->get_card_draw_time_list()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }


    public function get_card_report_order_by_barcode(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Barcode_report_model->get_card_game_barcode_report_by_date($post_data['start_date'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }
     public function insert_claimed_barcode_details(){
            $post_data =json_decode(file_get_contents("php://input"), true);
            $result=$this->Barcode_report_model->insert_claimed_barcode($post_data['barcode'],$post_data['game_id'],$post_data['prize_value']);
            $report_array['records']=$result;
            echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }




}
?>