<?php
include("access.php");
if(isset($_POST['addedit'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->apartmentDetailsentryform();
	$direc=mysql_real_escape_string($_POST['flag']);
	if($direc != 0){
		header("location:apartment-list.php?client_id=".base64_encode($direc));
	}else{
	   header("location:apartment-list.php");
	}
	exit;	
}
include("header.php"); 
if(isset($_GET['flag'])){
	$flag=mysql_real_escape_string($_GET['flag']);
	
}else{
	$flag=0;
}
if(isset($_GET['id']) && $_GET['id'] != ""){
	$id = $bsiCore->ClearInput(base64_decode($_GET['id']));
	if($id){
		$row = mysql_fetch_assoc(mysql_query($bsiCore->sqlApartment($id)));
		$apmtType = $bsiCore->getApmtTypeCombobox($row['appmt_type_id']);
		$rowf = $bsiCore->getApmtFeatures($row['appmt_id']);
		$getfacilityhtml = $bsiAdminMain->showAllFacility($row['appmt_id']); 
	}else{
		$row  = NULL;
		$rowf = NULL;
		$apmtType = $bsiCore->getApmtTypeCombobox();
		$getfacilityhtml = $bsiAdminMain->showAllFacility(); 
	}
}else{
	header("location:apartment-list.php");	
	exit;
}
?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<script type="text/javascript">
	$(document).ready(function(){
		$('#submitappmt').click(function(){
			if($('#appmt_type_id').val() == ""){
				$('#showmsg').html('<font color="#FF0000">Please select Apartment Type.</font>');
				$('#appmt_type_id').css({'background-color' : '#FDE3ED'});
				$('#appmt_type_id').focus();
				return false;	
			}
		});
		$('#appmt_type_id').change(function(){
			if($('#appmt_type_id').val() != ""){
				$('#showmsg').html('');
				$('#appmt_type_id').css({'background-color' : ''});
			}else{
				$('#showmsg').html('<font color="#FF0000">Please select Apartment Type.</font>');
				$('#appmt_type_id').css({'background-color' : '#FDE3ED'});
			}
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
<div id="container-inside"><span style="font-size:16px; font-weight:bold;">Adicionar/Editar Imóvel</span>
  <hr />
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="form1" enctype="multipart/form-data">
      <input type="hidden" name="flag" value="<?php echo $flag;?>">
    <table cellpadding="5" cellspacing="5" border="0" width="100%">
      <tr>
        <td width="150px"><strong>Tipo de imóvel:</strong></td>
        <td><select name="appmt_type_id" id="appmt_type_id">
            <?php echo $apmtType;?>
          </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="showmsg"></span></td>
      </tr>
      <tr>
        <td><strong>Nome do imóvel:</strong></td>
        <td><input type="text" class="required" name="appmt_name" id="appmt_name" value="<?php echo $row['appmt_name'];?>" style="width:200px;"></td>
      </tr>
      <tr>
        <td><strong>Endereço 1:</strong></td>
        <td><input type="text" class="required" name="addr1" id="addr1" value="<?php echo $row['addr1'];?>" style="width:200px;"></td>
      </tr>
      <tr>
        <td><strong>Complemento:</strong></td>
        <td><input type="text" name="addr2" id="addr2" value="<?php echo $row['addr2'];?>" style="width:200px;"></td>
      </tr>
      <tr>
        <td><strong>Cidade:</strong></td>
        <td><input type="text" class="required" name="city" id="city" value="<?php echo $row['city'];?>" style="width:200px;"></td>
      </tr>
      <tr>
        <td><strong>Estado:</strong></td>
        <td><input type="text" class="required" name="state" id="state" value="<?php echo $row['state'];?>" style="width:200px;"></td>
      </tr>
      <tr>
        <td><strong>País:</strong></td>
        <td><input type="text" class="required" name="country" id="country" value="<?php echo $row['country'];?>" style="width:200px;"></td>
      </tr>
      <tr>
        <td><strong>CEP:</strong></td>
        <td><input type="text" class="required" name="zipcode" id="zipcode" value="<?php echo $row['zipcode'];?>" style="width:60px;"></td>
      </tr>
      <tr>
        <td><strong>Telefone:</strong></td>
        <td><input type="text" class="required" name="phone" id="phone" value="<?php echo $row['phone'];?>" style="width:90px;" ></td>
      </tr>
      <tr>
        <td valign="top"><strong>Pequena descrição:</strong></td>
        <td><textarea name="short_desc" id="desc_short" cols="90" rows="3" class="required" maxlength='300'><?php echo $row['short_desc'];?>
</textarea><div id='CharCountLabel1'></div></td>
      </tr>
      <tr>
        <td valign="top"><strong>Descrição completa:</strong></td>
        <td><textarea name="long_desc" class="ckeditor"><?php echo $row['long_desc'];?>
</textarea></td>
      </tr>
      <tr>
        <td><strong>Imagem do imóvel:</strong></td>
        <input type="hidden" name="pre_img" value="<?php echo $row['default_img'];?>" />
        <td><input type="file" name="default_img" id="default_img"/>
          <?php if($row['default_img'] != ""){?>
          <span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="../gallery/ApartImage/<?php echo $row['default_img'];?>" target="_blank"><strong>Ver imagem</strong></a>&nbsp;&nbsp;&nbsp;&nbsp;Apagar imagem?<input type="checkbox" name="deldefault" id="deldefault" /></span><?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp; <b>Sem imagem</b>";} ?></td>
      </tr>
      <tr>
        <td><strong>Planta do imóvel:</strong></td>
        <input type="hidden" name="pre_floor_img" value="<?php echo $row['apart_floor_img'];?>" />
        <td><input type="file" name="apart_floor_img" id="apart_floor_img"/>
          <?php if($row['apart_floor_img'] != ""){?>
          <span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="../gallery/ApartFloor/<?php echo $row['apart_floor_img'];?>" target="_blank"><strong>Ver imagem</strong></a>&nbsp;&nbsp;&nbsp;&nbsp;Apagar imagem da planta?<input type="checkbox" name="delfloor" id="delfloor" /></span><?php }else{ echo "&nbsp;&nbsp;&nbsp;&nbsp; <b>Sem imagem</b>";} ?></td>
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
        <td width="230px"><input type="text" name="appmt_size" class="" id="appmt_size" value="<?php echo $rowf['appmt_size'];?>" style="width:40px;" >
          <span style="font-size:12px; color:#FFF;">&nbsp;<?php echo $bsiCore->config['conf_mesurment_unit'];?></span></td>
        <td width="150px"><strong>Área total:</strong></td>
        <td><input type="text" name="apptmt_lot_size" id="apptmt_lot_size" class="" value="<?php echo $rowf['apptmt_lot_size'];?>" style="width:40px;" >
          <span style="font-size:12px; color:#FFF;">&nbsp;<?php echo $bsiCore->config['conf_mesurment_unit'];?></span></td>
      </tr>
      <tr>
        <td><strong>Quartos:</strong></td>
        <td><input type="text" name="bedroom" id="bedroom" class="digits" value="<?php echo $rowf['bedroom'];?>" style="width:30px;" ></td>
        <td><strong>Banheiros:</strong></td>
        <td><input type="text" name="bathroom" id="bathroom" class="digits" value="<?php echo $rowf['bathroom'];?>" style="width:30px;" ></td>
      </tr>
      <tr>
        <td><strong>Garagem:</strong></td>
        <td><input type="text" name="car_garage" id="car_garage" class="digits" value="<?php echo $rowf['car_garage'];?>" style="width:30px;" ></td>
        <td><strong>Salas:</strong></td>
        <td><input type="text" name="garage_size" id="garage_size" class="" value="<?php echo $rowf['garage_size'];?>" style="width:40px;" >
          <span style="font-size:12px; color:#FFF;">&nbsp;<?php echo $bsiCore->config['conf_mesurment_unit'];?></span></td>
      </tr>
      <tr>
        <td><strong>Total de cômodos:</strong></td>
        <td><input type="text" name="total_rooms" id="total_rooms" class="digits" value="<?php echo $rowf['total_rooms'];?>" style="width:30px;" ></td>
        <td><strong>Suites:</strong></td>
        <td><input type="text" name="basement" id="basement" class="" value="<?php echo $rowf['basement'];?>" style="width:60px;" ></td>
      </tr>
      <tr>
        <td><strong>Andares</strong></td>
        <td><input type="text" name="floors" id="floors" class="digits" value="<?php echo $rowf['floors'];?>" style="width:30px;" ></td>
        <td><strong>Tipo:</strong></td>
        <td><input type="text" name="year_of_build" class="" id="year_of_build" value="<?php echo $rowf['year_of_build'];?>" style="width:90px;" ></td>
      </tr>
      <tr>
        <td><strong>Status:</strong></td>
        <td><?php
               if($row['status'] == 1){
	         ?>
          <input type="checkbox" name="status" id="status" checked="checked" />
          <?php
               }else{
            ?>
          <input type="checkbox" name="status" id="status" />
          <?php
               }
           ?></td><td></td><td></td>
      </tr>
      <tr><td colspan="4" style="height:2px;" valign="top"><hr style="color:#000;"/></td></tr>
      <tr>
        <td><input type="hidden" name="addedit" value="<?php echo $id;?>"></td>
        <td><input type="submit" value="Submit" id="submitappmt" name="submitCapacity" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td><td></td><td></td>
      </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
	$().ready(function() {
		$("#form1").validate();
     });     
</script> 
<script src="ckeditor/ckeditor_basic.js" type="text/javascript"></script> 
<script src="js/jquery.validate.js" type="text/javascript"></script>
<?php include("footer.php"); ?>
