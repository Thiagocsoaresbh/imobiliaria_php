<?php
class adminAjaxProcessor
{
	public $errorMsg;
	public function sendErrorMsg(){		
		$this->errorMsg = "unknown error";	
		echo json_encode(array("errorcode"=>99,"strmsg"=>$this->errorMsg));
	}	
	
	public function getbsiEmailcontent(){
		global $bsiCore;
		$errorcode = 0;
		$strmsg = "";
		$getArray = array();
		$choiceid = $bsiCore->ClearInput($_POST['choiceid']);
		$result = mysql_query("select * from bsi_email_contents where id=".$choiceid);
		if(mysql_num_rows($result)){
			$getEmailcontentlist=mysql_fetch_assoc($result);
			echo json_encode(array("errorcode"=>$errorcode, "viewcontent"=>$getEmailcontentlist['email_subject'], "viewcontent1"=>$choiceid, "viewcontent2"=>$getEmailcontentlist['email_text']));
		}else{
			$errorcode = 1;
			$strmsg = "Sorry! no  result found ";
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));
		}
	}
	
	public function getbsiGallery(){
		global $bsiCore;
		$errorcode = 0;
		$strmsg = "";
		$getArray = array();
		$appmt = $bsiCore->ClearInput($_POST['appmt']);
		$result = mysql_query("select * from bsi_appmt_gallery where appmt_id=".$appmt);
		if(mysql_num_rows($result)){
			$html = '<ul>';
			while($row = mysql_fetch_assoc($result)){
				$html .= '<li><a rel="collection" href="../gallery/ApartImage/'.$row['img_path'].'"><img src="../gallery/ApartImage/thumb_'.$row['img_path'].'" width="150px" height="130px"/></a>
        <button onclick="javascript:deleteImage(\''.$row['img_path'].'\');" class="button"><span>Delete</span></button>
      </li>';
			}
    		$html .= '</ul> 
					<script type="text/javascript">	
					//FancyBox Config (more info can be found at http://www.fancybox.net/)
						$(".gallery ul li a").fancybox({
							\'overlayColor\':\'#000\' 		
						});
						$("a img.fancy").fancybox();
					</script>'; 
			echo json_encode(array("errorcode"=>$errorcode, "viewcontent"=>$html));
		}else{
			$errorcode = 1;
			$strmsg = "Sorry! no  result found ";
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));
		}
	}
	
	public function getPriceplan(){
		global $bsiCore;
		$errorcode = 0;
		$strmsg = "";
		$getArray = array();
		$appmt = $bsiCore->ClearInput($_POST['appmt']);
		$result = mysql_query("select * from bsi_priceplan where appmt_id=".$appmt);
		if(mysql_num_rows($result)){
			$html = '<table class="display datatable" border="0">
						<thead>
						  <tr>
							<th><strong>Tipo de plano</strong></th>
							<th><strong>Inicio('.$bsiCore->showBooktype().')</strong></th>
							<th><strong>Valor/'.$bsiCore->showBooktype().'</strong></th>
							<th><strong>Percentual(%)</strong></th>
							<th><strong>&nbsp;</strong></th>
						  </tr>
						</thead>
						<tbody>';
			while($row = mysql_fetch_assoc($result)){
				if($row['default_pp'] == 1){
					$html .= '<tr>
								<td>Default</td>
								<td>N/A - N/A</td>
								<td>'.$bsiCore->config['conf_currency_symbol'].$row['price'].'</td>
								<td>'.number_format($row['deposit_percent']).'%</td>
								<td align="right"><a href="add_edit_priceplan.php?id='.base64_encode($row['pp_id']).'"><strong>Edit</strong></a></td>
							  </tr>';
				}else{
					$html .= '<tr>
								<td nowrap="nowrap">Plano especial</td>
								<td>'.$row['stay_from']." to ".$row['stay_to'].'</td>
								<td>'.$bsiCore->config['conf_currency_symbol'].$row['price'].'</td>
								<td>'.number_format($row['deposit_percent']).'%</td>
								<td align="right"><a href="add_edit_priceplan.php?id='.base64_encode($row['pp_id']).'"><strong>Edit</strong></a> | <a href="priceplan.php?delid='.base64_encode($row['pp_id']).'"><strong>Delete</strong></a></td>
							  </tr>';
				}
			}
    		$html .= '	</tbody>
  					</table>
					<script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
					<script type="text/javascript" src="js/bsi_datatables.js"></script>
					<link href="css/data.table.css" rel="stylesheet" type="text/css" />
					<link href="css/jqueryui.css" rel="stylesheet" type="text/css" />';
			echo json_encode(array("errorcode"=>$errorcode, "viewcontent"=>$html));
		}else{
			$errorcode = 1;
			$strmsg = "Sorry! no  result found ";
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));
		}
	}
	
	public function getPricepermonth(){
		global $bsiCore;
		$errorcode = 0;
		$strmsg = "";
		$getArray = array();
		$amt = $bsiCore->ClearInput($_POST['amt']);
		$amt = $bsiCore->config['conf_currency_symbol'].number_format(($amt*30), 2)." / month";
		echo json_encode(array("errorcode"=>$errorcode, "viewcontent"=>$amt));
	}
	
	public function getBookingType(){
		global $bsiCore;
		$errorcode = 0;
		$booktypecom='<td valign="middle"><strong>Selecione o tipo de aluguel:</strong></td>
            <td><select name="book_type" id="book_type">
            <option value="0">---Selecionar tipo---</option><option value="1">Active Booking</option><option value="2">Historico do aluguel</option></select></td>';
		echo json_encode(array("errorcode"=>$errorcode, "viewcombo"=>$booktypecom));
		
	}
	
	public function getDaycomb(){
		
		  global $bsiCore;
		  $dayarr=array(0=>"Sun",1=>"Mon",2=>"Tue",3=>"Wed",4=>"Thu",5=>"Fri",6=>"Sat");
		  $errorcode = 0;
		  $daycomb='<td><strong>Select Checkin day:</strong></td><td><select name="checkin_day" id="checkin_day">';
		  foreach($dayarr as $key=>$value){
			  if($key == $bsiCore->config['conf_week_checkin_day']){
				  $daycomb.='<option value="'.$key.'" selected="selected">'.$value.'</option>';
				  
			  }else{
				  $daycomb.='<option value="'.$key.'">'.$value.'</option>';
				  
			  }
			  
		  }
		$daycomb.='</select></td>';
		
		echo json_encode(array("errorcode"=>$errorcode, "strhtml"=>$daycomb));
	}
	
}
?>