<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
$shorting2=array("asc"=>"Valor crescente", "desc"=>"Valor decrescente");
if(isset($_POST['submit'])){
	$condition1="";
	if($_POST['appartment_type']!=""){
		$condition1.=" and bam.appmt_type_id=".mysql_real_escape_string($_POST['appartment_type']);
	}
	if($_POST['city']!=""){
		$condition1.=" and bam.city='".mysql_real_escape_string(trim($_POST['city']))."'";
	}
	
	if($_POST['zipcode']!="cep"){
		$condition1.=" and bam.zipcode=".mysql_real_escape_string($_POST['zipcode']);
	}
	
	$condition2=mysql_real_escape_string($_POST['sorting']);
	$shorthing_option="";
	foreach($shorting2  as $key=>$val){
		if($key==$condition2)
		$shorthing_option.='<option value="'.$key.'" selected="selected">'.$val.'</option>';
		else
		$shorthing_option.='<option value="'.$key.'">'.$val.'</option>';
	}
	$getCitycombo=$bsiCore->getCitycombo(mysql_real_escape_string(trim($_POST['city'])));
	$getApmtTypeCombobox=$bsiCore->getApmtTypeCombobox(mysql_real_escape_string($_POST['appartment_type']));
	$zipcode=$_POST['zipcode'];
}else{
	$condition2="asc";
	$condition1="";
	$zipcode="cep";
	
	$shorthing_option="";
	foreach($shorting2  as $key=>$val){
		
		$shorthing_option.='<option value="'.$key.'">'.$val.'</option>';
	}
	$getCitycombo=$bsiCore->getCitycombo();
	$getApmtTypeCombobox=$bsiCore->getApmtTypeCombobox();
}
$allappmt_sql="select * from (select baf.bedroom,baf.bathroom, min(bp.price) as price,bam.appmt_name,bam.addr1,bam.state,bam.city, bam.zipcode, bam.country, bam.short_desc, bam.lat, bam.longitude, bam.default_img, bam.appmt_id  from bsi_apartment_master bam, bsi_priceplan bp, bsi_appmt_features baf where bp.appmt_id=bam.appmt_id and bp.appmt_id=baf.appmt_id and bam.status=true ".$condition1." group by bp.appmt_id) as t1 order by t1.price ".$condition2;
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
 <link href="css/pagination.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

 <script type="text/javascript" src="js/menu/superfish.js"></script>
 <script type="text/javascript">
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
		$("#allmap").hide();
		$("#mapshowhide").click(function () {
		$("#allmap").toggle(function(){
		$("#map").css({'width':'100%', 'height':'500px'});
        initMap();
		});
		});

	}); 
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
 <style type="text/css">
#map {
	width: 100%;
	height: 500px;
	border: 1px #CCC solid;
	padding: 0px;
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}
</style>
 <script type="text/javascript">
 //Sample code written by August Li
 var icon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/truck.png",
 new google.maps.Size(32, 32), new google.maps.Point(0, 0),
 new google.maps.Point(16, 32));
 var center = null;
 var map = null;
 var currentPopup;

  var bounds = new google.maps.LatLngBounds();
  var pt2;
  
