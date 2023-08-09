<?php 
include("access.php");
if(isset($_GET['del'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->deleteApmt($bsiCore->ClearInput($_GET['del']));
	header("location:apartment-list.php");
	exit; 
}

if(isset($_GET['delid'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$delid=$bsiCore->ClearInput($_GET['delid']);
	$bsiAdminMain->deleteApmt($delid);
	if(isset($_GET['client_id'])){
		header("location:apartment-list.php?client_id=".base64_encode($_GET['client_id']));
	}else{
		header("location:apartment-list.php");
	}
	
	exit; 
}

if(isset($_GET['client_id'])){
	$client_id=base64_decode($_GET['client_id']);
	
}else{
	$client_id=0;
	
}
include("header.php"); 
?>
<script type="text/javascript">
function deleteType(delid){
	var ans=confirm("Do you want to delete this Apartment. Rememeber corressponding of this Apartment will also deleted");
	if(ans){
		window.location='<?php echo $_SERVER['PHP_SELF'];?>?del='+delid;
		return true;
	}else{
		return false;
	}
}

function delType(delid){
	//alert(delid);
	var app;
	var cli;
	var n = delid.split(" ");
	cli = n[0];
	app = n[1];
	//alert(app);
	var ans=confirm("Quer mesmo deletar este tipo de imóvel? Itens relacionados a este tipo de apartamento também serão apagadas");
	if(ans){
		window.location='<?php echo $_SERVER['PHP_SELF'];?>?delid='+app+'&client_id='+cli;
		return true;
	}else{
		return false;
	}
}
</script>
<div id="container-inside">
<span style="font-size:16px; font-weight:bold">Imóveis cadastrados</span>
	<input type="button" value="Adicionar novo imóvel" onclick="window.location.href='add_edit_apartment.php?id=<?php echo base64_encode(0);?>'" style="background: #EFEFEF; float:right"/>
 <hr />
 <?php if($client_id != 0){?>
 <table class="display datatable" border="0">
    <thead>
      <tr>
        <th width="5%">Id#</th>
        <th width="27%">Nome do imóvel</th>
        <th width="28%">Endereço</th>
        <th width="20%">Telefone</th>
        <th width="5%">Status</th>
        <th width="15%">&nbsp;</th>
      </tr>
    </thead>
    <?php echo $bsiAdminMain->getApartmentListHtml($client_id);?>
  </table>
 <?php }else{?>
 <table class="display datatable" border="0">
    <thead>
      <tr>
        <th width="5%">Id#</th>
        <th width="27%">Nome do imóvel</th>
        <th width="28%">Endereço</th>
        <th width="20%">Telefone</th>
        <th width="5%">Status</th>
        <th width="15%">&nbsp;</th>
      </tr>
    </thead>
    <?php echo $bsiAdminMain->getApartmentListHtml($client_id);?>
  </table>
 <?php }?>
  
</div>
<script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
<script type="text/javascript" src="js/bsi_datatables.js"></script>
<link href="css/data.table.css" rel="stylesheet" type="text/css" />
<link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
<?php include("footer.php"); ?>
