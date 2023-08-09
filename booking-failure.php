<?php

session_start();

include("includes/db.conn.php");

include("includes/conf.class.php");

if(isset($_REQUEST["error_code"]))
$errorCode = $bsiCore->ClearInput($_REQUEST["error_code"]);
else
$errorCode=9;

$erroMessage = array(); 

define('BOOKING_FAILURE_ERROR_9', 'Direct access to this page is restricted.');

define('BOOKING_FAILURE_ERROR_13', 'Somebody else already acquire  the reservation lock on rooms specified by you. Reservation lock will be automatically released after few minutes on booking completion or failure by the other person. Please modify your search criteria and try again.');

define('BOOKING_FAILURE_ERROR_22', 'Undefined payment method selected. Please contact administrator.');

define('BOOKING_FAILURE_ERROR_25', 'Failed to send email notification. Please contact technical support.');

$erroMsg[9] = BOOKING_FAILURE_ERROR_9;

$erroMsg[13] = BOOKING_FAILURE_ERROR_13;

$erroMsg[22] = BOOKING_FAILURE_ERROR_22;

$erroMsg[25] = BOOKING_FAILURE_ERROR_25;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<title>
<?php echo $bsiCore->config['conf_apartment_name'];?>
</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/menu/superfish.js"></script>
<script type="text/javascript">
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	}); 
</script>
</head>

<body style="font-family: 'MavenProRegular';">
<?php include("header.php"); ?>
<div id="shadow">
 <div id="shadow-inside">
  <div id="shadow-left"></div>
  <div id="shadow-right"></div>
 </div>
</div>
<div id="container-div">
 <div id="container-inside"> 
 <h1 class="col2">Booking Failure</h1>
 <div class="booking1" style="padding-left:10px; padding-right:10px; width:95% !important; ">
 <?php echo $erroMsg[$errorCode];?>
 </div>
  </div>
 <div class="clr"></div>
</div>
<div class="clr"></div>
<?php include("footer.php"); ?>
</body>
</html>