<?php

 $sql4=mysql_query($allappmt_sql);
 $apartmentexist=mysql_num_rows($sql4);
 //$hotelexist=0;
  while ($row4 = mysql_fetch_assoc($sql4)){
	  if($row4['lat']!="" || $row4['longitude'] != ""){
?>
   var pt2 = new google.maps.LatLng(<?php echo $row4['lat'];?>, <?php echo $row4['longitude']; ?>);
   bounds.extend(pt2);	   
<?php }  }?>
//bounds.extend(bounds);
// alert (bounds);
 function addMarker(lat, lng, info, number_pos) {
 var pt = new google.maps.LatLng(lat, lng);
 <?php
 if($apartmentexist){
 }else{
	 echo "bounds.extend(pt);";
 }
?>
//bounds.extend(pt);

 var marker = new google.maps.Marker({
 position: pt,
 //icon: 'http://chart.apis.google.com/chart?chst=d_map_spin&chld=1|30|00FFFF|24|_|'+number_pos,
 icon: 'https://chart.googleapis.com/chart?chst=d_map_pin_letter_withshadow&chld='+number_pos+'|00FFFF|000000',
 map: map
 });
 var popup = new google.maps.InfoWindow({
 content: info,
 maxWidth: 150
 });
 google.maps.event.addListener(marker, "click", function() {
 if (currentPopup != null) {
 currentPopup.close();
 currentPopup = null;
 }
 popup.open(map, marker);
 currentPopup = popup;
 });



 google.maps.event.addListener(marker, "closeclick", function() {
 map.panTo(center);
 currentPopup = null;
 });


 }
 function initMap() {
 map = new google.maps.Map(document.getElementById("map"), {
 center: new google.maps.LatLng(0, 0),
 zoom: 14,
 mapTypeId: google.maps.MapTypeId.ROADMAP,
 mapTypeControl: false,
 mapTypeControlOptions: {
 style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
 },
 navigationControl: true,
 navigationControlOptions: {
 style: google.maps.NavigationControlStyle.LARGE
 }
 });
 <?php 
 if($apartmentexist){

 $sql=mysql_query($allappmt_sql);
 
  $point_total=mysql_num_rows($sql);
 $i=1;
	 while ($row = mysql_fetch_assoc($sql)){
	if($row['lat']!="" || $row['longitude'] != ""){
	 $address='<a href="'.str_replace(" ","-",strtolower(trim($row['appmt_name']))).'-'.$row['appmt_id'].'.html"><b>'.$row['appmt_name'].'</b></a><br/>'.$row['addr1'].'<br/>'.$row['city'].', '. $row['state'].'-'.$row['zipcode'].'<br><b>'.$bsiCore->config['conf_currency_symbol'].$row['price'].' per '.$bsiCore->showBooktype().'</b>';
	 $lat=$row['lat'];
	 $lon=$row['longitude'];
	 echo ("addMarker($lat, $lon,'$address','".$i."');\n");
	 $i++;
	 }
	 }
 }else{
	 $point_total=0;
 }
 ?>
 //center = bounds.getCenter();
 //map.fitBounds(bounds);
 //map.setZoom(3);
//alert (bounds);




<?php if ($point_total >1  ){ 
echo "map.fitBounds(bounds);";
?>

//alert(curZoom);
<?php
} else { ?>
  map.setCenter(bounds.getCenter());
  map.setZoom(14);
   
<?php } ?>

 }
//alert(map.getZoom());
 </script>
 </head>

 <body onload="initMap()" >
 <?php include("header.php"); ?>
 <div id="shadow">
   <div id="shadow-inside">
     <div id="shadow-left"></div>
     <div id="shadow-right"></div>
   </div>
 </div>
 <div id="container-div">
   <div id="container-inside">
     <h1 class="col2">Lista de Imóveis</h1>
     <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
     <table cellpadding="5" cellspacing="0" border="0"  align="left" width="100%">
     <tr><td> <select id="appartment_type" name="appartment_type" class="home-select">
              <?php echo $getApmtTypeCombobox;?>
            </select></td>
            <td>
             <select id="city" name="city" class="home-select">
             <?php echo $getCitycombo; ?>
             </select>
            </td><td>
            <select id="sorting" name="sorting" class="home-select">
              <?php echo  $shorthing_option; ?>
              
            </select>
            </td>
            <td><input type="text" name="zipcode" style="width:70px;"  class="home-input" onblur="if (this.value == '') {this.value = 'cep';}" onfocus="if(this.value == 'cep') {this.value = '';}" value="<?php echo $zipcode; ?>"  maxlength="10" /></td>
            <td>  <input name="submit" id="btn_appmt_search" value="BUSCA" type="submit" class="btn1" /></td>
            <td width="40%"> <span style="float:right; font-size:16px; font-family: 'MavenProRegular'; font-weight:bold;"><a href="#" id="mapshowhide">Mapa de imóveis</a></span></td>
            </tr>
     </table>
     </form>
    
      <hr style="width:100%; height:1px; float:left;"/>
