<?php
ob_start();
include ("./inc/db.php");
include ("./inc/func.php");
function current_host(){
		$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
		$url = str_replace($scriptname, '', $url);
		return $url;
		}

if (!LoggedIn()){
	$credentials = @json_decode(@file_get_contents('php://input'));
	if(isset($credentials->username, $credentials->password)){
	if("$credentials->username" == "$adminusername" AND "$credentials->password" == "$adminpassword"){
		setcookie('auth_token', md5($credentials->username.$credentials->password), time()+864000);
		echo Success(1, "Login Successful");
		header('Location: ' . current_host() . "manager.php");
		die();
		
	}
	
}
else { 



echo <<<EOLY
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style_login.css">

    <link href="https://fonts.googleapis.com/css?family=Exo&display=swap" rel="stylesheet">

    <title>Log In</title>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <h1>CloudyGram</h1>
            
            <form class="form">
                <input type="text" placeholder="Username">
                <input type="password" placeholder="Password">
                <button type="submit" id="login-button">Login</button>
            </form>
        </div>
        
        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</body>
</html>

EOLY;

 }

}





else{

	header('Location: ' . current_host() . "manager.php" );
	echo Success(1, "Login Successful");
	die;
	
	}
?>