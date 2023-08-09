<?php 
include("access.php");
if(isset($_POST['submitContent'])){
	include("../includes/db.conn.php");
	$menu_title = mysql_real_escape_string($_POST['menu_title']);
	$menu_order = mysql_real_escape_string($_POST['menu_order']);
	$parent_id = mysql_real_escape_string($_POST['parent_id']);
	$status = mysql_real_escape_string($_POST['status']);
	$content_type = mysql_real_escape_string($_POST['content_type']);
	if($content_type==1){
		$url = mysql_real_escape_string($_POST['url']);
		$page_content = "";
	}elseif($content_type==2){
		$url = "";
		$page_content = mysql_real_escape_string($_POST['page_content']);
	}elseif($content_type==3){
		$url = mysql_real_escape_string($_POST['url']);
		$page_content = mysql_real_escape_string($_POST['page_content']);
	}
	
	$mid = mysql_real_escape_string($_POST['mid']);
	
	if($mid){
		$r=mysql_query("update bsi_site_contents set cont_title='$menu_title', contents='$page_content', status=$status, url='$url', parent_id=$parent_id, ord=$menu_order, content_type=$content_type where id=".$mid);
		if($r){
		header("Location: content-list.php");
		$_SESSION['content_update']="sdxx33";
		}else{
		header("Location: page_editor.php?mid=".$mid);
		}
	}else{
		$r=mysql_query("insert into bsi_site_contents(cont_title, contents, status, url, parent_id, ord, content_type) values('$menu_title', '$page_content', $status, '$url', $parent_id, $menu_order, $content_type)");
		if($r){
		header("Location: content-list.php");
		$_SESSION['content_insert']="sdxxd33";
		}else{
		header("Location: page_editor.php?mid=".$mid);
		}
	}
	
}
include("header.php"); 


if(mysql_real_escape_string($_GET['mid'])){
	$content_edit_row=mysql_fetch_assoc(mysql_query("select * from bsi_site_contents where id=".mysql_real_escape_string($_GET['mid'])));
	$menu_title1=$content_edit_row['cont_title'];
	$menu_order1=$content_edit_row['ord'];
	if($content_edit_row['status']){
		$status_active='selected="selected"';
		$status_desable='';
	}else{
		$status_active='';
		$status_desable='selected="selected"';
	}
	
	if($content_edit_row['content_type']==1){
		$content_type1='checked="checked" ';
		$content_type2='';
		$content_type3='';
	}elseif($content_edit_row['content_type']==2){
		$content_type1='';
		$content_type2='checked="checked" ';
		$content_type3='';
	}elseif($content_edit_row['content_type']==3){
		$content_type1='';
		$content_type2='';
		$content_type3='checked="checked" ';
	}
	$parent_menu1=$content_edit_row['parent_id'];
	$url1=$content_edit_row['url'];
	$page_content1=$content_edit_row['contents'];
}else{
	$menu_title1="";
	$menu_order1="";
	$status_active='';
	$status_desable='';
	$content_type1='';
	$content_type2='';
	$content_type3='';
	$parent_menu1="0";
	$url1="";
	$page_content1="";
}
//*********************parent menu********************
$parent_sql=mysql_query("select id, cont_title from bsi_site_contents where parent_id=0");
$parent_options='<option value="0">--- Top Level Menu ---</option>';
while($parent_row=mysql_fetch_assoc($parent_sql)){
	if($parent_row['id']==$parent_menu1)
	$parent_options.='<option value="'.$parent_row['id'].'" selected="selected">'.$parent_row['cont_title'].'</option>';
	else
	$parent_options.='<option value="'.$parent_row['id'].'">'.$parent_row['cont_title'].'</option>';
}

//*********************parent menu********************
?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<div id="container-inside">
<span style="font-size:16px; font-weight:bold">Menu &amp; Content Editor</span>
<hr />
 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1">
 <input type="hidden"  name="mid" value="<?php echo mysql_real_escape_string($_GET['mid']);?>" />
<table cellspacing="0" cellpadding="5" border="0" width="100%">
<tr><td><strong>Menu Title:</strong></td><td><input type="text" name="menu_title" id="menu_title" value="<?php echo $menu_title1;?>" style="width:200px;" class="required" /></td></tr>
<tr><td><strong>Menu Order:</strong></td><td><input type="text" name="menu_order" id="menu_order" value="<?php echo $menu_order1;?>" style="width:40px;" class="required number"/> [Example. 2]</td></tr>
<tr><td nowrap="nowrap"><strong>Parent Header:</strong></td><td><select name="parent_id" id="parent_id"><?php echo $parent_options;?></select></td></tr>
<tr><td><strong>Status:</strong></td><td><select name="status" id="status"><option value="1" <?php echo $status_active;?>>Enable</option><option value="0" <?php echo $status_desable;?>>Desable</option></select></td></tr>
<tr><td width="80"><strong>Content Type:</strong></td><td><input type="radio" name="content_type"  id="content_type" value="1" <?php echo $content_type1;?> /> URL  Only&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="content_type"  id="content_type" value="2" <?php echo $content_type2;?> /> Page Content Only &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="content_type"  id="content_type" value="3" <?php echo $content_type3;?> /> Both</td></tr>
<tr id="url_td"><td ><strong>URL:</strong></td><td><input type="text" name="url" id="url" value="<?php echo $url1;?>" style="width:200px;" /></td></tr>
<tr id="content_td"><td valign="top" >
<strong>Page Content:</strong></td><td>
<textarea name="page_content" class="ckeditor"><?php echo $page_content1;?></textarea>
</td></tr>
<tr id="button_td"><td></td><td><input type="submit" value="Submit" name="submitContent" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td></tr>
</table>
</form>
</div>

<script type="text/javascript">
	$().ready(function() {
		$("#form1").validate();
		var content_val =$('input:radio[name=content_type]:checked').val();
		if(content_val=='1'){
			$('#url_td').show()
			$('#content_td').hide()
			$('#button_td').show()
		}else if(content_val=='2'){
			$('#url_td').hide()
			$('#content_td').show()
			$('#button_td').show()
		}else if(content_val=='3'){
			$('#url_td').show()
			$('#content_td').show()
			$('#button_td').show()
		}else{
			$('#url_td').hide()
			$('#content_td').hide()
			$('#button_td').hide()
		}
		
		$("input:radio[name=content_type]").click(function() {
		 var select_value = $(this).val();

			if(select_value=='1'){
				$('#url_td').show()
				$('#content_td').hide()
				$('#button_td').show()
			}else if(select_value=='2'){
				$('#url_td').hide()
				$('#content_td').show()
				$('#button_td').show()
			}else if(select_value=='3'){
				$('#url_td').show()
				$('#content_td').show()
				$('#button_td').show()
			}
		});
     });     
</script> 
<script src="ckeditor/ckeditor_basic.js" type="text/javascript"></script> 
<script src="js/jquery.validate.js" type="text/javascript"></script>
<?php include("footer.php"); ?>
