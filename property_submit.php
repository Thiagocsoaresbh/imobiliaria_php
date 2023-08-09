<?php
session_start();
if(isset($_POST['issubmit'])){
	include("includes/db.conn.php");
	include("includes/conf.class.php");
	include("includes/mail.class.php");
	$bsiCore->insertpropertyDetails();
	if(isset($_POST['payment_gateway']) && $_POST['payment_gateway'] == "pp"){
		echo "<script language=\"JavaScript\">";
		echo "document.write('<form action=\"paypal-submission.php\" method=\"post\" name=\"formpaypal\">');";
		echo "document.write('<input type=\"hidden\" name=\"amount\"  value=\"".number_format($bsicore->config['conf_appmt_listing_price'], 2, '.', '')."\">');";
		echo "document.write('<input type=\"hidden\" name=\"invoice\"  value=\"".$_SESSION['apmt_appmtid']."\">');";
		echo "document.write('<input type=\"hidden\" name=\"appmtName\"  value=\"".$_SESSION['apmt_appmtname']."\">');";
		echo "document.write('</form>');";
		echo "setTimeout(\"document.formpaypal.submit()\",500);";
		echo "</script>";	
	}else{
		header("location:property_submit_complete.php"); 
		exit;
	}
}else{
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/admin.class.php");
$apmtType = $bsiCore->getApmtTypeCombobox();
$getfacilityhtml = $bsiAdminMain->showAllFacility(); 
$rentType = array(1=>"Night", 2=>"Week", 3=>"Month");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<title>
<?php echo $bsiCore->config['conf_apartment_name'];?>
</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/menu/superfish.js"></script>
<script type="text/javascript">
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	}); 
</script>
<link href="css/smart_wizard.css" rel="stylesheet" type="text/css">
<script src="cp/ckeditor/ckeditor_basic.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.smartWizard-2.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	// Smart Wizard     	
  		$('#wizard').smartWizard({transitionEffect:'slideleft',onLeaveStep:leaveAStepCallback,onFinish:onFinishCallback,enableFinishButton:true});
		  function leaveAStepCallback(obj){
			 var step_num= obj.attr('rel');
			 return validateSteps(step_num);
		  }
      
		  function onFinishCallback(){
			 if(validateAllSteps()){
				$('form').submit();
			 }
		  }
	});
	   
	function validateAllSteps(){
	   var isStepValid = true;
	   if(validateStep1() == false){
		 isStepValid = false;
		 $('#wizard').smartWizard('setError',{stepnum:1,iserror:true});         
	   }else{
		 isStepValid = true;
		 $('#wizard').smartWizard('setError',{stepnum:1,iserror:false});
	   }
	   
	   if(validateStep2() == false){
		 isStepValid = false;
		 $('#wizard').smartWizard('setError',{stepnum:2,iserror:true}); 
	   }else{
		 isStepValid = true;
		 $('#wizard').smartWizard('setError',{stepnum:2,iserror:false});
	   }
	   
	   if(validateStep3() == false){
		 isStepValid = false;
		 $('#wizard').smartWizard('setError',{stepnum:3,iserror:true}); 
	   }else{
		 isStepValid = true;
		 $('#wizard').smartWizard('setError',{stepnum:3,iserror:false});
	   }
	   
	   if(!isStepValid){
		  $('#wizard').smartWizard('showMessage','Corrija os erros para prosseguir');
	   } else{
		  $('#wizard').smartWizard('showMessage','');
	   }
	   return isStepValid;
	} 	
		
		
	function validateSteps(step){
	  var isStepValid = true;
	  // validate step 1
	  if(step == 1){
		 if(validateStep1() == false ){
			isStepValid = false; 
			$('#wizard').smartWizard('showMessage','<font style="color:#F00;">Corrija os erros no passo'+step+ ' e clique em avançar.</font>');
			$('#wizard').smartWizard('setError',{stepnum:step,iserror:true});         
		 }else{
			isStepValid = true;
			$('#wizard').smartWizard('setError',{stepnum:step,iserror:false});
		 }
	  } 
	  
	  if(step == 2){
		 if (validateStep2() == false){
			isStepValid = false; 
			$('#wizard').smartWizard('showMessage','<font style="color:#F00;">Corrija os erros no passo '+step+ ' e clique em avançar.</font>');
			$('#wizard').smartWizard('setError',{stepnum:step,iserror:true});  
		 } else{
			isStepValid = true; 
			$('#wizard').smartWizard('setError',{stepnum:step,iserror:false});
		 } 
	  }
	  
	  if(step == 3){
		 if (validateStep3() == false){
			isStepValid = false; 
			$('#wizard').smartWizard('showMessage','<font style="color:#F00;">Corrija os erros no passo'+step+ ' e clique em avançar.</font>');
			$('#wizard').smartWizard('setError',{stepnum:step,iserror:true});  
		 } else{
			isStepValid = true; 
			$('#wizard').smartWizard('setError',{stepnum:step,iserror:false});
		 } 
	  }
	  return isStepValid;
   }
      function validateStep2(){
		  var isValid = true; 
		  
		  // Validate Apartment Type
	      var appmt_type_id = $('#appmt_type_id').val();
		  if(!appmt_type_id && appmt_type_id == ""){
			 isValid = false;
			 $('#msg_appmt_type_id').html('<font style="color:#F00;">&nbsp;&nbsp;Selecione o tipo de imóvel.</font>').show();
		  }else{
			 $('#msg_appmt_type_id').html('').show();
			 isValid = true;
		  } 
		  
		  // Validate Apartment Name
	      var appmt_name = $('#appmt_name').val();
		  if(!appmt_name && appmt_name.length <= 0){
			 isValid = false;
			 $('#msg_appmt_name').html('<font style="color:#F00;">&nbsp;&nbsp;Insira um nome para o imóvel.</font>').show();
		  }else{
			 $('#msg_appmt_name').html('').show();
			 isValid = true;
		  }
		  
		  // Validate Apartment Address
	      var addr1 = $('#addr1').val();
		  if(!appmt_type_id && appmt_type_id <= 0){
			 isValid = false;
			 $('#msg_addr1').html('<font style="color:#F00;">&nbsp;&nbsp;Digite o endereço do imóvel.</font>').show();
		  }else{
			 $('#msg_addr1').html('').show();
			 isValid = true;
		  }
		  
		  // Validate Apartment City
	      var acity = $('#acity').val();
		  if(!acity && acity.length <= 0){
			 isValid = false;
			 $('#msg_acity').html('<font style="color:#F00;">&nbsp;&nbsp;Insira a cidade.</font>').show();
		  }else{
			 $('#msg_acity').html('').show();
			 isValid = true;
		  }
		  
		  // Validate Apartment State
	      var astate = $('#astate').val();
		  if(!astate && astate.length <= 0){
			 isValid = false;
			 $('#msg_astate').html('<font style="color:#F00;">&nbsp;&nbsp;Digite o estado.</font>').show();
		  }else{
			 $('#msg_astate').html('').show();
			 isValid = true;
		  }
		  
		  // Validate Apartment Country
	      var acountry = $('#acountry').val();
		  if(!acountry && acountry.length <= 0){
			 isValid = false;
			 $('#msg_acountry').html('<font style="color:#F00;">&nbsp;&nbsp;Digite o Celular.</font>').show();
		  }else{
			 $('#msg_acountry').html('').show();
			 isValid = true;
		  }
		  
		  // Validate Apartment Zip/Postal Code
	      var azipcode = $('#azipcode').val();
		  if(!azipcode && azipcode.length <= 0){
			 isValid = false;
			 $('#msg_azipcode').html('<font style="color:#F00;">&nbsp;&nbsp;Digite o CEP.</font>').show();
		  }else{
			 $('#msg_azipcode').html('').show();
			 isValid = true;
		  }
		  
		  // Validate Apartment Phone
	      var aphone = $('#aphone').val();
		  if(!aphone && aphone.length <= 0){
			 isValid = false;
			 $('#msg_aphone').html('<font style="color:#F00;">&nbsp;&nbsp;Digite o telefone.</font>').show();
		  }else{
			 $('#msg_aphone').html('').show();
			 isValid = true;
		  }
		  
		  // Validate Apartment Rent Amount
	      var arent = $('#arent').val();
		  if(!arent && arent <= 0){
			 isValid = false;
			 $('#msg_arent').html('<font style="color:#F00;">&nbsp;&nbsp;Digite o valor aluguel.</font>').show();
		  }else{
			 $('#msg_arent').html('').show();
			 isValid = true;
		  }
		  
		  return isValid;
	  }
		
	  function validateStep1(){
       	  var isValid = true; 
	   // Validate Email
	      var emai = $('#email').val();
		  if(!emai && emai.length <= 0){
			 isValid = false;
			 $('#msg_email').html('<font style="color:#F00;">Digite o e-mail</font>').show();
		  }else{
			 if( !isValidEmailAddress(emai) ){
				 isValid = false; 
				 $('#msg_email').html('<font style="color:#F00;">Digite um e-mail válido</font>').show();
			 } else{
				 isValid = true;
			 }
		  }        
       // validate password
       	   var pw = $('#password').val();
		   if(!pw && pw.length <= 0){
			 isValid = false;
			 $('#msg_password').html('<font style="color:#F00;">Digite a senha</font>').show();         
		   }else{
			 $('#msg_password').html('').hide();
			 isValid = true;
		   }
       
       // validate confirm password
       		var cpw = $('#cpassword').val();
       		if(!cpw && cpw.length <= 0){
         		isValid = false;
         		$('#msg_cpassword').html('<font style="color:#F00;">Digite a senha novamente</font>').show();         
		   }else{
			 $('#msg_cpassword').html('').hide();
		   }  
       
       // validate password match
       		if(pw && pw.length > 0 && cpw && cpw.length > 0){
				if(pw != cpw){
					isValid = false;
					$('#msg_cpassword').html('<font style="color:#F00;">Senhas não conferem</font>').show();            
				}else{
					$('#msg_cpassword').html('').hide();
				}
       		}
		// Validate Username
		   var un = $('#username').val();
		   if(!un && un.length <= 0){
			 isValid = false;
			 $('#msg_username').html('<font style="color:#F00;">Digite o primeiro nome</font>').show();
		   }else{
			 $('#msg_username').html('').hide();
		   }
			
		// validate Last Name
       	   var sname = $('#sname').val();
		   if(!sname && sname.length <= 0){
			 isValid = false;
			 $('#msg_lastname').html('<font style="color:#F00;">Digite o sobrenome</font>').show();
		   }else{
			 $('#msg_lastname').html('').hide();
		   }
		// validate Street Address
       	   var sadd = $('#sadd').val();
		   if(!sadd && sadd.length <= 0){
			 isValid = false;
			 $('#msg_sadd').html('<font style="color:#F00;">Digite o endereço</font>').show();
		   }else{
			 $('#msg_sadd').html('').hide();
		   }
		
		// validate City
       	   var city = $('#city').val();
		   if(!city && city.length <= 0){
			 isValid = false;
			 $('#msg_city').html('<font style="color:#F00;">Digite a cidade</font>').show();
		   }else{
			 $('#msg_city').html('').hide();
		   }
		
		// validate State
       	   var province = $('#province').val();
		   if(!province && province.length <= 0){
			 isValid = false;
			 $('#msg_province').html('<font style="color:#F00;">Digite o estado</font>').show();
		   }else{
			 $('#msg_province').html('').hide();
		   }
		   
		// validate Postal
       	   var zip = $('#zip').val();
		   if(!zip && zip.length <= 0){
			 isValid = false;
			 $('#msg_zip').html('<font style="color:#F00;">Digite o CEP</font>').show();
		   }else{
			 $('#msg_zip').html('').hide();
		   }
		   
		// validate Country
       	   var country = $('#country').val();
		   if(!country && country.length <= 0){
			 isValid = false;
			 $('#msg_country').html('<font style="color:#F00;">Digite o celular</font>').show();
		   }else{
			 $('#msg_country').html('').hide();
		   }
		   
		// validate Country
       	   var phone = $('#phone').val();
		   if(!phone && phone.length <= 0){
			 isValid = false;
			 $('#msg_phone').html('<font style="color:#F00;">Digite o telefone</font>').show();
		   }else{
			 $('#msg_phone').html('').hide();
		   }   
       	  return isValid;
    	}
		
		// Email Validation
		function isValidEmailAddress(emailAddress) {
		   var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		  return pattern.test(emailAddress);
		} 
		function validateStep3(){
		  var isValid = true; 
		  
		  if($("#tos1").attr("checked")==true){
    		  isValid = true;
		  }else{
			  isValid = false;
			  $('#msg_tos1').html('<font style="color:#F00;">Você precisa aceitar os termos e condições para prosseguir</font>').show();
		  }
		  
		  return isValid;
		}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#email').blur(function(){
			var querystr='actioncode=1&email='+$('#email').val();
			$.post("ajax-processor.php", querystr, function(data){						 
				if(data.errorcode == 1){
					$('#msg_email').html('<font style="color:#F00;">&nbsp;Já existe. Faça login</font>');
					$('#email').focus();
				}else{
					$('#msg_email').html('');
				}
			}, "json");	
		});
	});
