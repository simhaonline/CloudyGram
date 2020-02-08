<?php

	ini_set('log_errors', 'On');
	ini_set('error_log', 'error.log');
	@ini_set('session.cookie_lifetime', 864000);
	function CleanStr($data)
	{
		return strip_tags(trim($data));
	}
	
	
	function Success($code, $message, $extraparameter="disabled", $extravalue="disabled")
    {
		if($extraparameter !== "disabled" AND $extravalue !== "disabled"){
		return '{"successCode":' . '"' . $code . '",'. '"response":' . '"' . $message . '"' . ',' . '"' . $extraparameter . '":' . '"' .  $extravalue . '"}';
		}
		else
		{
		return '{"successCode":' . '"' . $code . '"' . ',"response":'. '"' . $message. '"}';
		}
	}
	
	
	
	
	function LoggedIn()
	{
		global $adminusername, $adminpassword;
		
		if ($_COOKIE['auth_token'] == md5($adminusername.$adminpassword))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
		function Updates()
	{
		global $version;
		$version_check = @json_decode(@file_get_contents("https://raw.githubusercontent.com/ClodyGram/CloudyGram/master/VERSION"));
		if($version_check->version > $version)
		{
		return $version_check->improvements;
		}
		else
		{
		return false;
		}
	
	}
	

?>