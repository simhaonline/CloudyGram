<?php
ob_start();
include("./inc/func.php");

	if(file_exists("dbase.db")){   echo Success(0, "Already configured"); die;   }      
		//Dbase block
		$parameters = @json_decode(@file_get_contents("php://input"));
		$time = time();
		$db_init = new SQLite3("dbase.db");
		$sql_table_user = 'CREATE TABLE user(appid INTEGER, apphash TEXT, username TEXT, password TEXT, chat TEXT)';
		$sql_values = "INSERT INTO user (appid, apphash, username, password, chat) VALUES ('$parameters->app_id', '$parameters->app_hash', '$parameters->username', '$parameters->password', '$parameters->chat')";
		$sql_files = 'CREATE TABLE files(fileId INTEGER, name TEXT, extension TEXT, filesize TEXT, date INTEGER, parentId INTEGER)';
		$sql_folders = 'CREATE TABLE folders(folderId INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, date INTEGER, type TEXT, parentId INTEGER)';
		$sql_folders_values = "INSERT INTO folders(folderId, name, date) VALUES ('0', '/', '$time')";
		$sql_links = 'CREATE TABLE links(link TEXT, fileId TEXT, password TEXT, name TEXT)';
		$result_table_user = $db_init->exec($sql_table_user);
		$result_values_user = $db_init->exec($sql_values);
		$result_files = $db_init->exec($sql_files);
		$result_folders = $db_init->exec($sql_folders);
		$result_folders_values = $db_init->exec($sql_folders_values);
		$result_links = $db_init->exec($sql_links);
		
		if($result_table_user !== true OR $result_values_user !== true OR $result_files !== true OR $result_folders !== true OR $result_folders_values !== true OR $result_links !== true  ){
			unlink("dbase.db");
			echo $db->lastErrorMsg() . PHP_EOL;
			die("Something went wrong while initializing database");
		}
		$db_init->close();
		header('Location: /login.html');
		

?>