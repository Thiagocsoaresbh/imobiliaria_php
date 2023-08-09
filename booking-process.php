<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/mail.class.php");
include("includes/process.class.php");
$bookprs = new BookingProcess();
switch($bookprs->paymentGatewayCode){	
	case "poa": processPayOnArrival();break;	
	case "pp": processPayPal();break;		
	default: processOther();
}
/* PAY ON ARIVAL: MANUAL PAYMENT */ 	
function processPayOnArrival(){	
	global $bookprs;
	global $bsiCore;
	$bsiMail      = new bsiMail();
	$emailContent = $bsiMail->loadEmailContent();
	$subject      = $emailContent['subject'];
	
	mysql_query("UPDATE bsi_bookings SET payment_amount='".$bookprs->totalPaymentAmount."', payment_success=true WHERE booking_id = ".$bookprs->bookingId);
	mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email = '".$bookprs->clientEmail."'");		
			
	$emailBody  = "Dear ".$bookprs->clientName.",<br><br>";
	$emailBody .= $emailContent['body']."<br><br>";
	$emailBody .= $bookprs->invoiceHtml;
	$emailBody .= '<br><br>Regards,<br>'.$bsiCore->config['conf_apartment_name'].'<br>'.$bsiCore->config['conf_apartment_phone'];
	$emailBody .= '<br><br><font style=\"color:#F00; font-size:10px;\">[ You will need to carry a print out of this e-mail and present it to the hotel on arrival and check-in. This e-mail is the confirmation voucher for your booking. ]</font>';				
	$returnMsg = $bsiMail->sendEMail($bookprs->clientEmail, $subject, $emailBody);
	if($returnMsg == true) {		
		$notifyEmailSubject = "Booking no.".$bookprs->bookingId." - Notification of Apartment  Booking by ".$bookprs->clientName;				
		$notifynMsg = $bsiMail->sendEMail($bsiCore->config['conf_apartment_email'], $notifyEmailSubject, $bookprs->invoiceHtml); 
		header('Location:booking-confirm.php?success_code=1');
		die;
	}else {
		header('Location: booking-failure.php?error_code=25');
		die;
	}	
	//header('Location: booking-confirm.php?success_code=1');
}
/* PAYPAL PAYMENT */ 
function processPayPal(){
	global $bookprs;
	echo "<script language=\"JavaScript\">";
	echo "document.write('<form action=\"paypal.php\" method=\"post\" name=\"formpaypal\">');";
	echo "document.write('<input type=\"hidden\" name=\"amount\"  value=\"".number_format($bookprs->totalPaymentAmount, 2, '.', '')."\">');";
	echo "document.write('<input type=\"hidden\" name=\"invoice\"  value=\"".$bookprs->bookingId."\">');";
	echo "document.write('<input type=\"hidden\" name=\"appmtName\"  value=\"".$bookprs->appmtName."\">');";
	echo "document.write('</form>');";
	echo "setTimeout(\"document.formpaypal.submit()\",500);";
	echo "</script>";	
}
/* OTHER PAYMENT */
function processOther(){
	/* not implemented yet */
	header('Location: booking-failure.php?error_code=22');
	die;
}
?>