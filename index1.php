<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
if(isset($_REQUEST['cid']))
$body_content=mysql_fetch_assoc(mysql_query("select * from bsi_site_contents where id=".$bsiCore->ClearInput($_REQUEST['cid'])));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<title>
<?=$bsiCore->config['conf_apartment_name']?>
</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="js/slider/coin-slider.css" rel="stylesheet" type="text/css" />
<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/slider/coin-slider.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#coin-slider').coinslider({ hoverPause: false });
	});
</script>
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
 <h1 class="col2"><?=$body_content['cont_title']?></h1>
 <?=$body_content['contents']?>
  </div>
 <div class="clr"></div>
</div>
<div class="clr"></div>
<?php include("footer.php"); ?>
</body>
</html>