<div class="list-div_all" id="allmap">
<div id="map"></div>
</div>
    <div id="paginationdemo">
    <div id="p1" class=" _current">   
    <?php
	$m1=1;
	$appt_cn=0;
	$page_cn=1;
	$allappmt_result=mysql_query($allappmt_sql);
	while($rowappt=mysql_fetch_assoc($allappmt_result)){
		if($appt_cn==5){
				$appt_cn=0;
				$page_cn++;
				echo '</div><div id="p'.$page_cn.'"  style="display:none;">';
			}
	?> 
    
     <div class="list-div_all">
      <div class="list-text">
       <h2><a href="<?php echo str_replace(" ","-",strtolower(trim($rowappt['appmt_name'])))."-".$rowappt['appmt_id'].".html"; ?>"><?php echo $rowappt['appmt_name']; ?></a></h2>
       <label><?php echo $rowappt['addr1']; ?> , <?php echo $rowappt['city']; ?>, <?php echo $rowappt['state']; ?> - <?php echo $rowappt['zipcode']; ?>, <?php echo $rowappt['country']; ?></label>
       <p><?php echo $rowappt['short_desc']; ?> </p>
       <label><?php echo $rowappt['bedroom']; ?> Quartos, <?php echo $rowappt['bathroom']; ?> Banheiros</label>
       <p class="link"><a href="<?php echo str_replace(" ","-",strtolower(trim($rowappt['appmt_name'])))."-".$rowappt['appmt_id'].".html"; ?>"><?php echo $bsiCore->config['conf_currency_symbol'].$rowappt['price']; ?> </a></p>
      </div>
      <div class="list-map"> 
      <!--*************************************************************************************-->
      <script type="text/javascript" language="javascript">
    //<![CDATA[
    $(document).ready(function() {
    
      function initialize() {
        var myLatlng = new google.maps.LatLng(<?php echo $rowappt['lat']; ?>, <?php echo $rowappt['longitude']; ?>);
        var myOptions = {
          zoom: 12,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        
        var map = new google.maps.Map(document.getElementById("map_canvas_<?php echo $rowappt['appmt_id']; ?>"), myOptions);
        
        
    
        var infowindow = new google.maps.InfoWindow({
            maxWidth: 280
        });
    
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: '<?php echo $rowappt['appmt_name']; ?>'
        });
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map,marker);
        });
    
      }
    	// Function added to help reset map and container boundaries
        $("#apartment_paginate").click(function() {
        $("#map_canvas_<?php echo $rowappt['appmt_id']; ?>").css({'width':'280px', 'height':'200px'});
        initialize();
        //alert('showMap Clicked!');
      });
        
     initialize(); 

    });
    //]]>
    </script>
      <div id="map_canvas_<?php echo $rowappt['appmt_id']; ?>" style="width:280px;height:200px;"></div>
      <!--***************************************************************************************-->
      
       </div>
       <div class="list-image"> <a href="<?php echo str_replace(" ","-",strtolower(trim($rowappt['appmt_name'])))."-".$rowappt['appmt_id'].".html"; ?>"><img src="<?php echo ($rowappt['default_img']=="")? "images/no_photo2.jpg":"gallery/ApartImage/".$rowappt['default_img']; ?>" alt="" height="180px" width="260px"></a> </div>
     </div>
     <?php 
	 $m1++;
	 $appt_cn++;
	 } ?>
   </div>
   </div>
   
    <div class="listing">
    <div id="apartment_paginate"></div>
   </div>
     
   </div>
   <div class="clr"></div>
 </div>
 <div class="clr"></div>
 <script src="js/jquery.paginate.js" type="text/javascript"></script>
		<script type="text/javascript">
		$(function() {
	
			$("#apartment_paginate").paginate({
				count 		: <?php echo $page_cn; ?>,
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
