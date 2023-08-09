<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/mail.class.php");
$paymentGatewayDetails = $bsiCore->loadPaymentGateways();
$bsiMail = new bsiMail();
$emailContent = $bsiMail->loadEmailContent();
require_once('paypal.class.php');  // include the class file
$invoice = time();
$p = new paypal_class;             // initiate an instance of the class
//$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
            
// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  
switch ($_GET['action']) {
	case 'process':      // Process and order...   
		$p->add_field('business', $paymentGatewayDetails['pp']['account']);
		$p->add_field('return', $this_script.'?action=success');
		$p->add_field('cancel_return', $this_script.'?action=cancel');
		$p->add_field('notify_url', $this_script.'?action=ipn');
		$p->add_field('item_name', $bsiCore->config['conf_apartment_name'].":".mysql_real_escape_string($_POST['appmtName']));
		$p->add_field('invoice', mysql_real_escape_string($_POST['invoice']));
		$p->add_field('currency_code', $bsiCore->config['conf_currency_code']); 
		$p->add_field('amount', mysql_real_escape_string($_POST['amount']));		
		$p->submit_paypal_post(); // submit the fields to paypal
		//$p->dump_fields();      // for debugging, output a table of all the fields
		
      break;
      
	case 'success':      // Order was successful... 
		header("location:booking-confirm.php?success_code=1");
		break;
      
	case 'cancel':       // Order was canceled...		
		
		header("location:booking-failure.php?error_code=25");
		break;		
      
	case 'ipn':          // Paypal is calling page for IPN validation...     
		if ($p->validate_ipn()) {  
			if($p->ipn_data['payment_status'] == "Completed" || $p->ipn_data['payment_status'] == "Pending"){	
			   //*****************************************************************************************
			   	mysql_query("UPDATE bsi_bookings SET payment_amount='".$p->ipn_data['payment_gross']."', payment_success=true, payment_txnid='".$p->ipn_data['txn_id']."', paypal_email='".$p->ipn_data['payer_email']."' WHERE booking_id='".$p->ipn_data['invoice']."'");
	
				$invoiceROWS = mysql_fetch_assoc(mysql_query("SELECT client_name, client_email, invoice FROM bsi_invoice WHERE booking_id='".$p->ipn_data['invoice']."'"));
				mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email='".$invoiceROWS['client_email']."'");
				
				$invoiceHTML = $invoiceROWS['invoice'];	
					
				$invoiceHTML.= '<br><br><table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; bgcolor:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1"><tr><td align="left" colspan="2" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Payment Details</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Payment Option</td><td align="left" style="background:#ffffff">PayPal</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Payer E-Mail</td><td align="left" style="background:#ffffff">'.$p->ipn_data['payer_email'].'</td></tr><tr><td align="left" style="font-weight:bold; font-variant:small-caps; background:#ffffff">Transaction ID</td><td align="left" style="background:#ffffff">'.$p->ipn_data['txn_id'].'</td></tr></table>';
				
				mysql_query("UPDATE bsi_invoice SET invoice = '$invoiceHTML' WHERE booking_id='".$p->ipn_data['invoice']."'");
				$rowB = mysql_fetch_assoc(mysql_query("select appmt_id from bsi_bookings WHERE booking_id='".$p->ipn_data['invoice']."'"));
				$resultCheck = mysql_query("select user_id from bsi_appmt_user WHERE appmt_id=".$rowB['appmt_id']);
				
				$emailString = '';
				if(mysql_num_rows($resultCheck)){
					$rowU = mysql_fetch_assoc($resultCheck);
					$rowC = mysql_fetch_assoc(mysql_query("select * from bsi_clients where client_id=".$rowU['user_id']." and user_type=2"));
					$emailString .= $rowC['email'].",";
				}
				$emailString .= $bsiCore->config['conf_notification_email'];
				
				$emailBody  = "Dear ".$invoiceROWS['client_name'].",<br><br>";
				$emailBody .= html_entity_decode($emailContent['body'])."<br><br>";
				$emailBody .= $invoiceHTML;
				$emailBody .= "<br><br>Regards,<br>".$bsiCore->config['conf_apartment_name'].'<br>'.$bsiCore->config['conf_apartment_phone'];
				$emailBody .= "<br><br><font style=\"color:#F00; font-size:10px;\">[ You will need to carry a print out of attcahed voucher and present it to the hotel on arrival and check-in. This e-mail is the confirmation  for your booking. ]</font>";
				$flag = 1;
				$bsiMail->sendEMail($invoiceROWS['client_email'], $emailContent['subject'], $emailBody, $p->ipn_data['invoice'], $flag);
				
				/* Notify Email for Hotel about Booking */
				$notifyEmailSubject = "Booking no.".$p->ipn_data['invoice']." - Notification of Apartment Booking by ".$invoiceROWS['client_name'];
				
				$bsiMail->sendEMail($emailString, $notifyEmailSubject, $invoiceHTML);
			//*****************************************************************************************
			}elseif($p->ipn_data['payment_status'] == "Refunded"){
			   mysql_query("update bsi_bookings set payment_success='0' where invoice=".$p->ipn_data['invoice']);  
			}elseif($p->ipn_data['payment_status'] == "Reversed"){
			   mysql_query("update bsi_bookings set payment_success='0' where invoice=".$p->ipn_data['invoice']);		
			}
		}
	break;
  }   
?>