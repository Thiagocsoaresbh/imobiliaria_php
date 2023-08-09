<?php
include("access.php");
if(isset($_GET['delid'])){
	include("includes/db.conn.php");
	include("includes/conf.class.php");
	include("includes/admin.class.php");
	$bsiAdminMain->deleteApmt(base64_decode($bsiCore->ClearInput($_GET['delid'])));
	mysql_query("delete from bsi_appmt_user where appmt_id=".base64_decode($bsiCore->ClearInput($_GET['delid'])));
	header("location:my-account.php");
	exit; 
}
include("includes/db.conn.php");
include("includes/conf.class.php");
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
	<body >
    <?php include("header.php"); ?>
    <?php include("left.php"); ?> 
            <td width="77%" valign="top" >
            <div id="getFormtd">
            	<h1 style="margin-left:10px;">Hi <?php echo $_SESSION['apmt_firstname']; ?>!!! WelCome to your Control Panel.</h1>
                <br /><br />
                <h2  style="margin-left:10px;">Last 10 Booking List</h2><br /><br />
                <?php
			$html = '<div style="margin-left:10px;" id="activelistDiv"><table class="display datatable" style="font-family:Verdana, Geneva, sans-serif; font-size:10px; " >
                				<thead>
									<tr>
										<th width="15%">Booking Id</th>
										<th width="35%">Apartment Name</th>
										<th width="15%">Check in</th>
										<th width="15%">Check out</th>
										<th>&nbsp;</th> 
									 </tr>
              					</thead>
								<tbody>'; 										
							$result2 = mysql_query("SELECT booking_id, bb.appmt_id, DATE_FORMAT(checkin_date, '".$bsiCore->userDateFormat."') as checkin, DATE_FORMAT(checkout_date, '".$bsiCore->userDateFormat."') as checkout from bsi_appmt_user bau, bsi_bookings bb where bb.appmt_id=bau.appmt_id and bau.user_id=7 and payment_success=true order by checkin_date desc limit 10");						
							if(mysql_num_rows($result2)){								
								while($rowAp = mysql_fetch_assoc($result2)){
									$rowApmt = mysql_fetch_assoc(mysql_query("select appmt_name from bsi_apartment_master where appmt_id=".$rowAp['appmt_id']));
									$html .= '<tr>
												<td align="center">'.$rowAp['booking_id'].'</td>
												<td align="center">'.$rowApmt['appmt_name'].'</td>
												<td align="center">'.$rowAp['checkin'].'</td>
												<td align="center">'.$rowAp['checkout'].'</td>
												<td align="right"><a href="viewdetails.php?booking_id='.$rowAp['booking_id'].'">View Details</a></td>
											</tr>';	
								}
							}
				    echo $html .= '</tbody>
						</table>
					    </div>';
			?>
            </div>
              <div style="margin-left:195px;" id="btnpro">
                <input type="button" class="btn1" value="Update" name="buttonprofile" id="buttonprofile"/>
              </div>
              <div style="margin-left:220px;" id="btnpss">
                <input type="button" class="btn1"  value="Update" id="buttonpassword"/>
              </div></td>
          </tr>
        </table>
      </div>
      <div class="clr"></div>
    </div>
    <script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
    <script type="text/javascript" src="js/bsi_datatables.js"></script>
    <link href="css/data.table.css" rel="stylesheet" type="text/css" />
    <link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
    <div class="clr"></div>
    <?php include("footer.php"); ?>
</body>
</html>
