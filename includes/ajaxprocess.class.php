<?php
class AjaxProcessor
{
	public $errorMsg;
	public function sendErrorMsg(){		
		$this->errorMsg = "unknown error";	
		echo json_encode(array("errorcode"=>99,"strmsg"=>$this->errorMsg));
	}
	
	public function validateEmail(){
		global $bsiCore;
		$errorcode = 0;
		$result = mysql_query("select * from bsi_clients where email='".$bsiCore->ClearInput($_POST['email'])."' and user_type=2");
		if(mysql_num_rows($result)){
			$errorcode = 1;
			echo json_encode(array("errorcode"=>$errorcode));
		}else{
			echo json_encode(array("errorcode"=>$errorcode));
		}
	}
	
	public function validateLogin(){ 
		global $bsiCore;
		$errorcode = 0;
		$result = mysql_query("select * from bsi_clients where email='".$bsiCore->ClearInput($_POST['emailid'])."' and password='".md5($bsiCore->ClearInput($_POST['passid']))."' and user_type=2");
		if(mysql_num_rows($result)){
			$row = mysql_fetch_assoc($result);
			$_SESSION['apmt_clientid']  = $row['client_id'];
			$_SESSION['apmt_firstname'] = $row['first_name'];
			$_SESSION['apmt_surname']   = $row['surname'];
			$_SESSION['apmt_email']     = $row['email'];
			$_SESSION['apmt_password']  = $row['password'];
			$_SESSION['apmt_usertype']  = $row['user_type'];
			echo json_encode(array("errorcode"=>$errorcode));
		}else{
			$errorcode = 1;
			echo json_encode(array("errorcode"=>$errorcode));
		}
	}
	
	public function getselectedForm(){
		global $bsiCore;
		$errorcode = 0;	
		$form = mysql_real_escape_string($_POST['form']);
		if($form == 1){
			$row = mysql_fetch_assoc(mysql_query("select * from bsi_clients where client_id=".$_SESSION['apmt_clientid']));	
		}
		switch($form){
			case 1: $html = '<div id="mngProfile" style="margin-left:50px;">
			<h1>Update your profile</h1><br><br>
                <table cellpadding="5" cellspacing="0" border="0" style="font-family: \'MavenProRegular\';">
                <tr>
                    <td align="left"><strong>Email Id:</strong></td>
                    <td><input type="text" class="home-input required email" name="email" id="email" value="'.$row['email'].'" readonly="readonly" style="width:250px;" />
                    <span id="msg_email"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>First Name:</strong></td>
                    <td><input type="text" class="home-input required"  style="width:250px;" name="first_name" value="'.$row['first_name'].'" id="username"/>
                    <span id="msg_username"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>Last Name:</strong></td>
                    <td><input type="text" class="home-input required" style="width:250px;" name="surname" value="'.$row['surname'].'" id="sname"/>
					<span id="msg_sname"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>Street Address:</strong></td>
                    <td><input type="text" class="home-input required" style="width:250px;" name="street_addr" value="'.$row['street_addr'].'" id="sadd"/>
					<span id="msg_sadd"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>Street Address:</strong></td>
                    <td><input type="text" class="home-input" style="width:250px;" name="street_addr2" value="'.$row['street_addr2'].'" id="sadd2"/>
					<span id="msg_sadd2"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>City:</strong></td>
                    <td><input type="text" class="home-input required"  style="width:250px;"  name="city" value="'.$row['city'].'" id="city"/>
					<span id="msg_city"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>Province:</strong></td>
                    <td><input type="text" class="home-input required"  style="width:250px;"  name="province" value="'.$row['province'].'" id="province"/>
					<span id="msg_province"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>Zip / Post code:</strong></td>
                    <td><input type="text" class="home-input"  style="width:250px;"  name="zip" value="'.$row['zip'].'" id="zip"/>
					<span id="msg_zip"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>Country:</strong></td>
                    <td><input type="text" class="home-input"  style="width:250px;" value="'.$row['country'].'"  name="country" id="country"/></td>
					<span id="msg_country"></span>
                  </tr>
                <tr>
                    <td align="left"><strong>Phone Number:</strong></td>
                    <td><input type="text" class="home-input"  style="width:250px;" value="'.$row['phone'].'" name="phone" id="phone"/>
					<span id="msg_phone"></span></td>
                  </tr>
              </table>
              </div>';	
			break;
			  
		    case 2: $html = '<div id="mngProfile" style="margin-left:50px;">
			<h1>Change Your Password</h1><br><br>
                <table cellpadding="5" cellspacing="2" border="0" style="font-family: \'MavenProRegular\';">
                <tr>
                    <td align="left"><strong>Old Password:</strong></td>
                    <td><input type="password" class="home-input" name="oldpass" id="oldpass" style="width:150px;" />
                    <span id="msg_oldpass"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>New Password:</strong></td>
                    <td><input type="password" class="home-input" style="width:150px;" name="newpass" id="newpass"/>
                    <span id="msg_newpass"></span></td>
                  </tr>
                <tr>
                    <td align="left"><strong>Confirm Password:</strong></td>
                    <td><input type="password" class="home-input" style="width:150px;" name="conpass" id="conpass"/>
					<span id="msg_conpass"></span><span id="msg_matched"></span></td>
                  </tr>
              </table>
              </div>';
		    break;
		}
		echo json_encode(array("errorcode"=>$errorcode, "getForm"=>$html));
	}
	
