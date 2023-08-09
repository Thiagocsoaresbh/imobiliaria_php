<?php
include("access.php");
include("includes/db.conn.php");
include("includes/conf.class.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
	<title>
    <?php echo $bsiCore->config['conf_apartment_name'];?>
    </title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/accordionmenu.css" rel="stylesheet" type="text/css" />
	<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/menu/superfish.js"></script>
	<script type="text/javascript">
		jQuery(function(){
			jQuery('ul.sf-menu').superfish();
		}); 
	</script>
	</head>

	<body >
    <?php include("header.php"); ?>
    <?php include("left.php"); ?>
            <td width="77%" valign="top">
            <div id="getFormtd"></div>
              <div style="margin-left:200px;" id="btnpro">
                <input type="button" class="btn1" value="Update" name="buttonprofile" id="buttonprofile"/>
              </div>
              <div style="margin-left:220px;" id="btnpss">
                <input type="button" class="btn1"  value="Update" id="buttonpassword"/>
              </div>
            <div style="margin-left:0px;" id="activelistDiv">
            <h1 style="margin-left:10px;">Customer List</h1><br /><br />
            	<table class="display datatable" border="0"  style="font-family:Verdana, Geneva, sans-serif; font-size:11px;">
                <thead>
                <tr>
                    <th width="30%">Guset Name</th>
                    <th width="10%">Phone</th>
                    <th width="40%">Email Id</th>
                    <th width="20%">&nbsp;</th>
                 </tr>
              </thead>
                <tbody id="getcustomerHtml">
                <?php echo $bsiCore->getCustomerHtml($_SESSION['apmt_clientid']); ?> 
              </tbody>
              </table></div></td>
          </tr>
        </table>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
    <script type="text/javascript" src="js/bsi_datatables.js"></script>
    <link href="css/data.table.css" rel="stylesheet" type="text/css" />
    <link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
    <?php include("footer.php"); ?>
</body>
</html>