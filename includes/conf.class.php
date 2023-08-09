<?php
$bsiCore = new bsiHotelCore();
class bsiHotelCore{
	public $config = array();
	public $userDateFormat = "";		
	
	function bsiHotelCore(){		
		$this->getBSIConfig();
		$this->getUserDateFormat();		
	}	
	
	private function getBSIConfig(){
		$sql = mysql_query("SELECT conf_id, IFNULL(conf_key, false) AS conf_key, IFNULL(conf_value,false) AS conf_value FROM bsi_configure");
		while($currentRow = mysql_fetch_assoc($sql)){
			if($currentRow["conf_key"]){
				if($currentRow["conf_value"]){
					$this->config[trim($currentRow["conf_key"])] = trim($currentRow["conf_value"]);
				}else{
					$this->config[trim($currentRow["conf_key"])] = false;
				}
			}
		}
		mysql_free_result($sql);	
	}
	
	private function getUserDateFormat(){		
		$dtformatter = array('dd'=>'%d', 'mm'=>'%m', 'yyyy'=>'%Y', 'yy'=>'%Y');		
		$dtformat = preg_split("@[/.-]@", $this->config['conf_dateformat']);
		$dtseparator = ($dtformat[0] === 'yyyy')? substr($this->config['conf_dateformat'], 4, 1) : substr($this->config['conf_dateformat'], 2, 1);
		$this->userDateFormat = $dtformatter[$dtformat[0]].$dtseparator.$dtformatter[$dtformat[1]].$dtseparator.$dtformatter[$dtformat[2]];	
	}	
	
	public function getMySqlDate($date){
		if($date == "") return "";
		$dateformatter = preg_split("@[/.-]@", $this->config['conf_dateformat']);
		$date_part = preg_split("@[/.-]@", $date);		
		$date_array = array();		
		for($i=0; $i<3; $i++) {
			$date_array[$dateformatter[$i]] = $date_part[$i];
		}
		return $date_array['yy']."-".$date_array['mm']."-".$date_array['dd'];
	}	
	
	public function ClearInput($dirty){
		$dirty = mysql_real_escape_string($dirty);
		return $dirty;
	}	
	
	public function sqlApartmenttype($id=0){
		if($id){
			$subquery = " where id=".$id;
		}else{
			$subquery = "";
		}
		$sql = "select * from  bsi_apartment_type".$subquery;
		return $sql;
	}
	
	public function sqlApartment($id=0){
		if($id){
			$subquery = " where appmt_id=".$id;
		}else{
			$subquery = "";
		}
		$sql = "select * from  bsi_apartment_master".$subquery;
		return $sql;
	}
	
	public function getApartmentTypename($id){
		$row = mysql_fetch_assoc(mysql_query("select appartment_type from bsi_apartment_type where id=".$id));
		return $row['appartment_type'];
	}
	
	public function getApartmentname($id){
		$row = mysql_fetch_assoc(mysql_query("select appmt_name from bsi_apartment_master  where status=true and appmt_id=".$id));
		return $row['appmt_name'];
	}
	
	public function getApmtTypeCombobox($id=0){
		$apmtType = '<option value="">-- Todos tipos --</option>';
		$result = mysql_query($this->sqlApartmenttype());
		if(mysql_num_rows($result)){
			while($row = mysql_fetch_assoc($result)){
				if($id == $row['id']){
					$apmtType .= '<option value="'.$row['id'].'" selected="selected">'.$row['appartment_type'].'</option>';
				}else{
					$apmtType .= '<option value="'.$row['id'].'">'.$row['appartment_type'].'</option>';
				}
			}
		}
		return $apmtType;
	}
	
	public function getApmtFeatures($id){
		$row = mysql_fetch_assoc(mysql_query("select * from  bsi_appmt_features where appmt_id=".$id));
		return $row;	
	}
	
	public function getMaximumbed(){
		$result = mysql_query("SELECT max(`year_of_build`) as maxbed FROM `bsi_appmt_features`");
		if(mysql_num_rows($result)){
			$row = mysql_fetch_assoc($result);
			$maxbed = $row['maxbed'];
			$html = '<select name="maxbed" id="maxbed" class="home-select"><option value="0"> # year_of_builds&nbsp;</option>';
			for($i=1; $i<=$maxbed; $i++){
				$html .= '<option value="'.$i.'"> '.$i.' Bed</option>';	
			}
			$html .= '</select>';
		}else{
			$html = 'No Bed Found';
		}
		return $html;
	}
	
	public function getMaximumbath(){
		$result = mysql_query("SELECT max(`bathroom`) as maxbath FROM `bsi_appmt_features`");
		if(mysql_num_rows($result)){
			$row = mysql_fetch_assoc($result);
			$maxbath = $row['maxbath'];
			$html = '<select name="maxbath" id="maxbath" class="home-select"><option value="0"> # Bathrooms</option>';
			for($i=1; $i<=$maxbath; $i++){
				$html .= '<option value="'.$i.'"> '.$i.' Bath</option>';	
			}
			$html .= '</select>';
		}else{
			$html = 'No Bathroom Found';
		}
		return $html;
	}
	
