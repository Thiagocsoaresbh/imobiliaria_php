<?php
include("access.php");
if(isset($_GET['delid'])){
	include("includes/db.conn.php");
	include("includes/conf.class.php");
	include("includes/admin.class.php");
	$bsiAdminMain->deleteApmt(base64_decode($bsiCore->ClearInput($_GET['delid'])), 1);
	header("location:my-apartList.php");
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
	<body>
    <?php include("header.php"); ?>
    <?php include("left.php"); ?>
            <td width="77%" valign="top"><div id="getFormtd"></div>
              <div style="margin-left:200px;" id="btnpro">
                <input type="button" class="btn1" value="Update" name="buttonprofile" id="buttonprofile"/>
              </div>
              <div style="margin-left:220px;" id="btnpss">
                <input type="button" class="btn1"  value="Update" id="buttonpassword"/>
              </div>
              <?php
			$html = '<div  id="apartlistDiv">
			<h1 style="margin-left:10px;">Property List</h1><br /><br />
			<table class="display datatable" border="0"  style="font-family:Verdana, Geneva, sans-serif; font-size:11px;">
                				<thead>
									<tr>
										<th width="45%" align="left">Apartment Name</th>
										<th width="11%" align="left">Phone</th>
										<th width="15%" align="left">Status</th>
										<th>&nbsp;</th> 
									 </tr>
              					</thead>
								<tbody>';
					$result = mysql_query("select * from bsi_appmt_user where user_id=".$_SESSION['apmt_clientid']);		
					if(mysql_num_rows($result)){
						while($row = mysql_fetch_assoc($result)){
							$result2 = mysql_query("SELECT bam.appmt_id, bat.appartment_type, bam.appmt_name, bam.phone, bam.status FROM `bsi_apartment_master` bam, `bsi_apartment_type` bat WHERE bam.appmt_type_id=bat.id and bam.`appmt_id`=".$row['appmt_id']);
							if(mysql_num_rows($result2)){
							$rowAp = mysql_fetch_assoc($result2);
							if($rowAp['status']){
								$text = '<font style="color:#0C0; font-weight:bold;">Active</font>';	
							}else{
								$text = '<font style="color:#F00; font-weight:bold;">In-Active</font>';
							}
							 $html .= '<tr>
										<td align="left">'.$rowAp['appmt_name'].'</td>
										<td align="left">'.$rowAp['phone'].'</td>
										<td align="left">'.$text.'</td>
										<td align="right"><a href="javascript:;" onclick="javascript:editAppartment(\''.base64_encode($rowAp['appmt_id']).'\')">Edit</a>&nbsp;|&nbsp;<a href="javascript:;" onclick="javascript:deleteAppmt(\''.base64_encode($rowAp['appmt_id']).'\')">Delete</a></td>
									</tr>';
							}
						}
					}
				    echo $html .= '</tbody>
						</table>
					    </div>';
			?></td>
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