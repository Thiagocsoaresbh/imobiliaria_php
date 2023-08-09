<?php
include("access.php");
if(isset($_GET['delid'])){
	include("includes/db.conn.php");
	include("includes/conf.class.php");
	mysql_query("delete from bsi_priceplan WHERE pp_id=".mysql_real_escape_string(base64_decode($_GET['delid'])));
	header("location:my-apartpriceList.php");
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
	<script type="text/javascript" src="cp/js/jquery.min.js"></script>
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
            <td width="77%" valign="top">
              <div id="getFormtd"></div>
              <div style="margin-left:200px;" id="btnpro">
                <input type="button" class="btn1" value="Update" name="buttonprofile" id="buttonprofile"/>
              </div>
              <div style="margin-left:220px;" id="btnpss">
                <input type="button" class="btn1"  value="Update" id="buttonpassword"/>
              </div>
              <div id="inputDiv"><h1 style="margin-left:10px; float:left; width:120px;">Priceplan List</h1><input type="button" value="Add New Priceplan" onClick="window.location.href='add-edit-my-priceplan.php?id=<?php echo base64_encode(0); ?>'" style="float:right; width:150px;" class="btn2"/>
              <select name="apartmentid" id="apartmentid" class="home-select" style="float:right; padding-right:15px; width:auto;">
                <?php if(isset($_SESSION['apartmentid'])){echo $bsiCore->getApmtcombo($_SESSION['apartmentid']);}else{echo $bsiCore->getApmtcombo();}?>
              </select><br /><br /></div>
  			  <span id="priceplanhtml"></span></td>
          </tr>
        </table>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <script type="text/javascript">
	$(document).ready(function(){
		$('#apartmentid').change(function(){
			if($('#apartmentid').val() != ""){
				getPriceplan();
			}else{
				$('#priceplanhtml').html('');
			}
		});
		if($('#apartmentid').val() > 0){
			getPriceplan();
		}
		function getPriceplan(){
			var querystr='actioncode=9&appmt='+$('#apartmentid').val();
			$.post("ajax-processor.php", querystr, function(data){						 
				if(data.errorcode == 0){
					$('#priceplanhtml').html(data.viewcontent);
				}else{
					$('#priceplanhtml').html(data.strmsg);
				}
			}, "json");	
		}
	});	
	</script> 
    <?php include("footer.php"); ?>
</body>
</html>