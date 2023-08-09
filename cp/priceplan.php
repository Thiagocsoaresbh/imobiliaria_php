<?php
include("access.php");
if(isset($_GET['delid'])){
	include("../includes/db.conn.php");
	mysql_query("delete from bsi_priceplan WHERE pp_id=".mysql_real_escape_string(base64_decode($_GET['delid'])));
	header("location:priceplan.php");
	exit;
}
include("header.php");  
?>

<div id="container-inside"><span style="font-size:16px; font-weight:bold;">Priceplan List</span><?php if(isset($_SESSION['msg_err'])){echo '<span style="font-size:12px; font-weight:bold; width:100%; color:#F00; margin-left:300px;">'.$_SESSION['msg_err'].'</span>';
unset($_SESSION['msg_err']);}?>
  <input type="button" value="Add New Priceplan" onClick="window.location.href='add_edit_priceplan.php?id=<?php echo base64_encode(0); ?>'" style="background: #EFEFEF; float:right"/>
  <select name="apartmentid" id="apartmentid" style="float:right; margin-right:15px;">
    <?php if(isset($_SESSION['appmtpp'])){echo $bsiAdminMain->getApmtcombo($_SESSION['appmtpp']);}else{echo $bsiAdminMain->getApmtcombo();}?>
  </select>
  <hr />
  <span id="priceplanhtml"></span>
</div>
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
		var querystr='actioncode=3&appmt='+$('#apartmentid').val();
		$.post("admin_ajax_processor.php", querystr, function(data){						 
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
