<?php
include("access.php");
if(isset($_POST['addedit'])){
	include("includes/db.conn.php");
	include("includes/conf.class.php");
	$bsiCore->addgallery();
	header("location:my-apartGallery.php");
	exit;
}
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/admin.class.php");
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
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1" enctype="multipart/form-data">
              <table  cellpadding="5" cellspacing="5" border="0">
                <tr>
                  <td><strong>Apartment Name:</strong></td>
                  <td valign="middle"><select name="apartmentid" id="apartmentid" class="home-select" style="width:auto;">
                      <?php echo $bsiCore->getApmtcombo();?>
                    </select></td>
                </tr>
                <tr>
                  <td width="120px"><strong>Image 1:</strong></td>
                  <td valign="middle"><input type="file" name="image1" id="image1"  style="width:250px;" /></td>
                </tr>
                <tr>
                  <td width="120px"><strong>Image 2:</strong></td>
                  <td valign="middle"><input type="file" name="image2" id="image2" style="width:250px;" /></td>
                </tr>
                <tr>
                  <td width="120px"><strong>Image 3:</strong></td>
                  <td valign="middle"><input type="file" name="image3" id="image3"  style="width:250px;" /></td>
                </tr>
                <tr>
                  <td width="120px"><strong>Image 4:</strong></td>
                  <td valign="middle"><input type="file" name="image4" id="image4"  style="width:250px;" /></td>
                </tr>
                <tr>
                  <td width="120px"><strong>Image 5:</strong></td>
                  <td valign="middle"><input type="file" name="image5" id="image5"  style="width:250px;" /></td>
                </tr>
                <tr>
                  <td><input type="hidden" name="addedit" value="1"></td>
                  <td><input type="submit" value="Submit" name="submitCapacity" id="submitCapacity" class="btn1"/></td>
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
<?php include("footer.php"); ?>
</body>
</html>