<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Game_model');
        $this -> load -> model('Card_model');
        $this -> load -> model('Lucky3_model');
        $this -> is_logged_in();
    }
    function is_logged_in() {
        $is_logged_in = $this -> session -> userdata('is_logged_in');
        $person_cat_id = $this -> session -> userdata('person_cat_id');
        if (!isset($is_logged_in) || $is_logged_in != '1' || $person_cat_id!=3) {
            echo 'you have no permission to use this area'. '<a href="#!" ng-click="goToFrontPage()">Login</a>';
            die();
        }
    }
    
    function get_sessiondata(){
        echo json_encode($this->session->userdata(),JSON_NUMERIC_CHECK);
    }
    

    function get_active_terminal_balance(){
        $terminal_id=$this-> session -> userdata('person_id');
        $result=$this->Game_model->select_terminal_balance($terminal_id);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }



    public function angular_view_play(){
        ?>
        <style type="text/css">
            #main-working-div h1{
                color: darkblue;
            }
            /*input.ng-invalid {*/
            /*background-color: pink;*/
            /*}*/
            body{
                background: #e98b39;
            }
            .td-input{
                width: 35px;
                padding: 0px;
                margin-left: 0px;
                margin-right: 0px;
                font-weight: bold;
                color: #000080;
                border-radius: 50px;
            }
            .result-input{
                width: 80px;
            }
            table tr td{
                padding: 0 !important;
                margin: 0 !important;
                margin-left: 2px;
            }
            #game-main-div{
                border-radius: 25px;
                background: linear-gradient(to bottom, #996633 0%, #993300 100%);
                color: #ffffff;
            }
            #matrix-table{
                width: 600px;
            }

            #series-div{
                background: linear-gradient(to top left, #ff6600 0%, #99ffcc 100%);
                border-radius: 25px;

            }
            #result-div{
                border-radius: 15px 50px;
                padding-top: 35px;
                background-color: #009999;
                /*margin-left: 60px;*/
                background: linear-gradient(to top, #99ffcc 0%, #ff99cc 100%);
            }


            .header-table{
                color: white;
                font-size: 18px;
            }

            #game-page{
                background-color: #e98b39;
            }
            #top-div{
                background-color: #dd4814;
            }
            em {
                font-family: 'EB Garamond', serif;
                font-size: 3.5em;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                display: block;
                font-style:normal;
                padding-top: 0.1em;
                text-shadow: 0.07em 0.07em 0 rgba(0, 0, 0, 0.1);

            &::before, &::after {
                            content: "§";
                            display: inline-block;
                            -webkit-transform: rotate(90deg);
                            -moz-transform: rotate(90deg);
                            -o-transform: rotate(90deg);
                            -ms-transform: rotate(90deg);
                            transform: rotate(90deg);
                            opacity: 0.2;
                            margin: 0 0.6em;
                            font-size: 0.3em;
                        }

            }
            .modal-header, h4{
                background-color: transparent;
            }
            .result-panel{
                height: 600px;
                overflow-y:scroll;
            }
            .card-result-panel{
                height: 450px;
                overflow-y:scroll;
            }
            #result-table tr,#result-table th,#result-table td{
                border: 1px solid black;

            }
            #welcome-text{
                color: #b8daff;
                text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
                font-size: 20px;
            }
            .square {
                width: 120px;
                height: 75px;
                font-size: 40px;
            }
            .bee{
                background-color: #d9edf7;
            }
            .banana{
                background-color: #999988;
            }
            
            /*#game-page{
				 overflow:scroll;
			}*/

        </style>
        <div class="container-fluid" id="game-page">
            <div class="card bg-transparent">
            					<!-- game page header  -->
                <div class="card-header p-0">
                    <nav class="navbar navbar-expand-lg  bg-orange mt-0 pt-0">
                        <img class="img-responsive" src="img/capture1.png"  align="left">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon bg-light"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <a href="#!reportterm" target="_blank"><input type="button" class="btn-info" value="Report"></a>
                            &nbsp;<input type="button" class="btn-info mr-1" value="Result" ng-click="getResultSheetToday(gameNumber)">
                            &nbsp;<a href="#!" ng-click="logoutCpanel()"><input type="button" class="btn-warning" value="Logout"></a>

                            <div class="card m-auto bg-transparent header-table">
                                <div class="card-header">
                                    <ul class="nav nav-tabs card-header-tabs">
                                        <li class="nav-item">
                                            <span class="nav-link py-0">Terminal ID: <b><?php echo ($this->session->userdata('user_id'));?></b></span>
                                        </li>

                                        <li class="nav-item">
                                            <span class="nav-link py-0">Agent: <b><?php echo ($this->session->userdata('person_name'));?></b></span>
                                        </li>
                                        <li class="nav-item">
                                            <span class="nav-link py-0">Balance: <b>{{activeTerminalDetails.current_balance | number:2}}</b></span>
                                        </li>
                                    </ul>

                                    <ul class="nav nav-tabs card-header-tabs">
                                        <li class="nav-item">
                                            <span class="nav-link">Date: <b>{{gameStartingDate}}</b></span>
                                        </li>
                                        <li class="nav-item">
                                            <!--<span class="nav-link">Time: <b>{{theclock + '  ' + am_pm}}</b></span>-->
                                            <span class="nav-link">Time: <b>{{show_time}}</b></span>
                                        </li>
                                        <li class="nav-item">
                                                    <span class="nav-link">
                                                        Draw time:
                                                        <b ng-show="gameNumber!=3">{{tenDigitDrawTime ? drawTimeList[0].end_time + ' '+ drawTimeList[0].meridiem : cardDrawTimeList[0].end_time + ' '+ cardDrawTimeList[0].meridiem}}</b>
                                                        <b ng-show="gameNumber==3">{{luckyDrawTimeList.end_time + ' '+ luckyDrawTimeList.meridiem}}</b>
                                                        <!--                                                        <b>{{cardDrawTimeList[0].end_time + ' '+ cardDrawTimeList[0].meridiem }}</b>-->
                                                    </span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                            <a href="#" ng-click="refreshTerminalBalance()"><i class="fas fa-sync-alt"></i></a>
                            <input type="button" class="mr-1 ml-1" ng-click="selectGame100(); tenDigitDrawTime=true" value="2 Digit"/>
                            <input type="button" class="" ng-click="selectGame12();tenDigitDrawTime=false" value="Card"/>
                            <input type="button" class="" ng-click="selectLuky3()" value="LUCKY3"/>
                        </div>

                        <form class="form-inline my-2 my-lg-0">
                            <img class="img-responsive" src="img/logo.png"  align="right">
                        </form>

                    </nav>

                </div>


                <div class="card-body p-0" ng-show="game100">
                    <div class=" d-flex flex-wrap align-content-start">
                        <div class="col ml-1 mr-1" id="series-div">
                            <div style="color: #990073;font-size: large" class="row pl-2 pt-4">
                                <label class="col">MRP:&nbsp;<span>1.10/-</span></label>
                                <label class="col">WIN:&nbsp;<span>100/-</span></label>
                            </div>
                            

                            <div ng-repeat="x in seriesList" id="show-series" class="row">
                                <div class="col">
                                    <label>
                                        <em>{{x.series_name}} <input id="chkSeries_{{x.play_series_id}}" type="checkbox"  ng-click="checkOneSeries($index);getTotalBuyTicketByClickSeries($index)" ng-model="x.Selected" /></em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                </div>
                            </div>
                            <div class="row checkbox" style="font-weight: bold">
                                <div class="col">
                                    <em>ALL <input type="checkbox"  ng-model="selectAll" ng-click="checkSeriesAll();getTotalBuyTicketByClickSeries(0)"></em>
                                </div>
                            </div>
                            
                            <div class="card mt-4">
                                <div class="card-header text-center text-primary" ng-show="remainingTime>=0" style="font-size: 50px">
                                    {{remainingTime | formatDuration}}
                                </div>
                            </div>
                        </div>
                        <div class="col ml-1"  id="game-main-div">
                            <form name="gameForm" class="form-horizontal" id="game-form">
                                <div class="d-flex">
                                    <div class="pb-3">
                                        <table id="matrix-table" navigatable>
                                            <thead></thead>
                                            <tbody ng-repeat="r in getRow(row) track by $index" ng-init="rowIndex = $index">
                                            <tr>
                                                <td></td>
                                                <td ng-repeat="c in getCol(coloumn-2) track by $index" ng-init="columnIndex = $index">
                                                    <span style='text-align: center;display: block;'>{{rowIndex}}{{columnIndex}}</span>
                                                </td>
                                                <td><span style='text-align: center;display: block;'>E{{rowIndex}}</span></td>
                                                <td><span style='text-align: center;display: block;'>{{rowIndex}}0-{{rowIndex}}9</span></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">{{rowIndex}}</td>
                                                <td ng-repeat="c in getCol(coloumn) track by $index">
                                                    <input
                                                            type="text" numbers-only ng-model="playInput[rowIndex][$index]" ng-change="" ng-keyup="verticallyHorizontallyPushValue($index,rowIndex)"
                                                            ng-style="$index==10 && verticalBoxCss || $index==11 && horizontalBoxCss"
                                                            my-maxlength="3" class="col td-input text-right">
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex p-0 pl-5">
                                    <div class="p-2"><input type="button"  ng-model="lpValue"  value="Clear" ng-click="clearDigitInputBox()"></div>
                                    <div class="p-2"><input type="text" my-maxlength="3" numbers-only ng-model="lpValue"  class="td-input text-right"></div>
                                    <div class="p-2"><input type="button" class="button btn-info" value="LP" ng-click="generateNumberByLp()"></div>
                                    <div class=""></div>
                                    <div class="pl-5">{{ticketPrice | number: 2}}</div>
                                    <div class="pl-3">X</div>
                                    <div class="pl-2"><input class="result-input text-center" type="text" ng-model="totalBoxSum" readonly/></div>
                                    <div class="p-2">=</div>
                                    <div class=""><input class="result-input text-center" type="text" ng-model="totalTicketBuy | number:2" readonly/></div>
                                    <div class="pl-2">
                                        <input type="button" class="button btn-success" value="Print" ng-click="submitGameValues(playInput,checkList)" ng-disabled="disable2d">
                                    </div>

                                </div>


                            </form>
                        </div>
                        <div class="col ml-1 mr-1" id="result-div" ng-show="!showResultSheet">
                            <h4>{{winningValue[0].end_time + " " + winningValue[0].meridiem}}</h4>
                            <div ng-repeat="x in seriesList" id="show-series" style="padding-left: 20px;padding-top: 40px">
                                <label>
                                    <em>{{x.series_name}}&nbsp;&nbsp;&nbsp;{{winningValue[$index].row_number}}{{winningValue[$index].column_number}}</em>
                                </label>
                            </div><br><br><br>
                        </div>

                        <div class="col ml-1 mr-1 result-panel" id="result-div" ng-show="showResultSheet">
                            <input type="button" ng-click="closeResultSheet(1)" value="Close" style="margin-bottom: 5px">
                            <input type="date" style="margin-bottom: 5px" ng-model="result_date" ng-change="result_date=changeDateFormat(result_date)">
                            <input type="button" ng-click="getResultbyDate(result_date,1)" value="Show" style="margin-bottom: 5px">
                            <table class="table" id="result-table">
                                <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>A</th>
                                    <th>B</th>
                                    <th>C</th>
                                    <th>D</th>
                                    <th>E</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="x in resultSheetRecord">
                                    <td class="text-center">{{x.end_time+' '+x.meridiem}}</td>
                                    <td class="text-center">{{x.a_row+''+x.a_column}}</td>
                                    <td class="text-center">{{x.b_row+''+x.b_column}}</td>
                                    <td class="text-center">{{x.c_row+''+x.c_column}}</td>
                                    <td class="text-center">{{x.d_row+''+x.d_column}}</td>
                                    <td class="text-center">{{x.e_row+''+x.e_column}}</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <!--                        end of card-body of first game-->

                <div class="card-body pt-0" ng-show="game12">
                    <form name="twelveCardForm" class="form-horizontal" id="twelve-card-form">
                        <div class="d-flex justify-content-between">
                            <div class="flex-fill" id="left-panel">

                                <div class="d-flex justify-content-between mt-2 text-white font-weight-bold">
                                    <div class="col-3">MRP : {{cardPrice.mrp}} /-</div>
                                    <div class="col-3 mr-5">WIN : {{cardPrice.winning_price}} /-</div>
                                </div>
                                <div class="d-flex m-3">
                                    <div class="pl-5"></div>
                                    <div class="m-auto">
                                        <img class="img-responsive" src="img/12-cards/flower.png">
                                    </div>
                                    <div class="m-auto">
                                        <img class="img-responsive" src="img/12-cards/diamond.png">
                                    </div>
                                    <div class="m-auto">
                                        <img class="img-responsive" src="img/12-cards/pan.png">
                                    </div>
                                    <div class="m-auto">
                                        <img class="img-responsive" src="img/12-cards/love.png">
                                    </div>
                                </div>
                                <div class="d-flex m-3">
                                    <div class="p-2">
                                        <img class="img-responsive" src="img/12-cards/j.png">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="jack[0].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="jack[1].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="jack[2].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="jack[3].val">
                                    </div>
                                </div>
                                <div class="d-flex m-3">
                                    <div class="p-2">
                                        <img class="img-responsive" src="img/12-cards/q.png">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="queen[0].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="queen[1].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="queen[2].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control p-4 text-right square" numbers-only hide-zero maxlength="3" ng-model="queen[3].val">
                                    </div>
                                </div>
                                <div class="d-flex m-3">
                                    <div class="p-2">
                                        <img class="img-responsive" src="img/12-cards/k.png">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="saheb[0].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="saheb[1].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="saheb[2].val">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="3" ng-model="saheb[3].val">
                                    </div>
                                </div>
                            </div>


                            <div ng-class="number>2?'card-result-panel':''" id="right-panel" ng-show="defaultCardResult">
                                <div class="d-flex justify-content-end p-2" ng-show="selectCardGameDate">
                                    <div class="col"><input type="date" class="form-control" ng-model="result_date" ng-change="result_date=changeDateFormat(result_date)"></div>
                                    <div class="col">
                                        <div class="btn-group">
                                            <div class="btn-group">
                                                <button type="button" class="btn  dropdown-toggle" data-toggle="dropdown">
                                                    Show
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#" ng-click="getResultbyDate(result_date,2);cardImage=true"><i class="fas fa-images"></i>&nbsp;Image</a>
                                                    <a class="dropdown-item" href="#" ng-click="getResultbyDate(result_date,2);cardImage=false"><i class="fas fa-list-ul"></i>&nbsp;Name</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"><input type="button" class="form-control" ng-click="closeResultSheet(2)" value="Close"></div>
                                </div>

                                <div class="d-flex justify-content-end" ng-repeat="i in getNumber(number) track by $index"  ng-init="myVar=testFunc($index)">
                                    <figure class="figure" ng-show="winningCard[myVar].enable">
                                        <figcaption class="figure-caption font-weight-bold text-white">{{winningCard[myVar].end_time | limitTo: 5}} {{ ' ' + winningCard[myVar].meridiem}}</figcaption>
                                        <img ng-src="{{path + winningCard[myVar].result_row + winningCard[myVar].result_column + '.png'}}" class="figure-img img-fluid" alt="{{winningCard[myVar].result_row + winningCard[myVar].result_column}}">
                                    </figure>
                                    <figure class="figure" ng-show="winningCard[myVar+1].enable">
                                        <figcaption class="figure-caption font-weight-bold text-white">{{winningCard[myVar+1].end_time | limitTo: 5}} {{ ' ' + winningCard[myVar+1].meridiem}}</figcaption>
                                        <img ng-src="{{path + winningCard[myVar+1].result_row + winningCard[myVar+1].result_column + '.png'}}" class="figure-img img-fluid" alt="{{winningCard[myVar+1].result_row + winningCard[myVar+1].result_column}}">
                                    </figure>
                                    <figure class="figure" ng-show="winningCard[myVar+2].enable">
                                        <figcaption class="figure-caption font-weight-bold text-white">{{winningCard[myVar+2].end_time | limitTo: 5}} {{ ' ' + winningCard[myVar+2].meridiem}}</figcaption>
                                        <img ng-src="{{path + winningCard[myVar+2].result_row + winningCard[myVar+2].result_column + '.png'}}" class="figure-img img-fluid" alt="{{winningCard[myVar+2].result_row + winningCard[myVar+2].result_column}}">
                                    </figure>
                                    <figure class="figure" ng-show="winningCard[myVar+3].enable">
                                        <figcaption class="figure-caption font-weight-bold text-white">{{winningCard[myVar+3].end_time | limitTo: 5}} {{ ' ' + winningCard[myVar+3].meridiem}}</figcaption>
                                        <img ng-src="{{path + winningCard[myVar+3].result_row + winningCard[myVar+3].result_column + '.png'}}" class="figure-img img-fluid" alt="{{winningCard[myVar+3].result_row + winningCard[myVar+3].result_column}}">
                                    </figure>
                                    <figure class="figure" ng-show="winningCard[myVar+4].enable">
                                        <figcaption class="figure-caption font-weight-bold text-white">{{winningCard[myVar+4].end_time | limitTo: 5}} {{ ' ' + winningCard[myVar+4].meridiem}}</figcaption>
                                        <img ng-src="{{path + winningCard[myVar+4].result_row + winningCard[myVar+4].result_column + '.png'}}" class="figure-img img-fluid" alt="{{winningCard[myVar+4].result_row + winningCard[myVar+4].result_column}}">
                                    </figure>
                                    <figure class="figure" ng-show="winningCard[myVar+5].enable">
                                        <figcaption class="figure-caption font-weight-bold text-white">{{winningCard[myVar+5].end_time | limitTo: 5}} {{ ' ' + winningCard[myVar+5].meridiem}}</figcaption>
                                        <img ng-src="{{path + winningCard[myVar+5].result_row + winningCard[myVar+5].result_column + '.png'}}" class="figure-img img-fluid" alt="{{winningCard[myVar+5].result_row + winningCard[myVar+5].result_column}}">
                                    </figure>

                                </div>

                            </div>

                            <div class="flex-fill" ng-show="!defaultCardResult">
                                <div ng-class="preCardRecord.length / 6 > 2?'card-result-panel':''" id="right-panel" ng-show="!defaultCardResult">
                                    <div class="d-flex justify-content-end p-2" ng-show="selectCardGameDate">
                                        <div class="col"><input type="date" class="form-control" ng-model="result_date" ng-change="result_date=changeDateFormat(result_date)"></div>
                                        <div class="col">
                                            <div class="btn-group">
                                                <div class="btn-group">
                                                    <button type="button" class="btn  dropdown-toggle" data-toggle="dropdown">
                                                        Show
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" ng-click="getResultbyDate(result_date,2);cardImage=true"><i class="fas fa-images"></i>&nbsp;Image</a>
                                                        <a class="dropdown-item" href="#" ng-click="getResultbyDate(result_date,2);cardImage=false"><i class="fas fa-list-ul"></i>&nbsp;Name</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col"><input type="button" class="form-control" ng-click="closeResultSheet(2)" value="Close"></div>
                                    </div>

                                    <div class="d-flex justify-content-end" ng-repeat="i in getNumber(preCardRecord.length / 6) track by $index" ng-init="myVar=testFunc($index)" ng-show="cardImage">
                                        <figure class="figure" ng-show="preCardRecord[myVar].enable">
                                            <figcaption class="figure-caption font-weight-bold text-white">{{preCardRecord[myVar].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar].meridiem}}</figcaption>
                                            <img ng-src="{{path + preCardRecord[myVar].result_row + preCardRecord[myVar].result_column + '.png'}}" class="figure-img img-fluid" alt="{{preCardRecord[myVar].result_row + preCardRecord[myVar].result_column}}">
                                        </figure>
                                        <figure class="figure" ng-show="preCardRecord[myVar+1].enable">
                                            <figcaption class="figure-caption font-weight-bold text-white">{{preCardRecord[myVar+1].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+1].meridiem}}</figcaption>
                                            <img ng-src="{{path + preCardRecord[myVar+1].result_row + preCardRecord[myVar+1].result_column + '.png'}}" class="figure-img img-fluid" alt="{{preCardRecord[myVar+1].result_row + preCardRecord[myVar+1].result_column}}">
                                        </figure>
                                        <figure class="figure" ng-show="preCardRecord[myVar+2].enable">
                                            <figcaption class="figure-caption font-weight-bold text-white">{{preCardRecord[myVar+2].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+2].meridiem}}</figcaption>
                                            <img ng-src="{{path + preCardRecord[myVar+2].result_row + preCardRecord[myVar+2].result_column + '.png'}}" class="figure-img img-fluid" alt="{{preCardRecord[myVar+2].result_row + preCardRecord[myVar+2].result_column}}">
                                        </figure>
                                        <figure class="figure" ng-show="preCardRecord[myVar+3].enable">
                                            <figcaption class="figure-caption font-weight-bold text-white">{{preCardRecord[myVar+3].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+3].meridiem}}</figcaption>
                                            <img ng-src="{{path + preCardRecord[myVar+3].result_row + preCardRecord[myVar+3].result_column + '.png'}}" class="figure-img img-fluid" alt="{{preCardRecord[myVar+3].result_row + preCardRecord[myVar+3].result_column}}">
                                        </figure>
                                        <figure class="figure" ng-show="preCardRecord[myVar+4].enable">
                                            <figcaption class="figure-caption font-weight-bold text-white">{{preCardRecord[myVar+4].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+4].meridiem}}</figcaption>
                                            <img ng-src="{{path + preCardRecord[myVar+4].result_row + preCardRecord[myVar+4].result_column + '.png'}}" class="figure-img img-fluid" alt="{{preCardRecord[myVar+4].result_row + preCardRecord[myVar+4].result_column}}">
                                        </figure>
                                        <figure class="figure" ng-show="preCardRecord[myVar+5].enable">
                                            <figcaption class="figure-caption font-weight-bold text-white">{{preCardRecord[myVar+5].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+5].meridiem}}</figcaption>
                                            <img ng-src="{{path + preCardRecord[myVar+5].result_row + preCardRecord[myVar+5].result_column + '.png'}}" class="figure-img img-fluid" alt="{{preCardRecord[myVar+5].result_row + preCardRecord[myVar+5].result_column}}">
                                        </figure>

                                    </div>



                                    <div class="d-flex"  ng-show="!cardImage">



                                        <table cellpadding="2" cellspacing="0" class="table table-bordered">
                                            <tbody ng-repeat="i in getNumber(preCardRecord.length / 6) track by $index" ng-init="myVar=testFunc($index)">

                                                <tr class="text-white">
                                                    <td >{{preCardRecord[myVar].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar].meridiem}}</td>
                                                    <td >{{preCardRecord[myVar+1].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+1].meridiem}}</td>
                                                    <td >{{preCardRecord[myVar+2].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+2].meridiem}}</td>
                                                    <td >{{preCardRecord[myVar+3].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+3].meridiem}}</td>
                                                    <td >{{preCardRecord[myVar+4].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+4].meridiem}}</td>
                                                    <td >{{preCardRecord[myVar+5].end_time | limitTo: 5}} {{ ' ' + preCardRecord[myVar+5].meridiem}}</td>
                                                </tr>
                                                <tr class="banana">
                                                    <td class="font-weight-bold text-center">{{preCardRecord[myVar].result_row + preCardRecord[myVar].result_column |  uppercase}}</td>
                                                    <td class="font-weight-bold text-center">{{preCardRecord[myVar+1].result_row + preCardRecord[myVar+1].result_column |  uppercase}}</td>
                                                    <td class="font-weight-bold text-center">{{preCardRecord[myVar+2].result_row + preCardRecord[myVar+2].result_column |  uppercase}}</td>
                                                    <td class="font-weight-bold text-center">{{preCardRecord[myVar+3].result_row + preCardRecord[myVar+3].result_column |  uppercase}}</td>
                                                    <td class="font-weight-bold text-center">{{preCardRecord[myVar+4].result_row + preCardRecord[myVar+4].result_column |  uppercase}}</td>
                                                    <td class="font-weight-bold text-center">{{preCardRecord[myVar+5].result_row + preCardRecord[myVar+5].result_column |  uppercase}}</td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="card-footer pt-0 body-color" ng-show="game12">
                            <div class="d-flex mt-5 pt-1">
                                <div class="col-4 bg-warning rounded">
                                    <div class="d-flex justify-content-center">
                                        <div class="display-4 text-primary" ng-show="cardRemainingTime>=0">{{cardRemainingTime | formatDuration}}</div>
                                    </div>
                                    <!--                            Time left <h2>{{cardRemainingTime | formatDuration}}-->
                                    <!--                                           <span class="text-success" ng-show="remainingTime>=0" style="font-size: 50px"> {{remainingTime | formatDuration}}</span>-->
                                </div>
                                
                                
                              
                                <div class="col bg-light rounded pt-2">
                                    <div class="d-flex justify-content-end">
                                        <div class="mt-1 pr-4"><input type="button" class="button btn-secondary form-control" value="Clear" ng-click="clearAll()"></div>
                                        <div class="mt-1 mb-1"><input type="text" maxlength="3" numbers-only ng-model="lpValueCard"  class="text-right result-input form-control"></div>
                                        <div class="mt-1 pl-3"><input type="button" class="button btn-info form-control" value="LP" ng-click="getCardLpValue(lpValueCard)" ng-disabled="false"></div>
                                        <div class=""></div>
                                        <div class="ml-5 pl-5 pt-2">{{cardPrice.mrp | number:2}}</div>
                                        <div class="ml-4 pt-2">X</div>
                                        <div class="pl-2 pt-1"><input class="form-control text-center result-input" type="text" ng-model="totalCardInput" readonly/></div>
                                        <div class="pt-1 pl-2">=</div>
                                        <div class="mt-1 pl-3"><input class="text-center form-control result-input" type="text" ng-model="totalTicketBuyByCard | number:2" readonly/></div>
                                        <div class="pt-1 pl-2"><input type="button" class="button btn-success form-control" value="Print" ng-click="submitCardGameValue()" ng-disabled="twelveCardForm.$pristine || disableCard"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--                        end of card-body of 12 card game-->
                
                
                <!-- START GAME LUKY3  -->
                <div class="card-body pt-0" ng-show="luky3">
                	<style type="text/css">
                		.option-size{
							font-size: 2.0rem;
							height: 0;
						}
						
                		
                	</style>
                    <form name="luckyForm" class="form-horizontal" id="luky-form">
                        <div class="d-flex justify-content-between">
                            <div class="flex-fill" id="left-panel">

                                <div class="d-flex justify-content-between mt-2 text-white font-weight-bold">
                                    <div class="col-3">MRP : 10 /-</div>
                                    <div class="col-5 ">
                                    	{{luckyGamePriceList[0].cat_name+'- '+luckyGamePriceList[0].winning_prize+', '+luckyGamePriceList[1].cat_name+'- '+luckyGamePriceList[1].winning_prize+', '+luckyGamePriceList[2].cat_name+'/'+luckyGamePriceList[3].cat_name+'- '+luckyGamePriceList[3].winning_prize}}
                                    </div>
                                </div>
                                
                                
                                <div class="d-flex m-3">
                                   	<div class="p-2 mt-2">
                                        <select class="form-control-lg pt-0 option-size" ng-model="row1.cat" ng-change="catWiseBehave(row1)">
                                        	<option size=12>ST</option>
                                        	<option>BX</option>
                                        	<option>FP</option>
                                        	<option>BP</option>
                                        </select>
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="1" ng-model="row1.val1" ng-disabled="row1.frnt_dis">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only maxlength="1" ng-model="row1.val2">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only  maxlength="1" ng-model="row1.val3" ng-disabled="row1.back_dis">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" ng-style="game3box" class="form-control text-right square" numbers-only  ng-model="row1.qty">
                                    </div>
                                    <div class="mt-3">
                                        
                                        <input type="button" class="btn btn-info" value="LP" ng-click="getLpForLucky3(row1)">
                                    </div>
                                </div>
                                
                                <div class="d-flex m-3">
                                    <div class="p-2 mt-2">
                                        <select class="form-control-lg pt-0 option-size" ng-model="row2.cat" ng-change="catWiseBehave(row2)">
                                        	<option size=12>ST</option>
                                        	<option>BX</option>
                                        	<option>FP</option>
                                        	<option>BP</option>
                                        </select>
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="1" ng-model="row2.val1" ng-disabled="row2.frnt_dis">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only  maxlength="1" ng-model="row2.val2">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only  maxlength="1" ng-model="row2.val3" ng-disabled="row2.back_dis">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" ng-style="game3box" class="form-control text-right square" numbers-only   ng-model="row2.qty">
                                    </div>
                                    <div class="mt-3">
                                        <input type="button" class="btn btn-info" value="LP" ng-click="getLpForLucky3(row2)">
                                    </div>
                                </div>
                                <div class="d-flex m-3">
                                    <div class="p-2 mt-2">
                                        <select class="form-control-lg pt-0 option-size" ng-model="row3.cat" ng-change="catWiseBehave(row3)">
                                        	<option size=12>ST</option>
                                        	<option>BX</option>
                                        	<option>FP</option>
                                        	<option>BP</option>
                                        </select>
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="1" ng-model="row3.val1" ng-disabled="row3.frnt_dis">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only  maxlength="1" ng-model="row3.val2">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only  maxlength="1" ng-model="row3.val3" ng-disabled="row3.back_dis">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" ng-style="game3box" class="form-control text-right square" numbers-only   ng-model="row3.qty">
                                    </div>
                                    <div class="mt-3">
                                        <input type="button" class="btn btn-info" value="LP" ng-click="getLpForLucky3(row3)">
                                    </div>
                                </div>
                                <div class="d-flex m-3">
                                    <div class="p-2 mt-2">
                                        <select class="form-control-lg pt-0 option-size" ng-model="row4.cat" ng-change="catWiseBehave(row4)">
                                        	<option size=12>ST</option>
                                        	<option>BX</option>
                                        	<option>FP</option>
                                        	<option>BP</option>
                                        </select>
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only hide-zero maxlength="1" ng-model="row4.val1" ng-disabled="row4.frnt_dis">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only  maxlength="1" ng-model="row4.val2">
                                    </div>
                                    <div class="p-2">
                                        <input type="text" class="form-control text-right square" numbers-only  maxlength="1" ng-model="row4.val3" ng-disabled="row4.back_dis">
                                    </div>
                                   <div class="p-2">
                                        <input type="text" ng-style="game3box" class="form-control text-right square" numbers-only   ng-model="row4.qty">
                                    </div>
                                    <div class="mt-3">
                                        <input type="button" class="btn btn-info" value="LP" ng-click="getLpForLucky3(row4)">
                                    </div>
                                </div>
                            </div>
                            <!--	Show result		-->
                            
                              <div class="d-flex flex-column mt-1">
							    <div class="bg-info text-center text-white">11:00 AM&nbsp;
							    	<span class="display-4">000</span>	
							    </div>
							    <div class="mt-2 bg-success text-center text-white">01:00 PM&nbsp;
							    	<span class="display-4">000</span>	
							    </div>
							    <div class="mt-2 bg-primary text-center text-white">03:00 PM&nbsp;
							    	<span class="display-4">000</span>	
							    </div>
							    <div class="mt-2 bg-info text-center text-white">05:00 PM&nbsp;
							    	<span class="display-4">000</span>	
							    </div>
							    <div class="mt-2 bg-warning text-center text-white">07:00 PM&nbsp;
							    	<span class="display-4">000</span>	
							    </div>
							    <div class="mt-2 bg-danger text-center text-white">09:00 PM&nbsp;
							    	<span class="display-4">000</span>	
							    </div>
							  </div>
                            
                        </div>
                        
                        
                        
                        
                        <div class="card-footer p-0 body-color" ng-show="luky3">
                            <div class="d-flex mt-5 pt-1">
                                <div class="col-4 bg-warning rounded">
                                    <div class="d-flex justify-content-center">
                                        <div class="display-4 text-primary" ng-show="cardRemainingTime>=0">{{cardRemainingTime | formatDuration}}</div>
                                    </div>
                                    <!--                            Time left <h2>{{cardRemainingTime | formatDuration}}-->
                                    <!--                                           <span class="text-success" ng-show="remainingTime>=0" style="font-size: 50px"> {{remainingTime | formatDuration}}</span>-->
                                </div>
                                
                                
                              
                                <div class="col bg-light rounded pt-2">
                                    <div class="d-flex justify-content-end">
                                        <div class="mt-1 pr-4"><input type="button" class="button btn-secondary form-control" value="Clear" ng-click="clearAll()"></div>
                                        <div class="mt-1 mb-1"><input type="text" maxlength="3" numbers-only ng-model="lpValueCard"  class="text-right result-input form-control"></div>
                                        <div class="mt-1 pl-3"><input type="button" class="button btn-info form-control" value="LP" ng-click="getCardLpValue(lpValueCard)" ng-disabled="false"></div>
                                        <div class=""></div>
                                        <div class="ml-5 pl-5 pt-2">{{luckyMrp| number:2}}</div>
                                        <div class="ml-4 pt-2">X</div>
                                        <div class="pl-2 pt-1"><input class="form-control text-center result-input" type="text" ng-model="totalCardInput" readonly/></div>
                                        <div class="pt-1 pl-2">=</div>
                                        <div class="mt-1 pl-3"><input class="text-center form-control result-input" type="text" ng-model="totalTicketBuyByCard | number:2" readonly/></div>
                                        <div class="pt-1 pl-2"><input type="button" class="button btn-success form-control" value="Print" ng-click="submitLuckyValue()" ng-disabled="luckyForm.$pristine"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                
                
                
                <!-- END OF GAME LUKY3  -->

            </div>
          