	public function updateProfile(){
		$errorcode = 0;
		$result = mysql_query("UPDATE `bsi_clients` SET `first_name` = '".mysql_real_escape_string($_POST['username'])."', `surname` = '".mysql_real_escape_string($_POST['sname'])."', `street_addr` = '".mysql_real_escape_string($_POST['sadd'])."', `street_addr2` = '".mysql_real_escape_string($_POST['sadd2'])."', `city` = '".mysql_real_escape_string($_POST['city'])."', `province` = '".mysql_real_escape_string($_POST['province'])."', `zip` = '".mysql_real_escape_string($_POST['zip'])."', `country` = '".mysql_real_escape_string($_POST['country'])."', `phone` = '".mysql_real_escape_string($_POST['phone'])."' WHERE `client_id` = ".$_SESSION['apmt_clientid']);	
		if($result){
			echo json_encode(array("errorcode"=>$errorcode, "msg"=>"Your Profile is Successfuly Updated."));
		}else{
			$errorcode = 1;
			echo json_encode(array("errorcode"=>$errorcode, "msg"=>"Profile Not Updated."));
		}
	}
	
	public function updatePassword(){
		$errorcode = 0;
		$result = mysql_query("select * from `bsi_clients` WHERE `client_id` = ".$_SESSION['apmt_clientid']." and `password`='".md5(mysql_real_escape_string($_POST['oldpass']))."'");
		if(mysql_num_rows($result)){
			$result = mysql_query("UPDATE `bsi_clients` SET `password` = '".md5(mysql_real_escape_string($_POST['newpass']))."' WHERE `client_id` = ".$_SESSION['apmt_clientid']);
			$_SESSION['apmt_password'] = md5(mysql_real_escape_string($_POST['newpass']));
			echo json_encode(array("errorcode"=>$errorcode, "msg"=>"Password successfully updated."));
		}else{
			$errorcode = 1;
			echo json_encode(array("errorcode"=>$errorcode, "msg"=>"Password do not matched."));
		}
	}
	
