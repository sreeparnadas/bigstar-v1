<?php
class terminal_limit_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function insert_terminal_recharge($terminal_id,$stockist_id,$amount){
        $return_array=array();
        $financial_year=get_financial_year();
        try{
            $this->db->query("START TRANSACTION");
            $this->db->trans_start();


            //adding recharge_to_stockist//
            $sql="insert into recharge_to_terminal (
                   recharge_to_terminal_id
                  ,recharge_master_id
                  ,recharge_master_cat_id
                  ,terminal_id
                  ,amount
                ) VALUES (null,?,1,?,?)";

            $result=$this->db->query($sql,array(
                $this->session->userdata('person_id')
                ,$terminal_id
                ,$amount
            ));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }

            //update current balance of the terminal//
            $sql="update stockist_to_person set current_balance=current_balance + ? where person_id=?";

            $result=$this->db->query($sql,array($amount,$terminal_id));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }

            $sql="select current_balance from stockist_to_person where person_id=?";

            $result=$this->db->query($sql,array($terminal_id));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }
            $current_balance=$result->row()->current_balance;

            //update current balance of the stockist//
            $sql="update stockist set current_balance=current_balance - ? where stockist_id=?";

            $result=$this->db->query($sql,array($amount,$stockist_id));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }

            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
            $return_array['current_balance']=$current_balance;
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


    function select_all_terminal(){
        $sql="select 
            stockist_to_person.stockist_id
            , stockist_to_person.person_id as terminal_id
            , stockist_to_person.current_balance as terminal_current_balance
            , person.person_name as terminal_name
            , person.user_id as terminal_user_id
            , person.user_password as terminal_password
            , stockist.current_balance as stockist_current_balance
            , stockist.user_id as stockist_user_id
            from stockist_to_person
            inner join person on stockist_to_person.person_id = person.person_id
            inner join stockist on stockist_to_person.stockist_id = stockist.stockist_id";
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