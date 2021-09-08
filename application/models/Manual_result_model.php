<?php
class manual_result_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function select_series(){
        $sql="select * from play_series";
        $result = $this->db->query($sql,array());
        return $result;
    }
    function select_ten_digit_draw_time(){
        $sql="select * from draw_master where draw_master_id not in
(select draw_master_id from result_master where date(record_time)=date(curdate()))";
        $result = $this->db->query($sql,array());
        return $result;
    }
    function select_card_draw_time(){
        $sql="select * from card_draw_master where card_draw_master_id not in
(select card_draw_master_id from card_result_master where date(record_time)=date(curdate()))";
        $result = $this->db->query($sql,array());
        return $result;
    }



    function insert_digit_game_manual_result($master){
        $return_array=array();
        try{
            $this->db->query("START TRANSACTION");
            $this->db->trans_start();


            //adding two_digit payout//
            $sql="select count(*) as count_row from manual_result_digit where play_series_id=? and draw_master_id=? and game_date=date(CURDATE())";
            $result=$this->db->query($sql,array(
                $master->play_series_id
                ,$master-> draw_master_id

            ));

            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }
            $is_result_exist=$result->row()->count_row;

            if($is_result_exist==1){
                $sql="update manual_result_digit set result=? where play_series_id=? and draw_master_id=? and game_date=date(CURDATE())";
                $result=$this->db->query($sql,array(
                    $master->result
                    ,$master->play_series_id
                    ,$master-> draw_master_id

                ));

            }
            if($is_result_exist==0){
                $sql="insert into manual_result_digit (
                       id
                      ,play_series_id
                      ,draw_master_id
                      ,game_date
                      ,result
                    ) VALUES (null,?,?,curdate(),?)";

                $result=$this->db->query($sql,array(
                    $master->play_series_id
                ,$master-> draw_master_id
                ,$master->result

                ));
            }
            //adding card payout//


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



    function insert_twelve_card_game_manual_result($cardMaster){
        $return_array=array();
        try{
            $this->db->query("START TRANSACTION");
            $this->db->trans_start();


            //adding two_digit payout//
            $sql="select count(*) as count_row from manual_result_card where draw_master_id=? and game_date=date(CURDATE())";
            $result=$this->db->query($sql,array(
                $cardMaster->card_draw_master_id

            ));

            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }
            $is_result_exist=$result->row()->count_row;

            if($is_result_exist==1){
                $sql="update manual_result_card set result=? where draw_master_id=? and game_date=date(CURDATE())";
                $result=$this->db->query($sql,array(
                    $cardMaster->result
                ,$cardMaster->card_draw_master_id
                ));

            }
            if($is_result_exist==0){
                $sql="insert into manual_result_card (
                       id
                      ,draw_master_id
                      ,game_date
                      ,result
                    ) VALUES (null,?,curdate(),?)";

                $result=$this->db->query($sql,array(
                   $cardMaster-> card_draw_master_id
                    ,$cardMaster->result

                ));
            }
            //adding card payout//


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

    function update_stockist_details($stockist){
        $return_array=array();
        try{
            $this->db->trans_start();
            $sql="update stockist set stockist_name=?, user_id=?, user_password=? where stockist_id=?";
            $result=$this->db->query($sql,array(
            $stockist->stockist_name
            ,$stockist->user_id
            ,$stockist->user_password
            ,$stockist->stockist_id
            ));
            $return_array['dberror']=$this->db->error();
            if($result==FALSE){
                throw new Exception('error adding purchase master');
            }
            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
        }
        catch(mysqli_sql_exception $e){
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
            $return_array['error_code']=$err->code;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//end of function



}//final

?>