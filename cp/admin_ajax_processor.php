<?php
include("../includes/db.conn.php");
include("../includes/conf.class.php");
include("../includes/admin.ajaxprocess.class.php");	 
$adminAjaxProc = new adminAjaxProcessor();
$actionCode = isset($_POST['actioncode']) ? $_POST['actioncode'] : 0;
switch($actionCode){
	case "1": $adminAjaxProc->getbsiEmailcontent(); break;
	case "2": $adminAjaxProc->getbsiGallery(); break;
	case "3": $adminAjaxProc->getPriceplan(); break;
	case "4": $adminAjaxProc->getPricepermonth(); break;
	case "5": $adminAjaxProc->getBookingType(); break;
	case "6": $adminAjaxProc->getDaycomb(); break;
	default:  $adminAjaxProc->sendErrorMsg();
}
?>