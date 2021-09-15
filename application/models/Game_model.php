<?php
class game_model extends CI_Model {
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

    function select_from_draw_master(){
        $sql="select * from draw_master where active=1";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }


//    testing to get activation of game
    function select_game_activation(){
        $sql="select * from game_activation_table";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }


    function select_game_result_after_each_draw($draw_id){
        $return_array=array();
        try{
            $this->db->trans_start();
            $sql="select curdate() as today_date";
            $result=$this->db->query($sql,array());
            $today=$result->row()->today_date;
            $return_array['today']=$today;

            $sql="select date_format(curtime(),'%H-%i-%s') as today_time";
            $result=$this->db->query($sql,array());
            $today_time=$result->row()->today_time;
            $return_array['today_time']=$today_time;


            $sql="select get_final_result_row(?, ?, ?) as row_number,get_final_result_column(?,?,?) as column_number, 1 as series_id";
            $result=$this->db->query($sql,array($draw_id,1,$today,$draw_id,1,$today));
            if($result==FALSE){
                throw new Exception('error getting result');
            }
            $record[]=$result->row();

            $sql="select get_final_result_row(?, ?, ?) as row_number,get_final_result_column(?,?,?) as column_number, 2 as series_id";
            $result=$this->db->query($sql,array($draw_id,2,$today,$draw_id,2,$today));
            if($result==FALSE){
                throw new Exception('error getting result');
            }
            $record[]=$result->row();


            $sql="select get_final_result_row(?, ?, ?) as row_number,get_final_result_column(?,?,?) as column_number, 3 as series_id";
            $result=$this->db->query($sql,array($draw_id,3,$today,$draw_id,3,$today));
            if($result==FALSE){
                throw new Exception('error getting result');
            }
            $record[]=$result->row();

            $sql="select get_final_result_row(?, ?, ?) as row_number,get_final_result_column(?,?,?) as column_number, 4 as series_id";
            $result=$this->db->query($sql,array($draw_id,4,$today,$draw_id,4,$today));
            if($result==FALSE){
                throw new Exception('error getting result');
            }
            $record[]=$result->row();

            $sql="select get_final_result_row(?, ?, ?) as row_number,get_final_result_column(?,?,?) as column_number, 5 as series_id";
            $result=$this->db->query($sql,array($draw_id,5,$today,$draw_id,5,$today));
            if($result==FALSE){
                throw new Exception('error getting result');
            }
            $record[]=$result->row();

            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['records']=$record;

            //INSERT INTO result_master AND result_details TABLE

            $result_master_id = 'RSLT'.'-'.$today.'-'.$draw_id.'-'.$today_time;
            $sql="insert into result_master (
				   result_master_id
				  ,draw_master_id
				  ,game_date
				) VALUES (?,?,?)";
            $result=$this->db->query($sql,array(
                $result_master_id
            ,$draw_id
            ,$today
            ));
            if($result==FALSE){
                throw new Exception('error adding play_master');
            }

            //INSERT INTO result_details TABLE

            $sql="insert into result_details (
				   result_details_id
				  ,result_master_id
				  ,play_series_id
				  ,result_row
				  ,result_column
				) VALUES (?,?,?,?,?)";
            foreach($record as $index=>$value){
                $row=(object)$value;
                $result=$this->db->query($sql,array(
                    $result_master_id.'-'.($index+1)
                ,$result_master_id
                ,$index+1
                ,$row->row_number
                ,$row->column_number
                ));
            }


