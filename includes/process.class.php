<?php
/**
* @package BSI
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/
class BookingProcess
{
	private $apparmentid		= 0;
	private $checkInDate		= '';
	private $checkOutDate		= '';
	private $noOfNights			= 0;
	private $noOfBed			= 0;
	private $noOfBath			= 0;
	private $mysqlCheckInDate	= '';
	private $mysqlCheckOutDate	= '';	
	private $clientdata			= array();
	private $pricedata			= array();
	private $apmtArr		    = array();
	private $expTime			= 0;	
	private $depositAmount      = 0.00;
	private $taxAmount 			= 0.00;
	private $taxPercent			= 0.00;
	private $subTotalAmount 	= 0.00;	
	private $grandTotalAmount 	= 0.00;	
	private $currencySymbol		= '';
	private $type               = '';
	private $bedroom            = 0;
	private $bathroom           = 0;
	private $rentalType         = '';
	public $clientId			= 0;
	public $clientName			= '';
	public $clientEmail			= '';
	public $bookingId			= 0;
	public $paymentGatewayCode	= '';		
	public $totalPaymentAmount 	= 0.00;	
	public $invoiceHtml			= '';
	public $appmtName			= '';
	
	function BookingProcess() {				
		$this->setMyRequestParams();
		$this->removeSessionVariables();
		$this->checkAvailability();
		$this->saveClientData();
		$this->saveBookingData();
		$this->createInvoice();
	}
	
	private function removeSessionVariables(){
		if(isset($_SESSION['sv_checkindate']))   unset($_SESSION['sv_checkindate']);
		if(isset($_SESSION['sv_checkoutdate']))  unset($_SESSION['sv_checkoutdate']);
		if(isset($_SESSION['sv_mcheckindate']))  unset($_SESSION['sv_mcheckindate']);
		if(isset($_SESSION['sv_mcheckoutdate'])) unset($_SESSION['sv_mcheckoutdate']);	
		if(isset($_SESSION['sv_nightcount']))    unset($_SESSION['sv_nightcount']);
		if(isset($_SESSION['appmtid_12']))       unset($_SESSION['appmtid_12']);
		if(isset($_SESSION['reservationdata']))  unset($_SESSION['reservationdata']);	
		if(isset($_SESSION['svars_details']))    unset($_SESSION['svars_details']);
		if(isset($_SESSION['appmt_type']))       unset($_SESSION['appmt_type']);
		if(isset($_SESSION['tax']))              unset($_SESSION['tax']);
		if(isset($_SESSION['grandtotal']))       unset($_SESSION['grandtotal']);
		if(isset($_SESSION['deposit']))          unset($_SESSION['deposit']);
		if(isset($_SESSION['sv_bedroom']))       unset($_SESSION['sv_bedroom']);
		if(isset($_SESSION['sv_bathroom']))      unset($_SESSION['sv_bathroom']);
		if(isset($_SESSION['dv_subtotal']))      unset($_SESSION['dv_subtotal']);
	}
	
	private function setMyRequestParams(){ 
		global $bsiCore;	
		$this->setMyParamValue($this->checkInDate, 'SESSION', 'sv_checkindate', NULL, true);
		$this->setMyParamValue($this->checkOutDate, 'SESSION', 'sv_checkoutdate', NULL, true);
		$this->setMyParamValue($this->mysqlCheckInDate, 'SESSION', 'sv_mcheckindate', NULL, true);
		$this->setMyParamValue($this->mysqlCheckOutDate, 'SESSION', 'sv_mcheckoutdate', NULL, true);
		$this->setMyParamValue($this->noOfNights, 'SESSION', 'sv_nightcount', 0, true);
		$this->setMyParamValue($this->apparmentid, 'SESSION', 'appmtid_12', '', true);			
		$this->setMyParamValue($this->reservationdata, 'SESSION', 'reservationdata', NULL, true);	
		$this->setMyParamValue($this->taxAmount, 'SESSION', 'tax', NULL, true);	
		$this->setMyParamValue($this->subTotalAmount, 'SESSION', 'dv_subtotal', NULL, true);
		$this->setMyParamValue($this->grandTotalAmount, 'SESSION', 'grandtotal', NULL, true);	
		$this->setMyParamValue($this->depositAmount, 'SESSION', 'deposit', NULL, true);
		$this->setMyParamValue($this->type, 'SESSION', 'appmt_type', NULL, true);
		$this->setMyParamValue($this->bedroom, 'SESSION', 'sv_bedroom', NULL, true);
		$this->setMyParamValue($this->bathroom, 'SESSION', 'sv_bathroom', NULL, true);			
		$this->setMyParamValue($this->clientdata['title'], 'POST', 'title', true); 
		$this->setMyParamValue($this->clientdata['first_name'], 'POST', 'first_name', '', true);
		$this->setMyParamValue($this->clientdata['surname'], 'POST', 'surname', '', true);
		$this->setMyParamValue($this->clientdata['street_addr'], 'POST', 'street_addr', '', true);
		$this->setMyParamValue($this->clientdata['street_addr2'], 'POST', 'street_addr2', '', true);
		$this->setMyParamValue($this->clientdata['city'], 'POST', 'city', '', true);
		$this->setMyParamValue($this->clientdata['province'], 'POST', 'province', '', true);
		$this->setMyParamValue($this->clientdata['zip'], 'POST', 'zip', '', true);
		$this->setMyParamValue($this->clientdata['country'], 'POST', 'country', '', true);
		$this->setMyParamValue($this->clientdata['phone'], 'POST', 'phone', '', true);
		$this->setMyParamValue($this->clientdata['email'], 'POST', 'email', '', false);					
		$this->setMyParamValue($this->clientdata['clientip'], 'POST', 'ip','', true);
		$this->setMyParamValue($this->paymentGatewayCode, 'POST', 'payment_gateway','', true);	
			
		$this->bookingId		= time();		
		$this->expTime 			= intval($bsiCore->config['conf_booking_exptime']);	
		$this->currencySymbol 	= $bsiCore->config['conf_currency_symbol'];
		$this->taxPercent 		= $bsiCore->config['conf_tax_amount'];
		$this->clientName 		= $this->clientdata['title']." ".$this->clientdata['first_name']." ". $this->clientdata['surname'];
		$this->clientEmail		= $this->clientdata['email'];	
		$this->pricedata        = $bsiCore->getApartmentPrice($this->apparmentid, $this->noOfNights);
		$this->apmtArr          = $bsiCore->getApartmentdetails($this->apparmentid);
		$this->appmtName        = $bsiCore->getApartmentname($this->apparmentid);
		if($this->depositAmount > 0){
			$this->totalPaymentAmount = $this->depositAmount;
		}else{
			$this->totalPaymentAmount = $this->grandTotalAmount;
		}
	}
	
	private function setMyParamValue(&$membervariable, $vartype, $param, $defaultvalue, $required = false){
		global $bsiCore;
		switch($vartype){
			case "POST": 
				if($required){if(!isset($_POST[$param])){$this->invalidRequest(9);} 
					else{$membervariable = $bsiCore->ClearInput($_POST[$param]);}}
				else{if(isset($_POST[$param])){$membervariable = $bsiCore->ClearInput($_POST[$param]);} 
					else{$membervariable = $defaultvalue;}}				
				break;	
			case "GET":
				if($required){if(!isset($_GET[$param])){$this->invalidRequest(9);} 
					else{$membervariable = $bsiCore->ClearInput($_GET[$param]);}}
				else{if(isset($_GET[$param])){$membervariable = $bsiCore->ClearInput($_GET[$param]);} 
					else{$membervariable = $defaultvalue;}}				
				break;	
			case "SESSION":
				if($required){if(!isset($_SESSION[$param])){$this->invalidRequest(9);} 
					else{$membervariable = $_SESSION[$param];}}
				else{if(isset($_SESSION[$param])){$membervariable = $_SESSION[$param];} 
					else{$membervariable = $defaultvalue;}}				
				break;	
			case "REQUEST":
				if($required){if(!isset($_REQUEST[$param])){$this->invalidRequest(9);}
					else{$membervariable = $bsiCore->ClearInput($_REQUEST[$param]);}}
				else{if(isset($_REQUEST[$param])){$membervariable = $bsiCore->ClearInput($_REQUEST[$param]);}
					else{$membervariable = $defaultvalue;}}				
				break;
			case "SERVER":
				if($required){if(!isset($_SERVER[$param])){$this->invalidRequest(9);}
					else{$membervariable = $_SERVER[$param];}}
				else{if(isset($_SERVER[$param])){$membervariable = $_SERVER[$param];}
					else{$membervariable = $defaultvalue;}}				
				break;			
		}		
	}	
	
	private function invalidRequest($errocode = 9){		
		header('Location: booking-failure.php?error_code='.$errocode.'');
		die;
	}
	 
	/* Check Immediate Booking Status For Concurrent Access */
	private function checkAvailability(){		
		$sql = "select appmt_id from bsi_bookings where appmt_id = ".$this->apparmentid." AND ((NOW() - booking_time) < ".$this->expTime.") AND is_deleted = FALSE
AND (('".$this->mysqlCheckInDate."' BETWEEN checkin_date AND DATE_SUB(checkout_date, INTERVAL 1 DAY)) OR (DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY) BETWEEN checkin_date AND DATE_SUB(checkout_date, INTERVAL 1 DAY)) OR (checkin_date BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY))
OR (DATE_SUB(checkout_date, INTERVAL 1 DAY) BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY)))";				
		$sql = mysql_query($sql);	
		if(mysql_num_rows($sql)){	
			mysql_free_result($sql);
			$this->invalidRequest(13);
			die;
		}
		mysql_free_result($sql);
	}
	
	private function saveClientData(){
		$sql1 = mysql_query("SELECT client_id FROM bsi_clients WHERE email = '".$this->clientdata['email']."'");
		if(mysql_num_rows($sql1) > 0){
			$clientrow = mysql_fetch_assoc($sql1);
			$this->clientId = $clientrow["client_id"];	
			$sql2 = mysql_query("UPDATE bsi_clients SET first_name = '".$this->clientdata['first_name']."', surname = '".$this->clientdata['surname']."', title = '".$this->clientdata['title']."', street_addr = '".$this->clientdata['street_addr']."', street_addr2 = '".$this->clientdata['street_addr2']."', city = '".$this->clientdata['city']."' , province = '".$this->clientdata['province']."', zip = '".$this->clientdata['zip']."', country = '".$this->clientdata['country']."', phone = '".$this->clientdata['phone']."', ip = '".$this->clientdata['clientip']."' WHERE client_id = ".$this->clientId);				
		}else{
			$sql2 = mysql_query("INSERT INTO bsi_clients (first_name, surname, title, street_addr, street_addr2, city, province, zip, country, phone, email, ip) values('".$this->clientdata['first_name']."', '".$this->clientdata['surname']."', '".$this->clientdata['title']."', '".$this->clientdata['street_addr']."', '".$this->clientdata['street_addr2']."', '".$this->clientdata['city']."' , '".$this->clientdata['province']."', '".$this->clientdata['zip']."', '".$this->clientdata['country']."', '".$this->clientdata['phone']."', '".$this->clientdata['email']."', '".$this->clientdata['clientip']."')");
			$this->clientId = mysql_insert_id();			
		}
		mysql_free_result($sql1);		
	}
	
	private function saveBookingData(){
		$sql = mysql_query("INSERT INTO bsi_bookings (booking_id, appmt_id, booking_time, checkin_date, checkout_date, client_id, total_cost, payment_amount, payment_type) values(".$this->bookingId.", ".$this->apparmentid.", NOW(), '".$this->mysqlCheckInDate."', '".$this->mysqlCheckOutDate."', ".$this->clientId.", ".$this->grandTotalAmount.", ".$this->depositAmount.", '".$this->paymentGatewayCode."')");
	}	
	
	private function createInvoice(){
		global $bsiCore;
		$this->invoiceHtml = '<table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:700px; border:none;" cellpadding="3" cellspacing="1"><tbody><tr><td align="left" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" colspan="4">Booking Details</td></tr>
		<tr><td align="left" style="background:#ffffff;">Booking Number</td><td align="left" style="background:#ffffff;" colspan="3">'.$this->bookingId.'</td></tr>
		<tr><td align="left" style="background:#ffffff;">Customer Name</td><td align="left" style="background:#ffffff;" colspan="3">'.$this->clientName.'</td></tr>	
		<tr height="8px;"><td align="left" style="background:#ffffff;" colspan="4"></td></tr>
		<tr><td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">Check In Date</td><td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">Check Out Date</td><td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">Total '.$bsiCore->showBooktype().'(s)</td></tr>
		<tr><td align="center" style="background:#ffffff;">'.$this->checkInDate.'</td><td align="center" style="background:#ffffff;">'.$this->checkOutDate.'</td><td align="center" style="background:#ffffff;">';
		if(isset($_SESSION['rentaltype'])){
			$this->invoiceHtml .= $_SESSION['rentaltype'];	
		}else{
			$this->invoiceHtml .= $this->noOfNights;	
		}
		$this->invoiceHtml .= '</td></tr>
		<tr height="8px;"><td align="left" style="background:#ffffff;" colspan="3">&nbsp;</td></tr>
		<tr><td align="" style="font-weight:bold; font-variant:small-caps; background:#eeeeee; width:30%">Apartment Name</td><td colspan="2" align="" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">'.$this->apmtArr['appmt_name'].'</td></tr><tr><td align="" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">Apartment Address</td><td colspan="2" align="" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">'.$this->apmtArr['addr1']." ".$this->apmtArr['city'].'</td></tr>';		
		
		$this->invoiceHtml.= '<tr height="8px;"><td align="left" style="background:#ffffff;" colspan="3"></td></tr><tr><td colspan="2" align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">Sub Total</td><td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">'.$this->currencySymbol.number_format($this->subTotalAmount, 2 , '.', ',').'</td></tr>';
					
			
		if($this->taxPercent > 0){ 	
			$this->invoiceHtml.= '<tr><td colspan="2" align="right" style="background:#ffffff;">Tax('.number_format($this->taxPercent, 2 , '.', '').'%)</td><td align="right" style="background:#ffffff;">(+) '.$this->currencySymbol.number_format($this->taxAmount, 2 , '.', ',').'</td></tr><tr><td colspan="2" align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">Grand Total</td><td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">'.$this->currencySymbol.number_format($this->grandTotalAmount, 2 , '.', ',').'</td></tr>';
		}
		if($this->pricedata['pricepercent'] > 0){
			$this->invoiceHtml.= '<tr><td colspan="2" align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">Advance Payment Amount(<span style="font-size: 10px;">'.number_format($this->pricedata['pricepercent'], 2 , '.', '').'% of Grand Total</span>)</td><td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">'.$this->currencySymbol.number_format($this->depositAmount, 2 , '.', ',').'</td></tr>';
		}
		$this->invoiceHtml.= '</tbody></table>';
		
		if($this->paymentGatewayCode == "poa" || $this->paymentGatewayCode == "admin"){
			$payoptions = $bsiCore->getPaymentGateway($this->paymentGatewayCode);		
			if($this->paymentGatewayCode == "admin"){
				$payoptions = "Manual: Booked By Administrator";	
			}
			$this->invoiceHtml.= '<br /><table  style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1"><tr><td align="left" colspan="2" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">Payment Details</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps;background:#ffffff;">Payment Options</td><td align="left" style="background:#ffffff;">'.$payoptions.'</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff;">Transaction Id</td><td align="left" style="background:#ffffff;">NA</td></tr></table>';					
		}
		
		/* insert the invoice data in bsi_invoice table */
		$insertInvoiceSQL = mysql_query("INSERT INTO bsi_invoice(booking_id, client_name, client_email, invoice) values(".$this->bookingId.", '".$this->clientName."', '".$this->clientdata['email']."', '".$this->invoiceHtml."')");
		if(isset($_SESSION['rentaltype'])) unset($_SESSION['rentaltype']);	
	}
}

?>