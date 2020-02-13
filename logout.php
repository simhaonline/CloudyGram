<?php
function current_host(){
		$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
		$url = str_replace($scriptname, '', $url);
		return $url;
		}
setcookie('auth_token', 'deleted');
header('Location: ' . current_host() . "login.php");

?>