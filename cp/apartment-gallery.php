<?php 
include("access.php");
if(isset($_GET['appmtid'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->deletegallery();
	header("location:apartment-gallery.php");
	exit;
}
include("header.php"); 
?>
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/gallery.css" media="screen">
<div id="container-inside"> <span style="font-size:16px; font-weight:bold">Galeria de fotos de imóveis</span>
  <input type="button" value="Adicionar" onclick="window.location.href='add_gallery.php'" style="background: #EFEFEF; float:right"/>
  <select name="apartmentid" id="apartmentid" style="float:right; margin-right:15px;">
    <?php if(isset($_SESSION['appmtgal'])){echo $bsiAdminMain->getApmtcombo($_SESSION['appmtgal']);}else{echo $bsiAdminMain->getApmtcombo();}?>
  </select>
  <hr />
  <div class="indent gallery" id="gallery"></div>
</div>
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
		var querystr='actioncode=2&appmt='+$('#apartmentid').val();
		$.post("admin_ajax_processor.php", querystr, function(data){						 
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
	window.location.href='apartment-gallery.php?appmtid='+appmtid+'&delimg='+delimg;	
}
</script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.js"></script> 
<?php include("footer.php"); ?>
