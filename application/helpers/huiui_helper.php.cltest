<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_current_month')){
	function get_current_month(){
		return date('m');
	}
}
if ( ! function_exists('get_date_value')){
    function get_date_value(){
        date_default_timezone_set('Asia/Kolkata');
        $todayDate = date("Ymd");
        return $todayDate;
    }
}
if ( ! function_exists('get_time_value')){
    function get_time_value(){
        date_default_timezone_set('Asia/Kolkata');
        $dt2=date("His");//Get current time in 24 format//
        return $dt2;
    }
}

if ( ! function_exists('convert_number_to_words')){
	
	function convert_number_to_words($number) {
		
		$hyphen      = '-';
		$conjunction = ' ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' and paisa ';
		$dictionary  = array(
		0                   => 'zero',
		1                   => 'one',
		2                   => 'two',
		3                   => 'three',
		4                   => 'four',
		5                   => 'five',
		6                   => 'six',
		7                   => 'seven',
		8                   => 'eight',
		9                   => 'nine',
		10                  => 'ten',
		11                  => 'eleven',
		12                  => 'twelve',
		13                  => 'thirteen',
		14                  => 'fourteen',
		15                  => 'fifteen',
		16                  => 'sixteen',
		17                  => 'seventeen',
		18                  => 'eighteen',
		19                  => 'nineteen',
		20                  => 'twenty',
		30                  => 'thirty',
		40                  => 'fourty',
		50                  => 'fifty',
		60                  => 'sixty',
		70                  => 'seventy',
		80                  => 'eighty',
		90                  => 'ninety',
		100                 => 'hundred',
		1000                => 'thousand',
		1000000             => 'million',
		1000000000          => 'billion',
		1000000000000       => 'trillion',
		1000000000000000    => 'quadrillion',
		1000000000000000000 => 'quintillion'
		);
		
		if (!is_numeric($number)) {
			return false;
		}
		
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
			'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
			E_USER_WARNING
			);
			return false;
		}
		
		if ($number < 0) {
			return $negative . convert_number_to_words(abs($number));
		}
		
		$string = $fraction = null;
		
		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}
		
		switch (true) {
			case $number < 21:
			$string = $dictionary[$number];
			break;
			case $number < 100:
			$tens   = ((int) ($number / 10)) * 10;
			$units  = $number % 10;
			$string = $dictionary[$tens];
			if ($units) {
				$string .= $hyphen . $dictionary[$units];
			}
			break;
			case $number < 1000:
			$hundreds  = $number / 100;
			$remainder = $number % 100;
			$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
			if ($remainder) {
				//$string .= $conjunction . $this->convert_number_to_words($remainder);
				$string .= $conjunction . convert_number_to_words($remainder);
			}
			break;
			default:
			$baseUnit = pow(1000, floor(log($number, 1000)));
			$numBaseUnits = (int) ($number / $baseUnit);
			$remainder = $number % $baseUnit;
			//$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
			$string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
			if ($remainder) {
				$string .= $remainder < 100 ? $conjunction : $separator;
				//$string .= $this->convert_number_to_words($remainder);
				$string .= convert_number_to_words($remainder);
			}
			break;
		}
		
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			//$words = array();
			/*foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}*/
			if($fraction<10){
				$fraction*=10;
			}
			//$string.=$this->convert_number_to_words($fraction);
			$string.=convert_number_to_words($fraction);
			//$string .= implode(' ', $words);
		}
		
		return $string;
	}
}
//get financial year
if ( ! function_exists('get_financial_year')){
	function get_financial_year($year=0,$month=0){
		
		$date=array();
		$date=getdate();
		if($year==0 && $month==0){
			$year=get_current_year();
			$month=get_current_month();
		}
		if($year>0 && $year<100){
			$year=2000+$year;
		}
		if($year){
			$date['year']=$year;
		}
		if($month){
			$date['mon']=$month;
		}
		$date['year']=$year;
		$date['year']=$date['year']%100;
		$fy=0;
		if($date['mon']>=1 && $date['mon']<=3){
			$fy=($date['year']-1)*100+$date['year'];
		}else{
			$fy=($date['year']*100)+$date['year']+1;
		}
		return $fy;
	}
}
if ( ! function_exists('get_current_date')){
	//function to get current date
	function get_current_date($separator="/"){
        date_default_timezone_set('Asia/Kolkata');
		list($day, $month, $year) = explode("/", date("d/m/Y"));
		$dt=$day.$separator.$month.$separator.$year;
		return $dt;
	}
}
if ( ! function_exists('get_current_sql_date')){
	//function to get current date
	function get_current_sql_date($separator="-"){
		list($day, $month, $year) = explode("/", date("d/m/Y"));
		$dt=$year.$separator.$month.$separator.$day;
		return $dt;
	}
}

