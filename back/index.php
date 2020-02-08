<?php
ob_start();
include("./inc/func.php");
include("./inc/db.php");

//if(!file_exists("dbase.db")){   header("Location: /welcome.html")   }
if (LoggedIn()){
	
	header('Location: /manager.php');
	echo Success(1, "Login Successful");
	die;
}
else{
	header('Location: /login.html');
}




?>
