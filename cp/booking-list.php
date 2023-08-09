<?php 
include("access.php");
include("header.php"); 
?>    
 <link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
 <script type="text/javascript">
 $(document).ready(function(){
	 $("#getbutt").hide();
	 $('#apartmentid').change(function() { 
		if($('#apartmentid').val() != ""){
			var querystr = 'actioncode=5&apartid='+$('#apartmentid').val(); 
			$.post("admin_ajax_processor.php", querystr, function(data){												 
				if(data.errorcode == 0){ 
					$('#booktypecombo').html(data.viewcombo)
					$("#getbutt").show();
				}
				
			}, "json");
		}
	 });	 
	 
});

function checkbooktype(){
	var v=document.getElementById("book_type").value;
	if(v != 0){
		return true;
	}else{
		alert("select booktype!");
		return false;
	}
	
}
	
</script>
      
        <div id="container-inside">
         <span style="font-size:16px; font-weight:bold">Booking List</span>
       <br/>
        <hr/>
           <form action="view_active_or_archieve_bookings.php" method="post" id="form1" onSubmit="return checkbooktype();">
          <table cellpadding="5" cellspacing="2" border="0">
          <tr>
            <td valign="middle"><strong>Select Apartment:</strong></td>
            <td><select name="apartmentid" id="apartmentid"><?php echo $bsiAdminMain->getApmtcombo();?></select></td>
            </tr>
          <tr id="booktypecombo">
            </select></td>
            </tr>
            <tr>
            <td></td>
            <td id="getbutt"> <input type="submit" value="Submit" name="submitviewdetails" id="submitviewdetails" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>
            </tr>
            </table>
            </form>
        </div>
        <script type="text/javascript">
		$(document).ready(function() {
			$("#form1").validate();		
     	});
         
</script>      
<script src="js/jquery.validate.js" type="text/javascript"></script>
<?php include("footer.php"); ?> 