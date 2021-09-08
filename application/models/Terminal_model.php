<?php
class terminal_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function select_next_user_id_for_terminal($serialNo,$stockistId){
        $sql="select count(*) as row_num from max_terminal where stockist_id=?";
        $result = $this->db->query($sql,array($stockistId));
        $count_row=$result->row()->row_num;
        if($count_row>0){
            $sql="select (current_value+1) current_value from max_terminal where stockist_id=?";
            $result = $this->db->query($sql,array($stockistId));
            $current_value=$result->row()->current_value;
            $terminal_user_id = 'S'.$serialNo.'-'.leading_zeroes($current_value,4);
        }else{
            $current_value=1;
            $terminal_user_id = 'S'.$serialNo.'-'.leading_zeroes($current_value,4);
        }

        return $terminal_user_id;
    }

    function insert_new_terminal($terminal,$stockist_sl_no,$stockist_id){
        $return_array=array();
        $financial_year=get_financial_year();
        try{
            $this->db->query("START TRANSACTION");
            $this->db->trans_start();

            //insert into maxtable
            $sql="insert into maxtable (subject_name, current_value, financial_year,prefix)
            	values('terminal',1,?,'T')
				on duplicate key UPDATE id=last_insert_id(id), current_value=current_value+1";
            $result = $this->db->query($sql, array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for sale_master');
            }
            //getting from maxtable
            $sql="select * from maxtable where id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $terminal_id=$result->row()->prefix.'-'.leading_zeroes($result->row()->current_value,4).'-'.$financial_year;


//            insert into max_terminal

            $sql="insert into max_terminal (stockist_id,current_value,financial_year) VALUES (?,1,?)
            on duplicate key UPDATE id=last_insert_id(id), current_value=current_value+1";
            $result = $this->db->query($sql, array($stockist_id,$financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for sale_master');
            }
            //getting from maxtable
            $sql="select * from max_terminal where id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $terminal_user_id='S'.$stockist_sl_no.'-'.leading_zeroes($result->row()->current_value,4);

//            adding New Bill Master
            //adding product//
            $sql="insert into person (
                   person_id
                  ,person_cat_id
                  ,person_name
                  ,user_id
                  ,user_password
                  ,inforce
                ) VALUES (?,?,?,?,?,1)";

            $result=$this->db->query($sql,array(
                $terminal_id
            ,3
            ,$terminal-> person_name
            ,$terminal_user_id
            ,$terminal-> user_password
            ));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }
            //insert into stockist to person

            $sql="insert into stockist_to_person (
                   stockist_to_person_id
                  ,stockist_id
                  ,person_id
                  ,inforce
                ) VALUES (null,?,?,1)";

            $result=$this->db->query($sql,array(
                $terminal->stockist
            ,$terminal_id
            ));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }



            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
            $return_array['person_id']=$terminal_id;
            $return_array['user_id']=$terminal_user_id;
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


//    function insert_new_terminal($terminal,$stockist_sl_no){
//        $return_array=array();
//        $financial_year=get_financial_year();
//        try{
//            $this->db->query("START TRANSACTION");
//            $this->db->trans_start();
//
//            //insert into maxtable
//            $sql="insert into maxtable (subject_name, current_value, financial_year,prefix)
//            	values('terminal',1,?,'T')
//				on duplicate key UPDATE id=last_insert_id(id), current_value=current_value+1";
//            $result = $this->db->query($sql, array($financial_year));
//            if($result==FALSE){
//                throw new Exception('Increasing Maxtable for sale_master');
//            }
//            //getting from maxtable
//            $sql="select * from maxtable where id=last_insert_id()";
//            $result = $this->db->query($sql);
//            if($result==FALSE){
//                throw new Exception('error getting maxtable');
//            }
//            $terminal_id=$result->row()->prefix.'-'.leading_zeroes($result->row()->current_value,4).'-'.$financial_year;
//            $terminal_user_id='S'.$stockist_sl_no.'-'.leading_zeroes($result->row()->current_value,4);
////            adding New Bill Master
//
//            //adding product//
//            $sql="insert into person (
//                   person_id
//                  ,person_cat_id
//                  ,person_name
//                  ,user_id
//                  ,user_password
//                  ,inforce
//                ) VALUES (?,?,?,?,?,1)";
//
//            $result=$this->db->query($sql,array(
//                $terminal_id
//                ,3
//                ,$terminal-> person_name
//                ,$terminal_user_id
//                ,$terminal-> user_password
//            ));
//            $return_array['dberror']=$this->db->error();
//
//            if($result==FALSE){
//                throw new Exception('error adding sale master');
//            }
//            //insert into stockist to person
//
//            $sql="insert into stockist_to_person (
//                   stockist_to_person_id
//                  ,stockist_id
//                  ,person_id
//                  ,inforce
//                ) VALUES (null,?,?,1)";
//
//            $result=$this->db->query($sql,array(
//                $terminal->stockist
//                ,$terminal_id
//            ));
//            $return_array['dberror']=$this->db->error();
//
//            if($result==FALSE){
//                throw new Exception('error adding sale master');
//            }
//
//
//
//            $this->db->trans_complete();
//            $return_array['success']=1;
//            $return_array['message']='Successfully recorded';
//            $return_array['person_id']=$terminal_id;
//            $return_array['user_id']=$terminal_user_id;
//        }catch(mysqli_sql_exception $e){
//            //$err=(object) $this->db->error();
//
//            $err=(object) $this->db->error();
//            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
//            $return_array['success']=0;
//            $return_array['message']='test';
//            $this->db->query("ROLLBACK");
//        }catch(Exception $e){
//            $err=(object) $this->db->error();
//            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
//            // $return_array['error']=mysql_error;
//            $return_array['success']=0;
//            $return_array['message']=$err->message;
//            $this->db->query("ROLLBACK");
//        }
//        return (object)$return_array;
//    }//end of function


    function select_all_stockist(){
        $sql="select 
            stockist_id, stockist_name, user_id, user_password,serial_no
            from stockist where inforce=1";
        $result = $this->db->query($sql,array());
        return $result;
    }

    function select_all_terminal(){
        $sql="select 
             stockist_to_person.person_id
            ,stockist_to_person.stockist_id
            , stockist.stockist_name
            , person.person_name
            , person.user_id
            , person.user_password
            from stockist_to_person
            inner join stockist on stockist_to_person.stockist_id = stockist.stockist_id
            inner join person on stockist_to_person.person_id = person.person_id
            where stockist.inforce=1";
        $result = $this->db->query($sql,array());
        return $result;
    }

    function update_terminal_details($terminal){
        $return_array=array();
        try{
            $this->db->trans_start();
            $sql="update person set person_name=?, user_password=? where person_id=?";
            $result=$this->db->query($sql,array(
                $terminal->person_name
                ,$terminal->user_password
                ,$terminal->person_id
            ));
            $return_array['dberror']=$this->db->error();
            if($result==FALSE){
                throw new Exception('error adding purchase master');
            }
//            update the stockist_to_person table
            $sql="update stockist_to_person set stockist_id=? where person_id=?";
            $result=$this->db->query($sql,array(
                $terminal->stockist_id
                ,$terminal->person_id
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