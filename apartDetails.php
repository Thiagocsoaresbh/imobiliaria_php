<?php
include("includes/db.conn.php");
include("includes/conf.class.php");
if(isset($_GET['id']))
$appmt = mysql_real_escape_string($_GET['id']);
else
header("location:all-apartment.php");
$apartDetails = $bsiCore->getApartmentdetails($appmt);
$dateRange = $bsiCore->getBookingdate($appmt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
 <title>
 <?php echo $bsiCore->config['conf_apartment_name']." :: ".$apartDetails['appmt_name'];?>
 </title>
 <link href="css/style.css" rel="stylesheet" type="text/css" />
 <link href="css/tab.css" rel="stylesheet" type="text/css" />
 <link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
 <link href="css/tab.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
 <script type="text/javascript" src="js/slider/coin-slider.js"></script>
 <script type="text/javascript" src="js/tab.js"></script>
 <script type="text/javascript">
	$(document).ready(function() {
		$('#coin-slider').coinslider({ hoverPause: false });
	});
</script>
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
<link rel="stylesheet" type="text/css" href="css/custom-theme/jquery-ui-1.8.22.custom.css" />
 <script type="text/javascript" src="js/jquery-ui.min.js"></script>
 <script type="text/javascript">
    $(function() {
      	var unavailableDates = [<?php echo substr($dateRange, 0, -1);?>]; // yyyy/MM/dd
		
		function unavailable(date) {
			ymd = date.getFullYear() + "-" + ("0"+(date.getMonth()+1)).slice(-2) + "-" + ("0"+date.getDate()).slice(-2);
			day = new Date(ymd).getDay();
			if ($.inArray(ymd, unavailableDates) < 0 ) {
				return [true, "enabled", "Available"];
			} else {
				return [false,"disabled","Booked Out"];
			}
		}

		$('#iDate').datepicker({ beforeShowDay: unavailable, minDate:0 });
    });
 </script>
 <!-- ****************************************************************************************************************** -->
 <style type="text/css">
.disabled span {
	color:black !important;
	background:red !important;
}
.ui-datepicker {
	width: 17em;
	padding: .2em .2em 0;
	display: none;
	font-size:14px;
}
</style>
 <!-- ****************************************************************************************************************** -->
 
 <!-- **************************************************Tab**************************************************** -->
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

 <body  style="font-family: 'MavenProRegular';" >
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
        
       
      <?php echo $apartDetails['appmt_name'];?>
      </h1>
      <p class="listing-para">
        <?php echo $apartDetails['addr1']." , ".$apartDetails['city'].", ".$apartDetails['state']." - ".$apartDetails['zipcode'].", ".$apartDetails['country'];?>
      </p>
      <?php 
	  	if($apartDetails['default_img'] == ""){
			echo '<div id="listing-large-image-div"> <img src="images/no_appmt_photo.jpg" alt="" width="600px" height="340px"/> </div>';
		}else{
	  ?>
      <div id="listing-large-image-div"> <img src="gallery/ApartImage/<?php echo $apartDetails['default_img'];?>" alt="" width="600px" height="340px"/> </div>
      <?php } ?>
      <p class="listing-para2">
        <?php echo $apartDetails['long_desc'];?>
      </p>
      <br/>
       <!--TABS-->
       <div style="width:620px;">
  <ul class="tabs">
    <li class="active"><a href="#tab1">Características</a></li>
    <li><a href="#tab2">Extras</a></li>
    <li><a href="#tab3">Planta do Imóvel</a></li>
    <li><a href="#tab4"  id="showMap">Localização</a></li>
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
                <td nowrap="nowrap" width="23%"><strong>Tamanho:</strong></td>
                <td width="27%"><?php if($featuresarr['appmt_size'] == ""){echo "NA";}else{ echo $featuresarr['appmt_size'];?>
                  &nbsp;<?php echo $bsiCore->config['conf_mesurment_unit'];?>
                  <?php } ?></td>
                <td nowrap="nowrap" width="23%"><strong>Área Total:</strong></td>
                <td width="27%"><?php if($featuresarr['apptmt_lot_size'] == ""){echo "NA";}else{ echo $featuresarr['apptmt_lot_size'];?>
                  &nbsp;<?php echo $bsiCore->config['conf_mesurment_unit'];?>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><strong>Garagem:</strong></td>
                <td><?php if($featuresarr['car_garage'] == 0){ echo "NA"; }else{ echo $featuresarr['car_garage']; }?></td>
                <td><strong>Suites:</strong></td>
                <td><?php if($featuresarr['garage_size'] == ""){ echo "NA"; }else{ echo $featuresarr['garage_size'];?>
                  &nbsp;<?php echo $bsiCore->config['conf_mesurment_unit'];?>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><strong>Quartos:</strong></td>
                <td><?php if($featuresarr['bedroom'] == 0){ echo "NA";}else{ echo $featuresarr['bedroom']; }?></td>
                <td><strong>Banheiros:</strong></td>
                <td><?php if($featuresarr['bathroom'] == 0){ echo "NA";}else{ echo $featuresarr['bathroom']; }?></td>
              </tr>
              <tr>
                <td><strong>Total cômodos:</strong></td>
                <td><?php if($featuresarr['total_rooms'] == 0){ echo "NA";}else{ echo $featuresarr['total_rooms']; }?></td>
                <td><strong>Condomínio:</strong></td>
                <td><?php if($featuresarr['basement'] == 0){ echo "NA";}else{ echo $featuresarr['basement']; }?></td>
              </tr>
              <tr>
                <td><strong>Andares:</strong></td>
                <td><?php if($featuresarr['floors'] == 0){ echo "NA";}else{ echo $featuresarr['floors']; }?></td>
                <td><strong>Tipo:</strong></td>
                <td><?php if($featuresarr['year_of_build'] == 0){ echo "NA";}else{ echo $featuresarr['year_of_build']; }?></td>
              </tr>
            </table>
            <?php
				}
				?>
            </p>
    </div>
    
    
     <div style="display: none;" id="tab2" class="tab_content">
      <p><?php echo $bsiCore->getAmenities($appmt);?></p>
    </div>
    
    
     <div style="display: none;" id="tab3" class="tab_content">
                  
              <p>
              
            <?php
				if($apartDetails['apart_floor_img'] == ""){
				echo '<img src="images/no_floorplan.jpg" style="margin-top:5px; margin-bottom:5px;"/>';	
				}else{
					list($width)=getimagesize("gallery/ApartFloor/".$apartDetails['apart_floor_img']);
					if($width > 585){
						$str='width="585"';
					}else{
						$str='';
					}
			?>
              <img src="gallery/ApartFloor/<?php echo $apartDetails['apart_floor_img'];?>" style="margin-top:5px; margin-bottom:5px;" <?php echo $str;?>/>
               <?php } ?></p>
    </div>
    <div style="display: none;" id="tab4" class="tab_content">
     
      <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
      <script type="text/javascript" language="javascript">
    //<![CDATA[
    $(document).ready(function() {
    
      function initialize() {
        var myLatlng = new google.maps.LatLng(<?php echo $apartDetails['lat'];?>, <?php echo $apartDetails['longitude'];?>);
        var myOptions = {
          zoom: 14,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        
        var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        
        var contentString = '<div id="content">'+
            '<h6><?php echo $apartDetails['appmt_name']."<br> ".$apartDetails['addr1'].", ".$apartDetails['city'].", ".$apartDetails['country'];?></h6>';
    
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
    
  
  </div>
  
  <!-- End tab container --> 
  
</div>
       <div class="clr1"></div>
       <h1 class="col4">Fotos</h1>
       <div id="appartment-media">
         <?php echo $bsiCore->getApartmentmedia($appmt);?>
       </div>
       
     </div>
     <div class="clr"></div>
     <div id="rightside-div">
       <div class="clr1"></div>
       <div id="top-appartment" style="float:left; margin:30px 0 30px 0;">
         <h1 class="col2">Imóveis em Destaque</h1>
      <?php echo $bsiCore->getTopTenApartmentName();?>
       </div>
       <div class="clr1"></div>
       <h1 class="col2">Destaque <span class="col3">Fotos</span></h1>
      
        <?php echo $bsiCore->getTopApartmentPhoto();?>
      
     </div>
   </div>
   <div class="clr"></div>
 </div>
 <div class="clr"></div>
 <script type="text/javascript">
	$().ready(function() {
		$("#form1").validate();
     });     
</script> 
 <script src="cp/js/jquery.validate.js" type="text/javascript"></script>
 <?php include("footer.php"); ?>
</body>
</html>
