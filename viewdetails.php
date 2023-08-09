<?php
include("access.php");
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/admin.class.php");
if(!isset($_GET['booking_id']) || $_GET['booking_id'] == ""){
	header('location:my-account.php');
	exit;
}else{
	$bookingid = $bsiCore->ClearInput($_GET['booking_id']);
	$viewdetailsquery = mysql_query("select DATE_FORMAT(bb.checkin_date, '".$bsiCore->userDateFormat."') AS start_date, checkout_date, DATE_FORMAT(bb.checkout_date, '".$bsiCore->userDateFormat."') AS end_date, DATE_FORMAT(bb.booking_time, '".$bsiCore->userDateFormat."') AS booking_time, bc.title, bc.first_name, bc.surname, bc.street_addr, bc.phone, bc.zip,bc.city,bc.province,bc.email, bc.country, bb.total_cost, bb.payment_type, bb.is_deleted from bsi_bookings as bb, bsi_clients as bc where  bb.client_id=bc.client_id and booking_id=".$bookingid."");
	$rowviewdetails = mysql_fetch_assoc($viewdetailsquery);	 
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
	</head>
	<body style="font-family: 'MavenProRegular';">
    <?php include("header.php"); ?>
    <?php include("left.php"); ?>
            <td width="77%" valign="top"><div id="getFormtd"></div>
              <div style="margin-left:155px;" id="btnpro">
                <input type="button" class="btn1" value="Update" name="buttonprofile" id="buttonprofile"/>
              </div>
              <div style="margin-left:175px;" id="btnpss">
                <input type="button" class="btn1"  value="Update" id="buttonpassword"/>
              </div>
              <div style="margin-left:20px;" id="activelistDiv">
                <table cellpadding="4" cellspacing="0" width="100%" >
                  <tr>
                    <td align="left" colspan="2"><b>CUSTOMER DETAILS</b></td>
                  </tr>
                  <tr>
                    <td align="left">Name</td>
                    <td align="left"><?php echo $rowviewdetails['title'];?>
                      <?php echo $rowviewdetails['first_name'];?>
                      <?php echo $rowviewdetails['surname'];?></td>
                  </tr>
                  <tr>
                    <td align="left">Phone</td>
                    <td align="left"><?php echo $rowviewdetails['phone'];?></td>
                  </tr>
                  <tr>
                    <td align="left">Address</td>
                    <td align="left"><?php echo $rowviewdetails['street_addr'];?></td>
                  </tr>
                  <tr>
                    <td align="left">City</td>
                    <td align="left"><?php echo $rowviewdetails['city'];?></td>
                  </tr>
                  <tr>
                    <td align="left">State</td>
                    <td align="left"><?php echo $rowviewdetails['province'];?></td>
                  </tr>
                  <tr>
                    <td align="left">Country</td>
                    <td align="left"><?php echo $rowviewdetails['country'];?></td>
                  </tr>
                  <tr>
                    <td align="left">Zip</td>
                    <td align="left"><?php echo $rowviewdetails['zip'];?></td>
                  </tr>
                  <tr>
                    <td align="left">Phone</td>
                    <td align="left"><?php echo $rowviewdetails['phone'];?></td>
                  </tr>
                  <tr>
                    <td align="left">Email</td>
                    <td align="left"><?php echo $rowviewdetails['email'];?></td>
                  </tr>
                </table>
                <br />
                <table>
                  <tr>
                    <td align="left" colspan="2"><?php echo $bsiAdminMain->paymentDetails($rowviewdetails['payment_type'], $bookingid);?></td>
                  </tr>
                </table>
                <br />
                <table cellpadding="4" cellspacing="1">
                  <tr>
                    <td align="left" colspan="2"><b>BOOKING STATUS</b></td>
                  </tr>
                  <tr>
                    <?php
					$status='';
					$curdate=date('Y-m-d');
					$rowviewdetails['is_deleted'];
					if($rowviewdetails['is_deleted'] == 0 && $rowviewdetails['checkout_date']<$curdate ){
						$status='Departed';
						echo '<td align="left" style="background:#ffffff;color:blue;"><strong>'.$status.'</strong></td>';	
					}else if($rowviewdetails['is_deleted']==0 && $rowviewdetails['checkout_date']>$curdate){
						$status='Active';
						echo '<td align="left" style="background:#ffffff;color:green;"><strong>'.$status.'</strong></td>';	
					}else if($rowviewdetails['is_deleted']==1){
						$status='Cancelled';
						echo '<td align="left" style="background:#ffffff;color:red;"><strong>'.$status.'</strong></td>';	
					}
				  ?>
                  </tr>
                </table>
              </div></td>
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