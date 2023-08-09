<?php
include("access.php");
if(isset($_POST['addedit'])){
	include("includes/db.conn.php");
	include("includes/conf.class.php");
	include("includes/admin.class.php");
	$bsiAdminMain->addeditPriceplan($bsiCore->ClearInput($_POST['addedit']));
	header("location:my-apartpriceList.php");
	exit;
}
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/admin.class.php");
$id = $bsiCore->ClearInput(base64_decode($_GET['id']));
if($id){
	$row = mysql_fetch_assoc(mysql_query("select * from bsi_priceplan where pp_id=".$id));
	$_SESSION['appmtpp'] = $row['appmt_id'];
	$text = '<strong>'.$bsiCore->getApartmentname($row['appmt_id']).'</strong><input type="hidden" name="apartmentid" id="apartmentid" value="'.$row['appmt_id'].'">';
	if($row['default_pp'] == 1){
		$htmltext = '<input type="hidden" name="stay_from" value="0"><input type="hidden" name="stay_to" value="0">';
	}else{
		$htmltext = '<tr>
        <td width="120px"><strong>Stay From:</strong></td>
        <td valign="middle"><input type="text" name="stay_from" id="stay_from" class="required digits" value="'.$row['stay_from'].'" style="width:50px;">
       </td>&nbsp;'.$bsiCore->showBooktype().'
      </tr>
      <tr>
        <td width="120px"><strong>Stay To:</strong></td>
        <td valign="middle"><input type="text" name="stay_to" id="stay_to" class="required digits" value="'.$row['stay_to'].'" style="width:50px;">&nbsp;'.$bsiCore->showBooktype().'
       </td>
      </tr>';
	}
}else{
	$row = NULL;
	$text = '<select name="apartmentid" id="apartmentid" class="home-select" style="float:right; width:auto; margin-right:15px;">
    						'.$bsiCore->getApmtcombo().'</select>';
	$htmltext = '<tr>
        <td width="120px"><strong>Stay From:</strong></td>
        <td valign="middle"><table><tr><td><input type="text" name="stay_from" id="stay_from" class="required digits home-input" value="'.$row['stay_from'].'" style="width:50px;"></td><td>&nbsp;'.$bsiCore->showBooktype().'
       </td></tr></table></td>
      </tr>
      <tr>
        <td width="120px"><strong>Stay To:</strong></td>
        <td valign="middle"><table><tr><td><input type="text" name="stay_to" id="stay_to" class="required digits home-input" value="'.$row['stay_to'].'" style="width:50px;"></td><td>&nbsp;'.$bsiCore->showBooktype().'
       </td></tr></table></td>
      </tr>';	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<title><?php echo $bsiCore->config['conf_apartment_name'];?></title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/accordionmenu.css" rel="stylesheet" type="text/css" />
<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/menu/superfish.js"></script>
<script src="cp/ckeditor/ckeditor_basic.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	}); 
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#form1").validate();
		$('#submitCapacity').attr('disabled', 'disabled');	
		$('#apartmentid').change(function(){
			if($('#apartmentid').val() != ""){
				$('#submitCapacity').removeAttr('disabled');
			}else{
				$('#submitCapacity').attr('disabled', 'disabled');
			}
		});
     });     
</script>
<script src="cp/js/jquery.validate.js" type="text/javascript"></script>
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
          <div id="activelistDiv">
          <h1>Add / Edit Price Plan</h1><br /><br /><br />
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1">
              <table cellpadding="5" cellspacing="5" border="0" style="font-family:Verdana, Geneva, sans-serif; font-size:11px;">
                <tr>
                  <td width="120px"><strong>Apartment Name:</strong></td>
                  <td valign="middle"><?php echo $text;?></td>
                </tr>
                <tr>
                  <td width="120px"><strong>Price/<?php echo $bsiCore->showBooktype();?>:</strong></td>
                  <td valign="middle"><table><tr><td><input type="text" name="price" id="price" class="required number home-input" value="<?php echo $row['price'];?>" style="width:50px;">
                    </td><td>&nbsp;<?php echo $bsiCore->config['conf_currency_code'];?></td></tr></table></td>
                </tr>
                <?php echo $htmltext;?>
                <tr>
                  <td width="120px"><strong>Deposit:</strong></td>
                  <td valign="middle"><table><tr><td><input type="text" name="deposit_percent" id="deposit_percent" class="required number home-input" value="<?php echo $row['deposit_percent'];?>" style="width:50px;">
                    </td><td>&nbsp;%</td></tr></table></td>
                </tr>
                <tr>
                  <td><input type="hidden" name="addedit" value="<?php echo $id;?>"></td>
                  <td><input type="submit" value="Submit" name="submitCapacity" id="submitCapacity" style="cursor:pointer; cursor:hand;" class="btn1"/></td>
                </tr>
              </table>
            </form>
          </div></td>
      </tr>
    </table>
  </div>
  <div class="clr"></div>
</div>
<div class="clr"></div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#form1").validate();
		$('#submitCapacity').attr('disabled', 'disabled');
		$('#apartmentid').change(function(){
			if($('#apartmentid').val() != ""){
				$('#submitCapacity').removeAttr('disabled');
			}else{
				$('#submitCapacity').attr('disabled', 'disabled');
			}
		});
		if($('#apartmentid').val() > 0){
			$('#submitCapacity').removeAttr('disabled');
		}
		$('#price').keyup(function(){
			if($('#price').val() != ""){
				var querystr='actioncode=4&amt='+$('#price').val();
				$.post("admin_ajax_processor.php", querystr, function(data){						 
					if(data.errorcode == 0){
						$('#pricepermonth').html(data.viewcontent);
					}
				}, "json");	
			}else{
				$('#pricepermonth').html('');
			}
		});
     });     
</script> 
<script src="js/jquery.validate.js" type="text/javascript"></script> 
<?php include("footer.php"); ?>
</body>
</html>