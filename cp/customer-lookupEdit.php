<?php 
include ("access.php"); 
if(isset($_POST['act'])){
	include("../includes/db.conn.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->updateCustomerLookup();
	header("location:customer-lookup.php"); 
	exit;
}

include("header.php");
$update=base64_decode($_GET['update']);
if(isset($_GET['vid'])){
	$vid=mysql_real_escape_string($_GET['vid']);
	
}else{
	$vid=0;
}
if(isset($update)){
	$row   = $bsiAdminMain->getCustomerLookup($update);
	$title = $bsiAdminMain->getTitle($row['title']);
}else{
	header("location:customer-lookup.php");
}

 ?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<div id="container-inside"><?php if($vid == 1){?> <span style="font-size:16px; font-weight:bold">Property Owner Details</span>
<input type="submit" value="Back" style="background:#e5f9bb; cursor:pointer; cursor:hand; float:right" onClick="window.location.href='property_owner_list.php'"/><?php }else{?><span style="font-size:16px; font-weight:bold">Customer Details Edit</span>
<input type="submit" value="Back" style="background:#e5f9bb; cursor:pointer; cursor:hand; float:right" onClick="window.location.href='customer-lookup.php'"/><?php }?><br/><br/>
  <hr />
  <?php if($vid == 1){?>
  <table cellpadding="5" cellspacing="2" border="0">
      <tr>
        <td align="left" width="100px"><strong>Title:</strong></td>
        <td><?php echo $row['title'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>First Name:</strong></td>
        <td style="width:200px;"><?php echo $row['first_name'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>Last Name:</strong></td>
        <td style="width:200px;"><?php echo $row['surname'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>Street Address:</strong></td>
        <td style="width:250px;"><?php echo $row['street_addr'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>City:</strong></td>
        <td><?php echo $row['city'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>Province:</strong></td>
        <td><?php echo $row['province'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>Zip / Post code:</strong></td>
        <td><?php echo $row['zip'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>Country:</strong></td>
        <td><?php echo $row['country'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>Phone Number:</strong></td>
        <td><?php echo $row['phone'];?></td>
      </tr>
      <tr>
        <td align="left"><strong>Email Id:</strong></td>
        <td style="width:250px;"><?php echo $row['email'];?></td>
      </tr>
    </table>
  <?php }else{?>
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0">
      <tr>
        <td><strong>Title:</strong></td>
        <td><?php echo $title;?></td>
      </tr>
      <tr>
        <td align="left"><strong>First Name:</strong></td>
        <td><input type="text" class="required" value="<?php echo $row['first_name'];?>" style="width:200px;" name="fname" id="fname"/></td>
      </tr>
      <tr>
        <td align="left"><strong>Last Name:</strong></td>
        <td><input type="text" class="required" value="<?php echo $row['surname'];?>" style="width:200px;" name="sname" id="sname"/></td>
      </tr>
      <tr>
        <td align="left"><strong>Street Address:</strong></td>
        <td><input type="text" class="required" value="<?php echo $row['street_addr'];?>" style="width:250px;" name="sadd" id="sadd"/></td>
      </tr>
      <tr>
        <td align="left"><strong>City:</strong></td>
        <td><input type="text" class="required" value="<?php echo $row['city'];?>"  name="city" id="city"/></td>
      </tr>
      <tr>
        <td align="left"><strong>Province:</strong></td>
        <td><input type="text" class="required" value="<?php echo $row['province'];?>"  name="province" id="province"/></td>
      </tr>
      <tr>
        <td align="left"><strong>Zip / Post code:</strong></td>
        <td><input type="text" class="required" value="<?php echo $row['zip'];?>"  name="zip" id="zip"/></td>
      </tr>
      <tr>
        <td align="left"><strong>Country:</strong></td>
        <td><input type="text" class="required" value="<?php echo $row['country'];?>"  name="country" id="country"/></td>
      </tr>
      <tr>
        <td align="left"><strong>Phone Number:</strong></td>
        <td><input type="text" class="required" value="<?php echo $row['phone'];?>"  name="phone" id="phone"/></td>
      </tr>
      <tr>
        <td align="left"><strong>Email Id:</strong></td>
        <td><input type="text" value="<?php echo $row['email'];?>"  name="email" id="email" style="width:250px;" readonly="readonly"/>
          <input type="hidden" name="httpreffer" value="<?php echo $_SERVER['HTTP_REFERER'];?>" /></td>
      </tr>
      <input type="hidden" name="cid" value="<?php echo $row['client_id'];?>">
      <input type="hidden" name="act" value="1">
      <tr>
        <td  width="100px"></td>
        <td align="left"><input type="submit" value="Submit"  style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>
      </tr>
    </table>
  </form>
  <?php }?>
  
</div>
<script type="text/javascript">
	$().ready(function() {
		$("#form1").validate();
		
     });
         
</script> 
<script src="js/jquery.validate.js" type="text/javascript"></script>
<?php include("footer.php"); ?>