	public function getApartmentdetails($id){
		$row = mysql_fetch_assoc(mysql_query("select * from  bsi_apartment_master  where status=true and appmt_id=".$id));
		return $row;
	}
	
	public function getPaymentGateway($gateway_code){
		$row = mysql_fetch_assoc(mysql_query("select gateway_name from  bsi_payment_gateway where gateway_code='".$gateway_code."'"));
		return $row['gateway_name'];
	}
	
	public function getApartmentPrice($appmt_id, $staycount){
		$nightCount=$staycount;
		$price = 0;
		$priceArr = array();
		$priceResult = mysql_query("SELECT * FROM bsi_priceplan WHERE appmt_id=".$appmt_id." AND (".$nightCount." BETWEEN stay_from AND stay_to)");
		if(mysql_num_rows($priceResult)){
			$row = mysql_fetch_assoc($priceResult);
			$price = $row['price']*$nightCount;
			$priceArr['totalprice'] = $price;
			$priceArr['priceperday'] = $row['price'];
			$priceArr['pricepercent'] = $row['deposit_percent'];
		}else{
			$row = mysql_fetch_assoc(mysql_query("SELECT * FROM bsi_priceplan WHERE appmt_id=".$appmt_id." and default_pp='1'"));
			$price = $row['price']*$nightCount;
			$priceArr['totalprice'] = $price;
			$priceArr['priceperday'] = $row['price'];
			$priceArr['pricepercent'] = $row['deposit_percent'];
		}	
		return $priceArr;
	}
	
	public function getTopTenApartmentName(){
		  $apartresult = mysql_query("select `appmt_name`, appmt_id from bsi_apartment_master where status=true order by `appmt_id` DESC limit 7");
		  $getlist='<ul id="top-appart">';
		  if(mysql_num_rows($apartresult)){
			   while($row=mysql_fetch_assoc($apartresult)){
				 $getlist.='<li><a href="'.str_replace(" ","-",strtolower(trim($row['appmt_name']))).'-'.$row['appmt_id'].'.html">'.$row['appmt_name'].'</a></li>';  
				   
			   }
			  
		  }else{
			  $getlist.='<li>No Apartment Found!</li>';
		  }
		   $getlist.='</ul>';
		   
		   return $getlist;
	}
	
	public function paymentGateway($code){
		$row = mysql_fetch_assoc(mysql_query("SELECT gateway_name FROM bsi_payment_gateway where gateway_code='".$code."'"));
		return  $row['gateway_name'];
	}
	
	public function getTopApartmentPhoto(){
		$apartphotoresult = mysql_query("select * from bsi_apartment_master  where status=true order by RAND() limit 0,4");
		  $getphotolist='<ul id="top-photo">';
		  if(mysql_num_rows($apartphotoresult)){
			  $i=1;
			   while($row=mysql_fetch_assoc($apartphotoresult)){
				   if($i%2 == 0){
					   $getphotolist.='<li class="margin-left"><a href="'.str_replace(" ","-",strtolower(trim($row['appmt_name']))).'-'.$row['appmt_id'].'.html"><img src="'.(($row['default_img']=="")? "images/no_photo2.jpg" : "gallery/ApartImage/thumb_".$row['default_img']).'" alt=""></a></li>';  
				   }else{
					    $getphotolist.=' <li><a href="'.str_replace(" ","-",strtolower(trim($row['appmt_name']))).'-'.$row['appmt_id'].'.html"><img src="'.(($row['default_img']=="")? "images/no_photo2.jpg" : "gallery/ApartImage/thumb_".$row['default_img']).'" alt=""></a></li>'; 
				   }
				 
				   $i++;
			   }
			  
		  }else{
			  $getphotolist.='<li style="font-family: \'MavenProRegular\';">No Property Found</li>';
		  }
		   $getphotolist.='</ul>';
		   
		   return $getphotolist;
	}
	
	public function showAllApartImage(){
		$imageresult=mysql_query("select * from bsi_apartment_master where status=true");
		$imagelist='<ul>';
		if(mysql_num_rows($imageresult)){
			while($row=mysql_fetch_assoc($imageresult)){
				$imagelist.='<li><a href=\''.str_replace(" ","-",strtolower(trim($row['appmt_name']))).'-'.$row['appmt_id'].'.html\'><img src="'.(($row['default_img']=="")? "images/no_appmt_photo.jpg" : "gallery/ApartImage/".$row['default_img']).'" alt="" width="600" height="350" title="'.$row['appmt_name'].'"    /></a></li>';
			}
		}else{
			$imagelist.='<li><img src="images/no_photo.jpg" alt="" title="No Property Found" /></li>';
		}
		$imagelist.='</ul>';
		return $imagelist;
 	}
 
