<?php
class report_terminal_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function select_next_user_id_for_stockist(){
        $sql="select (current_value+1) current_value from maxtable where id=17";
        $result = $this->db->query($sql,array());
        $stockist_user_id = 'ST'.leading_zeroes($result->row()->current_value,4);
        return $stockist_user_id;
//        return $result->row();
    }

//    function select_next_user_id_for_stockist(){
//        $sql="select (current_value+1) as current_value from maxtable where id=last_insert_id()";
//        $result = $this->db->query($sql,array());
//
//        return $result;
////        return $result->row();
//    }




    function get_terminal_total_sale_report($start_date,$end_date){
        $terminal_id=$this->session->userdata('user_id');
        $sql="select person_id from person where user_id=?";
        $result = $this->db->query($sql,array($terminal_id));
        if($result==FALSE){
            throw new Exception('error getting person_id');
        }
        $person_id=$result->row()->person_id;
      

        $sql="call fetch_terminal_digit_total_sale(?,?,?);";
        $result = $this->db->query($sql,array($person_id,$start_date,$end_date));
        return $result;
    }


    function get_terminal_card_game_total_sale_report($start_date,$end_date){
        $terminal_id=$this->session->userdata('user_id');
        $sql="select person_id from person where user_id=?";
        $result = $this->db->query($sql,array($terminal_id));
        if($result==FALSE){
            throw new Exception('error getting person_id');
        }
        $person_id=$result->row()->person_id;
        

        $sql="call fetch_terminal_card_total_sale(?,?,?);";
        $result = $this->db->query($sql,array($person_id ,$start_date,$end_date));
        return $result;
    }



    function get_all_barcode_report_by_date($start_date){
        $terminal_id=$this->session->userdata('user_id');
        $sql="select person_id from person where user_id=?";
        $result = $this->db->query($sql,array($terminal_id));
        if($result==FALSE){
            throw new Exception('error getting person_id');
        }
        $person_id=$result->row()->person_id;

        $sql="call digit_barcode_report_from_terminal(?,?);";
        $result = $this->db->query($sql,array($person_id,$start_date));
        return $result;
    }

    function get_all_2d_draw_time_list(){
        $sql="select * from draw_master";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }


//    report for card
    function get_card_game_barcode_report_by_date($start_date){
        $terminal_id=$this->session->userdata('user_id');
        $sql="select person_id from person where user_id=?";
        $result = $this->db->query($sql,array($terminal_id));
        if($result==FALSE){
            throw new Exception('error getting person_id');
        }
        $person_id=$result->row()->person_id;

        $sql="call card_barcode_report_from_terminal(?,?);";
        $result = $this->db->query($sql,array($person_id,$start_date));
        return $result;
    }

    function get_card_draw_time_list(){
        $sql="select * from card_draw_master";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }

    function insert_claimed_barcode($barcode,$game_id,$prize_value){
        $return_array=array();
        try{
            $this->db->query("START TRANSACTION");
            $this->db->trans_start();


            //adding two_digit payout//
            $terminal_id=$this->session->userdata('user_id');
            $sql="select person_id from person where user_id=?";
            $result = $this->db->query($sql,array($terminal_id));
            if($result==FALSE){
                throw new Exception('error getting person_id');
            }
            $person_id=$result->row()->person_id;


            //adding claim_details//
            $sql="insert into claim_details (
                   claim_id
                   ,game_id
                  ,barcode
                  ,terminal_id
                ) VALUES (null,?,?,?)";
            if($game_id==1){
                $result=$this->db->query($sql,array(1,$barcode,$person_id));
            }
            if($game_id==2){
                $result=$this->db->query($sql,array(2,$barcode,$person_id));
            }


            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }

            if($game_id==1) {
                $sql = "update play_master set is_claimed=1 where play_master_id=?";
            }
            if($game_id==2) {
                $sql = "update card_play_master set is_claimed=1 where card_play_master_id=?";
            }
            $result=$this->db->query($sql,array($barcode));

            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }

            $sql="update stockist_to_person set current_balance=current_balance+? where person_id=?";
            $result=$this->db->query($sql,array($prize_value,$person_id));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }



            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();

            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            // $return_array['error']=mysql_error;
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
        }




}//final

?>