	public function getbsiGallery(){
		global $bsiCore;
		$errorcode = 0;
		$strmsg = "";
		$getArray = array();
		$appmt = $bsiCore->ClearInput($_POST['appmt']);
		$result = mysql_query("select * from bsi_appmt_gallery where appmt_id=".$appmt);
		if(mysql_num_rows($result)){
			$html = '<ul>';
			while($row = mysql_fetch_assoc($result)){
				$html .= '<li><a rel="collection" href="gallery/ApartImage/'.$row['img_path'].'"><img src="gallery/ApartImage/thumb_'.$row['img_path'].'" width="150px" height="130px"/></a>
        <button onclick="javascript:deleteImage(\''.$row['img_path'].'\');" class="button"><span>Delete</span></button>
      </li>';
			}
    		$html .= '</ul> 
					<script type="text/javascript">	
						$(".gallery ul li a").fancybox({
							\'overlayColor\':\'#000\' 		
						});
						$("a img.fancy").fancybox();
					</script>';
			echo json_encode(array("errorcode"=>$errorcode, "viewcontent"=>$html));
		}else{
			$errorcode = 1;
			$strmsg = "Sorry! no  result found ";
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));
		}
	}
	
	public function forgotPassword(){
		global $bsiCore;
		$errorcode = 0;
		$emailid = mysql_real_escape_string($_POST['emailid']);
		$result  = mysql_query("select * from bsi_clients where email='".$emailid."' and user_type=2");
		if(mysql_num_rows($result)){
			$bsiMail       = new bsimail();
			$temp_password = substr(uniqid(), -6, 6);
			$row           = mysql_fetch_assoc($result);
			mysql_query("update bsi_clients set password='".md5($temp_password)."' where client_id=".$row['client_id']);
			$subject =  "Apartment Login Panel : Your password has been reset";
			$body    =  "Hi,<br><br>" .
						"Your new login information is: <br><br>" .
						"Username: " . $row['email'] . "<br>" .
						"Password: " . $temp_password . "<br><br>" ."Thanking You.";
			$bsiMail->sendEMail($emailid, $subject, $body);	
			echo json_encode(array("errorcode"=>$errorcode));
		}else{
			$errorcode = 1;
			echo json_encode(array("errorcode"=>$errorcode));
		}
	}
	
	public function getPriceplan(){
		global $bsiCore;
		$errorcode = 0;
		$strmsg = "";
		$getArray = array();
		$appmt = $bsiCore->ClearInput($_POST['appmt']);
		$result = mysql_query("select * from bsi_priceplan where appmt_id=".$appmt);
		if(mysql_num_rows($result)){
			$html = '<hr /><table class="display datatable" border="0" style="font-family:Verdana, Geneva, sans-serif; font-size:10px;">
						<thead>
						  <tr>
							<th><strong>Plan type</strong></th>
							<th><strong>Stay Duration('.$bsiCore->showBooktype().')</strong></th>
							<th><strong>Price/'.$bsiCore->showBooktype().'</strong></th>
							<th><strong>Deposit(%)</strong></th>
							<th><strong>&nbsp;</strong></th>
						  </tr>
						</thead>
						<tbody>';
			while($row = mysql_fetch_assoc($result)){
				if($row['default_pp'] == 1){
					$html .= '<tr>
								<td>Default</td>
								<td>N/A - N/A</td>
								<td>'.$bsiCore->config['conf_currency_symbol'].$row['price'].'</td>
								<td>'.number_format($row['deposit_percent']).'%</td>
								<td align="right"><a href="add-edit-my-priceplan.php?id='.base64_encode($row['pp_id']).'">Edit</a></td>
							  </tr>';
				}else{
					$html .= '<tr>
								<td nowrap="nowrap">Special Plan</td>
								<td>'.$row['stay_from']." to ".$row['stay_to'].'</td>
								<td>'.$bsiCore->config['conf_currency_symbol'].$row['price'].'</td>
								<td>'.number_format($row['deposit_percent']).'%</td>
								<td align="right"><a href="add-edit-my-priceplan.php?id='.base64_encode($row['pp_id']).'">Edit</a> | <a href="my-apartpriceList.php?delid='.base64_encode($row['pp_id']).'">Delete</a></td>
							  </tr>';
				}
			}
    		$html .= '	</tbody>
  					</table>
					<script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
					<script type="text/javascript" src="js/bsi_datatables.js"></script>
					<link href="css/data.table.css" rel="stylesheet" type="text/css" />
					<link href="css/jqueryui.css" rel="stylesheet" type="text/css" />';
			echo json_encode(array("errorcode"=>$errorcode, "viewcontent"=>$html));
		}else{
			$errorcode = 1;
			$strmsg = "Sorry! no  result found ";
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));
		}
	}
}
?>