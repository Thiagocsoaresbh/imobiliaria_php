<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
session_destroy();
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
 <h1 class="col2">Booking Complete</h1>
 <div class="booking1" style="padding-left:10px; padding-right:10px; width:95% !important; ">
 <h4>Thank You!</h4><br />Your Booking confirmed. Invoice sent in your email address.
 </div>
  </div>
 <div class="clr"></div>
</div>
<div class="clr"></div>
<?php include("footer.php"); ?>
</body>
</html>
