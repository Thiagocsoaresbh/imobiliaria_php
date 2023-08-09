<?php
include("access.php");
if(isset($_GET['appmtid'])){
	include("includes/db.conn.php");
	include("includes/conf.class.php");
	$bsiCore->deletegallery();
	header("location:my-apartGallery.php");
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
	<link rel="stylesheet" type="text/css" href="cp/js/fancybox/jquery.fancybox-1.3.4.css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/gallery.css" media="screen">
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
	<script type="text/javascript">
	$(document).ready(function(){
		$('#apartmentid').change(function(){
			if($('#apartmentid').val() != ""){
				getImage();
			}else{
				$('#gallery').html('');
			}
		});
		if($('#apartmentid').val() > 0){
			getImage();
		}
		function getImage(){
			var querystr='actioncode=7&appmt='+$('#apartmentid').val();
			$.post("ajax-processor.php", querystr, function(data){						 
				if(data.errorcode == 0){
					$('#gallery').html(data.viewcontent);
				}else{
					$('#gallery').html(data.strmsg);
				}
			}, "json");	
		}
	});	

	function deleteImage(delimg){
		var appmtid = document.getElementById('apartmentid').value;
		window.location.href='my-apartGallery.php?appmtid='+appmtid+'&delimg='+delimg;	
	}
	</script>
	<script type="text/javascript" src="cp/js/fancybox/jquery.fancybox-1.3.4.js"></script>
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
              <div id="inputDiv"><h1 style="margin-left:10px; float:left; width:120px;">Gallery List</h1><input type="button" value="Add Photo" onClick="window.location.href='gallery-add.php'" style="float:right" class="btn2"/>
              <select name="apartmentid" id="apartmentid" class="home-select" style="float:right; padding-right:15px; width:auto;">
                <?php if(isset($_SESSION['apartmentid'])){echo $bsiCore->getApmtcombo($_SESSION['apartmentid']);}else{echo $bsiCore->getApmtcombo();}?>
              </select><br /><br /></div>
              <div class="indent gallery" id="gallery"></div></td>
          </tr>
        </table>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <?php include("footer.php"); ?>
</body>
</html>