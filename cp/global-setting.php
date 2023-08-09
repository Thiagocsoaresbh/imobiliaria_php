<?php 
include("access.php");
if(isset($_POST['act_sbmt'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->global_setting_post();
	header("location:global-setting.php");
	exit;
}
include("header.php");
$global_setting = $bsiAdminMain->global_setting();	
?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<div id="container-inside"> <span style="font-size:16px; font-weight:bold">Configurações Gerais</span>
  <hr />
  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0" width="100%">
      <tr>
        <td><strong>Nome do Site:</strong></td>
        <td><input type="text" class="required" value="<?php echo $bsiCore->config['conf_apartment_name'];?>" size="50" name="conf_apartment_name" id="conf_apartment_name"/></td>
      </tr>
      <tr>
        <td><strong>Endereço:</strong></td>
        <td><input type="text" class="required" value="<?php echo $bsiCore->config['conf_apartment_streetaddr'];?>" size="50" name="conf_apartment_streetaddr" id="conf_apartment_streetaddr"/></td>
      </tr>
      <tr>
        <td><strong>Cidade:</strong></td>
        <td><input type="text" class="required" value="<?php echo $bsiCore->config['conf_apartment_city'];?>" size="30" name="conf_apartment_city" id="conf_apartment_city"/></td>
      </tr>
      <tr>
        <td><strong>Estado:</strong></td>
        <td><input type="text" class="required" value="<?php echo $bsiCore->config['conf_apartment_state'];?>" size="30" name="conf_apartment_state" id="conf_apartment_state"/></td>
      </tr>
      <tr>
        <td><strong>País:</strong></td>
        <td><input type="text" class="required" value="<?php echo $bsiCore->config['conf_apartment_country'];?>" size="30" name="conf_apartment_country" id="conf_apartment_country"/></td>
      </tr>
      <tr>
        <td><strong>CEP:</strong></td>
        <td><input type="text" class="required" value="<?php echo $bsiCore->config['conf_apartment_zipcode'];?>" size="10" name="conf_apartment_zipcode" id="conf_apartment_zipcode"/></td>
      </tr>
      <tr>
        <td><strong>Telefone:</strong></td>
        <td><input type="text"  class="required" value="<?php echo $bsiCore->config['conf_apartment_phone'];?>" size="15" name="conf_apartment_phone" id="conf_apartment_phone"/></td>
      </tr>
      <tr>
        <td><strong>Celular:</strong></td>
        <td><input type="text" class="" value="<?php echo $bsiCore->config['conf_apartment_fax'];?>" size="15" name="conf_apartment_fax" id="conf_apartment_fax"/></td>
      </tr>
      <tr>
        <td><strong>Email:</strong></td>
        <td><input type="text" class="required email" value="<?php echo $bsiCore->config['conf_apartment_email'];?>" size="30" name="conf_apartment_email" id="email"/></td>
      </tr>
      <tr><td colspan="2" width="100%"><hr /></td></tr>
      <tr>
        <td><strong>Notificação de e-mail:</strong></td>
        <td valign="middle"><input type="text" name="conf_notification_email" id="conf_notification_email" value="<?php echo $bsiCore->config['conf_notification_email'];?>" class="required email" style="width:250px;" /></td>
      </tr>
      <tr>
        <td><strong>Moeda:</strong></td>
        <td><input type="text" name="conf_currency_code" id="conf_currency_code" value="<?php echo $bsiCore->config['conf_currency_code'];?>"  class="required" style="width:70px;"  /></td>
      </tr>
      <tr>
        <td><strong>Símbolo da moeda:</strong></td>
        <td><input type="text" name="conf_currency_symbol" id="conf_currency_symbol" value="<?php echo $bsiCore->config['conf_currency_symbol'];?>"  class="required" style="width:70px;"  /></td>
      </tr>
      <tr>
        <td><strong>Fusu horário:</strong></td>
        <td><select name="conf_apartment_timezone" id="conf_apartment_timezone">
            <?php echo $global_setting['select_timezone'];?>
          </select></td>
      </tr>
      <tr>
        <td><strong>Formato da data</strong></td>
        <td><select name="conf_dateformat" id="conf_dateformat">
            <?php echo $global_setting['select_dt_format'];?>
          </select></td>
      </tr>
      <tr>
        <td><strong>Tempo de bloqueios:</strong></td>
        <td><select name="conf_booking_exptime" id="conf_booking_exptime">
            <?php echo $global_setting['select_room_lock'];?>
          </select>
          <span style="font-size:10px">&nbsp;&nbsp;Obs.: Quando o imóvel estiver bloqueado, mostrará para o cliente a página de pagamento.</span></td>
      </tr>
       <tr>
        <td><strong>Unidade de Medição:</strong></td>
        <td><input type="text" name="conf_mesurment_unit" id="conf_mesurment_unit" size="6" class="required" value="<?php echo $bsiCore->config['conf_mesurment_unit'];?>" /></td>
      </tr>
      <tr>
        <td><strong>Imposto:</strong></td>
        <td><input type="text" name="conf_tax_amount" id="conf_tax_amount" size="6" class="required number" value="<?php echo $bsiCore->config['conf_tax_amount'];?>" />
          &nbsp;%</td>
      </tr>
        <tr>
        <td><input type="hidden" name="act_sbmt" value="1" /></td>
        <td><input type="submit" value="Salvar" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>
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