if ( ! function_exists('add_days')){
	//function to get current date
	function add_days($base_date,$days,$separator="/"){
		list($day, $month, $year) = explode("/",$base_date);
		$dt=($day+$days).$separator.$month.$separator.$year;
		return $dt;
	}
}

if ( ! function_exists('get_current_month')){
	//function to get current date
	function get_current_month($separator="/"){
		list($day, $month, $year) = explode("/", date("d/m/Y"));
		return $month;
	}
}
if ( ! function_exists('get_current_year')){
	//function to get current date
	function get_current_year($separator="/"){
		list($day, $month, $year) = explode("/", date("d/m/Y"));
		return $year;
	}
}
if ( ! function_exists('sql_date_to_dmy')){
	function sql_date_to_dmy($sql_date) {
		if($sql_date==''){
			return '';
		}
		list($year, $month, $day) = explode("-", $sql_date);
		$dmy = $day;
		$dmy .= '/';
		$dmy .= $month;
		$dmy .= '/';
		$dmy .= $year;
		return $dmy;
	}
}
if ( ! function_exists('timestamp_to_dmy')){
	function timestamp_to_dmy($timestamp) {
		if($timestamp==''){
			return '';
		}
		list($sql_date,$sql_time)=explode(" ", $timestamp);
		list($year, $month, $day) = explode("-", $sql_date);
		$dmy = $day;
		$dmy .= '/';
		$dmy .= $month;
		$dmy .= '/';
		$dmy .= $year;
		return $dmy;
	}
}
if ( ! function_exists('to_sql_date')){
	function to_sql_date($our_date) {
		list($day, $month, $year) = explode("/", $our_date);
		$sql_date = $year;
		$sql_date .= '-';
		$sql_date .= $month;
		$sql_date .= '-';
		$sql_date .= $day;
		return $sql_date;
	}
}
if ( ! function_exists('prepare_dropdown_list')){
	function prepare_dropdown_list($table,$id_field,$name_field1,$name_field2='') {
		$CI =& get_instance();
    	$CI->load->model('main_model');
		$result = $CI->main_model -> get_dropdown_array($table,$id_field,$name_field1,$name_field2);
		$data = array();
		$data[0] = '--Select--';
		if($name_field2==''){
			foreach ($result as $row) {
			$data[$row[$id_field]] = $row[$name_field1];
			}
		}else{
			foreach ($result as $row) {
			$data[$row[$id_field]] = $row[$name_field1].' - '.$row[$name_field2];
			}
		}
		
		return $data;
	}
}




if ( ! function_exists('month_name_from_value')){
	function month_name_from_value($month){
		$timestamp = mktime(0, 0, 0, $month, date("d"), date("Y"));
		return date("F", $timestamp);  
	}
}

