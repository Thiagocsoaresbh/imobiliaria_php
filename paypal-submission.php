<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/mail.class.php");
$paymentGatewayDetails = $bsiCore->loadPaymentGateways();
$bsiMail = new bsiMail();
$emailContent = $bsiMail->loadEmailContent();
require_once('paypal.class.php');  // include the class file

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
		$p->add_field('amount', $bsiCore->config['conf_appmt_listing_price']);		
		$p->submit_paypal_post(); // submit the fields to paypal
		//$p->dump_fields();      // for debugging, output a table of all the fields
		
      break;
      
	case 'success':      // Order was successful... 
		header("location:property_submit_complete.php");
		break;
      
	case 'cancel':       // Order was canceled...		
		
		header("location:property_submit_complete.php");
		break;		
      
	case 'ipn':          // Paypal is calling page for IPN validation...     
		if ($p->validate_ipn()) {  
			if($p->ipn_data['payment_status'] == "Completed" || $p->ipn_data['payment_status'] == "Pending"){	
			   //*****************************************************************************************
			   	mysql_query("UPDATE bsi_apartment_master SET status=true WHERE appmt_id=".$p->ipn_data['invoice']);				
				$rowAp = mysql_fetch_assoc(mysql_query("select user_id from bsi_appmt_user WHERE appmt_id=".$p->ipn_data['invoice']));				
				$rowCl = mysql_fetch_assoc(mysql_query("select * from bsi_clients WHERE client_id=".$rowAp['user_id']." and user_type=2"));								
				$emailBody  = "Hi, ".$rowCl['first_name']." ".$rowCl['surname'].",<br>";
				$emailBody .= "Thank you for submit property.<br>Please login in your control panel to manage your property, booking list.";				
				$emailBody .= "<br><br>Regards,<br>".$bsiCore->config['conf_apartment_name'].'<br>'.$bsiCore->config['conf_apartment_phone'];				
				$bsiMail->sendEMail($rowCl['email'], "New Property Listing", $emailBody);				
				//*****************************************************************************************
			}elseif($p->ipn_data['payment_status'] == "Refunded"){
			   mysql_query("UPDATE bsi_apartment_master SET status=false WHERE appmt_id=".$p->ipn_data['invoice']); 
			}elseif($p->ipn_data['payment_status'] == "Reversed"){
			   mysql_query("UPDATE bsi_apartment_master SET status=false WHERE appmt_id=".$p->ipn_data['invoice']);		
			}
		}
	break;
  }   
?>