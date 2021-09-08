<?php
class lucky3_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }
    
    function logout_current_session($userid){           
            
			//Getting database id of the terminal//
            $sql="select person_id from person where user_id=?";
            $result = $this->db->query($sql,array($userid));
            if($result->num_rows()>0){
                $person_id=$result->row()->person_id;
            }else{
				$person_id='';
			}
            
            //set zero active user sum_value
            
            $sql="update active_terminal set check_sum=0,last_loggedout=now() where person_id=?";
    		$result = $this->db->query($sql,array($person_id));
    		return $result;
    }//end of function


    function select_terminal_balance($terminal_id){
        $sql="select * from stockist_to_person where person_id=?";
        $result = $this->db->query($sql,array($terminal_id));
        return $result->row();
    }


    function select_play_series(){
        $sql="select * from play_series";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }


    function insert_game_values($playDetails,$checkList,$drawId,$purchasedTicket){
    	$financial_year=get_financial_year();
    	 //insert into maxtable
            $sql="insert into barcode_max (subject_name, current_value, financial_year)
            	values('digit bill',1,?)
				on duplicate key UPDATE id=last_insert_id(id), current_value=current_value+1";
            $result = $this->db->query($sql, array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for sale_master');
            }
            
             //getting from maxtable
            $sql="select * from barcode_max where id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $bcd=leading_zeroes($result->row()->current_value,10).''.$financial_year;            
    	
    	
        $checkList_length= sizeof($checkList);
        $ticket_taken_date=get_date_value();
        $ticket_taken_time=get_time_value();
        /* GET TICKET TAKEN TIME  */
        $hr=intval(substr($ticket_taken_time, 0, 2));
        $min=intval(substr($ticket_taken_time, 2, 2));
        $sec=intval(substr($ticket_taken_time, 4, 2));
        if($hr>=12){
			$merid='PM';
		}else{
			$merid='AM';
		}
		if($hr>12){
			$hr-=12;
		}
		if($min<10){
			$min='0'.$min;
		}
		if($sec<10){
			$sec='0'.$sec;
		}
		$show_purchase_time=$hr.':'.$min.':'.$sec.''.$merid;
		/* GET TICKET TAKEN DATE */
		$yyyy=intval(substr($ticket_taken_date, 0, 4));
        $mm=intval(substr($ticket_taken_date, 4, 2));
        $dd=intval(substr($ticket_taken_date, 6, 2));
        if($dd<10){
			$dd='0'.$dd;
		}
		if($mm<10){
			$mm='0'.$mm;
		}
		$show_purchase_date=$dd.'/'.$mm.'/'.$yyyy;
		
        $terminal_id=$this->session->userdata('user_id');
        $i=0;
        $return_array=array();
        try{
            $this->db->trans_start();
            //Getting database id of the terminal//
            $sql="select person_id from person where user_id=?";
            $result = $this->db->query($sql,array($terminal_id));
            if($result==FALSE){
                throw new Exception('error getting person_id');
            }
            $person_id=$result->row()->person_id;
            while($i<$checkList_length)
            {
                $sr=$checkList[$i]['series_name'];
                //$barcode=$drawId.'-'.$sr.'-'.$ticket_taken_date.'-'.$ticket_taken_time.'-'.$terminal_id;
                $barcode=$sr.''.$bcd;
                //ADDING INTO PLAY_MASTER//

                $sql="insert into play_master (
                       play_master_id
                      ,terminal_id
                      ,draw_master_id
                    ) VALUES (?,?,?)";
                $result=$this->db->query($sql,array(
                    $barcode
                ,$person_id
                ,$drawId
                ));
                if($result==FALSE){
                    throw new Exception('error adding play_master');
                }
                //ADDING PLAY MASTER COMPLETED//

                //adding play_details//

                $sql="insert into play_details (
                   play_details_id
                  ,play_master_id
                  ,play_series_id
                  ,row_num
                  ,col_num
                  ,game_value
                 ) VALUES (?,?,?,?,?,?)";
                foreach($playDetails as $index=>$value){
                    $row=(object)$value;
                    $result=$this->db->query($sql,array(
                        $barcode . '-' . ($index+1)
                    ,$barcode
                    ,$checkList[$i]['play_series_id']
                    ,$row->row_num
                    ,$row->col_num
                    ,$row->game_value
                    ));
                }
                $return_array['barcode'.$i]=$barcode;
                $i++;
            }

            $return_array['dberror']=$this->db->error();
            if($result==FALSE){
                throw new Exception('error adding play_details');
            }

            $sql="update stockist_to_person set current_balance = current_balance - ? where person_id=?";

            $result=$this->db->query($sql,array($purchasedTicket,$person_id));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error updating terminal balance');
            }

            $this->db->trans_complete();
            $return_array['success']=1;
//            $playDetails=$this->db->query("select * from play_details where play_details_id= LAST_INSERT_ID()")->row();
//            $return_array['play_details_id']=$playDetails->play_details_id;
            //$return_array['play_master_id']=$playDetails->play_details_id;
            $return_array['message']='Successfully recorded';
            $return_array['purchase_time']=$show_purchase_time;
            $return_array['purchase_date']=$show_purchase_date;
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
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }

    function select_draw_time_list(){
        $sql="select * from lucky3_draw_master where active=1";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }
    
    function select_category_details(){
        $sql="select * from lucky3_category where inforce=1";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }













}//final

?>