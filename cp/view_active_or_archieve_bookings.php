<?php 
include("access.php");

if(isset($_REQUEST['delete'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");	
	$bsiAdminMain->booking_cencel_delete(2);
	header("location:view_active_or_archieve_bookings.php?book_type=".base64_encode($_GET['booktype'])."&appmt_id=".base64_encode($_GET['appmtid']));
	exit;
}
if(isset($_REQUEST['cancel'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");	
	include("../includes/mail.class.php");	
	$bsiAdminMain->booking_cencel_delete(1); 
	header("location:view_active_or_archieve_bookings.php?book_type=".base64_encode($_GET['booktype'])."&appmt_id=".base64_encode($_GET['appmtid']));
	exit;
}

include("header.php"); 
if(isset($_POST['book_type']) && isset($_POST['apartmentid'])){
	$book_type = $bsiCore->ClearInput($_POST['book_type']);
	$appmt_id  = $bsiCore->ClearInput($_POST['apartmentid']);	
}
if(isset($_GET['book_type']) && isset($_GET['appmt_id'])){
	 $book_type = base64_decode($_GET['book_type']);
	 $appmt_id  = base64_decode($_GET['appmt_id']);
}
if(isset($_GET['booktype']) && isset($_GET['appmtid'])){
	 $book_type = $_GET['book_type'];
	 $appmt_id  = $_GET['appmt_id'];
}
$viewlist = $bsiAdminMain->getViewBookingList($book_type, $appmt_id);

$title_hr = array(1=>"Active Booking List", 2=>"Booking History List");

?>   
<script type="text/javascript">
function deleteBooking(bid){
		var answer = confirm ("Are you sure want to delete Booking?");
		if (answer)
			window.location="<?php echo $_SERVER['PHP_SELF'];?>?delete="+bid+"&booktype="+<?php echo $book_type;?>+"&appmtid="+<?php echo $appmt_id;?>;
	}
    function cancel(bid){
		var answer = confirm ("Are you sure want to cancel Booking?");
		if (answer)
			window.location="<?php echo $_SERVER['PHP_SELF'];?>?cancel="+bid+"&booktype="+<?php echo $book_type;?>+"&appmtid="+<?php echo $appmt_id;?>;
	}
	
		
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
</script> 
      <div id="container-inside">
      <span style="font-size:16px; font-weight:bold"><?php echo $title_hr[$book_type].":".$bsiCore->getApartmentname($appmt_id);?></span>
      <input type="submit" value="Back" style="background:#e5f9bb; cursor:pointer; cursor:hand; float:right" onClick="window.location.href='booking-list.php'"/>
       <br/>
        <hr/>
        <table class="display datatable" border="0">
         <?php echo $viewlist;?>
        </table>
      </div>
 <script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/bsi_datatables.js"></script>
<link href="css/data.table.css" rel="stylesheet" type="text/css" />
<link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
<?php include("footer.php"); ?> 