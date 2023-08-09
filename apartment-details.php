<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");

$pos2 = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
if(!$pos2){
	header('Location: booking-failure.php?error_code=9');
}

$appmt = $bsiCore->ClearInput(base64_decode($_REQUEST['appmt_id']));
$_SESSION['appmtid_12'] = $appmt;
$appartmentArr = $_SESSION['svars_details'];
$array = $appartmentArr[$appmt];
$apartmentDetails = $bsiCore->getApartmentdetails($appmt);


if($bsiCore->config['conf_rental_type'] == 1){
	$stayduration = $_SESSION['sv_nightcount']." Night(s)";
	$priceDetails = $bsiCore->getApartmentPrice($appmt, $_SESSION['sv_nightcount']);
}else if($bsiCore->config['conf_rental_type'] == 2){
	$stayduration = $_SESSION['rentaltype']." Week(s)";
	$priceDetails = $bsiCore->getApartmentPrice($appmt, $_SESSION['rentaltype']);
}else if($bsiCore->config['conf_rental_type'] == 3){
	$stayduration = $_SESSION['rentaltype']." Months(s)";
	$priceDetails = $bsiCore->getApartmentPrice($appmt, $_SESSION['rentaltype']);
}

$_SESSION['reservationdata'] = $array;
$_SESSION['tax'] = 0;
$_SESSION['grandtotal'] = 0;
$_SESSION['deposit'] = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<title>
<?php echo $bsiCore->config['conf_apartment_name'];?>
</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/tab.css" rel="stylesheet" type="text/css" />
<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/menu/superfish.js"></script>
<script type="text/javascript">
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	}); 
</script>
<script type="text/javascript" src="js/lightbox/jquery.lightbox-0.5.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/lightbox/jquery.lightbox-0.5.css" media="screen" />
<script type="text/javascript">
    $(function() {
        $('#appartment-media a').lightBox();
    });
    </script>
<!-- **************************************************************************************************************** -->
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function() {
// If your site is large with many images use window.load instead
//$(window).load(function() {
	//Default Action
	$(".tab_content").hide();
	$("ul.tabs li:first").addClass("active").show(); 
	$(".tab_content:first").show(); 
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active"); 
		$(".tab_content").hide(); 
		var activeTab = $(this).find("a").attr("href"); 
		$(activeTab).fadeIn();
		if(activeTab == '#tab2') {      
			$(window).resize(function(){
			//$("#tab2").css({'display':'block'});
			//$("#map_canvas").css({'width':'630px', 'height':'400px'});
			//initialize();
			//alert('Changed!');
			}); 
    		}	
		return false;
	});
});
-->
</script>

<!-- **************************************************************************************************************** -->
</head>

<body  style="font-family: 'MavenProRegular';">
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
      <h1 class="col2">
        <?php echo $apartmentDetails['appmt_name'];?>
      </h1>
      <p class="listing-para">
        <?php echo $apartmentDetails['addr1']." , ".$apartmentDetails['city'].", ".$apartmentDetails['state']."  - ".$apartmentDetails['zipcode'].", ".$apartmentDetails['country'];?>
      </p>
      <?php 
	  	if($apartmentDetails['default_img'] == ""){
			echo '<div id="listing-large-image-div"> <img src="images/no_appmt_photo.jpg" alt="" width="600px" height="340px"/> </div>';
		}else{
	  ?>
      <div id="listing-large-image-div"> <img src="gallery/ApartImage/<?php echo $apartmentDetails['default_img'];?>" alt="" width="600px" height="340px"/> </div>
      <?php } ?>
      <p class="listing-para2">
        <?php echo $apartmentDetails['long_desc'];?>
      </p>
      <br/>
      
      <!-- ******************************************************************************************************* -->
      <div style="width:620px;">
        <ul class="tabs">
          <li class="active"><a href="#tab1">Características</a></li>
          <li><a href="#tab2">Descontos</a></li>
          <li><a href="#tab3">Planta</a></li>
          <li><a href="#tab4"  id="showMap">Mapa</a></li>
