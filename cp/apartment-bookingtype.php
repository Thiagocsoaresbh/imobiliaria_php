<?php
include("access.php");
if(isset($_POST['booktype'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$min_booking=mysql_real_escape_string($_POST['min_booking']);
	$max_booking=mysql_real_escape_string($_POST['max_booking']);
	if($min_booking < $max_booking){
	$rtmsg=$bsiAdminMain->updateBookingtype();
		if($_POST['booking_type_id'] != $_POST['checkexisting'])
		header("location:apartment-bookingtype.php?err=2");
		else
		header("location:apartment-bookingtype.php");
	}else{
		header("location:apartment-bookingtype.php?err=1");	
	}
}
include("header.php"); 
?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />

<div id="container-inside"><span style="font-size:16px; font-weight:bold">Apartment Booking Type</span>
  <hr />
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1" name="form1">
  <input type="hidden" name="checkexisting" value="<?php echo $bsiCore->config['conf_rental_type'];?>" />
    <table cellpadding="5" cellspacing="5" border="0" width="100%">
      <tr>
        <td width="150px"><strong>Select Booking Type:</strong></td>
        <td><select name="booking_type_id" id="booking_type_id">
            <?php echo $bsiAdminMain->getBookingtype();?>
          </select></td>
      </tr>
      <tr>
        <td><strong>Minimum Stay:</strong></td>
        <td><input type="text" class="required digits" name="min_booking" id="min_booking" value="<?php echo $bsiCore->config['conf_minimum_stay'];?>" style="width:50px;">&nbsp;&nbsp;<span id="add"></span></td>
      </tr>
      <tr>
        <td><strong>Maximum Stay:</strong></td>
        <td><input type="text" class="required digits" name="max_booking" id="max_booking" value="<?php echo $bsiCore->config['conf_maximum_stay'];?>" style="width:50px;">&nbsp;&nbsp;<span id="add1"></span></td>
      </tr>
      <tr id="daycomb">
        
      </tr>
      <tr>
        <td><input type="hidden" name="booktype" value="2"></td>
        <td><input type="submit" value="Submit" name="submitCapacity" id="submitCapacity" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><?php
		if(isset($_GET['err'])){
			if($bsiCore->ClearInput($_GET['err']) == 2){
				$ctypemsg="Your priceplan reset to 0.00 for all apartment. please go to price manager > priceplan for set new price";
			echo '<font  color="#9c0c0c">'.$ctypemsg.'</font>';
			unset($ctypemsg);
			}
			
		}
        ?></td>
      </tr>
      <tr>
        <td colspan="2"><?php
		if(isset($_GET['err'])){
			if($bsiCore->ClearInput($_GET['err']) == 1){
				$ctypemsg1="Minimum Stay can not greater than Maximum Stay";
			echo '<font color="#FF0000">'.$ctypemsg1.'</font>';
			unset($ctypemsg);
			unset($_GET['error2']);
			}
			
		}
        ?></td>
      </tr>
    </table>
  </form>
</div>
<script src="js/jquery.validate.js" type="text/javascript"></script> 
<script type="text/javascript">
$(document).ready(function(){
	$("#form1").validate();
	//if($('#booking_type_id').val()>0){
		if(<?php echo $bsiCore->config['conf_rental_type'];?> == 1){
					$('#add').html('Nights');
					$('#add1').html('Nights');
					$('#daycomb').html('');
				
		}else if(<?php echo $bsiCore->config['conf_rental_type'];?> == 2){
			 $('#add').html('Weeks');
			 $('#add1').html('Weeks');
			GetCheckinday(2);
		
		}else{
			 $('#add').html('Months');
			 $('#add1').html('Months');
			 $('#daycomb').html('');
		
		}
	//}
	$('#booking_type_id').change(function(){
		if($('#booking_type_id').val() != ""){
				if($('#booking_type_id').val() == 1){
					$('#add').html('Nights');
					$('#add1').html('Nights');
					$('#daycomb').html('');
				
				}else if($('#booking_type_id').val() == 2){
				     $('#add').html('Weeks');
					 $('#add1').html('Weeks');
					GetCheckinday($('#booking_type_id').val());
					
				}else{
				     $('#add').html('Months');
					 $('#add1').html('Months');
					 $('#daycomb').html('');
					
				}
			
		}
		
	});
	
	function GetCheckinday($book_typeid){
		if($book_typeid == 2){
			var qrystr='actioncode=6&book_typeid='+$book_typeid;
			$.post("admin_ajax_processor.php",qrystr,function(data){
				  
				      if(data.errorcode == 0){
						  
						  $('#daycomb').html(data.strhtml);
						  
					  }
				
				
				
				},"json")
			
			
		}
		
	}
	
	
	
	
	
});	
</script> 


<?php include("footer.php"); ?>
