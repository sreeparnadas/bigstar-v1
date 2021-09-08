<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportTerminal extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Report_terminal_model');
//        $this -> is_logged_in();
    }

    function is_logged_in() {
        $is_logged_in = $this -> session -> userdata('is_logged_in');
        $person_cat_id = $this -> session -> userdata('person_cat_id');
        if (!isset($is_logged_in) || $is_logged_in != 1 || $person_cat_id!=3) {
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
                            <a class="nav-link" href="#" onclick="self.close()">Close</a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
        <div class="d-flex col-12">
            <div class="col-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" ng-style="tab==1 && selectedTab" href="#" role="tab" ng-click="setTab(1)">Total Sale</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" ng-style="tab==2 && selectedTab" href="#" role="tab" ng-click="setTab(2)">Barcode Wise</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="row my-tab-1">
                            <form name="stockistForm" class="form-horizontal">
                                <div class="card">

                                    <div class="card-header">
                                        <div class="d-flex justify-content-center">
                                            <div class=""><input type="date" class="form-control" ng-model="start_date" ng-change="changeDateFormat(start_date)"></div>
                                            <div class="ml-2 mr-2">To</div>
                                            <div class=""><input type="date" class="form-control" ng-model="end_date" ng-change="changeDateFormat(end_date)"></div>
                                            <div class="ml-2"><input type="button" class="btn btn-info form-control" value="Show" ng-click="getNetPayableDetailsByDate(start_date,end_date)"></div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <div class="loader mt-1" ng-show="isLoading"></div>
                                        </div>

                                        <div class="d-flex" ng-show="!isLoading">
                                            <div class="col-3"></div>
                                            <div class="col-6">
                                                <table cellpadding="0" cellspacing="0" class="table table-bordered table-hover small text-justify">
                                                    <thead>
                                                    <tr>
                                                        <th class="p-0 pl-1 text-center col-1">Date</th>
                                                        <th class="p-0 pl-1 text-center col-1">Amount</th>
                                                        <th class="p-0 pl-1 text-center col-1">Commission</th>
                                                        <th class="p-0 pl-1 text-center col-1">Prize Value</th>
                                                        <th class="p-0 pl-1 text-center col-1">Net payable</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr ng-show="saleReport.length">
                                                        <td class="p-0 pl-1 text-left font-weight-bold" colspan="5">2 DIGIT</td>
                                                    </tr>
                                                    <tr ng-repeat="x in saleReport">
                                                        <td class="p-0 pl-1">{{x.ticket_taken_time}}</td>
                                                        <td class="p-0 pl-1 text-right">{{x.amount | number:2}}</td>
                                                        <td class="p-0 pl-1 text-right">{{x.commision | number:2}}</td>
                                                        <td class="p-0 pl-1 text-right">{{x.prize_value}}</td>
                                                        <td class="p-0 pl-1 text-right">{{x.net_payable | number:2}}</td>
                                                    </tr>
                                                    <tr ng-show="cardSaleReport.length">
                                                        <td class="p-0 pl-1 text-left font-weight-bold" colspan="5">Card</td>
                                                    </tr>
                                                    <tr ng-repeat="x in cardSaleReport">
                                                        <td class="p-0 pl-1">{{x.ticket_taken_time}}</td>
                                                        <td class="p-0 pl-1 text-right">{{x.amount | number:2}}</td>
                                                        <td class="p-0 pl-1 text-right">{{x.commision | number:2}}</td>
                                                        <td class="p-0 pl-1 text-right">{{x.prize_value}}</td>
                                                        <td class="p-0 pl-1 text-right">{{x.net_payable | number:2}}</td>
                                                    </tr>

                                                    </tbody>

                                                    <tfoot ng-show="saleReport.length">
                                                    <tr>
                                                        <td class="p-0 pl-1">Total</td>
                                                        <td class="p-0 pl-1 text-right">{{(saleReportFooter.total_amount)+(cardSaleReportFooter.total_amount) | number:2}}</td>
                                                        <td class="p-0 pl-1 text-right">{{(saleReportFooter.total_commision)+(cardSaleReportFooter.total_commision) | number:2}}</td>
                                                        <td class="p-0 pl-1 text-right">{{(saleReportFooter.total_prize_value)+(cardSaleReportFooter.total_prize_value) | number:2}}</td>
                                                        <td class="p-0 pl-1 text-right">{{(saleReportFooter.total_net_payable)+(cardSaleReportFooter.total_net_payable) | number:2}}</td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-center" ng-show="alertMsg && alertMsgCard">
                                    <div>No records found</div>
                                </div>
                            </form>
                        </div> <!--//End of my tab1//-->

                        <!--                        <pre>cardSaleReport={{cardSaleReport | json}}</pre>-->
                        <!--                        <pre>saleReportFooter={{saleReportFooter | json}}</pre>-->
                    </div>

                    <div ng-show="isSet(2)">
                        <div id="my-tab-2">

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
                                    <select class="form-control " ng-change="getAllBarcodeDetailsByDate(barcode_report_date,select_game.id,select_barcode_type.id,select_draw_time)"
                                            data-ng-model="select_barcode_type"
                                            data-ng-options="x as x.type for x in barcodeType">
                                    </select>
                                </div>
                                <div class="col-1">
                                    <select ng-model="select_draw_time" class="form-control">
                                        <option value="0" selected="All">All</option>
                                        <option ng-repeat="x in drawTime" value="{{x.draw_master_id}}">
                                            {{(x.end_time |limitTo: 5) + ' '+ (x.meridiem)}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control" ng-model="select_barcode" placeholder="Enter Barcode">
                                </div>

                                <div class="ml-2"><input type="button" class="btn btn-info form-control" value="Show" ng-click="getAllBarcodeDetailsByDate(barcode_report_date,select_game.id,select_barcode_type.id,select_draw_time)"></div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="loader mt-1" ng-show="isLoading2"></div>
                            </div>


                            <div class="d-flex justify-content-between" ng-show="!isLoading2">

                                <div class="col">
                                    <table cellpadding="0" cellspacing="0" class="table table-hover report-table  text-justify">
                                        <tr>
                                            <th>SL</th>
                                            <th>D.Time</th>
                                            <th>T.Time</th>
                                            <th>Barcode</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                            <th>Prize</th>
<!--                                            <th>Particulars</th>-->
                                            <th>Particulars</th>
                                            <th ng-show="select_barcode_type.id==2"></th>
                                        </tr>
                                        <tbody ng-repeat="x in showbarcodeReport | filter : select_barcode">
                                        <tr>
                                            <td>{{ $index+1}}</td>
                                            <td>{{x.draw_time +' '+ x.meridiem}}</td>
                                            <td>{{x.ticket_taken_time}}</td>
                                            <td>{{x.barcode}}</td>
                                            <td>{{x.quantity |number:2}}</td>
                                            <td>{{x.amount |number:2}}</td>
                                            <td>{{x.prize_value |number:2}}</td>
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
                                            {{showbarcodeReport[target].particulars}}
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
<!--                                                                <pre>claimReport={{claimReport | json}}</pre>-->
<!--                                                            </div>-->
<!--                                                            <div class="col"><pre>showbarcodeReport={{showbarcodeReport | json}}</pre></div>-->
<!--                                                            <div class="col"></div>-->
<!--                                                        </div>-->

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

    public function get_net_payable_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Report_terminal_model->get_terminal_total_sale_report($post_data['start_date'],$post_data['end_date'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }


    public function get_card_net_payable_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Report_terminal_model->get_terminal_card_game_total_sale_report($post_data['start_date'],$post_data['end_date'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }


    public function get_2d_report_order_by_barcode(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Report_terminal_model->get_all_barcode_report_by_date($post_data['start_date'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }

    public function get_2d_draw_time(){
        $result=$this->Report_terminal_model->get_all_2d_draw_time_list()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_card_draw_time(){
        $result=$this->Report_terminal_model->get_card_draw_time_list()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }


    public function get_card_report_order_by_barcode(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Report_terminal_model->get_card_game_barcode_report_by_date($post_data['start_date'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }
     public function insert_claimed_barcode_details(){
            $post_data =json_decode(file_get_contents("php://input"), true);
            $result=$this->Report_terminal_model->insert_claimed_barcode($post_data['barcode'],$post_data['game_id'],$post_data['prize_value']);
            $report_array['records']=$result;
            echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }




}
?>