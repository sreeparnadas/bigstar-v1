<?php
class payout_settings_model extends CI_Model {
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
    }



    function update_game_payout($twoDigitPayOut,$cardPayOut){
        $return_array=array();
        try{
            $this->db->query("START TRANSACTION");
            $this->db->trans_start();


            //adding two_digit payout//
            $sql="update play_series set payout=? where play_series_id=?";
            foreach($twoDigitPayOut as $index=>$value){
                $row=(object)$value;
                $result=$this->db->query($sql,array(
                $row->payout
                ,$row->play_series_id
                ));
            }

            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }

            //adding card payout//
            $sql="update card_price_details set payout=? where card_price_details_id=?";
            $result=$this->db->query($sql,array(
                $cardPayOut->payout
                ,$cardPayOut-> card_price_details_id

            ));

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
    }//end of function


    function select_game1_payout(){
        $sql="select *,1 as game_no, 'TWO DIGIT' as game_name from play_series";
        $result = $this->db->query($sql,array());
        return $result;
    }

    function select_game2_payout(){
        $sql="select *,2 as game_no,'12 CARDS' as game_name from card_price_details";
        $result = $this->db->query($sql,array());
        return $result;
    }

    



}//final

?>