 	public function getNewListing(){
	 	global $bsiCore; 
		$masterres=mysql_query("select baf.year_of_build,baf.bathroom, min(bp.price) as price,bam.appmt_name,bam.addr1,bam.state,bam.zipcode,bam.default_img, bam.appmt_id  from bsi_apartment_master bam, bsi_priceplan bp, bsi_appmt_features baf where bp.appmt_id=bam.appmt_id and bp.appmt_id=baf.appmt_id and bam.status=true group by bp.appmt_id order by bp.appmt_id desc limit 10");
 		if(mysql_num_rows($masterres)){
			$apartdetails='<ul>';
			while($row=mysql_fetch_assoc($masterres)){
				$apartdetails.='<li> <a href="'.str_replace(" ","-",strtolower(trim($row['appmt_name']))).'-'.$row['appmt_id'].'.html"><img src="'.(($row['default_img']=="")? "images/no_photo2.jpg" : "gallery/ApartImage/thumb_".$row['default_img']).'" alt="" title="" /></a> <label>'.$row['addr1'].','.$row['state'].' '.$row['zipcode'].'</label><span>TIPO: '.$row['year_of_build'].'<br /><span style="background-color:#E1E1E1; padding:1px;">Valor '.$this->config['conf_currency_symbol'].$row['price'].'</span> </li>';  
			}
		}else{
			$apartdetails='Não encontrado';
		}
		$apartdetails.='</ul>';
  		return $apartdetails;
 	}
 public function getAmenities($appmtid){
	        global $bsiCore;
			$amenitiesstr='<table cellpadding="3">';
			$result=mysql_query("select facilities from bsi_all_facilities as baf,bsi_appmt_facilities as bpf where bpf.facility_id=baf.id and bpf.appmt_id=".$appmtid);
			if(mysql_num_rows($result)){
					$i=1;
					while($row=mysql_fetch_assoc($result)){
						if($i%2==0){
						     $amenitiesstr.='<td>'.$row['facilities'].'</td></tr>';
						}else{
						      $amenitiesstr.='<tr><td>'.$row['facilities'].'</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td>';
						}
						$i++;
					}
				if($i%2==0){
					$amenitiesstr.='<td></td></tr>'; 
				}
			}else{
				$amenitiesstr.='<tr><td><h2>Não encontrado para este imóvel.</h2></td></tr>';
			}
			$amenitiesstr.='</table>';
			return $amenitiesstr;
	 
 }
 
 public function getApartmentmedia($appmtid){
	global $bsiCore; 
	$medialist='<ul>';
	$mediagalleryres=mysql_query("select img_path from bsi_appmt_gallery where appmt_id=".$appmtid);
	if(mysql_num_rows($mediagalleryres)){
		while($row=mysql_fetch_assoc($mediagalleryres)){
				$medialist.=' <li><a href="gallery/ApartImage/'.$row['img_path'].'"><img src="gallery/ApartImage/thumb_'.$row['img_path'].'" alt=""></a></li>';
		} 
	}else{
		$medialist.='<h2>Sem imagens</h2>';	
	}
	$medialist.='</ul>';
	return $medialist;
	 
 }
 
 public function getContact(){
	 global $bsiCore;
	 $bsiMail      = new bsiMail();
	 $name = mysql_real_escape_string($_POST['name']);
	 $email = mysql_real_escape_string($_POST['email']);
	 $subject = mysql_real_escape_string($_POST['subject']);
	 $message = mysql_real_escape_string($_POST['message']);
	 $contactemail=$bsiCore->config['conf_apartment_email'];
	 $emailBody    = "Hi<br/><br/>".$message."<br/><br/>Reagrds,<br/>Name : ".$name."<br/>Email : ".$email;
	 $sucess            = 'Successfully message send';
	 $error             = 'Error message';
	 $returnMsg = $bsiMail->sendEMail($contactemail, $subject, $emailBody);
	 if($returnMsg){
		 return $sucess;
	 }else{
		  return $error;
	}
	
 }
 
 	public function showBooktype(){
		global $bsiCore;
		$rental_type='';
		if($bsiCore->config['conf_rental_type'] == 1){
			 $rental_type='Night';
		}else if($bsiCore->config['conf_rental_type'] == 2){
			 $rental_type='Week';
		}else{
			$rental_type='Month';
		}
		return $rental_type;
	}
	
	public function phpDateformat($format) {
		$dt_format_array = array("mm/dd/yy" => "m/d/Y", "dd/mm/yy" => "d/m/Y", "mm-dd-yy" => "m-d-Y", "dd-mm-yy" => "d-m-Y", "mm.dd.yy" => "m.d.Y", "dd.mm.yy" => "d.m.Y", "yy-mm-dd" => "Y-m-d");	
		return $dt_format_array[$format];
	}
 
