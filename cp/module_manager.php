<?php 
include("access.php");
if(isset($_POST['act_sbmt'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->modulevaluepost();
	header("location:module_manager.php");
	exit;
}
include("header.php");
$bookingengineturnoff= ($bsiCore->config['conf_booking_turn_off'] == 1) ? 'checked="checked"': '';
$submissiononoff= ($bsiCore->config['conf_property_submission'] == 1 ) ? 'checked="checked"': '';
$conf_appmt_auto_approve = ($bsiCore->config['conf_appmt_auto_approve'] == 1 ) ? 'checked="checked"': '';
?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<div id="container-inside"> <span style="font-size:16px; font-weight:bold">Module Setting</span>
  <hr />
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="0" border="0" >
      <tr>
        <td nowrap="nowrap"><strong>Booking Engine Turn off:</strong></td>
        <td colspan="4"><input type="checkbox" name="conf_booking_turn_off" id="conf_booking_turn_off" <?php echo $bookingengineturnoff; ?>     value="1" /></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><strong>Property Submission:</strong></td>
        <td><input type="checkbox" name="conf_property_submission" id="conf_property_submission"  <?php echo $submissiononoff; ?>  value="1" /></td>
        <td id="listType" nowrap="nowrap"> Listing Auto Aprove?
          <input type="checkbox" id="autoapprove" name="autoapprove" value="1" <?php echo $conf_appmt_auto_approve; ?> /></td>
        <td id="listTypefee" nowrap="nowrap"> Listing Type
          <select name="listingtype" id="listingtype">
            <option value="0" <?php if($bsiCore->config['conf_apppmt_listing_type'] == 0){ echo 'selected="selected"'; }?>>Free</option>
            <option value="1" <?php if($bsiCore->config['conf_apppmt_listing_type'] == 1){ echo 'selected="selected"'; }?>>Paid</option>
          </select></td>
        <td id="tdprice" nowrap="nowrap">Listing Fee
          <input type="text" name="price" id="price" style="width:50px" value="<?php echo $bsiCore->config['conf_appmt_listing_price'];?>" />
          <?php echo $bsiCore->config['conf_currency_code'];?></td>
      </tr>
      <tr>
        <td><input type="hidden" name="act_sbmt" value="1" /></td>
        <td colspan="4"><input type="submit" value="Submit" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>
      </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		if($('#conf_property_submission').attr('checked')) {
    		$("#listType").show();
			$("#listTypefee").show();
		}else{
			$("#listType").hide();
    		$("#listTypefee").hide();
		}
		
		$('#conf_property_submission').click(function(){
			if($('#conf_property_submission').attr('checked')) {
				$("#listType").show();
				$("#listTypefee").show();
			}else{
				$("#listType").hide();
				$("#listTypefee").hide();
			}
		});
		$("#tdprice").hide();
		$('#listingtype').change(function(){
			if($('#listingtype').val() > 0){
				$("#tdprice").show();
			}else{
				$("#tdprice").hide();
			}
		});
		if($('#listingtype').val() > 0){
			$("#tdprice").show();
		}
	});
</script>
<?php include("footer.php"); ?>