</script>
<script type='text/javascript'>
	CharacterCount = function(TextArea,FieldToCount){
	var desc_short = document.getElementById(TextArea);
	var myLabel    = document.getElementById(FieldToCount); 
	if(!desc_short || !myLabel){return false}; // catches errors
	var MaxChars   =  desc_short.maxLengh;
	if(!MaxChars){MaxChars =  desc_short.getAttribute('maxlength') ; }; 	if(!MaxChars){return false};
	var remainingChars = MaxChars - desc_short.value.length
	myLabel.innerHTML  = remainingChars+" Characters Remaining of Maximum "+MaxChars
}
//SETUP!!
setInterval(function(){CharacterCount('desc_short','CharCountLabel1')},55);
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
    <h1 class="col2">Registrar imóvel</h1>
    <table align="left" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><form id="formSubmit" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <input type='hidden' name="issubmit" value="1">
            <!-- Tabs -->
            <div id="wizard" class="swMain">
              <ul>
                <li><a href="#step-1">
                  <label class="stepNumber">1</label>
                  <span class="stepDesc"> Dados da conta<br />
                  <small>Preencha os campos</small> </span> </a></li>
                <li><a href="#step-2">
                  <label class="stepNumber">2</label>
                  <span class="stepDesc"> Dados imovel<br />
                  <small>Preencha os campos</small> </span> </a></li>
                <?php if($bsiCore->config['conf_apppmt_listing_type']==0){	 ?>
                <li><a href="#step-3">
                  <label class="stepNumber">3</label>
                  <span class="stepDesc"> Confirmação<br />
                  <small>Está quase concluído</small> </span> </a></li>
                <?php }else{ ?>
                <li><a href="#step-3">
                  <label class="stepNumber">3</label>
                  <span class="stepDesc"> Pagamento<br />
                  <small>Ultimo passo</small> </span> </a></li>
                <?php } ?>
              </ul>
              <div id="step-1">
                <h2 class="StepTitle">Passo 1: Detalhes da conta</h2>
                <table cellpadding="5" cellspacing="2" border="0">
                  <tr>
                    <td align="left"><strong>Email Id:</strong></td>
                    <td><input type="text" class="txtBox" name="email" id="email" style="width:250px;" />
                      <span id="msg_email"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Senha:</strong></td>
                    <td><input type="password"  class="txtBox" name="password" id="password" style="width:250px;" />
                      <span id="msg_password"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Confirme a senha:</strong></td>
                    <td><input type="password" class="txtBox" name="cpassword" id="cpassword" style="width:250px;" />
                      <span id="msg_cpassword"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>First Name:</strong></td>
                    <td><input type="text" class="txtBox"  style="width:250px;" name="first_name" id="username"/>
                      <span id="msg_username"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Sobrenome:</strong></td>
                    <td><input type="text" class="txtBox" value="" style="width:250px;" name="surname" id="sname"/>
                    	<span id="msg_lastname"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Endereço:</strong></td>
                    <td><input type="text" class="txtBox" value="" style="width:250px;" name="street_addr" id="sadd"/>
                    	<span id="msg_sadd"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Eldorado:</strong></td>
                    <td><input type="text" class="txtBox" value="" style="width:250px;" name="street_addr2" id="sadd2"/></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Contagem:</strong></td>
                    <td><input type="text" class="txtBox"  style="width:250px;"  name="city" id="city"/>
                    	<span id="msg_city"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Estado:</strong></td>
                    <td><input type="text" class="txtBox"  style="width:250px;"  name="province" id="province"/>
                    	<span id="msg_province"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>CEP:</strong></td>
                    <td><input type="text" class="txtBox"  style="width:250px;"  name="zip" id="zip"/>
                    	<span id="msg_zip"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Celular:</strong></td>
                    <td><input type="text" class="txtBox"  style="width:250px;"  name="country" id="country"/>
                    	<span id="msg_country"></span></td>
                  </tr>
                  <tr>
                    <td align="left"><strong>Telefone:</strong></td>
                    <td><input type="text" class="txtBox"  style="width:250px;" name="phone" id="phone"/>
                    	<span id="msg_phone"></span>
                        <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>"/></td>
                  </tr>
                </table>
              </div>
              <div id="step-2">
                <h2 class="StepTitle">Passo 2: Detalhes do imóvel</h2>
                <!--*****************************************************************************************************************-->
                
                <table cellpadding="5" cellspacing="5" border="0" width="100%">
                  <tr>
                    <td width="150px"><strong>Tipo de imóvel:</strong></td>
                    <td><select name="appmt_type_id" id="appmt_type_id">
                        <?php echo $apmtType;?>
                      </select>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="msg_appmt_type_id"></span></td>
                  </tr>
                  <tr>
                    <td><strong>Nome do imóvel:</strong></td>
                    <td><input type="text" class="txtBox"  name="appmt_name" id="appmt_name"  style="width:200px;"><span id="msg_appmt_name"></span></td>
                  </tr>
                  <tr>
                    <td><strong>Endereço:</strong></td>
                    <td><input type="text" class="txtBox"  name="addr1" id="addr1"  style="width:200px;"><span id="msg_addr1"></span></td>
                  </tr>
                  <tr>
                    <td><strong>Complemento:</strong></td>
                    <td><input type="text" name="addr2" id="addr2" class="txtBox"   style="width:200px;"></td>
                  </tr>
                  <tr>
                    <td><strong>Cidade:</strong></td>
                    <td><input type="text" class="txtBox"  name="acity" id="acity" style="width:200px;"><span id="msg_acity"></span></td>
                  </tr>
                  <tr>
                    <td><strong>Estado:</strong></td>
                    <td><input type="text" class="txtBox"  name="astate" id="astate" style="width:200px;"><span id="msg_astate"></span></td>
                  </tr>
                  <tr>
                    <td><strong>Celular:</strong></td>
                    <td><input type="text" class="txtBox" name="acountry" id="acountry"  style="width:200px;"><span id="msg_acountry"></span></td>
                  </tr>
                  <tr>
                    <td><strong>CEP:</strong></td>
                    <td><input type="text" class="txtBox" name="azipcode" id="azipcode"  style="width:200px;"><span id="msg_azipcode"></span></td>
                  </tr>
                  <tr>
                    <td><strong>Telefone:</strong></td>
                    <td><input type="text" class="txtBox" name="aphone" id="aphone"  style="width:200px;"><span id="msg_aphone"></span></td>
                  </tr>
                  <tr>
                    <td><strong>Aluguel:</strong></td>
                    <td><input type="text" class="txtBox" name="arent" id="arent"  style="width:80px;">
                      &nbsp;/&nbsp;
                      <?php echo $rentType[$bsiCore->config['conf_rental_type']];?><span id="msg_arent"></span></td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>Pequena descrição:</strong></td>
                    <td><textarea name="short_desc" id="desc_short"  style="width:600px; height:100px;" class="required" maxlength='300'>
