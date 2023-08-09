<?php
include("access.php");
include("includes/db.conn.php");
include("includes/conf.class.php");
if(isset($_GET['client'])){
    $client    = mysql_real_escape_string(base64_decode($_GET['client']));
	$html      = $bsiCore->fetchClientBookingDetails($client);
}else{
	header("location:customer-lookup.php");die;
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
	<title>
    <?php echo $bsiCore->config['conf_apartment_name'];?>
    </title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/accordionmenu.css" rel="stylesheet" type="text/css" />
	<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/menu/superfish.js"></script>
	<script type="text/javascript">
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	}); 
</script>
	<script type="text/javascript">
		$(document).ready(function() {
			// Store variables			
			var accordion_head = $('.accordion > li > a'),
				accordion_body = $('.accordion li > .sub-menu');
			// Open the first tab on load
			//accordion_head.first().addClass('active').next().slideDown('normal');
			// Click function
			accordion_head.on('click', function(event) {
				// Disable header links				
				event.preventDefault();
				// Show and hide the tabs on click
				if ($(this).attr('class') != 'active'){
					accordion_body.slideUp('normal');
					$(this).next().stop(true,true).slideToggle('normal');
					accordion_head.removeClass('active');
					$(this).addClass('active');
				}
			});
		});
	</script>
    <script type="text/javascript">
function myPopup2(booking_id){
	var width = 730;
	var height = 650;
	var left = (screen.width - width)/2;
	var top = (screen.height - height)/2;
	var url='print_invoice.php?bid='+booking_id;
	var params = 'width='+width+', height='+height;
	params += ', top='+top+', left='+left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=no';
	params += ', scrollbars=yes';
	params += ', status=no';
	params += ', toolbar=no';
	newwin=window.open(url,'Chat', params);
	if (window.focus) {newwin.focus()}
	return false;
}
</script>
	</head>

	<body >
    <?php include("header.php"); ?>
    <div id="shadow">
      <div id="shadow-inside">
        <div id="shadow-left"></div>
        <div id="shadow-right"></div>
      </div>
    </div>
    <div id="container-div">
      <div id="container-inside" style="padding-top:0px !important;">
        <table cellpadding="5" cellspacing="0" border="0" width="100%">
          <tr>
            <td height="600px" width="23%" valign="top"><div id="wrapper-250">
                <ul class="accordion">
                <li id="one" class="files"> <a href="#one">My Profile</a>
                    <ul class="sub-menu">
                    <li><a href="javascript:;" id="mngprofile"><em>01</em>Manage Profile</a></li>
                    <li><a href="javascript:;" id="chngpss"><em>02</em>Change Password</a></li>
                  </ul>
                  </li>
                <li id="two" class="cloud"> <a href="#two">My Listing<span><?php echo $bsiCore->getuserlistingSql($_SESSION['apmt_clientid']); ?></span></a>
                    <ul class="sub-menu">
                    <li><a href="my-apartList.php" id="mnglisting"><em>01</em>Manage Listing<span><?php echo $bsiCore->getuserlistingSql($_SESSION['apmt_clientid']); ?></span></a></li>
                    <li><a href="my-apartGallery.php" id="mnggallery"><em>02</em>Listing Gallery<span><?php echo $bsiCore->getuserlistingSql($_SESSION['apmt_clientid']); ?></span></a></li>
                  </ul>
                  </li>
                <li id="three" class="cloud"> <a href="#three">My Bookings<span>58</span></a>
                    <ul class="sub-menu">
                    <li><a href="my-activeList.php" id="mngactbook"><em>01</em>Active Booking<span>12</span></a></li>
                    <li><a href="my-archieveList.php" id="mnghisbook"><em>02</em>Booking History<span>19</span></a></li>
                    <li><a href="my-clientList.php" id="mngcuslist"><em>03</em>Customer List<span>19</span></a></li>
                  </ul>
                  </li>
                <li id="four" class="sign"> <a href="#four">Sign Out</a>
                    <ul class="sub-menu">
                    <li><a href="#"><em>01</em>Log Out</a></li>
                  </ul>
                  </li>
              </ul>
              </div></td>
            <td width="77%" valign="top"><div style="margin-left:20px;"><table class="display datatable" border="0">
                                    <thead>
                                      <tr>
                                        <th width="9%" nowrap>Booking Id</th>
                                        <th width="8%" nowrap="nowrap">Check In Date</th>
                                        <th width="10%" nowrap>Check Out Date</th>
                                        <th width="10%" nowrap>Amount</th>
                                        <th width="8%" nowrap="nowrap">Status</th>
                                        <th width="25%">&nbsp;</th>
                                      </tr>
                                    </thead>
                                    <?php echo $html[0]; ?>
                                  </table></div></td>
          </tr>
        </table>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
    <script type="text/javascript" src="js/bsi_datatables.js"></script>
    <link href="css/data.table.css" rel="stylesheet" type="text/css" />
    <link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
    <?php include("footer.php"); ?>
</body>
</html>