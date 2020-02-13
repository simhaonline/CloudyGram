<?php
ob_start();
include("./inc/func.php");
//include("./inc/db.php");

function current_host(){
	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
	$url = str_replace($scriptname, '', $url);
	return $url;
		}
		
	

	if(!file_exists("dbase.db")){
		header('Location: ' . current_host() . "configure.php"); die;
	}
	elseif(!LoggedIn()){
		header('Location: ' . current_host() . "login.php"); die;
	}
	else{
		header('Location: ' . current_host() . "manager.php"); die;
	}
	
	
	
	




?>

