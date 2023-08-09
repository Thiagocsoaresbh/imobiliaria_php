<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/ajaxprocess.class.php");	
include("includes/mail.class.php"); 
$ajaxProc = new AjaxProcessor();
$actionCode = isset($_POST['actioncode']) ? $_POST['actioncode'] : 0;
switch($actionCode){
	case "1": $ajaxProc->validateEmail(); break;
	case "2": $ajaxProc->validateLogin(); break;
	case "3": $ajaxProc->getselectedForm(); break;
	case "4": $ajaxProc->updateProfile(); break;
	case "5": $ajaxProc->updatePassword(); break;
	case "6": $ajaxProc->updateApartment(); break; 
	case "7": $ajaxProc->getbsiGallery(); break; 
	case "8": $ajaxProc->forgotPassword(); break; 
	case "9": $ajaxProc->getPriceplan(); break; 
	default:  $ajaxProc->sendErrorMsg();
}
?>