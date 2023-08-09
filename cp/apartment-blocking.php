<?php 
include("access.php");
if((isset($_GET['action'])) && ($_GET['action'] == "unblock")){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	$booking_id  = $bsiCore->ClearInput($_GET['bid']);
	mysql_query("delete from bsi_bookings where booking_id=".$booking_id."");
	header("location:apartment-blocking.php");
	exit;
}
include("header.php"); 
?>

<div id="container-inside">
<span style="font-size:16px; font-weight:bold">Apartment Block List</span><span style="margin-left:150px; color:#F00;"><?php if(isset($_SESSION['errormsg'])){ echo "<strong>".$_SESSION['errormsg']."</strong>"; }
unset($_SESSION['errormsg']); ?></span>
    <input type="button" value="Click Here To Block Apartment" onClick="window.location.href='block_apartment.php'" style="background: #EFEFEF; float:right;"/>
<hr />
  <table class="display datatable" border="0">
    <thead>
      <tr>
      	<th>Block Name</th>
        <th>Apartment Name</th>
        <th>Date Range</th>       
        <th>Action</th>
      </tr>
    </thead>
    <?php echo $bsiAdminMain->getApartmentBlockDetails();?>
  </table>
</div>
<script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
<script type="text/javascript" src="js/bsi_datatables.js"></script>
<link href="css/data.table.css" rel="stylesheet" type="text/css" />
<link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
<?php include("footer.php"); ?>