<!--	  <li><a href="#tab5">Aluguel</a></li>-->
        </ul>
        <!-- Start tab container -->
        <div class="tab_container">
          <div style="display: block;" id="tab1" class="tab_content">
            <p>
              <?php
				  $featuresarr = $bsiCore->getApmtFeatures($appmt);
				  if(is_array($featuresarr)){
              ?>
            <table cellpadding="3" width="100%">
              <tr>
                <td nowrap="nowrap" width="23%"><strong>Temanho Imóvel:</strong></td>
                <td width="27%"><?php if($featuresarr['appmt_size'] == ""){echo "NA";}else{ echo $featuresarr['appmt_size'];?>
                  &nbsp;M²
                  <?php } ?></td>
                <td nowrap="nowrap" width="23%"><strong>Área total:</strong></td>
                <td width="27%"><?php if($featuresarr['apptmt_lot_size'] == ""){echo "NA";}else{ echo $featuresarr['apptmt_lot_size']?>
                  &nbsp;M²
                  <?php } ?></td>
              </tr>
              <tr>
                <td><strong>Garagem:</strong></td>
                <td><?php if($featuresarr['car_garage'] == 0){ echo "NA"; }else{ echo $featuresarr['car_garage']; }?></td>
                <td><strong>Frente:</strong></td>
                <td><?php if($featuresarr['garage_size'] == ""){ echo "NA"; }else{ echo $featuresarr['garage_size'];?>
                  &nbsp;M²
                  <?php } ?></td>
              </tr>
              <tr>
                <td><strong>Quartos:</strong></td>
                <td><?php if($featuresarr['bedroom'] == 0){ echo "NA";}else{ echo $featuresarr['bedroom']; }?></td>
                <td><strong>Banheiros:</strong></td>
                <td><?php if($featuresarr['bathroom'] == 0){ echo "NA";}else{ echo $featuresarr['bathroom']; }?></td>
              </tr>
              <tr>
                <td><strong>Cômodos:</strong></td>
                <td><?php if($featuresarr['total_rooms'] == 0){ echo "NA";}else{ echo $featuresarr['total_rooms']; }?></td>
                <td><strong>Situação:</strong></td>
                <td><?php if($featuresarr['basement'] == 0){ echo "NA";}else{ echo $featuresarr['basement']; }?></td>
              </tr>
              <tr>
                <td><strong>Pavimentos:</strong></td>
                <td><?php if($featuresarr['floors'] == 0){ echo "NA";}else{ echo $featuresarr['floors']; }?></td>
                <td><strong>Condominio:</strong></td>
                <td>R$ <?php if($featuresarr['year_of_build'] == 0){ echo "NA";}else{ echo $featuresarr['year_of_build']; }?></td>
              </tr>
            </table>
            <?php
				}
				?>
            </p>
          </div>
          <div style="display: none;" id="tab2" class="tab_content">
            <p>
              <?php echo $bsiCore->getAmenities($appmt);?>
            </p>
          </div>
          <div style="display: none;" id="tab3" class="tab_content">
            <p>
              
            <?php
				if($apartmentDetails['apart_floor_img'] == ""){
				echo '<img src="images/no_floorplan.jpg" style="margin-top:5px; margin-bottom:5px;"/>';	
				}else{
					list($width)=getimagesize("gallery/ApartFloor/".$apartmentDetails['apart_floor_img']);
					if($width > 585){
						$str='width="585"';
					}else{
						$str='';
					}
			?>
              <img src="gallery/ApartFloor/<?php echo $apartmentDetails['apart_floor_img'];?>" style="margin-top:5px; margin-bottom:5px;" <?php echo $str;?>/>
               <?php } ?></p>
          </div>
          <div style="display: none;" id="tab4" class="tab_content"> 
            <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
            <script type="text/javascript" language="javascript">
    //<![CDATA[
    $(document).ready(function() {
    
      function initialize() {
        var myLatlng = new google.maps.LatLng(<?php echo $apartmentDetails['lat'];?>, <?php echo $apartmentDetails['longitude'];?>);
        var myOptions = {
          zoom: 14,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        
        var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        
        var contentString = '<div id="content">'+
            '<h6><?php echo $apartmentDetails['appmt_name']."<br> ".$apartmentDetails['addr1'].", ".$apartmentDetails['city'].", ".$apartmentDetails['country'];?></h6>';
    
        var infowindow = new google.maps.InfoWindow({
            content: contentString,
            maxWidth: 300
        });
    
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'Perth, Western Australia'
        });
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map,marker);
        });
    
      }
    	// Function added to help reset map and container boundaries
        $("#showMap").click(function() {
        $("#tab4").css({'display':'block'});
        $("#map_canvas").css({'width':'575px', 'height':'400px'});
        initialize();
        //alert('showMap Clicked!');
        });
        
     initialize(); 

    });
    //]]>
    </script>
            <div id="map_canvas" style="width:575px;height:400px;"></div>
          </div>
          
           <div style="display: none;" id="tab5" class="tab_content">
      <p>
      <table cellpadding="5" cellspacing="0" border="0" width="100%">
      <tr><th align="left">Duração do aluguel</th><th align="left">Alugado por <?php echo $bsiCore->showBooktype();?></th><th align="left">Porcentagem(%)</th></tr>
      <?php echo $bsiCore->getpriceplanfront($appmt); ?>
      </table>
      </p>
    </div>
        </div>
        <!-- End tab container --> 
        
      </div>
      <!-- ******************************************************************************************************* -->
      <div class="clr1"></div>
      <h1 class="col4">Fotos Imóveis</h1>
      <div id="appartment-media">
        <?php echo $bsiCore->getApartmentmedia($appmt);?>
      </div>
    </div>
    <div class="clr"></div>
    <div id="rightside-div">
      <h1 class="col2">Campo de pesquisa</h1>
      <div class="search-text"><strong>Início do aluguel:</strong>
        <?php echo $_SESSION['sv_checkindate'];?>
      </div>
      <div class="search-text"><strong>Término Aluguel:</strong>
        <?php echo $_SESSION['sv_checkoutdate'];?>
      </div>
      <div class="search-text"><strong>Total
        <?php echo $bsiCore->showBooktype();?>
        :</strong>
        <?php echo $stayduration;?>
      </div>
      <div class="clr1"></div>
      <h2 class="col5">Valor</h2>
      <div class="price-text">
        <?php echo $stayduration;?>
        x
        <?php echo $bsiCore->config['conf_currency_symbol'].number_format($array['priceperday'], 2);?>
        : <span>
        <?php echo $bsiCore->config['conf_currency_symbol'].number_format($array['appartmentPrice'], 2);?>
        </span></div>
      <?php
	    $_SESSION['dv_subtotal'] = $array['appartmentPrice'];
	   	if($bsiCore->config['conf_tax_amount'] > 0 && $bsiCore->config['conf_tax_amount'] < 100){
	   		$tax = ($array['appartmentPrice']*$bsiCore->config['conf_tax_amount']/100);
			$amount = $array['appartmentPrice']+$tax;
	   ?>
<!--      <div class="price-text bod">Imposto: <span>
        <?php echo $bsiCore->config['conf_currency_symbol'].number_format($tax, 2);?>
        </span></div>
      <?php
	    }else{
			$amount = $array['appartmentPrice'];
		}
	   ?>-->
      <div class="price-text">Total: <span>
        <?php echo $bsiCore->config['conf_currency_symbol'].number_format($amount, 2);?>
        </span></div>
      <?php
			$_SESSION['tax'] = $tax;
			$_SESSION['grandtotal'] = $amount;
	   		if($priceDetails['pricepercent'] > 0){
				$advancePayment = $amount*$priceDetails['pricepercent']/100;
				$_SESSION['deposit'] = $advancePayment;
				echo '<div class="price-text">Advance Payment: <span>'.$bsiCore->config['conf_currency_symbol'].number_format($advancePayment, 2).'</span></div>';	
			}
	   ?>
      <div class="clr1"></div>
      <?php
	   	if(!$bsiCore->config['conf_booking_turn_off']){
	   ?>
      <form action="booking-process.php" method="post" id="form1">
        <div class="booking">
          <h2 class="col6">Detalhes cliente</h2>
          <dl class="list-dl">
            <dt>
              <label for=sc1>Tutulo:</label>
            </dt>
            <dd>
              <select id="title" name="title" class="list-select">
                <option value="Mr">Sr</option>
                <option value="Mrs">Sra</option>
                <option value="Dr">Dr</option>
                <option value="Ms">Srta</option>
                <option value="Prof">Prof</option>
              </select>
            </dd>
            <dt>
              <label for=fn>Primeiro nome:</label>
            </dt>
            <dd>
              <input type="text" value="" id="first_name" name="first_name" class="list-input required"/>
            </dd>
            <dt>
              <label for=ln>Sobrenome:</label>
            </dt>
            <dd>
              <input type="text" value="" id="surname" name="surname" class="list-input required"/>
            </dd>
            <dt>
              <label for=ad1>Endereço 1:</label>
            </dt>
            <dd>
              <input type="text" value="" id="street_addr" name="street_addr" class="list-input required"/>
            </dd>
            <dt>
              <label for=ad2>Endereço 2:</label>
            </dt>
            <dd>
              <input type="text" value="" id="street_addr2" name="street_addr2" class="list-input"/>
            </dd>
            <dt>
              <label for=ct>Cidade:</label>
            </dt>
            <dd>
              <input type="text" value="" id="city" name="city" class="list-input required"/>
            </dd>
            <dt>
              <label for=st>Estado:</label>
            </dt>
            <dd>
              <input type="text" value="" id="province" name="province" class="list-input required"/>
            </dd>
            <dt>
              <label for=pc>CEP:</label>
            </dt>
            <dd>
              <input type="text" value="" id="zip" name="zip" class="list-input required"/>
            </dd>
            <dt>
              <label for=co>País:</label>
            </dt>
            <dd>
              <input type="text" value="" id="country" name="country" class="list-input required"/>
            </dd>
            <dt>
              <label for=ph>Telefone:</label>
            </dt>
            <dd>
              <input type="text" value="" id="phone" name="phone" class="list-input required"/>
            </dd>
            <dt>
              <label for=em>E-mail:</label>
            </dt>
            <dd>
              <input type="text" value="" id="email" name="email" class="list-input required email"/>
              <input type="hidden" name="ip" id="ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>" />
            </dd>
            <dt>
              <label for=sc2>Método de pagamento:</label>
            </dt>
            <dd>
              <select id="payment_gateway" name="payment_gateway" class="list-select required">
                <?php 
		  	$result = mysql_query("select * from bsi_payment_gateway where enabled=true");
		  	while($row = mysql_fetch_assoc($result)){
				 echo '<option value="'.$row['gateway_code'].'">'.$row['gateway_name'].'</option>';
		    }
		  ?>
              </select>
            </dd>
            <dt></dt>
            <dd>
              <input name="submit" value="Alugar" type="submit" class="btn2" />
            </dd>
            <div class="clr1"></div>
          </dl>
        </div>
      </form>
      <?php
		}else{
			echo '<div><h4 style="color:#F30; padding-top:20px;">Aluguel indisponível no momento. Tente novamente mais tarde.</h4></div>';		
		}
	   ?>
    </div>
    <div class="clr"></div>
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>
<div class="clr"></div>
<style>
 input.error {

    border-color: #D00;

    color: #D00;

    background: #FFFFFE;

}



label.error {

    display: inline-block;

    font-size: 12px;

    color: #D00;

    padding-left: 10px;

    font-style: italic;
	

}


 </style>
<script type="text/javascript">
	$().ready(function() {
		$("#form1").validate();
     });     
</script> 
<script src="cp/js/jquery.validate.js" type="text/javascript"></script>
<?php include("footer.php"); ?>
</body>
</html>