	public function get_days_in_month($checking_month_year, $nom){
	   $myArr = explode(" ",$checking_month_year);
	   $m = $myArr[0];
	   $year = $myArr[1];
	   $month_array = array("Janeiro" => "01", "Fevereiro" => "02", "Março" => "03", "Abril" => "04", "Maio" => "05", "Junho" => "06", "Julho" => "07", "Agosto" => "08", "Setembro" => "09", "Outubro" => "10", "Novembro" => "11", "Dezembro" => "12");	
	   $month1 = $month_array[$m];
	   $checkin_date = $year."-".$month1."-01";
	   
	   $tmpVar = strtotime($checkin_date);
	   $tmpVar = strtotime("+".$nom." month", $tmpVar);
	   $tmpVar = strtotime("-1 day", $tmpVar);
	   $checkout_date = date('Y-m-d', $tmpVar);
	   $checkin_date_display  = date($this->phpDateformat($this->config['conf_dateformat']), strtotime($checkin_date));
	   $checkout_date_display = date($this->phpDateformat($this->config['conf_dateformat']), strtotime($checkout_date));   
	   return array($checkin_date_display, $checkout_date_display, $checkin_date, $checkout_date);
	}
	
	public function getDateRangeArray($startDate, $endDate) { 
  		$date_arr = array();  
  		$time_from = mktime(1,0,0,substr($startDate,5,2), substr($startDate,8,2),substr($startDate,0,4));
  		$time_to = mktime(1,0,0,substr($endDate,5,2), substr($endDate,8,2),substr($endDate,0,4));  
  		if($time_to >= $time_from) {  
   			while ($time_to >=  $time_from) {      
    			array_push($date_arr, date('Y-m-d',$time_from));
    			$time_from+= 86400; // add 24 hours
   			}
  		}  
    	return $date_arr;
 	}
	
	public function getBookingdate($id){
		$daterange = '';
		if($this->config['conf_rental_type'] == 1){
			$result = mysql_query("SELECT checkin_date, DATE_SUB(checkout_date, INTERVAL 1 DAY) as checkout_date FROM `bsi_bookings` WHERE `appmt_id`=$id");	
		}else{
			$result = mysql_query("SELECT checkin_date, checkout_date FROM `bsi_bookings` WHERE `appmt_id`=$id");	
		}		
		if(mysql_num_rows($result)){
			while($row = mysql_fetch_assoc($result)){
				$daterangeArr = $this->getDateRangeArray($row['checkin_date'], $row['checkout_date']);
				foreach($daterangeArr as $value){
					$daterange.='"'.$value.'",';
				}	
			}
		}
		return $daterange;
	}
	
	public function generateWeekMonthdd(){
		$dd="";
		for($i=$this->config['conf_minimum_stay']; $i <= $this->config['conf_maximum_stay']; $i++){
			$dd.='<option value="'.$i.'">'.$i.' '.$this->showBooktype().'</option>';
		}
		return $dd;
	}
	public function generateWeekMonthddadmin(){
		$dd="";
		for($i=1; $i <= $this->config['conf_maximum_stay']; $i++){
			$dd.='<option value="'.$i.'">'.$i.' '.$this->showBooktype().'</option>';
		}
		return $dd;
	}
	
	public function getpriceplanfront($appmt_id){
		$sql=mysql_query("select * from bsi_priceplan where appmt_id=".$appmt_id." order by stay_from");
		$td="";
		while($row=mysql_fetch_assoc($sql)){
			if($row['default_pp']==1){
			$td.='<tr><td>Valor mensal</td><td>'.$this->config['conf_currency_symbol'].$row['price'].' '.$this->config['conf_currency_code'].'</td><td>'.$row['deposit_percent'].'%</td></tr>';
			}else{
			$td.='<tr><td>'.$row['stay_from'].' '.$this->showBooktype().'(s) To '.$row['stay_to'].' '.$this->showBooktype().'(s)</td><td>'.$this->config['conf_currency_symbol'].$row['price'].' '.$this->config['conf_currency_code'].'</td><td>'.$row['deposit_percent'].'%</td></tr>';
			}
			
		}
		return $td;
	}
	
	public function clearExpiredBookings(){		
		$sql = mysql_query("SELECT booking_id FROM bsi_bookings WHERE payment_success = false AND ((NOW() - booking_time) > ".intval($this->config['conf_booking_exptime'])." )");
		while($currentRow = mysql_fetch_assoc($sql)){			
			mysql_query("DELETE FROM bsi_invoice WHERE booking_id = '".$currentRow["booking_id"]."'");
			mysql_query("DELETE FROM bsi_bookings WHERE booking_id = '".$currentRow["booking_id"]."'");			
		}
		mysql_free_result($sql);
	}
	
	public function loadPaymentGateways() {			
		$paymentGateways = array();
		$sql = mysql_query("SELECT * FROM bsi_payment_gateway where enabled=true");
		while($currentRow = mysql_fetch_assoc($sql)){	
			$paymentGateways[$currentRow["gateway_code"]] = array('name'=>$currentRow["gateway_name"], 'account'=>$currentRow["account"]);	 
		}
		mysql_free_result($sql);
		return $paymentGateways;
	}