</textarea>
                      <div id='CharCountLabel1'></div></td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>Descrição completa:</strong></td>
                    <td><textarea name="long_desc" id="editor1"></textarea></td>
                  </tr>
                </table>
                <br>
                <table cellpadding="5" cellspacing="5" border="0" width="100%">
                  <tr>
                    <td style="font-size:16px;"><strong>Instalações:</strong><br />
                      <hr style="color:#000;"/></td>
                  </tr>
                  <tr>
                    <td><?php echo $getfacilityhtml;?></td>
                  </tr>
                </table>
                <br>
                <table cellpadding="5" cellspacing="5" border="0" width="100%">
                  <tr>
                    <td colspan="4" style="font-size:16px;"><strong>Características</strong><br />
                      <hr style="color:#000;"/></td>
                  </tr>
                  <tr>
                    <td width="150px"><strong>Tamanho:</strong></td>
                    <td width="230px"><input type="text" name="appmt_size" class="txtBox" id="appmt_size"  style="width:100px;" >
                      <?php echo $bsiCore->config['conf_mesurment_unit'];?></td>
                    <td width="150px"><strong>Área total:</strong></td>
                    <td><input type="text" name="apptmt_lot_size" id="apptmt_lot_size" class="txtBox"  style="width:100px;" >
                      <?php echo $bsiCore->config['conf_mesurment_unit'];?></td>
                  </tr>
                  <tr>
                    <td><strong>Quartos:</strong></td>
                    <td><input type="text" name="bedroom" id="bedroom" class="txtBox"  style="width:100px;" ></td>
                    <td><strong>Banheiros:</strong></td>
                    <td><input type="text" name="bathroom" id="bathroom" class="txtBox"  style="width:100px;" ></td>
                  </tr>
                  <tr>
                    <td><strong>Garagem:</strong></td>
                    <td><input type="text" name="car_garage" id="car_garage" class="txtBox"  style="width:100px;" ></td>
                    <td><strong>Garage Size:</strong></td>
                    <td><input type="text" name="garage_size" id="garage_size" class="txtBox"  style="width:100px;" >
                      <?php echo $bsiCore->config['conf_mesurment_unit'];?> </td>
                  </tr>
                  <tr>
                    <td><strong>Cômodos:</strong></td>
                    <td><input type="text" name="total_rooms" id="total_rooms" class="txtBox"  style="width:100px;" ></td>
                    <td><strong>Suites:</strong></td>
                    <td><input type="text" name="basement" id="basement" class="txtBox"  style="width:100px;" ></td>
                  </tr>
                  <tr>
                    <td><strong>Andares:</strong></td>
                    <td><input type="text" name="floors" id="floors" class="txtBox" style="width:100px;" ></td>
                    <td><strong>Tipo:</strong></td>
                    <td><input type="text" name="year_of_build" class="txtBox" id="year_of_build"  style="width:100px;" ></td>
                  </tr>
                </table>
                
                <!--*****************************************************************************************************************--> 
                
              </div>
              <div id="step-3">
                <?php if($bsiCore->config['conf_apppmt_listing_type']==0){	 ?>
                <h2 class="StepTitle">Step 3: Confirmação</h2>
                <table cellspacing="3" cellpadding="3" align="left" width="100%">
                  <tr>
                    <td align="left"><textarea class="txtBox" rows="3" style="width:100%; height:300px;" readonly="readonly">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
          </textarea></td>
                  </tr>
                  <tr>
                    <td align="left"><input type="checkbox" name="tos" id="tos1" />
                      Aceite os termos e condições.&nbsp;&nbsp;<span id="msg_tos1"></span></td>
                  </tr>
                </table>
                <?php }else{ ?>
                <h2 class="StepTitle">Step 3: Pagamento &amp; Confirmação</h2>
                <table cellspacing="3" cellpadding="3" align="left" width="100%">
                  <tr>
                    <td width="15%"><strong>Método de pagamento:</strong></td>
                    <td width="85%" align="left">
                    	<select id="payment_gateway" name="payment_gateway" >
                        <?php 
						$result = mysql_query("select * from bsi_payment_gateway where gateway_code='pp' and enabled=true");
						while($row = mysql_fetch_assoc($result)){
							 echo '<option value="'.$row['gateway_code'].'">'.$row['gateway_name'].'</option>';
		    			}
		  				?>
                      </select></td>
                  </tr>
                  <tr>
                    <td ><strong>Lista de preços:</strong></td>
                    <td ><?php echo $bsiCore->config['conf_currency_symbol'].$bsiCore->config['conf_appmt_listing_price'].' '.$bsiCore->config['conf_currency_code']; ?></td>
                  </tr>
                  <tr>
                    <td align="left" colspan="2"><textarea name="address" id="address" class="txtBox" rows="3" style="width:100%; height:300px;" readonly="readonly">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia Alok
          </textarea></td>
                  </tr>
                  <tr>
                    <td align="left" colspan="2"><input type="checkbox" name="tos" id="tos1"  />
                      Aceite os termos e condições.&nbsp;&nbsp;<span id="msg_tos1"></span></td>
                  </tr>
                </table>
                <?php } ?>
              </div>
            </div>
            <!-- End SmartWizard Content -->
          </form></td>
      </tr>
    </table>
  </div>
  <div class="clr"></div>
</div>
<div class="clr"></div>
<script type="text/javascript">
	CKEDITOR.replace( 'editor1',
	{
		 toolbar : 'Basic'
	});
</script>
<?php include("footer.php"); ?>
</body>
</html>
<?php } ?>