<?php
ob_start();
	if(file_exists("dbase.db")){   echo "Already configured"; die; }      
		//Dbase block
		@rename("./backup.htaccess", "./.htaccess");
		$parameters = @json_decode(@file_get_contents("php://input"));
		if(isset($parameters->username) AND isset($parameters->password) AND isset($parameters->chat) ){	echo "Missing POST parameters";	die;}
		$time = time();
		$db_init = new SQLite3("dbase.db");
		$sql_table_user = 'CREATE TABLE user(username TEXT, password TEXT, chat TEXT)';
		$sql_values = "INSERT INTO user (username, password, chat) VALUES ('$parameters->username', '$parameters->password', '$parameters->chat')";
		$sql_files = 'CREATE TABLE files(fileId INTEGER, folderId INTEGER, type TEXT, name TEXT, extension TEXT, filesize TEXT, date INTEGER, parentId INTEGER, count INTEGER, thumb TEXT)';
		$sql_files_values = "INSERT INTO files(folderId, name, date, type) VALUES ('0', '/', '$time', 'folder')";
		$sql_links = 'CREATE TABLE links(link TEXT, fileId TEXT, password TEXT, last TEXT)';
		$result_table_user = $db_init->exec($sql_table_user);
		$result_values_user = $db_init->exec($sql_values);
		$result_files = $db_init->exec($sql_files);
		$result_files_init = $db_init->exec($sql_files_values);
		$result_links = $db_init->exec($sql_links);
		
		if($result_table_user !== true OR $result_values_user !== true OR $result_files !== true OR $result_files_init !== true OR $result_files_init !== true OR $result_links !== true  ){
			echo $db_init->lastErrorMsg() . PHP_EOL;
			die("Something went wrong while initializing database");
		}
		else{

			copy('https://phar.madelineproto.xyz/madeline.php', './inc/madeline/madeline.php');
			$pass = $parameters->password;
			///Path filters
			$madeline_get = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . "inc/madeline/index.php";
			$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . "inc/madeline/index.php";
			$scriptname = pathinfo(__FILE__, PATHINFO_FILENAME).".php";
			$url = str_replace($scriptname, '', $url);
			$madeline_get = str_replace($scriptname, '', $madeline_get) . "?pass=$pass";
			//Path filters end
			$CloudyCurl = curl_init();
			
			curl_setopt_array($CloudyCurl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode(array("cloudy"=>"$pass"))
			));
			$result_cloudy = curl_exec($CloudyCurl);
			curl_close($CloudyCurl);
		
		
		echo 
		<<<EOLY
<meta http-equiv="refresh" content="0; url=$madeline_get" />
EOLY;
 
		
		
		
		}
		die;
?>