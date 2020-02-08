<?php
ob_start();
include("./inc/func.php");
include("./inc/db.php");
$credentials = @json_decode(@file_get_contents('php://input'));

if (LoggedIn()){
	
	header('Location: /manager.php');
	echo Success(1, "Login Successful");
	die;
	
}

elseif(isset($credentials->username, $credentials->password)){
	if("$credentials->username" == "$adminusername" AND "$credentials->password" == "$adminpassword"){
		setcookie('auth_token', md5($credentials->username.$credentials->password), time()+864000);
		header('Location: /manager.php');
		echo Success(1, "Login Successful");
		
	}
	else{
		echo Success(0, "Wrong Password");
	}
}

else
{
	header('Location: /login.html');
	
}


?>