<?php
session_start();
if(isset($_POST['submit'])){
	include("includes/db.conn.php");
	include("includes/conf.class.php");
	include("includes/mail.class.php");
	$msg=$bsiCore->getContact();
	header("location:contacts.php?msg=".$msg);
}
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
<link href="js/slider/coin-slider.css" rel="stylesheet" type="text/css" />
<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/slider/coin-slider.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#coin-slider').coinslider({ hoverPause: false });
	});
	function validationcheck(){
			var name=document.forms["form1"]["name"].value;
			var email=document.forms["form1"]["email"].value;
			var subject=document.forms["form1"]["subject"].value;
			var message=document.forms["form1"]["message"].value;
			
			if(name == ""){
				alert('Digite seu nome');	
				return false;
			}
			if(email == ""){
				alert('Digite seu e-mail');
				return false;
			}else{
				var atpos=email.indexOf("@");
                var dotpos=email.lastIndexOf(".");
         if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
             {
              alert("Endereço de e-mail inválido");
              return false;
              }
				
			}
			
			if(subject == ""){
				alert('Digite o assunto');	
				return false;
			}
			if(message == ""){
				alert('Digite sua mensagem');
				return false;
			}
			return true;
	}
</script>
<script type="text/javascript" src="js/menu/superfish.js"></script>
<script type="text/javascript">
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	}); 
</script>
</head>

<body style="font-family: 'MavenProRegular';">
<?php include("header.php"); ?>
<div id="shadow">
 <div id="shadow-inside">
  <div id="shadow-left"></div>
  <div id="shadow-right"></div>
 </div>
</div>
<div id="container-div">
 <div id="container-inside">
  <div id="leftside-div">
   <?php echo $fixedurl_content;?><br />
   <h1 class="col2">Fale conosco</h1>
   <h3 class="col2"><?php 
   if(isset($_GET['msg'])){
	   $msg=mysql_real_escape_string($_GET['msg']);
	   if($msg == 'Mensagem enviada'){
		   echo '<font color="#006633">'.$msg.'</font>';
	   }else{
		   echo '<font color="#FF0000">'.$msg.'</font>';
	   }
	   unset($msg);
   }
   ?></h3>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1" onsubmit="return validationcheck();">
       	<div class="booking1">
        <table cellpadding="5" cellspacing="0" border="0" width="100%">
        <tr><td>Seu nome:</td>
        <td><input type="text" value="" id="name" name="name"  class="list-input1"/></td></tr>
        
         <tr><td>Email:</td>
         <td><input type="text" value="" id="email" name="email" class="list-input2"/></td></tr>
         <tr><td>Assunto:</td>
         <td><input type="text" value="" id="subject" name="subject" class="list-input2"/></td></tr>
         <tr><td valign="top">Mensagem:</td>
         <td><textarea id="message" name="message" style="margin-left:5px; width:450px; height:150px;"></textarea></td></tr>
          <tr><td></td><td align="left"><input name="submit" id="submit" value="Enviar" type="submit" class="btn34"  /></td></tr>
        
        </table>
       </div>
       </form>
			
		
  </div>
  <div class="clr"></div>
  <div id="rightside-div">
   
   <h1 class="col2">Nosso Endereço</h1>

      <div class="search-text"><strong><?php echo $bsiCore->config['conf_apartment_name'];?></strong></div>
      <div class="search-text"><strong>Endereço:</strong><br /><?php echo $bsiCore->config['conf_apartment_streetaddr'];?><br /><?php echo $bsiCore->config['conf_apartment_city'];?>&nbsp;<?php echo $bsiCore->config['conf_apartment_state'];?> - <?php echo $bsiCore->config['conf_apartment_zipcode'];?><br /><?php echo $bsiCore->config['conf_apartment_country'];?></div>
      <div class="search-text"><strong>Email :</strong><br />
      <?php echo $bsiCore->config['conf_apartment_email'];?></div>
      <div class="search-text"><strong>Telefone :</strong><br />
      <?php echo $bsiCore->config['conf_apartment_phone'];?></div>
  </div>
  <div class="clr"></div>
  <div class="clr"></div>
 </div>
 <div class="clr"></div>
</div>
<div class="clr"></div>
<?php include("footer.php"); ?>
</body>
</html>
