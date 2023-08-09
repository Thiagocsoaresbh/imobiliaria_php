<?php
	session_start();
	if(!isset($_SESSION['apmt_password'])){
		header('location:index.php');	
	}
?>