            $err=(object) $this->db->error();
            $return_array['error']= create_log($err->code,$this->db->last_query(),'game_model','select_game_result_after_each_draw',"log_file.csv");
        }catch (Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']= create_log($err->code,$this->db->last_query(),'game_model','select_game_result_after_each_draw',"log_file.csv");
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;


    }



    function select_previous_game_result(){
        $sql="select 
		result_details.play_series_id
		,result_details.result_details_id
		, result_details.result_row as row_number
		, result_details.result_column as column_number
		, draw_master.start_time
		, draw_master.end_time
		, draw_master.meridiem
		from result_details
		inner join result_master on result_details.result_master_id = result_master.result_master_id
		inner join draw_master on result_master.draw_master_id = draw_master.draw_master_id
		order by result_master.record_time desc limit 5";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }


    function select_today_result_sheet(){
        $sql="select start_time
				,end_time
				,meridiem
				,max(a_row) as a_row
				,max(a_column) as a_column
				, max(b_row) as b_row
				,max(b_column) as b_column
				,max(c_row) as c_row
				,max(c_column) as c_column
				,max(d_row) as d_row
				,max(d_column) as d_column
				,max(e_row) as e_row
				,max(e_column) as e_column
				from (select *,
				case when series_name = 'A' then row_number end as a_row ,
				    case when series_name = 'A' then column_number end as a_column,
				    case when series_name = 'B' then row_number end as b_row,
				    case when series_name = 'B' then column_number end as b_column,
				    case when series_name = 'C' then row_number end as c_row,
				    case when series_name = 'C' then column_number end as c_column,
				    case when series_name = 'D' then row_number end as d_row,
				    case when series_name = 'D' then column_number end as d_column,
				    case when series_name = 'E' then row_number end as e_row,
				    case when series_name = 'E' then column_number end as e_column
				from (select 
						 draw_master.start_time
						, draw_master.end_time
						, draw_master.meridiem
						,play_series.series_name
						,result_details.result_master_id
				    ,result_details.result_details_id
						, result_details.result_row as row_number
						, result_details.result_column as column_number
						from result_details
						inner join result_master on result_details.result_master_id = result_master.result_master_id
						inner join draw_master on result_master.draw_master_id = draw_master.draw_master_id
				    inner join play_series on result_details.play_series_id = play_series.play_series_id
						where date(now())=date(result_master.record_time)
				    order by result_details.result_details_id) as table1
				    group by result_details_id) as table2 group by result_master_id desc";
        $result=$this->db->query($sql,array());
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }

    function select_result_sheet_by_date($result_date){
        $sql="select start_time
				,end_time
				,meridiem
				,max(a_row) as a_row
				,max(a_column) as a_column
				, max(b_row) as b_row
				,max(b_column) as b_column
				,max(c_row) as c_row
				,max(c_column) as c_column
				,max(d_row) as d_row
				,max(d_column) as d_column
				,max(e_row) as e_row
				,max(e_column) as e_column
				from (select *,
				case when series_name = 'A' then row_number end as a_row ,
				    case when series_name = 'A' then column_number end as a_column,
				    case when series_name = 'B' then row_number end as b_row,
				    case when series_name = 'B' then column_number end as b_column,
				    case when series_name = 'C' then row_number end as c_row,
				    case when series_name = 'C' then column_number end as c_column,
				    case when series_name = 'D' then row_number end as d_row,
				    case when series_name = 'D' then column_number end as d_column,
				    case when series_name = 'E' then row_number end as e_row,
				    case when series_name = 'E' then column_number end as e_column
				from (select 
                        draw_master.serial_number
						 ,draw_master.start_time
						, draw_master.end_time
						, draw_master.meridiem
						,play_series.series_name
						,result_details.result_master_id
				    ,result_details.result_details_id
						, result_details.result_row as row_number
						, result_details.result_column as column_number
						from result_details
						inner join result_master on result_details.result_master_id = result_master.result_master_id
						inner join draw_master on result_master.draw_master_id = draw_master.draw_master_id
				    inner join play_series on result_details.play_series_id = play_series.play_series_id
						where date(result_master.record_time)=?
				    order by result_details.result_details_id) as table1
				    group by result_details_id) as table2 group by result_master_id order by serial_number desc";
        $result=$this->db->query($sql,array($result_date));
        if($result!=null){
            return $result;
        }else{
            return null;
        }
    }






}//final

?>