<!--                                            <div class="card">-->
<!--                                                <div class="card-header">-->
                                                <div class="d-flex">
                                                   <!--<div class="col"> <pre>luckyGamePriceList= {{luckyGamePriceList | json}}</pre></div>-->
                                                                                                         
                                                     
                                                       <!-- <div class="col"> <pre>row2= {{row2 | json}}</pre></div>
                                                       <div class="col"> <pre>row3= {{row3 | json}}</pre></div>
                                                        <div class="col"> <pre>row4= {{row4 | json}}</pre></div>-->
                                                </div>
<!--                                                </div>-->
<!--                                            </div>-->
									
            <div class="d-flex bg-orange">
            
                <div class="col pt-1"><marquee><p id="welcome-text" >Welcome in Bigstar</p></marquee></div>
            </div>
            
            
                <div class="container" id="receipt-div" ng-show="false" ng-repeat="x in barcodeList">
               
               <div ng-repeat="x in barcodeList">
	               <h4>{{x.bcd}}</h4>
	               	<div class="d-flex col-12 mt-1 pl-0">
	                    <label  class="col-2">Barcode</label>
	                    <div class="col-6">
	                        <span ng-bind="x.bcd">: </span>
	                    </div>
	                 </div>
	                 <div class="d-flex col-12 mt-1 pl-0">
	                    <label  class="col-3">Big Star {{x.game_name}} {{x.series_name}} - {{currentGameMrp | number:2}}</label>
	                 </div>
	                 
	                 <div class="d-flex col-12 mt-1 pl-0">
	                    <label  class="col-1">Date:</label><span ng-bind="purchase_date"></span>
	                   
	                    <label  class="col-1">Dr.Time:</label> <span ng-bind="ongoing_draw" class="col-1"></span>
	                 </div>
	                 <hr style="border-top: dotted 1px;" />
	                 

					<div class="d-flex flex-wrap align-content-start">
						 <div class="p-2" ng-repeat="i in allGameValue track by $index">
						   {{i + ','}}&nbsp;
						    
						</div>
					</div>
					
					
					<hr style="border-top: dotted 1px;" />
					<div class="d-flex col-12">
	                    <label  class="col-1">MRP:&nbsp;</label><span ng-bind="currentGameMrp | number:2"></span>
	                    <label  class="col-1">Qty:</label> <span ng-bind="totalticket_qty| number:2"></span>
	                    <label  class="col-2">{{purchase_time}}</label>
                 	</div>
                 	<div class="d-flex col-12">
	                    <label  class="col-1">Rs:</label><span ng-bind="totalticket_purchase|number: 2"></span>
                 	</div>
                 	<div class="d-flex col-12">
	                    <label  class="col-2">Terminal Id</label><span>: <?php echo ($this->session->userdata('user_id'));?></span>
	                    
                 	</div>
                 	<div class="d-flex col-12">
	                    <angular-barcode ng-model="x.bcd" bc-options="barcodeOilBill" bc-class="barcode" bc-type="img"></angular-barcode>
                 	</div>
	            	
	            </div>
				  

				</div>

            
            <!--<input type="button" value="Print" ng-click="huiPrintDiv('receipt-div','my_printing_style.css',1)">-->
        </div>

        <?php
    }

    public function insert_game_values(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Game_model->insert_game_values((object)$post_data['playDetails'],$post_data['checkList'],$post_data['drawId'],$post_data['purchasedTicket']);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_all_play_series(){
        $result=$this->Game_model->select_play_series()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_all_draw_time(){
        $result=$this->Game_model->select_from_draw_master()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_game_activation_details(){
        $result=$this->Game_model->select_game_activation()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_draw_result(){
        $post_data =json_decode(file_get_contents("php://input"), true);

        $result=$this->Game_model->select_game_result_after_each_draw($post_data['drawId']);
        //print_r($result);
//        $report_array['records']=$result->records;
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }


    public function get_previous_result(){
        $post_data =json_decode(file_get_contents("php://input"), true);

        $result=$this->Game_model->select_previous_game_result()->result_array();
//        $report_array['records']=$result->records;
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }

    public function get_result_sheet_today(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Game_model->select_today_result_sheet()->result_array();
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }

    public function get_result_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Game_model->select_result_sheet_by_date($post_data['result_date'])->result_array();
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }


//    	TWELVE(12) CARD GAME

    public function get_card_game_price(){
        $result=$this->Card_model->select_card_price()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function insert_card_values(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Card_model->insert_card_game_values((object)$post_data['cardValues'],$post_data['drawId'],$post_data['cardPriceDetailsId'],$post_data['purchasedTicket']);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }


    public function get_current_card_game_draw_time(){
        $result=$this->Card_model->select_from_card_draw_master()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }


    public function get_today_card_result(){
        $result=$this->Card_model->select_today_card_result()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_card_result_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Card_model->select_card_result_by_date($post_data['result_date'])->result_array();
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }
    
    
    
    	/* LUCKY 3 LUCKY 3 LUCKY 3 LUCKY 3 LUCKY 3 LUCKY 3 LUCKY 3 */
    public function get_lucky3_draw_time(){
        $result=$this->Lucky3_model->select_draw_time_list()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }				
    				
    public function get_lucky_cat_price_details(){
        $result=$this->Lucky3_model->select_category_details()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }				
    				
    				
    	/* END OF LUCKY 3 LUCKY 3 LUCKY 3 LUCKY 3 LUCKY 3 LUCKY 3 LUCKY 3 */
    
    
    
    function logout_cpanel(){
    	$user_id=$this->session->userdata('user_id');
    	
    	$result=$this->Game_model->logout_current_session($user_id);
    	
        $newdata = array(
            'person_id'  => '',
            'person_name'     => '',
            'user_id'=> '',
            'person_cat_id'     => '',
            'is_logged_in' => 0,
            'is_currently_loggedin' => 0,
        );
        $this->session->set_userdata($newdata);

        echo json_encode($newdata,JSON_NUMERIC_CHECK);
    }
    
    
    public function get_timestamp(){
    	$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
		echo $date->format('h:i:sA');    
            
    }


}
?>