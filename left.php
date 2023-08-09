<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript">
ddaccordion.init({
	headerclass: "submenuheader", //Shared CSS class name of headers group
	contentclass: "submenu", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["suffix", "<img src='images/plus.gif' class='statusicon' />", "<img src='images/minus.gif' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})
</script>
<style type="text/css">
.glossymenu {
	margin: 0px 0;
	padding: 0;
	width: 170px; /*width of menu*/
	border: 1px solid #9A9A9A;
	border-bottom-width: 0;
}
.glossymenu a.menuitem {
	background: black url(images/glossyback.gif) repeat-x bottom left;
	font: bold 14px "Lucida Grande", "Trebuchet MS", Verdana, Helvetica, sans-serif;
	color: white;
	display: block;
	position: relative; /*To help in the anchoring of the ".statusicon" icon image*/
	width: auto;
	padding: 4px 0;
	padding-left: 10px;
	text-decoration: none;
}
.glossymenu a.menuitem:visited, .glossymenu .menuitem:active {
	color: white;
}
.glossymenu a.menuitem .statusicon { /*CSS for icon image that gets dynamically added to headers*/
	position: absolute;
	top: 5px;
	right: 5px;
	border: none;
}
.glossymenu a.menuitem:hover {
	background-image: url(images/glossyback2.gif);
}
.glossymenu div.submenu { /*DIV that contains each sub menu*/
	background: white;
}
.glossymenu div.submenu ul { /*UL of each sub menu*/
	list-style-type: none;
	margin: 0;
	padding: 0;
}
.glossymenu div.submenu ul li {
	border-bottom: 1px solid blue;
}
.glossymenu div.submenu ul li a {
	display: block;
	font: normal 13px "Lucida Grande", "Trebuchet MS", Verdana, Helvetica, sans-serif;
	color: black;
	text-decoration: none;
	padding: 2px 0;
	padding-left: 10px;
}
.glossymenu div.submenu ul li a:hover {
	background: #DFDCCB;
	colorz: white;
}
</style>
<script type="text/javascript"> 
		function deleteAppmt(appmt){
			var del = confirm("Do You want to delete this Apartment. Remmember corresponding of its booking will also be deleted.");
			if(del){
				window.location='<?php echo $_SERVER['PHP_SELF']; ?>?delid='+appmt;	
			}
		}
		function editAppartment(appmt){
			window.location='my-apartmentEdit.php?pageEdit='+appmt;
		}
		
		$(document).ready(function() {
			$('#btnpro').hide();
			$('#btnpss').hide();
			$('#apartlistDiv').show();
			$('#gallery').show();
			$('#inputDiv').show();
			$('#activelistDiv').show();
			$('#mngprofile').click(function(){
				$('#btnpss').hide();
				$('#apartlistDiv').hide();
				$('#gallery').hide();
				$('#inputDiv').hide();
				$('#activelistDiv').hide();
				var querystr='actioncode=3&form='+1;
				$.post("ajax-processor.php", querystr, function(data){					 
					if(data.errorcode == 0){
						$('#getFormtd').html(data.getForm);
						$('#btnpro').show();
					}
				}, "json");
			});
			$('#chngpss').click(function(){
				$('#btnpro').hide();
				$('#btnpss').show();
				$('#apartlistDiv').hide();
				$('#gallery').hide();
				$('#inputDiv').hide();
				$('#activelistDiv').hide();
				var querystr='actioncode=3&form='+2;
				$.post("ajax-processor.php", querystr, function(data){					 
					if(data.errorcode == 0){
						$('#getFormtd').html(data.getForm);
					}
				}, "json");
			});
						
			$('#buttonprofile').click(function(){
				 var validation = validateuserFrm();
				 if(validation == true){
					var querystr='actioncode=4&email='+$('#email').val()+'&username='+$('#username').val()+'&sname='+$('#sname').val()+'&sadd='+$('#sadd').val()+'&sadd2='+$('#sadd2').val()+'&city='+$('#city').val()+'&province='+$('#province').val()+'&zip='+$('#zip').val()+'&country='+$('#country').val()+'&phone='+$('#phone').val();
					$.post("ajax-processor.php", querystr, function(data){					 
						if(data.errorcode == 0){
							alert(data.msg);
							$('#msg_username').html('');
							$('#msg_sname').html('');
							$('#msg_sadd').html('');
							$('#msg_city').html('');
							$('#msg_province').html('');
							$('#msg_zip').html('');
							$('#msg_country').html('');
							$('#msg_phone').html('');
						}else{
							alert(data.msg);
						}
					}, "json"); 
				 }else{
					 return validation; 
				 }
			});
			$('#buttonpassword').click(function(){
				var validation = validatepssFrm();
				if(validation == true){
					var querystr='actioncode=5&oldpass='+$('#oldpass').val()+'&newpass='+$('#newpass').val()+'&conpass='+$('#conpass').val();
					$.post("ajax-processor.php", querystr, function(data){					 
						if(data.errorcode == 0){
							alert(data.msg);
							$('#oldpass').val('');
							$('#newpass').val('');
							$('#conpass').val('');
							$('#msg_oldpass').html('');
							$('#msg_newpass').html('');
							$('#msg_conpass').html('');
							$('#msg_matched').html('');
						}else{
							alert(data.msg);
						}
					}, "json");
				}else{
					return validation;
				}
			});
			
			function validateuserFrm(){
				isValid = true;
				// Validate Firstname
				var username = $('#username').val();
				if(!username && username == ""){
					isValid = false;
					$('#msg_username').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your Firstname.</font>').show();
				} 
				
				// Validate Surname
				var sname = $('#sname').val();
				if(!sname && sname.length <= 0){
					isValid = false;
					$('#msg_sname').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your Surname.</font>').show();
				}
				
				// Validate Address
				var sadd = $('#sadd').val();
				if(!sadd && sadd.length <= 0){
					isValid = false;
					$('#msg_sadd').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your Address.</font>').show();
				}
				
				// Validate City
				var city = $('#city').val();
				if(!city && city.length <= 0){
					isValid = false;
					$('#msg_city').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your City.</font>').show();
				}
				
				// Validate Province
				var province = $('#province').val();
				if(!province && province.length <= 0){
					isValid = false;
					$('#msg_province').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your Province.</font>').show();
				}
				// Validate Postal
				var zip = $('#zip').val();
				if(!zip && zip.length <= 0){
					isValid = false;
					$('#msg_zip').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your Postal Code.</font>').show();
				}
				
				// Validate Country
				var country = $('#country').val();
				if(!country && country.length <= 0){
					isValid = false;
					$('#msg_country').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your Country.</font>').show();
				}
				
				// Validate Phone
				var phone = $('#phone').val();
				if(!phone && phone.length <= 0){
					isValid = false;
					$('#msg_phone').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your Phone No.</font>').show();
				}	
				return isValid;
			}
			
			function validatepssFrm(){
				isValid = true;
				// Validate old Password
				var oldpass = $('#oldpass').val();
				if(!oldpass && oldpass == ""){
					isValid = false;
					$('#msg_oldpass').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your Old Password.</font>').show();
				} 
				
				// Validate new Password
				var newpass = $('#newpass').val();
				if(!newpass && newpass.length <= 0){
					isValid = false;
					$('#msg_newpass').html('<font style="color:#F00;">&nbsp;&nbsp;Please Enter Your New Password.</font>').show();
				}
				
				// Validate con Password
				var conpass = $('#conpass').val();
				if(!conpass && conpass.length <= 0){
					isValid = false;
					$('#msg_conpass').html('<font style="color:#F00;">&nbsp;&nbsp;Please re-enter your Password.</font>').show();
				}
				// Validate password match
				if(newpass != conpass){
					isValid = false;
					$('#msg_matched').html('<font style="color:#F00;">&nbsp;&nbsp;Password do not matched.</font>').show();
				}								
				return isValid;
			}
		});
	</script>

<div id="shadow">
  <div id="shadow-inside">
    <div id="shadow-left"></div>
    <div id="shadow-right"></div>
  </div>
</div>
<div id="container-div">
<div id="container-inside" style="padding-top:0px !important;">
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
  <td height="600px" width="20%" valign="top"><div class="glossymenu"> <a class="menuitem submenuheader" href="#">My Profile</a>
      <div class="submenu">
        <ul>
          <li><a href="javascript:;" id="mngprofile">Manage Profile</a></li>
          <li><a href="javascript:;" id="chngpss">Change Password</a></li>
        </ul>
      </div>
      <a class="menuitem submenuheader" href="#" >My Listing</a>
      <div class="submenu">
        <ul>
          <li><a href="my-apartList.php" id="mnglisting">Manage Listing</a></li>
          <li><a href="my-apartGallery.php" id="mnggallery">Listing Gallery</a></li>
          <li><a href="my-apartpriceList.php" id="mnglisting">Manage Priceplan</a></li>
        </ul>
      </div>
      <a class="menuitem submenuheader" href="#">My Bookings</a>
      <div class="submenu">
        <ul>
          <li><a href="my-activeList.php" id="mngactbook">Active Booking</a></li>
          <li><a href="my-archieveList.php" id="mnghisbook">Booking History</a></li>
          <li><a href="my-clientList.php" id="mngcuslist">Customer List</a></li>
          <li><a href="my-apartmentblockList.php" id="mngcuslist">Apartment Block List</a></li>
        </ul>
      </div>
      <a class="menuitem" href="logout.php">Sign Out</a>
    </div></td>
