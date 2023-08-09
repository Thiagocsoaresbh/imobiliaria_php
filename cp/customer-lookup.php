<?php 
include ("access.php");
if(isset($_REQUEST['update'])){
header('location:customer-lookup.php'); 
exit;
}
include ("header.php");
$html = $bsiAdminMain->getCustomerHtml(1);
?>    

<div id="container-inside">
<span style="font-size:16px; font-weight:bold">Customer List</span>
<hr />
<table class="display datatable" border="0">
<thead>
<tr><th width="18%" nowrap="nowrap">Guset Name</th><th width="27%" nowrap="nowrap">Street Address</th><th width="15%" nowrap="nowrap">Phone Number</th><th width="25%" nowrap="nowrap">Email Id</th><th width="15%" nowrap="nowrap">&nbsp;</th></tr>
</thead>
<tbody id="getcustomerHtml">
<?php echo $html;?>
</tbody>
</table>
</div>
<script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/bsi_datatables.js"></script>
<link href="css/data.table.css" rel="stylesheet" type="text/css" />
<link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
<?php include("footer.php"); ?> 