if ( ! function_exists('print_line')){
	function print_line($val1='',$val2='',$val3='',$val4=''){
		echo '<br/>'.$val1.$val2.$val3.$val4;
		return NULL;
	}
}
if ( ! function_exists('leading_zeroes')){
	function leading_zeroes($val,$length){
		while($length-strlen($val)>0){
			$val='0'.$val;
		}
		return $val;
	}
	if ( ! function_exists('is_leap_year')){
		function is_leap_year($year){
			if($year%400==0 || ($year%1000!=0 && $year%4==0)){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	if ( ! function_exists('date_serial_number')){
		function date_serial_number($my_date){
			$months=array(31,28,31,30,31,30,31,31,30,31,30,31);
			list($day, $month, $year) = explode("/", $my_date);
			$temp_year=$year-1;
			$temp_days=0;
			for($x=1900;$x<=$temp_year;$x++){
				$temp_days+=365;
				if(is_leap_year($x)){
					$temp_days+=1;
				}
			}
			for($x=1;$x<=$month-1;$x++){
				$temp_days+=$months[$x-1];
			}
			if(is_leap_year($year) && $month>2){
				$temp_days+=1;
			}
			$temp_days+=$day;
			return $temp_days;
		}
	}
	if(! function_exists('date_diff')){
			function date_diff(DateTime $date1, DateTime $date2) {
	        $diff = new DateInterval();
	        if($date1 > $date2) {
	            $tmp = $date1;
	            $date1 = $date2;
	            $date2 = $tmp;
	            $diff->invert = true;
	        }
	       
	        $diff->y = ((int) $date2->format('Y')) - ((int) $date1->format('Y'));
	        $diff->m = ((int) $date2->format('n')) - ((int) $date1->format('n'));
	        if($diff->m < 0) {
	            $diff->y -= 1;
	            $diff->m = $diff->m + 12;
	        }
	        $diff->d = ((int) $date2->format('j')) - ((int) $date1->format('j'));
	        if($diff->d < 0) {
	            $diff->m -= 1;
	            $diff->d = $diff->d + ((int) $date1->format('t'));
	        }
	        $diff->h = ((int) $date2->format('G')) - ((int) $date1->format('G'));
	        if($diff->h < 0) {
	            $diff->d -= 1;
	            $diff->h = $diff->h + 24;
	        }
	        $diff->i = ((int) $date2->format('i')) - ((int) $date1->format('i'));
	        if($diff->i < 0) {
	            $diff->h -= 1;
	            $diff->i = $diff->i + 60;
	        }
	        $diff->s = ((int) $date2->format('s')) - ((int) $date1->format('s'));
	        if($diff->s < 0) {
	            $diff->i -= 1;
	            $diff->s = $diff->s + 60;
	        }
	       
	        return $diff;
	    }
	}
}
if ( ! function_exists('cell_format')){
	function cell_format($data,$type='number',$class='numeric',$id='ID',$prefix='',$suffix='',$colspan=0){
	
		switch($type){
			case 'number':
					if($data<0){
						$cell=array('data'=>$prefix.number_format($data,2, '.', ','),'class' => $class.' text-right '.' negetive','id'=>$id);
					}else{
						$cell=array('data'=>$prefix.number_format($data,2, '.', ','),'class' => $class.' text-right ','id'=>$id);
					}
					
					break;
			case 'signed_integer':
					if($data<0){
						$cell=array('data'=>$prefix.$data,'class' => $class.' negetive','id'=>$id);
					}else{
						$cell=array('data'=>$prefix.$data,'class' => $class,'id'=>$id);
					}
					
					break;
			case 'gold':
					if($data<0){
						$cell=array('data'=>$prefix.number_format($data,3, '.', ',').$suffix,'class' => $class.' negetive text-right','id'=>$id);

					}else{
						$cell=array('data'=>$prefix.number_format($data,3, '.', ',').$suffix,'class' => 'numeric text-right '.$class,'id'=>$id);

					}
					break;
			case 'currency':
					if($data<0){
						$cell=array('data'=>$prefix.'<img alt="Rs." src="'.base_url().'img/7px-Indian_Rupee_symbol.png" >'.' '.number_format($data,2, '.', ',').$suffix,'class' => $class.' negetive','id'=>$id);	
					}else{
						$cell=array('data'=>$prefix.'<img alt="Rs." src="'.base_url().'img/7px-Indian_Rupee_symbol.png" >'.' '.number_format($data,2, '.', ',').$suffix,'class' => $class,'id'=>$id);	
					}
					break;
			case 'rupee':
					if($data<0){
						$cell=array('data'=>$prefix.'<i class="fa fa-inr"></i>'.' '.number_format($data,2, '.', ',').$suffix,'class' => $class.' negetive'.' text-right ','id'=>$id);	
					}else{
						$cell=array('data'=>$prefix.'<i class="fa fa-inr"></i>'.' '.number_format($data,2, '.', ',').$suffix,'class' => $class.' text-right ','id'=>$id);	
					}
					break;
			case 'integer':
					if($data<0){
						
					}else{
						
					}
					$cell=array('data'=>$prefix.number_format($data,0).$suffix,'class' => $class,'id'=>$id,'colspan'=>$colspan);
					break;
			case 'job':
					$cell=array('data'=>$data.$suffix,'class' => $class,'id'=>$id,'colspan'=>$colspan);
					break;
			case 'percent':
					$cell=array('data'=>$prefix.number_format($data,2, '.', ',').'%'.$suffix,'class' =>'numeric '.$class,'id'=>$id);
					break;
			case 'tonch':
					$cell=array('data'=>$prefix.number_format($data,3, '.', ',').'t'.$suffix,'class' =>'numeric '.$class,'id'=>$id);
					break;	
			case 'text':
					$cell=array('data'=>$prefix.$data.$suffix,'class' => $class,'id'=>$id,'colspan'=>$colspan);
					break;	
			default:
					$cell=$data;
					break;
		}
		return $cell;
	}
	if ( ! function_exists('is_authorised')){
		function is_authorised($priv_value, $form_value) {//this is function is used to check the user credential for a particular page
			$x=65536;
			while ($priv_value>0) {
				if ($x <= $priv_value) {
					if ($x == $form_value) {
						return TRUE;
					} else {
						$priv_value -= $x;
						$x /= 2;
					}
				} else {
					$x /= 2;
				}
			}
			return FALSE;
		}//end of function
	}
	if(! function_exists('default_table_template')){
		function default_table_template($value = 'id=""') {
			$template = array('table_open' => '<table border="0" cellpadding="4" '.$value.' cellspacing="0">'
							, 'table_close' => '</table>'
							, 'heading_open' => '<thead>'
							, 'heading_close' => '</thead>'
							, 'heading_row_start' => '<tr>'
							, 'heading_row_end' => '</tr>'
							, 'heading_cell_start' => '<th>'
							, 'heading_cell_end' => '</th>'
							, 'sub_heading_row_start' => '<tr>'
							, 'sub_heading_row_end' => '</tr>'
							, 'sub_heading_cell_start' => '<th>'
							,'tfoot_open'=> '<tfoot>'
           					,'tfoot_close'=> '</tfoot>'
							, 'sub_heading_cell_end' => '</th>'
							, 'body_open' => '<tbody>'
							, 'body_close' => '</tbody>'
							//, 'row_start' => '<tr>'
							//, 'row_end' => '</tr>'
							, 'cell_start' => '<td>'
							, 'cell_end' => '</td>'
							//, 'row_alt_start' => '<tr class="alt">'
							//, 'row_alt_end' => '</tr>'
							, 'cell_alt_start' => '<td>'
							, 'cell_alt_end' => '</td>'
							, 'footing_open' => '<tfoot>'
							, 'footing_close' => '</tfoot>'
							, 'footing_row_start' => '<tr>'
							, 'footing_row_end' => '</tr>'
							, 'footing_cell_start' => '<td>'
							, 'footing_cell_end' => '</td>'
						);
			return $template;
		}
	}
	if(! function_exists('add_image')){
		function add_image($file='',$alt='',$height="20px") {
			$image_path='<img height="'.$height.'" alt="'.$alt.'" src="'.base_url().'img/'.$file.'">';
			return $image_path;
		}
	}
	if(! function_exists('is_even')){
		function is_even($num){
			if($num&1){
				return FALSE;
			}else{
				return TRUE;
			}
		}
	}
}
if ( ! function_exists('is_leap_year')){
	function is_leap_year($year){
		if($year%400==0 || ($year%100!=0 && $year%4==0)){
			return TRUE;
		}else{
			return FALSE;	
		}
	}
}
if ( ! function_exists('date_to_serial')){
	function date_to_serial($tempdate){
		$month_days=array(1=>0,2=>31,3=>59,4=>90,5=>120,5=>151,7=>181,8=>212,9=>243,10=>273,11=>304,12=>334);
		list($day, $month, $year) = explode("/", $tempdate);
		$day=(int)$day;
		$month=(int)$month;
		$year=(int)$year;
		$temp_year=$year-1;
		$temp_days=0;
		for($x=1900;$x<=$temp_year;$x++){
			$temp_days=$temp_days+365+(is_leap_year($x)?1:0);
		}
		$temp_days+=$month_days[$month]+$day+((is_leap_year($year) && $month>2)?1:0);
		return $temp_days;
	}
}
if ( ! function_exists('pagination')){
	function pagination($total_records,$per_page,$class="page gradient"){
		$page_required=ceil($total_records/$per_page);
		$pageVal=0;
		$page_str='<div class="pagination" id="pagination-div">';
		$page_str.='<a href="#" class="page gradient selected" pageVal="'.$pageVal.'">first</a>';
		for($i=1;$i<$page_required-1;$i++){
			$pageVal+=$per_page;
			$page_str.='<a href="#" class="page gradient" pageVal="'.$pageVal.'">'.($i+1).'</a>';
		}
		$pageVal+=$per_page;
		$page_str.='<a href="#" class="page gradient" pageVal="'.$pageVal.'">last</a>';
		$page_str.='</div>';
		return $page_str;
	}
}


if ( ! function_exists('generate_random_string')){
	function generate_random_string($characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',$length=10){
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}




if ( ! function_exists('create_log')){
	function create_log($error='no Error',$msg="no error",$model="no model",$method="no funnction",$file_name="log_file.csv"){
		// open file
	   $fd = fopen($file_name, "a");
	   // append date/time to message
	   $sl=generate_random_string('AXHY0123456789',10);
	   $str =date("Y-m-d h:i:s", time()) .','.$sl.','.$model.','.$method.','.$error .$msg ; 
	   // write string
	   fwrite($fd, $str.PHP_EOL);
	   // close file
	   fclose($fd);
	   return $sl.', '.$error;
	}
}

if ( ! function_exists('create_log_simple')){
	function create_log_simple($error,$file_name="log_file.csv"){
		// open file
		$fd = fopen($file_name, "a");
		// append date/time to message
		$str =date("Y-m-d h:i:s", time()) .','.','.','.','.$error ;
		// write string
		fwrite($fd, $str.PHP_EOL);
		// close file
		fclose($fd);
		return $error;
	}
}


if ( ! function_exists('start_div')){
	function start_div($parameters=""){
		echo '<div '.$parameters.' >';
	}
}
if ( ! function_exists('end_div')){
	function end_div($parameters="div end"){
		echo '<div>'.'<!--end of '.$parameters.'-->';
	}
}

if ( ! function_exists('base_url_with_port')){
	function base_url_with_port(){
		$organisation="";
		$actual_base_address='';
		if (file_exists('organisation.xml')) {
			$organisation = simplexml_load_file('organisation.xml');
		} else {
			exit('Failed to open test.xml.');
		}
		list($a,$b,$c,$d)=explode('/',base_url());
		$c=$organisation->our_website;
		if($_SERVER['SERVER_PORT']!=80) {
			return 'http://' . $c . ':' . $_SERVER['SERVER_PORT'] . '/' . $d;
		}else{
			return 'http://' . $c. '/' . $d;
		}
	}
}

?>