<?php 
include("access.php");
if(isset($_POST['submit'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->changePassword();
	header("location:change_password.php");
	exit;
}
include("header.php"); 
?>  
 <link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
      <div id="container-inside">
      <span style="font-size:16px; font-weight:bold">Change Password</span><span style="font-size:13px; color:#F00; padding-left:200px;"><?php if(isset($_SESSION['chngmsg'])){ echo $_SESSION['chngmsg']; }
unset($_SESSION['chngmsg']);?></span>
      <hr />
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="chngfrm">
          <table cellpadding="5" cellspacing="2" border="0">
            <tr>
              <td><strong>Old Password:</strong></td>
              <td valign="middle"><input type="password" name="old_pass" id="old_pass" class="required" style="width:150px;" /></td>
            </tr>
            <tr>
              <td><strong>New Password:</strong></td>
              <td valign="middle"><input type="password" name="new_pass" id="new_pass" class="required" style="width:150px;" /></td>
            </tr> 
            <tr>
              <td><strong>Confirm Password:</strong></td>
              <td valign="middle"><input type="password" name="con_pass" id="con_pass" class="required" style="width:150px;" /></td>
            </tr>     
            <tr>
              <td></td>
              <td><input type="submit" value="Change" name="submit" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>
            </tr>
          </table>
        </form>
      </div>
<script type="text/javascript">
	$().ready(function() {
		$("#chngfrm").validate({
			rules: {
				old_pass: {
					required: true,
				},
				new_pass: {
					required: true,
					minlength: 5				
				},
				con_pass: {
					required: true,
					minlength: 5,
					equalTo: "#new_pass"
				}
			},
			messages: {
				oldpass: {
					required: "<font color='red'>Please Provide old Password</font>",	
				},
				newpass: {
					required: "<font color='red'> Please Provide a Password</font>",	
				},
				conpass: {
					required: "<font color='red'> Please Provide a Password</font>",
				}
			}
		});
    });        
</script>      
<script src="js/jquery.validate.js" type="text/javascript"></script>
<?php include("footer.php"); ?> 
