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
		
		if (@$_COOKIE['auth_token'] == md5($adminusername.$adminpassword))
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
	
	
	
	function RandomCapitals($data){
		$i = 0;
		while($i < strlen($data)){
			if(rand(1, 100) % 2 == 0){
				$converted .= strtoupper($data{$i++});
			}
			else{
				$converted .= $data{$i++};
			}
		}
		
		return $converted;
		
	}
	
	
	
	
	
	function FileExtension($data){
				$c = explode(".", $data);
				if(end($c) == $data){
				return null;	
				}
			
			else
			{
				$v = explode(".", $data);
				return end($v);
				
				}
	
	}
	
	

	
	
	
	function make_thumb($src, $dest, $desired_width = "256") {
	global $new_file_extension;
	if($new_file_extension == "jpg" OR $new_file_extension == "jpeg"){
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	$desired_height = floor($height * ($desired_width / $width));
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	imagejpeg($virtual_image, $dest);
	}
	else {
		
		$source_image = imagecreatefrompng($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	$desired_height = floor($height * ($desired_width / $width));
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	imagepng($virtual_image, $dest);
		
		
	}
}





	function formatSize($bytes) {

    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }

    elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }

    elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }


    elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    }

    else {
       // $bytes = '0 byte';
    }

    return $bytes;
}

?>