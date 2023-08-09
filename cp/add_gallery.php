<?php
include("access.php");
if(isset($_POST['addedit'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->addgallery();
	header("location:apartment-gallery.php");
	exit;
}
include("header.php"); 
?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<div id="container-inside"><span style="font-size:16px; font-weight:bold">Add Gallery</span>
  <hr />
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1" enctype="multipart/form-data">
    <table cellpadding="5" cellspacing="5" border="0">
     <tr>
        <td><strong>Apartment Name:</strong></td>
        <td valign="middle"><select name="apartmentid" id="apartmentid">
    <?php echo $bsiAdminMain->getApmtcombo();?>
  </select></td>
      </tr>
      <tr>
        <td width="120px"><strong>Image 1:</strong></td>
        <td valign="middle"><input type="file" name="image1" id="image1"  style="width:250px;" /></td>
      </tr>
      <tr>
        <td width="120px"><strong>Image 2:</strong></td>
        <td valign="middle"><input type="file" name="image2" id="image2"  style="width:250px;" /></td>
      </tr>
      <tr>
        <td width="120px"><strong>Image 3:</strong></td>
        <td valign="middle"><input type="file" name="image3" id="image3" style="width:250px;" /></td>
      </tr>
      <tr>
        <td width="120px"><strong>Image 4:</strong></td>
        <td valign="middle"><input type="file" name="image4" id="image4" style="width:250px;" /></td>
      </tr>
      <tr>
        <td width="120px"><strong>Image 5:</strong></td>
        <td valign="middle"><input type="file" name="image5" id="image5"  style="width:250px;" /></td>
      </tr>
      <tr>
        <td><input type="hidden" name="addedit" value="1"></td>
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
     });     
</script> 
<script src="js/jquery.validate.js" type="text/javascript"></script> 
<?php include("footer.php"); ?>
