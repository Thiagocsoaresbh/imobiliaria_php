<?php 
include("access.php");
if(isset($_GET['pid'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	$row = mysql_fetch_assoc(mysql_query("select * from bsi_site_contents where id=".mysql_real_escape_string($_GET['pid'])));
	if($row['parent_id']){
		mysql_query("delete from bsi_site_contents where parent_id=".mysql_real_escape_string($_GET['pid']));
	}
	mysql_query("delete from bsi_site_contents where id=".mysql_real_escape_string($_GET['pid']));
	header("location:content-list.php");
	exit;
}
include("header.php"); 
?>

<link type="text/css" href="css/accordion.css" rel="stylesheet" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script> 
 $(function() {
	// Content Box Accordion Config		
		$( ".content_accordion" ).accordion({
			collapsible: true,
			active:false,
			header: 'h3.bar', // this is the element that will be clicked to activate the accordion 
			autoHeight:false,
			event: 'mousedown',
			icons:false,
			animated: true
		});
		
	// Sortable Accordion Items Config			
		$( ".content_accordion" ).sortable({
			handle:'a.handle',
			axis: 'y', // the items can only be sorted along the y axis
			revert:true,
			tolerance:'pointer',
			forcePlaceholderSize:true
		});
});
</script>
<div id="container-inside"> <span style="font-size:16px; font-weight:bold">Menu &amp; Gerenciamento de Conteúdo</span>
 <button  style="float:right; padding-right:0px; margin-left:15px;" onclick="javascript:window.location.href='page_editor.php?mid=0'"><span>Adicionar Menu</span></button>
 <hr />
 <ul class="block content_accordion no_header">
 <?php
 $menu1_sql=mysql_query("select * from bsi_site_contents where parent_id=0 order by ord");
 while($menu1_row=mysql_fetch_assoc($menu1_sql)){
	$menu2_sql=mysql_query("select * from bsi_site_contents where parent_id=".$menu1_row['id']." order by ord");	 
	 
	 if(mysql_num_rows($menu2_sql)){
 ?>
  <li>
   <h3 class="bar">+ <?php echo $menu1_row['cont_title'];?> <a href="<?php echo $_SERVER['PHP_SELF'];?>?pid=<?php echo $menu1_row['id'];?>" style="float:right"><strong>Delete</strong></a><a href="page_editor.php?mid=<?php echo $menu1_row['id'];?>" style="float:right; margin-right:3px;"><strong>Edit</strong>&nbsp;|&nbsp;</a></h3>
   <div class="content">
   <?php
   while($menu2_row=mysql_fetch_assoc($menu2_sql)){
   ?>
   <img src="images/arroww.png" border="0"  align="bottom" style="padding-top:10px;"/> <?php echo $menu2_row['cont_title'];?>  <a href="<?php echo $_SERVER['PHP_SELF'];?>?pid=<?php echo $menu2_row['id'];?>" style="float:right; padding-top:10px;"><strong>Delete</strong></a><a href="page_editor.php?mid=<?php echo $menu2_row['id'];?>" style="float:right; margin-right:3px; padding-top:10px;"><strong>Edit</strong>&nbsp;|&nbsp;</a>
   <?php } ?>
 
   </div>
  </li>
   <?php }else{ ?>
  <li><h3 class="bar">&nbsp;&nbsp;&nbsp;<?php echo $menu1_row['cont_title'];?> <a href="<?php echo $_SERVER['PHP_SELF'];?>?pid=<?php echo $menu1_row['id'];?>" style="float:right"><strong>Delete</strong></a><a href="page_editor.php?mid=<?php echo $menu1_row['id'];?>" style="float:right; margin-right:3px;"><strong>Edit</strong>&nbsp;|&nbsp;</a></h3></li>
 <?php } } ?>
 </ul><br />
 <div align="center" style="color:#030;"><strong>
 <?php
 	if(isset($_SESSION['content_insert'])){
		echo " Page content inserted!";
		unset($_SESSION['content_insert']);
	}elseif(isset($_SESSION['content_update'])){
		echo " Page content updated!";
		unset($_SESSION['content_update']);
	}
 ?>

 
 </strong></div>
</div>

<?php include("footer.php"); ?>
