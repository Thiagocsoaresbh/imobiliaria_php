<?php
include("access.php");
if(isset($_POST['addedit'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->addeditPriceplan($bsiCore->ClearInput($_POST['addedit']));
	header("location:priceplan.php");
	exit;
}
include("header.php"); 
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
	$text = '<select name="apartmentid" id="apartmentid" style="float:right; margin-right:15px;">
    						'.$bsiAdminMain->getApmtcombo().'</select>';
	$htmltext = '<tr>
        <td width="120px"><strong>Stay From:</strong></td>
        <td valign="middle"><input type="text" name="stay_from" id="stay_from" class="required digits" value="'.$row['stay_from'].'" style="width:50px;">&nbsp;'.$bsiCore->showBooktype().'
       </td>
      </tr>
      <tr>
        <td width="120px"><strong>Stay To:</strong></td>
        <td valign="middle"><input type="text" name="stay_to" id="stay_to" class="required digits" value="'.$row['stay_to'].'" style="width:50px;">&nbsp;'.$bsiCore->showBooktype().'
       </td>
      </tr>';	
}
?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<div id="container-inside"><span style="font-size:16px; font-weight:bold">Add/Edit Priceplan</span>
  <hr />
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="5" border="0">
      <tr>
        <td width="120px"><strong>Apartment Name:</strong></td>
        <td valign="middle"><?php echo $text;?> 
       </td>
      </tr>
      <tr>
        <td width="120px"><strong>Price/<?php echo $bsiCore->showBooktype();?>:</strong></td>
        <td valign="middle"><input type="text" name="price" id="price" class="required number" value="<?php echo $row['price'];?>" style="width:50px;">&nbsp;<?php echo $bsiCore->config['conf_currency_code'];?>
       </td>
      </tr>
      <?php echo $htmltext;?>
      <tr>
        <td width="120px"><strong>Deposit:</strong></td>
        <td valign="middle"><input type="text" name="deposit_percent" id="deposit_percent" class="required number" value="<?php echo $row['deposit_percent'];?>" style="width:50px;">&nbsp;%
       </td>
      </tr>
      <tr>
        <td><input type="hidden" name="addedit" value="<?php echo $id;?>"></td>
        <td><input type="submit" value="Submit" name="submitCapacity" id="submitCapacity" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>
      </tr>
    </table>
  </form>
</div>
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
