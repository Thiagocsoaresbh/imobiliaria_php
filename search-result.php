<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
$pos2 = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
if(!$pos2){
	header('Location: booking-failure.php?error_code=9');
}
$bsiCore->clearExpiredBookings();
include("includes/search.class.php");
$bsiSearch     = new bsiSearch();
$appArr        = $bsiSearch->getAvailableAppartment();	
if(isset($_SESSION['svars_details']))
$appartmentArr = $_SESSION['svars_details'];
//echo "<pre>";print_r($appartmentArr);echo "</pre>";

if($bsiCore->config['conf_rental_type'] == 1){
	$stayduration=$bsiSearch->nightCount." Night(s)";;
}else if($bsiCore->config['conf_rental_type'] == 2){
	$stayduration= mysql_real_escape_string($_POST['weeks'])." Week(s)";
}else if($bsiCore->config['conf_rental_type'] == 3){
	$stayduration= mysql_real_escape_string($_POST['months'])." Month(s)";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<title><?php echo $bsiCore->config['conf_apartment_name'];?></title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/pagination.css" rel="stylesheet" type="text/css" />
<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" />
<link href="css/canvas.css" rel="stylesheet" type="text/css" title="canvas" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/menu/superfish.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	}); 
</script>
</head>

<body>
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
      <h1 class="col2">Search Result</h1>
      <span style="float:left; font-family: 'MavenProRegular';"><?php echo $appArr['appmt_cn'];?> apartment found!</span> <span style="float:right; font-family: 'MavenProRegular';">Sorted By: <?php echo (mysql_real_escape_string($_POST['sorting'])=='asc')? 'price low to high':'price high to low' ;?></span>
      <hr style="width:100%; height:1px; float:left;"/>
      <div id="paginationdemo">
        <?php
	if($appArr['roomcnt'] > 0){
		$m1=1;
		$appt_cn=0;
		$page_cn=1;
		echo '<div id="p'.$page_cn.'" class=" _current">';
		foreach($appartmentArr as $key => $appartmentAr){
			$apartmentDetails = $bsiCore->getApartmentdetails($key);
			$apartmentFeature = $bsiCore->getApmtFeatures($key);
			if($appt_cn==5){
				$appt_cn=0;
				$page_cn++;
				echo '</div><div id="p'.$page_cn.'"  style="display:none;">';
			}
			echo '<style>			
					.overlay'.$key.' {
						background-color: rgba(0, 0, 0, 0.6);
						bottom: 0;
						cursor: default;
						left: 0;
						opacity: 0;
						position: fixed;
						right: 0;
						top: 0;
						visibility: hidden;
						z-index: 1;
					
						-webkit-transition: opacity .5s;
						-moz-transition: opacity .5s;
						-ms-transition: opacity .5s;
						-o-transition: opacity .5s;
						transition: opacity .5s;
					}
					.overlay'.$key.':target {
						visibility: visible;
						opacity: 1;
					}
					.popup'.$key.' {
						background-color: #fff;
						border: 3px solid #fff;
						display: inline-block;
						left: 50%;
						opacity: 0;
						padding: 15px;
						position: fixed;
						text-align: justify;
						top: 40%;
						visibility: hidden;
						z-index: 100000;
					
						-webkit-transform: translate(-50%, -50%);
						-moz-transform: translate(-50%, -50%);
						-ms-transform: translate(-50%, -50%);
						-o-transform: translate(-50%, -50%);
						transform: translate(-50%, -50%);
					
						-webkit-border-radius: 10px;
						-moz-border-radius: 10px;
						-ms-border-radius: 10px;
						-o-border-radius: 10px;
						border-radius: 10px;
					
						-webkit-box-shadow: 0 1px 1px 2px rgba(0, 0, 0, 0.4) inset;
						-moz-box-shadow: 0 1px 1px 2px rgba(0, 0, 0, 0.4) inset;
						-ms-box-shadow: 0 1px 1px 2px rgba(0, 0, 0, 0.4) inset;
						-o-box-shadow: 0 1px 1px 2px rgba(0, 0, 0, 0.4) inset;
						box-shadow: 0 1px 1px 2px rgba(0, 0, 0, 0.4) inset;
					
						-webkit-transition: opacity .5s, top .5s;
						-moz-transition: opacity .5s, top .5s;
						-ms-transition: opacity .5s, top .5s;
						-o-transition: opacity .5s, top .5s;
						transition: opacity .5s, top .5s;
					}
					.overlay'.$key.':target+.popup'.$key.' {
						top: 50%;
						opacity: 1;
						visibility: visible;
					}
					.close'.$key.' {
						background-color: rgba(0, 0, 0, 0.8);
						height: 30px;
						line-height: 30px;
						position: absolute;
						right: 0;
						text-align: center;
						text-decoration: none;
						top: -15px;
						width: 30px;
					
						-webkit-border-radius: 15px;
						-moz-border-radius: 15px;
						-ms-border-radius: 15px;
						-o-border-radius: 15px;
						border-radius: 15px;
					}
					.close'.$key.':before {
						color: rgba(255, 255, 255, 0.9);
						content: "X";
						font-size: 24px;
						text-shadow: 0 -1px rgba(0, 0, 0, 0.9);
					}
					.close'.$key.':hover {
						background-color: rgba(64, 128, 128, 0.8);
					}
					.popup'.$key.' p, .popup'.$key.' div {
						margin-bottom: 10px;
					}
				</style>';
	?>
    	<script type="text/javascript" language="javascript">
		//<![CDATA[
		$(document).ready(function() {
		
		  function initialize() {
			var myLatlng = new google.maps.LatLng(<?php echo $apartmentDetails['lat']; ?>, <?php echo $apartmentDetails['longitude']; ?>);
			var myOptions = {
			  zoom: 12,
			  center: myLatlng,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			
			var map = new google.maps.Map(document.getElementById("map_canvas_<?php echo $key; ?>"), myOptions);
			
			var infowindow = new google.maps.InfoWindow({
				maxWidth: 280
			});
		
			var marker = new google.maps.Marker({
				position: myLatlng,
				map: map,
				title: '<?php echo $apartmentDetails['appmt_name']; ?>'
			});
			google.maps.event.addListener(marker, 'click', function() {
			  infowindow.open(map,marker);
			});
		
		  }
			// Function added to help reset map and container boundaries
			$("#apartment_paginate").click(function() {
			$("#map_canvas_<?php echo $key; ?>").css({'width':'750px', 'height':'550px'});
			initialize();
			//alert('showMap Clicked!');
		  });
			
		 initialize(); 
	
		});
		//]]>
    </script>
        <div class="list-div">
          <div class="list-text">
            <h2><a href="apartment-details.php?appmt_id=<?php echo base64_encode($key);?>"><?php echo $apartmentDetails['appmt_name'];?></a></h2>
            <label><?php echo $apartmentDetails['addr1']." , ".$apartmentDetails['city'].", ".$apartmentDetails['state']." - ".$apartmentDetails['zipcode'].", ".$apartmentDetails['country'];?></label>
            <p><?php echo $apartmentDetails['short_desc'];?></p>
            <label><?php echo $apartmentFeature['bedroom'];?> Bed, <?php echo $apartmentFeature['bathroom'];?> Bath </label>
            <p class="link"><a href="apartment-details.php?appmt_id=<?php echo base64_encode($key); ?>"><?php echo $bsiCore->config['conf_currency_symbol'].$appartmentAr['appartmentPrice']." for ".$stayduration;?></a></p>
          </div>
          <div class="list-image"> <a href="apartment-details.php?appmt_id=<?php echo base64_encode($key);?>"><img src="<?php echo ($apartmentDetails['default_img']=="")? "images/no_photo2.jpg":"gallery/ApartImage/".$apartmentDetails['default_img']; ?>" alt="" height="180px" width="260px"></a>
            <div align="center"><a href="#login_form<?php echo $key; ?>" style="font-weight:bold;font-family: 'MavenProRegular';" id="login_pop">View Map</a> <a href="#x" class="overlay<?php echo $key; ?>" id="login_form<?php echo $key; ?>"></a>
              <div class="popup<?php echo $key; ?>"><div id="map_canvas_<?php echo $key; ?>" style="width:750px;height:550px;"></div> <a class="close<?php echo $key; ?>" href="#close<?php echo $key; ?>"></a> </div>
            </div>
          </div>
        </div>
        <?php
	  $m1++;
	  $appt_cn++;
		}
	}else{
		$page_cn=1;
		echo '<div class="list-div"><h2>Sorry! No Result found. Please try another date!</h2></div>';
	}
	
	if($appArr['roomcnt'] > 0){
		echo '</div>';
		$pagination='<div class="listing"><div id="apartment_paginate"></div></div>';
	}else{
		$pagination="";
	}
	?>
      </div>
      <?php echo $pagination;?> </div>
    <div class="clr"></div>
    <div id="rightside-div">
      <h1 class="col2">Search Input</h1>
      <div class="search-text"><strong>Check In Date:</strong> <?php echo $_SESSION['sv_checkindate'];?> </div>
      <div class="search-text"><strong>Check Out Date:</strong> <?php echo $bsiSearch->checkOutDate;?> </div>
      <div class="search-text"><strong>Total <?php echo $bsiCore->showBooktype();?>:</strong> <?php echo $stayduration;?> </div>
      <div class="clr1"></div>
      <div id="top-appartment" style="float:left; margin:10px 0 10px 0;">
        <h1 class="col2">TOP Appartment</h1>
        <?php echo $bsiCore->getTopTenApartmentName();?> </div>
      <div class="clr1"></div>
      <h1 class="col2">TOP APARTMENT <span class="col3">PHOTO</span></h1>
      <?php echo $bsiCore->getTopApartmentPhoto();?> </div>
    <div class="clr"></div>
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>
<div class="clr"></div>
<script src="js/jquery.paginate.js" type="text/javascript"></script> 
<script type="text/javascript">
		$(function() {
	
			$("#apartment_paginate").paginate({
				count 		: <?php echo $page_cn;?>,
				start 		: 1,
				display     : 3,
				border					: true,
				border_color			: '#BEF8B8',
				text_color  			: '#68BA64',
				background_color    	: '#E3F2E1',	
				border_hover_color		: '#68BA64',
				text_hover_color  		: 'black',
				background_hover_color	: '#CAE6C6', 
				rotate      : false,
				images		: false,
				mouse		: 'press',
				onChange     			: function(page){
											$('._current','#paginationdemo').removeClass('_current').hide();
											$('#p'+page).addClass('_current').show();
										  }
			});
		});
		</script>
<?php include("footer.php"); ?>
</body>
</html>
body>
</html>
