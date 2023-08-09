<?php
include ("access.php");
if(isset($_REQUEST['delete'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->booking_cencel_delete(2);
	$client = base64_encode($_REQUEST['client']);
	header("location:customer-booking.php?client=".$client);
	exit;
}

if(isset($_REQUEST['cancel'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");	
	include("../includes/admin.class.php");
	include("../includes/mail.class.php");	
	$bsiAdminMain->booking_cencel_delete(1); 
	$client = base64_encode($_REQUEST['client']);
	header("location:customer-booking.php?client=".$client);
	exit;
}

if(isset($_GET['client'])){
	include("header.php");
    $client    = base64_decode($_GET['client']);
	$delClient = $client;
	$html      = $bsiAdminMain->fetchClientBookingDetails($client);
}else{
	header("location:customer-lookup.php");die;
	exit;
}
?>
<script type="text/javascript">
function myPopup2(booking_id){
	var width = 730;
	var height = 650;
	var left = (screen.width - width)/2;
	var top = (screen.height - height)/2;
	var url='print_invoice.php?bid='+booking_id;
	var params = 'width='+width+', height='+height;
	params += ', top='+top+', left='+left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=no';
	params += ', scrollbars=yes';
	params += ', status=no';
	params += ', toolbar=no';
	newwin=window.open(url,'Chat', params);
	if (window.focus) {newwin.focus()}
	return false;
}

function cancel(bid){
	var answer = confirm ("Are you sure want to cancel Booking?");
	if (answer)
		window.location="<?php echo $_SERVER['PHP_SELF'];?>?cancel="+bid+"&client="+<?php echo $delClient;?>;
}

function booking_delete(delid){
	var answer = confirm ("Are you sure want to delete booking? Remember once booking deleted, it will be deleted forever from your database.")
	if (answer)
		window.location="<?php echo $_SERVER['PHP_SELF'];?>?delete="+delid+"&client="+<?php echo $delClient;?>;
	}
</script>

<div id="container-inside"> <span style="font-size:16px; font-weight:bold">Booking List of <?php echo $html[1];?></span>
<input type="submit" value="Back" style="background:#e5f9bb; cursor:pointer; cursor:hand; float:right" onClick="window.location.href='customer-lookup.php'"/>
  <hr />
  <table class="display datatable" border="0">
    <thead>
      <tr>
        <th width="9%" nowrap>Booking Id</th>
        <th width="18%" nowrap>Customer Name</th>
        <th width="8%" nowrap="nowrap">Check In Date</th>
        <th width="10%" nowrap>Check Out Date</th>
        <th width="10%" nowrap>Amount</th>
        <th width="9%" nowrap>Booking Date</th>
        <th width="8%" nowrap="nowrap">Status</th>
        <th width="25%">&nbsp;</th>
      </tr>
    </thead>
    <?php echo $html[0];?>
  </table>
</div>
<script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
<script type="text/javascript" src="js/bsi_datatables.js"></script>
<link href="css/data.table.css" rel="stylesheet" type="text/css" />
<link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
<?php include("footer.php"); ?>
