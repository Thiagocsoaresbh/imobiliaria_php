<?php
/**
* @package BSI
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/
class bsiSearch
{
	public $checkInDate       = '';
    public $checkOutDate      = '';	
	public $mysqlCheckInDate  = '';
    public $mysqlCheckOutDate = '';
	public $noofRoom          = 0;
	public $nightCount        = 0;
	public $price	          = 0;
	public $priceArr          = array();
	public $category          = "";
	public $type              = "";
	public $bedroom           = 0;
	public $bathroom          = 0;
	public $appartmentid      = 0;
	public $sorting           = 'asc';
	public $fullDateRange;
	public $searchCode        = "SUCCESS";
	const SEARCH_CODE         = "SUCCESS";

	

	function bsiSearch() {				
		$this->setRequestParams();
		$this->getNightCount();
		$this->checkSearchEngine();	
		//if($this->searchCode == self::SEARCH_CODE){
			$this->fullDateRange = $this->getDateRangeArray($this->mysqlCheckInDate, $this->mysqlCheckOutDate);
			$this->setMySessionVars();
		//}	
	}

	private function setRequestParams() {		
		global $bsiCore;
		$tmpVar = isset($_POST['apartmentname'])? $_POST['apartmentname'] : NULL;
		$this->setMyParamValue($this->appartmentid, $bsiCore->ClearInput($tmpVar), NULL, true);
		$tmpVar = isset($_POST['category'])? $_POST['category'] : NULL;
		$this->setMyParamValue($this->category, $bsiCore->ClearInput($tmpVar), NULL, true);
		
		$tmpVar = isset($_POST['appartment_type'])? $_POST['appartment_type'] : NULL; 		
		$this->setMyParamValue($this->type, $bsiCore->ClearInput($tmpVar), NULL, true); 
		
		$tmpVar = isset($_POST['sorting'])? $_POST['sorting'] : NULL; 		
		$this->setMyParamValue($this->sorting, $bsiCore->ClearInput($tmpVar), NULL, true); 
		
	   
		
		if($bsiCore->config['conf_rental_type'] == 1){
			$tmpVar = isset($_POST['check_in'])? $_POST['check_in'] : NULL;
			$this->setMyParamValue($this->checkInDate, mysql_real_escape_string($tmpVar), NULL, true);
			
			$tmpVar = isset($_POST['check_out'])? $_POST['check_out'] : NULL; 
			$this->setMyParamValue($this->checkOutDate, mysql_real_escape_string($tmpVar), NULL, true); 
			
			$this->mysqlCheckInDate  = $bsiCore->getMySqlDate($this->checkInDate); 
			$this->mysqlCheckOutDate = $bsiCore->getMySqlDate($this->checkOutDate);	 
			
		}else if($bsiCore->config['conf_rental_type'] == 2){
			$tmpVar = isset($_POST['check_in'])? $_POST['check_in'] : NULL;
			$this->setMyParamValue($this->checkInDate, mysql_real_escape_string($tmpVar), NULL, true);
		
			$this->mysqlCheckInDate  = $bsiCore->getMySqlDate($this->checkInDate); 
			$tmpVar = strtotime($this->mysqlCheckInDate);
			$tmpVar = strtotime("+".mysql_real_escape_string($_POST['weeks'])." week", $tmpVar);
			
			if(isset($_SESSION['rentaltype'])){ unset($_SESSION['rentaltype']); }
				$_SESSION['rentaltype'] = $_POST['weeks'];
				
			$tmpVar = strtotime("-1 day", $tmpVar);
			$tmpVar = date($bsiCore->phpDateformat($bsiCore->config['conf_dateformat']), $tmpVar);
			$this->setMyParamValue($this->checkOutDate, $tmpVar, NULL, true); 
			$this->mysqlCheckOutDate = $bsiCore->getMySqlDate($this->checkOutDate);	 
		}else if($bsiCore->config['conf_rental_type'] == 3){
			$checkinout = $bsiCore->get_days_in_month(mysql_real_escape_string($_POST['txtMonthIn']), mysql_real_escape_string($_POST['months']));
			
			if(isset($_SESSION['rentaltype'])){ unset($_SESSION['rentaltype']); }
			$_SESSION['rentaltype'] = $_POST['months'];
			$this->setMyParamValue($this->checkInDate, $checkinout[0], NULL, true);
			$this->setMyParamValue($this->checkOutDate, $checkinout[1], NULL, true); 
			$this->mysqlCheckInDate  = $checkinout[2]; 
			$this->mysqlCheckOutDate = $checkinout[3];	 
		}
		
	    $tmpVar = isset($_POST['maxbed'])? $_POST['maxbed'] : 0;
		$this->setMyParamValue($this->bedroom, $bsiCore->ClearInput($tmpVar), 0, true);
		$tmpVar = isset($_POST['maxbath'])? $_POST['maxbath'] : 0;
		$this->setMyParamValue($this->bathroom, $bsiCore->ClearInput($tmpVar), 0, true);  
			 			
		 			
	}

	

	private function setMyParamValue(&$membervariable, $paramvalue, $defaultvalue, $required = false){
		if($required){if(!isset($paramvalue)){$this->invalidRequest();}}
		if(isset($paramvalue)){ $membervariable = $paramvalue;}else{$membervariable = $defaultvalue;}
	}

	private function setMySessionVars(){
		if(isset($_SESSION['sv_checkindate']))   unset($_SESSION['sv_checkindate']);
		if(isset($_SESSION['sv_checkoutdate']))  unset($_SESSION['sv_checkoutdate']);
		if(isset($_SESSION['sv_mcheckindate']))  unset($_SESSION['sv_mcheckindate']);
		if(isset($_SESSION['sv_mcheckoutdate'])) unset($_SESSION['sv_mcheckoutdate']);
		if(isset($_SESSION['sv_nightcount']))    unset($_SESSION['sv_nightcount']);
		if(isset($_SESSION['sv_bedroom']))       unset($_SESSION['sv_bedroom']);
		if(isset($_SESSION['sv_bathroom']))      unset($_SESSION['sv_bathroom']);
		if(isset($_SESSION['appmt_type']))       unset($_SESSION['appmt_type']);
		if(isset($_SESSION['svars_details']))    unset($_SESSION['svars_details']);
		if(isset($_SESSION['svars_sorting']))    unset($_SESSION['svars_sorting']);
		
	    $_SESSION['sv_checkindate']   = $this->checkInDate;
		$_SESSION['sv_checkoutdate']  = $this->checkOutDate;
		$_SESSION['sv_mcheckindate']  = $this->mysqlCheckInDate;
		$_SESSION['sv_mcheckoutdate'] = $this->mysqlCheckOutDate;
		$_SESSION['sv_nightcount']    = $this->nightCount;		
		$_SESSION['sv_bedroom']       = $this->bedroom;	
		$_SESSION['sv_bathroom']      = $this->bathroom;
		$_SESSION['appmt_type']       = $this->type;	
		$_SESSION['svars_sorting']       = $this->sorting;	
		$_SESSION['svars_details']    = array();
		
	}

	
	private function invalidRequest(){
		header('Location: booking-failure.php?error_code=9');
		die;
	}

	private function getNightCount() {		
		$checkin_date = getdate(strtotime($this->mysqlCheckInDate));
		$checkout_date = getdate(strtotime($this->mysqlCheckOutDate));
		$checkin_date_new = mktime( 12, 0, 0, $checkin_date['mon'], $checkin_date['mday'], $checkin_date['year']);
		$checkout_date_new = mktime( 12, 0, 0, $checkout_date['mon'], $checkout_date['mday'], $checkout_date['year']);
		$this->nightCount = round(abs($checkin_date_new - $checkout_date_new) / 86400);
	}


	private function getDateRangeArray($startDate, $endDate, $nightAdjustment = true) {	
		$date_arr = array(); 
		$day_array=array(); 
		$total_array=array();
	    $time_from = mktime(1,0,0,substr($startDate,5,2), substr($startDate,8,2),substr($startDate,0,4));
		$time_to = mktime(1,0,0,substr($endDate,5,2), substr($endDate,8,2),substr($endDate,0,4));		
		if ($time_to >= $time_from) { 
			if($nightAdjustment){
				while ($time_from < $time_to) {      
					array_push($date_arr, date('Y-m-d',$time_from));
					array_push($day_array, date('D',$time_from));
					$time_from+= 86400; // add 24 hours
				}
			}else{
				while($time_from <= $time_to) {      
					array_push($date_arr, date('Y-m-d',$time_from));
					array_push($day_array, $time_from);
					$time_from+= 86400; // add 24 hours
				}
			}			
		}  
		array_push($total_array, $date_arr);
		array_push($total_array, $day_array);
		return $total_array;		
	}


	private function checkSearchEngine(){
		global $bsiCore;
		if(intval($bsiCore->config['conf_booking_turn_off']) > 0){
			$this->searchCode = "SEARCH_ENGINE_TURN_OFF";
			return 0;
		}

		$diffrow  = mysql_fetch_assoc(mysql_query("SELECT DATEDIFF('".$this->mysqlCheckOutDate."', '".$this->mysqlCheckInDate."') AS INOUTDIFF"));
		$dateDiff = intval($diffrow['INOUTDIFF']);
		if($dateDiff < 0){
			$this->searchCode = "OUT_BEFORE_IN";
			return 0;
		}else if($dateDiff < intval($bsiCore->config['conf_minimum_stay'])){
			$this->searchCode = "NOT_MINNIMUM_NIGHT";
			return 0;
		}

		$userInputDate = strtotime($this->mysqlCheckInDate);
		$hotelDateTime = strtotime(date("Y-m-d"));
		$timezonediff =  ($userInputDate - $hotelDateTime);
		if($timezonediff < 0){
			$this->searchCode = "TIME_ZONE_MISMATCH";
			return 0;
		}		
	}
		
	public function getAvailableAppartment(){
		global $bsiCore;		
		$currency_symbol = $bsiCore->config['conf_currency_symbol'];
		$tmpctr = 0;		
		$result = mysql_query("select appmt_id from bsi_appmt_user where user_id=".$_SESSION['apmt_clientid']);			
		if(mysql_num_rows($result)){
								
			while($row = mysql_fetch_assoc($result)){
				
				if($bsiCore->config['conf_rental_type'] == 1){	
						
					$searchsql = "select appmt_id, appmt_name from bsi_apartment_master where appmt_id=".$row['appmt_id']." and appmt_id not in (select appmt_id from bsi_bookings WHERE is_deleted = FALSE AND (('".$this->mysqlCheckInDate."' BETWEEN checkin_date AND DATE_SUB(checkout_date, INTERVAL 1 DAY)) OR (DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY) BETWEEN checkin_date AND DATE_SUB(checkout_date, INTERVAL 1 DAY)) OR (checkin_date BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY)) OR (DATE_SUB(checkout_date, INTERVAL 1 DAY) BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY))))";
				
				}else if($bsiCore->config['conf_rental_type'] == 2 || $bsiCore->config['conf_rental_type'] == 3){
					
					$searchsql = "select appmt_id, appmt_name from bsi_apartment_master where appmt_id=".$row['appmt_id']." and appmt_id not in (select appmt_id from bsi_bookings WHERE is_deleted = FALSE AND (('".$this->mysqlCheckInDate."' BETWEEN checkin_date AND checkout_date) OR ('".$this->mysqlCheckOutDate."' BETWEEN checkin_date AND checkout_date) OR (checkin_date BETWEEN '".$this->mysqlCheckInDate."' AND '".$this->mysqlCheckOutDate."') OR (checkout_date BETWEEN '".$this->mysqlCheckInDate."' AND '".$this->mysqlCheckOutDate."')))";	
					
				}
				
				$sql = mysql_query($searchsql);
				$tmpctr = 1;
				while($currentrow = mysql_fetch_assoc($sql)){ 				
					$searchresult[$currentrow['appmt_id']] = array('appartmentName' => $currentrow['appmt_name']);
					$tmpctr++;
				}
				mysql_free_result($sql);
				
			}
			
		}
		
		
		
		if($tmpctr > 1) $_SESSION['svars_details']= $searchresult;
		unset($searchresult);
		
		return array('roomcnt' => $tmpctr-1); 
	}
}
?>