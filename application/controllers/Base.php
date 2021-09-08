<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
    }
    public function index()
    {
        $this->load->view('public/index_top');
//        $this->load->view('public/index_header');
        $this->load->view('public/index_main');
        $this->load->view('public/index_end');
    }

    public function angular_view_main(){
        $this->load->view('angular_views/main');
    }

    public function validate_credential(){
        $post_data =json_decode(file_get_contents("php://input"), true);
       // $result=$this->Person->get_person_by_authentication((object)$post_data);
        $result=$this->Person->check_login((object)$post_data);
        
        $newdata = array(
            'person_id'  => $result->person_id,
            'person_name'     => $result->person_name,
            'user_id'     => $result->user_id,
            'person_cat_id'     => $result->person_cat_id,
            'is_logged_in' => $result->is_logged_in,
            'is_currently_loggedin' => $result->is_currently_loggedin
        );
        $this->session->set_userdata($newdata);
       echo json_encode($newdata);
    }
    
    
    public function show_headers(){
        if($_GET['person_cat_id']==3){
            $this->load->view('menus/index_header_staff');
        }
        if($_GET['person_cat_id']==1){
            $this->load->view('menus/index_header_admin');
        }
    }
    public function angular_view_login(){
    	
    	/* ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        $from = "sreeparnadas0675@gmail.com";
        $to = "suranjan.123@gmail.com";
        $subject = "Checking PHP mail";
        $message = "This mail is from big-star";
        $headers = "From:" . $from;
        mail($to,$subject,$message, $headers);
        echo "The email message was sent.";*/

        
        ?>
        <style type="text/css">
            body{
                /*background-image: url("img/orange-background.jpg");*/
                /*background-repeat: no-repeat;*/
                /*background-size: cover;*/
                background: -webkit-gradient(linear, left top, left bottom, from(#f6d365), to(#fda085)) fixed;
            }
            .my-panel{
                background-color: #741700;
                border-radius: 10px;
            }

        </style>
        <div class="container-fluid">
            <div class="row" id="main-div">
                <div class="col"></div>
                <div class="col">
                    <div class="row" id="image-div">
                        <div class="col"></div>
                        <div class="col"><img class="img-responsive" src="img/icon1.png"></div>
                        <div class="col"></div>
                    </div>
                    <div class="panel my-panel">
                        <div class="panel-body">
                            <form class="form-horizontal pt-3 mt-5" role="form">
                                <div class="row pt-3">
                                    <div class="col-1"></div>
                                    <div class="col">
                                        <input type="text" class="form-control " ng-model="loginData.user_id" placeholder="Card No">
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-1"></div>
                                    <div class="col">
                                        <input type="password" class="form-control" ng-model="loginData.user_password" placeholder="Pin No">
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                                <div class="row pt-3 pb-3">
                                    <div class="col-2"></div>
                                    <div class="col">
                                        <button ng-click="login(loginData)"  id="login-button" type="submit" class="btn btn-info btn-lg btn-block">Login</button>
                                    </div>
                                    <div class="col-2"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col"></div>
            </div>
<!--            <pre>loginDatabaseResponse= {{loginDatabaseResponse | json}}</pre>-->
        </div>


        <?php
    }
}
?>