	public function insertpropertyDetails($appmt_id=0){
		$type          = $this->ClearInput($_POST['appmt_type_id']);
		$apart_name    = $this->ClearInput($_POST['appmt_name']);
		$address1      = $this->ClearInput($_POST['addr1']);
		$address2      = $this->ClearInput($_POST['addr2']);
		$city_name     = $this->ClearInput($_POST['acity']);
		$state         = $this->ClearInput($_POST['astate']);
		$zip_code      = $this->ClearInput($_POST['azipcode']);
		$country       = $this->ClearInput($_POST['acountry']);
		$phone_num     = $this->ClearInput($_POST['aphone']);
		$desc_short    = $this->ClearInput($_POST['short_desc']);
		$desc_long     = $this->ClearInput($_POST['long_desc']);
		$arent         = $this->ClearInput($_POST['arent']);
		if($this->config['conf_apppmt_listing_type']){
			$status    = 0;
		}else{
			$status    = $this->config['conf_appmt_auto_approve'];
		}
		//latitude & longitude
		$address       = "";
		$address      .= mysql_real_escape_string($_POST['addr2'])." ";
		$address      .= mysql_real_escape_string($_POST['addr2'])." ";
		$address      .= mysql_real_escape_string($_POST['acity'])." ";
		$address      .= mysql_real_escape_string($_POST['azipcode'])." ";
		$address      .= $country; 	
		$geocode       = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
		$output        = json_decode($geocode);			
		$latitude      = $output->results[0]->geometry->location->lat;
		$longitude     = $output->results[0]->geometry->location->lng;	
		//Apartment feature
		$appmt_size    = $this->ClearInput($_POST['appmt_size']) ? $this->ClearInput($_POST['appmt_size']) : '';
		$apptmtLotsize = $this->ClearInput($_POST['apptmt_lot_size']) ? $this->ClearInput($_POST['apptmt_lot_size']) : '';
		$year_of_build       = $this->ClearInput($_POST['year_of_build']) ? $this->ClearInput($_POST['year_of_build']) : '';
		$bathroom      = $this->ClearInput($_POST['bathroom']) ? $this->ClearInput($_POST['bathroom']) : '';
		$car_garage    = $this->ClearInput($_POST['car_garage']) ? $this->ClearInput($_POST['car_garage']) : '';
		$garage_size   = $this->ClearInput($_POST['garage_size']) ? $this->ClearInput($_POST['garage_size']) : '';
		$total_rooms   = $this->ClearInput($_POST['total_rooms']) ? $this->ClearInput($_POST['total_rooms']) : '';
		$basement      = $this->ClearInput($_POST['basement']) ? $this->ClearInput($_POST['basement']) : '';
		$floors        = $this->ClearInput($_POST['floors']) ? $this->ClearInput($_POST['floors']) : '';
		$year_of_build = $this->ClearInput($_POST['year_of_build']) ? $this->ClearInput($_POST['year_of_build']) : '';
		
		$facilityArr   = array();
		if(isset($_POST['facility'])){
			$facilityArr = $_POST['facility'];
		}
		
		if($appmt_id){
			
			$appmtid = $appmt_id;
			
			if(isset($_POST['deldefault'])){
				if($_POST['pre_img'] != ""){
					unlink("gallery/ApartImage/".$_POST['pre_img']);
					unlink("gallery/ApartImage/thumb_".$_POST['pre_img']);
					unset($_POST['pre_img']);
				}
				mysql_query("UPDATE bsi_apartment_master SET default_img='' WHERE appmt_id=".$appmt_id);
			}
			
			if(isset($_POST['delfloor'])){
				if($_POST['pre_floor_img'] != ""){
					unlink("gallery/ApartFloor/".$_POST['pre_floor_img']);
					unset($_POST['pre_floor_img']);
				}
				mysql_query("UPDATE bsi_apartment_master SET apart_floor_img='' WHERE appmt_id=".$appmt_id);
			}
			
			$enable_thumbnails	= 1;
			$max_image_size		= 1024000 ;
			$upload_dir			= "gallery/ApartImage/";
			$upload_dir2		= "gallery/ApartFloor/";
			$img_rname = "";
			foreach($_FILES as $k => $v){ 	
				$img_type = "";
				if( !$_FILES[$k]['error'] && preg_match("#^image/#i", $_FILES[$k]['type']) && $_FILES[$k]['size'] < $max_image_size ){
					$img_type = ($_FILES[$k]['type'] == "image/jpeg") ? ".jpg" : $img_type ;
					$img_type = ($_FILES[$k]['type'] == "image/gif") ? ".gif" : $img_type ;
					$img_type = ($_FILES[$k]['type'] == "image/png") ? ".png" : $img_type ;
					$img_rname = time().'_'.$_FILES[$k]['name']; 
					$img_path = $upload_dir.$img_rname;
					$img_path2 = $upload_dir2.$img_rname;
					if(!empty($_POST['pre_img']) && $k == 'default_img'){ 
						unlink("gallery/ApartImage/".$_POST['pre_img']);
						unlink("gallery/ApartImage/thumb_".$_POST['pre_img']); 	
					}
					if(!empty($_POST['pre_floor_img']) && $k=='apart_floor_img'){ 
						unlink("gallery/ApartFloor/".$_POST['pre_floor_img']);						
					}  
					if($k != 'apart_floor_img'){
						copy($_FILES[$k]['tmp_name'], $img_path );
						if($enable_thumbnails) $this->make_thumbnails($upload_dir, $img_rname, 120, 120);
						mysql_query("UPDATE bsi_apartment_master SET default_img='".$img_rname."' WHERE appmt_id =".$appmt_id);
					}else{
						copy($_FILES[$k]['tmp_name'], $img_path2 );
						mysql_query("UPDATE bsi_apartment_master SET apart_floor_img='".$img_rname."' WHERE appmt_id =".$appmt_id);
					}
				}	
			}
			
			mysql_query("UPDATE bsi_apartment_master SET appmt_type_id = '".$type."', appmt_name = '".$apart_name."', addr1 = '".$address1."', addr2 = '".$address2."', city = '".$city_name."', state = '".$state."', country = '".$country."', zipcode = '".$zip_code."', phone = '".$phone_num."', short_desc = '".$desc_short."', long_desc = '".$desc_long."', lat = '".$latitude."', longitude = '".$longitude."' WHERE appmt_id =".$appmt_id);
			
			mysql_query("update `bsi_priceplan` set `price`='".$arent."' where `appmt_id`=".$appmt_id);
			
			mysql_query("delete from bsi_appmt_facilities where appmt_id=".$appmt_id);
			
			mysql_query("update bsi_appmt_features set appmt_size='".$appmt_size."', apptmt_lot_size='".$apptmtLotsize."', year_of_build=".$year_of_build.", bathroom=".$bathroom.", car_garage=".$car_garage.", garage_size='".$garage_size."', total_rooms=".$total_rooms.", basement='".$basement."', floors=".$floors.", year_of_build=".$year_of_build." where appmt_id=$appmt_id");
			
		}else{
			if(!isset($_SESSION['apmt_password'])){	
					
				mysql_query("insert into bsi_clients(first_name, surname, street_addr, street_addr2, city, province, zip, country, phone, email, password, user_type, ip) values ('".$this->ClearInput($_POST['first_name'])."', '".$this->ClearInput($_POST['surname'])."', '".$this->ClearInput($_POST['street_addr'])."', '".$this->ClearInput($_POST['street_addr2'])."', '".$this->ClearInput($_POST['city'])."', '".$this->ClearInput($_POST['province'])."', '".$this->ClearInput($_POST['zip'])."', '".$this->ClearInput($_POST['country'])."', '".$this->ClearInput($_POST['phone'])."', '".$this->ClearInput($_POST['email'])."', '".md5($this->ClearInput($_POST['password']))."', '2', '".$this->ClearInput($_POST['ip'])."')");
				
				$clientid = mysql_insert_id();
				
				$bsiMail = new bsiMail();
				$emailBody  = "Hi, ".$this->ClearInput($_POST['first_name'])." ".$this->ClearInput($_POST['surname']).",<br>";
				$emailBody .= "Thank you for register with us.<br>Detalhes da sua conta:<br><br>Login email: ".$this->ClearInput($_POST['email'])."<br>Password: ".$this->ClearInput($_POST['password']);				
				$emailBody .= "<br><br>Regards,<br>".$bsiCore->config['conf_apartment_name'].'<br>'.$bsiCore->config['conf_apartment_phone'];				
				$bsiMail->sendEMail($_POST['email'], "New Account Registration", $emailBody);
							
			}else{
				
				$clientid = $_SESSION['apmt_clientid'];
				
			}
			
			mysql_query("insert into  bsi_apartment_master (`appmt_type_id`, `appmt_name`, `addr1`, `addr2`, `city`, `state`, `country`, `zipcode`, `phone`, `short_desc`, `long_desc`, `default_img`, `apart_floor_img`, `lat`, `longitude`, `status`) values ('".$type."', '".$apart_name."', '".$address1."', '".$address2."', '".$city_name."', '".$state."', '".$country."', '".$zip_code."', '".$phone_num."', '".$desc_short."', '".$desc_long."', '', '', '".$latitude."', '".$longitude."', '".$status."')");
			$appmtid = mysql_insert_id();
			$_SESSION['apmt_appmtid'] = $appmtid;
			$_SESSION['apmt_appmtname'] = $apart_name;
			
			mysql_query("insert into  bsi_appmt_features (appmt_id, appmt_size, apptmt_lot_size, year_of_build, bathroom, car_garage, garage_size, total_rooms, basement, floors, year_of_build) values (".$appmtid.", '".$appmt_size."', '".$apptmtLotsize."', '".$year_of_build."', '".$bathroom."', '".$car_garage."', '".$garage_size."', '".$total_rooms."', '".$basement."', '".$floors."', '".$year_of_build."')");
				
			mysql_query("INSERT INTO `bsi_priceplan` (`appmt_id`, `price`, `stay_from`, `stay_to`, `deposit_percent`, `default_pp`)
VALUES ($appmtid, '$arent', '', '', '0', '1')");

			mysql_query("insert into bsi_appmt_user values($appmtid, $clientid)");
		
		}
		if(!empty($facilityArr)){
			foreach($facilityArr as $key => $facilityid){
				mysql_query("insert into bsi_appmt_facilities (`appmt_id`, `facility_id`) values (".$appmtid.", ".$facilityid.")");
			}	
		}
	}
	
	private function make_thumbnails($updir, $img, $w, $h){
		$thumbnail_width	= $w;
		$thumbnail_height	= $h;
		$thumb_preword		= "thumb_";
		$arr_image_details	= GetImageSize("$updir"."$img");
		$original_width		= $arr_image_details[0];
		$original_height	= $arr_image_details[1];
		if($original_width > $original_height ){
			$new_width	= $thumbnail_width;
			$new_height	= intval($original_height*$new_width/$original_width);
		} else {
			$new_height	= $thumbnail_height;
			$new_width	= intval($original_width*$new_height/$original_height);
		}
		$dest_x = intval(($thumbnail_width - $new_width) / 2);
		$dest_y = intval(($thumbnail_height - $new_height) / 2);
		if($arr_image_details[2]==1) { $imgt = "ImageGIF"; $imgcreatefrom = "ImageCreateFromGIF";  }
		if($arr_image_details[2]==2) { $imgt = "ImageJPEG"; $imgcreatefrom = "ImageCreateFromJPEG";  }
		if($arr_image_details[2]==3) { $imgt = "ImagePNG"; $imgcreatefrom = "ImageCreateFromPNG";  }
		if( $imgt ) { 
			$old_image	= $imgcreatefrom("$updir"."$img");
			$new_image	= imagecreatetruecolor($thumbnail_width, $thumbnail_height);
			imageCopyResized($new_image,$old_image,0, 0,0,0,$w,$h,$original_width,$original_height);
			$imgt($new_image,"$updir"."$thumb_preword"."$img");
		}
	}
	
	public function getuserlistingSql($uid){
		$row = mysql_fetch_assoc(mysql_query("select count(*) as counter from bsi_appmt_user where user_id=".$uid));
		return $row['counter'];
	}
	
	public function getApmtcombo($apmt=0){
		$html = '<option value="">--- Selecione o imóvel --</option>';
		$result = mysql_query("select * from bsi_appmt_user where user_id=".$_SESSION['apmt_clientid']);		
		if(mysql_num_rows($result)){
			while($row = mysql_fetch_assoc($result)){
				$rowAp = mysql_fetch_assoc(mysql_query("SELECT appmt_id, appmt_name FROM `bsi_apartment_master` WHERE status=true and `appmt_id`=".$row['appmt_id']));
				if($apmt == $rowAp['appmt_id']){
					$html .= '<option value="'.$rowAp['appmt_id'].'" selected="selected">'.$rowAp['appmt_name'].'</option>';
				}else{
					$html .= '<option value="'.$rowAp['appmt_id'].'">'.$rowAp['appmt_name'].'</option>'; 
				}
			}
		}
		return $html;
	}
	
	public function getCitycombo($city=""){
		$html = '<option value="">-- Cidades --</option>';
		$result = mysql_query("SELECT `city` FROM `bsi_apartment_master` WHERE status=true group by city order by city");		
		if(mysql_num_rows($result)){
			while($row = mysql_fetch_assoc($result)){				
					if($row['city']==$city)
					$html .= '<option value="'.$row['city'].'" selected="selected">'.$row['city'].'</option>';
					else
					$html .= '<option value="'.$row['city'].'" >'.$row['city'].'</option>';				
			}
		}
		return $html;
	}
	
	
	
	public function addgallery(){
		global $bsiCore;
		$enable_thumbnails	= 1 ;
		$max_image_size		= 1024000 ;
		$upload_dir			= "gallery/ApartImage/";
		$img_rname = "";
		foreach($_FILES as $k => $v){ 	
			$img_type = "";
			if( !$_FILES[$k]['error'] && preg_match("#^image/#i", $_FILES[$k]['type']) && $_FILES[$k]['size'] < $max_image_size ){
				$img_type = ($_FILES[$k]['type'] == "image/jpeg") ? ".jpg" : $img_type ;
				$img_type = ($_FILES[$k]['type'] == "image/gif") ? ".gif" : $img_type ;
				$img_type = ($_FILES[$k]['type'] == "image/png") ? ".png" : $img_type ;
				$img_rname = time().'_'.$_FILES[$k]['name']; 
				$img_path = $upload_dir.$img_rname;
				copy($_FILES[$k]['tmp_name'], $img_path); 
				if($enable_thumbnails) $this->make_thumbnails($upload_dir, $img_rname, 120, 120);
				mysql_query("insert into bsi_appmt_gallery (appmt_id, img_path) values (".$bsiCore->ClearInput($_POST['apartmentid']).", '".$img_rname."')");
			} 
		}
		$_SESSION['apartmentid'] = $bsiCore->ClearInput($_POST['apartmentid']);
	}
		
	public function deletegallery(){
		global $bsiCore;
		unlink("gallery/ApartImage/".$bsiCore->ClearInput($_GET['delimg']));	
		unlink("gallery/ApartImage/thumb_".$bsiCore->ClearInput($_GET['delimg']));	
		mysql_query("delete from bsi_appmt_gallery where appmt_id=".$bsiCore->ClearInput($_GET['appmtid'])." and img_path='".$bsiCore->ClearInput($_GET['delimg'])."'");
		$_SESSION['apartmentid'] = $bsiCore->ClearInput($_GET['appmtid']);
	}
	
	public function getCustomerHtml($userid){
		$html = '';
		$result = mysql_query("select bau.appmt_id, bc.* from bsi_appmt_user bau, bsi_bookings bb, bsi_clients bc where bau.user_id= $userid and bb.appmt_id=bau.appmt_id and bc.client_id=bb.client_id group by bb.client_id");
		while($row = mysql_fetch_assoc($result)){
			$html .= '<tr><td width="30%" nowrap="nowrap">'.$row['title']." ".$row['first_name']." ".$row['surname'].'</td><td width="10%">'.$row['phone'].'</td><td width="40%">'.$row['email'].'</td><td width="20%" align="right"><a href="my-clientBooking.php?client='.base64_encode($row['client_id']).'">View Bookings</a></td></tr>';
		}
		return $html;
	}
	
	public function fetchClientBookingDetails($clientid){
		global $bsiCore;
		$arr=array();
		$clienname='';
		$html = '<tbody>';
		$res = mysql_query("SELECT booking_id, DATE_FORMAT(checkin_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(checkout_date, '".$bsiCore->userDateFormat."') AS end_date, checkout_date as checkout, total_cost, DATE_FORMAT(booking_time, '".$bsiCore->userDateFormat."') AS booking_time, payment_type, is_deleted, is_block  FROM bsi_bookings where  payment_success=true and client_id=".$clientid);
		
		$client_info = mysql_fetch_assoc(mysql_query("select * from bsi_clients where client_id=".$clientid));
		$clienname   = $client_info['title']." ". $client_info['first_name']." ".$client_info['surname'];
		
      	while($row =  mysql_fetch_assoc($res)){
			if($row['checkout'] >= date('Y-m-d') && $row['is_deleted'] == 0 && $row['is_block'] == 0){
				$status = '<font color="#00CC00"><b>Active</b></font>';	
			}elseif($row['checkout'] < date('Y-m-d') && $row['is_deleted'] == 0 && $row['is_block'] == 0){
				$status = '<font color="#0033FF"><b>Completed</b></font>';	
			}else{
				$status = '<font color="#FF0000"><b>Cancelled</b></font>';
			}
			  $html .= '<tr class="gradeX">
				<td align="right">'.$row['booking_id'].'</td>
				<td align="right">'.$row['start_date'].'</td>
				<td align="right">'.$row['end_date'].'</td>
				<td align="right">'.$bsiCore->config['conf_currency_symbol'].$row['total_cost'].'</td>
				<td align="right">'.$status.'</td>
				<td align="right" nowrap="nowrap"><a href="viewdetails.php?booking_id='.$row['booking_id'].'&client='.base64_encode($clientid).'" class="bodytext">View Details</a></td>
			  </tr>';
       }
	   $html .= '</tbody>';	
	   $arr[0]=$html;
	   $arr[1]=$clienname;
	   return $arr;
	}
	
	public function getnoofMybookings($userid){
		$result = mysql_query("select count(*) as counter from bsi_appmt_user bau, bsi_bookings bb where bb.appmt_id=bau.appmt_id and bau.user_id=$userid");	
		if(mysql_num_rows($result)){
			$row = mysql_fetch_assoc($result);
			return $row['counter'];	
		}else{
			return 0;	
		}
	}
	
	public function getnoofMyActivebookings($userid){
		$result = mysql_query("select count(*) as counter from bsi_appmt_user bau, bsi_bookings bb where bb.appmt_id=bau.appmt_id and bb.payment_success=1 and bb.checkout_date>=curdate() and bau.user_id=$userid and bb.is_deleted=false and bb.is_block=false");	
		if(mysql_num_rows($result)){
			$row = mysql_fetch_assoc($result);
			return $row['counter'];	
		}else{
			return 0;	
		}
	}
	
	public function getnoofMyArchievebookings($userid){
		$result = mysql_query("select count(*) as counter from bsi_appmt_user bau, bsi_bookings bb where bb.appmt_id=bau.appmt_id and bau.user_id=$userid and (bb.checkout_date<curdate() or bb.is_deleted=1) and bb.is_block=0");	
		if(mysql_num_rows($result)){
			$row = mysql_fetch_assoc($result);
			return $row['counter'];	
		}else{
			return 0;	
		}
	}
		
	public function getnoofMyCustomer($userid){
		$result = mysql_query("select distinct bc.client_id from bsi_appmt_user bau, bsi_bookings bb, bsi_clients bc where bau.user_id=7 and bb.appmt_id=bau.appmt_id and bc.client_id=bb.client_id group by bc.client_id");	
		$i = 0;
		if(mysql_num_rows($result)){
			while($row = mysql_fetch_assoc($result)){
				$i++;	
			}
		}
		return $i;	
	}
	
	public function showlatestBooking($userid){
			
	}
}
?>