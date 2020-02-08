<?php
ob_start();
set_time_limit(600);
include("./inc/func.php");
include('./inc/db.php');




	if(!LoggedIn()){
	header('Location: /login.html');
	echo Success(0, "Your login session expired, please re-login");
	die;
}

	elseif($_GET['operation'] !== 'removelink' AND $_GET['operation'] !== 'removepass' AND $_GET['operation'] !== 'sharelink' AND $_GET['operation'] !== 'rename' AND $_GET['operation'] !== 'remove' )
{
	echo 'Missing GET option'; die;
}





	else{





	if($_GET['operation'] == 'removelink'){
		$parameters = @json_decode(@file_get_contents("php://input"));
		if(isset($parameters->fileId)){
			$fileId = (int)$parameters->fileId;
			$fileId = $db->escapeString($fileId);
			$sql_remove = "DELETE FROM links WHERE fileId = '$fileId'";
			$remove_result = $db->query($sql_remove);
			if($remove_result){ echo Success(1, "Link to file successfully removed", "fileId", "$fileId"); die; }
			else{   echo Success(0, "Error: $db->lastErrorMsg()");   }
			
		}
		else{   echo Success(0, "Missing POST parameters"); die;   }
	}



	if($_GET['operation'] == 'removepass'){
		$parameters = @json_decode(@file_get_contents("php://input"));
		if(isset($parameters->fileId)){
			$fileId = (int)$parameters->fileId;
			$fileId = $db->escapeString($fileId);
			$sql_remove = "UPDATE links SET password = NULL WHERE fileId = '$fileId'";
			$remove_result = $db->query($sql_remove);
			if($remove_result)
			{ echo Success(1, "Password to file successfully removed", "fileId", "$fileId"); die; }
			else
			{   echo Success(0, "Error: $db->lastErrorMsg()");   }
			
		}
		else{   echo Success(0, "Missing POST parameters"); die;   }
	}



	if($_GET['operation'] == 'sharelink'){
		$parameters = @json_decode(@file_get_contents("php://input"));
		if(isset($parameters->fileId, $parameters->name, $parameters->password))
		{
			$fileId = (int)$parameters->fileId;
			$fileId = $db->escapeString($fileId);
			$name = $parameters->name;
			$name = $db->escapeString(CleanStr($name));
			$password = $parameters->password;
			$password = $db->escapeString($password);
			$sql_link_check = "SELECT fileId FROM links WHERE fileId='$fileId'";
			$sql_link_check = $db->query($sql_link_check);
			$sql_link_check_row = $sql_link_check->fetchArray(SQLITE3_ASSOC);
			if (isset($sql_link_check_row['fileId']))
			{
				$sql_password_update = "UPDATE links SET password = '$password', name = '$name' WHERE fileId = '$fileId'";
				$db->query($sql_password_update);
				$link_data = "SELECT link FROM links WHERE fileId='$fileId'";
				$link_data = $db->query($link_data);
				$link_data_row = $link_data->fetchArray(SQLITE3_ASSOC);
				echo Success(1, "Data Updated", "link", $_SERVER['HTTP_HOST'] . "/sharelink.php?link=" . $link_data_row['link']); die;
			}
			else
			{
			$random_string = CleanStr($name) . "." . uniqid(str_replace(' ','', microtime()), true); //;D
			$sql_create_link = "INSERT INTO links(fileId, name, link, password) VALUES ('$fileId', '$name', '$random_string', '$password')";
			$db->query($sql_create_link);
			$sql_fetch_link = "SELECT link FROM links WHERE fileId='$fileId'";
			$sql_fetch_link = $db->query($sql_fetch_link);
			$sql_fetch_link_row = $sql_fetch_link->fetchArray(SQLITE3_ASSOC);
			
			echo Success(1, "Link created", "link", $_SERVER['HTTP_HOST'] . "/sharelink.php?link=" . $sql_fetch_link_row['link'] );
			$db->close();
			die();
			}
		}
		else{
			echo Success(0, "Missing POST parameters"); die;
		}
	}




		if($_GET['operation'] == 'rename'){
			$parameters = @json_decode(@file_get_contents("php://input"));
				if(isset($parameters->fileId) AND isset($parameters->name)){
					$fileId = (int)$parameters->fileId;
					$fileId = $db->escapeString($fileId);
					$name = $parameters->name;
					$name = $db->escapeString(CleanStr($name));
					
					if(end(explode(".", $name)) == $name) {$extension =  NULL;} else {$extension = end(explode(".", $name));}
						
					$sql_rename = "UPDATE files SET name = '$name', extension = '$extension' WHERE fileId = '$fileId'";
			
					$sql_rename = $db->query($sql_rename);
			
		if ($sql_rename == true)
		{	echo json_encode(array("successCode"=>1,"response"=>"File successfully renamed","fileId"=>$fileId,"name"=>$name));	}
			
			else{	echo Success(0, $db->lastErrorMsg() . PHP_EOL); $db->close(); die;	}
			
	}
		elseif(isset($parameters->folderId) AND isset($parameters->name))
		{
			$folderId = (int)$parameters->folderId;
			$folderId = $db->escapeString($folderId);
			$name = $parameters->name;
			$name = $db->escapeString(CleanStr($name));
			$sql_folder_rename = "UPDATE folders SET name = '$name' WHERE folderId = '$folderId'";
			
			$sql_folder_rename = $db->query($sql_folder_rename);
			
			if ($sql_folder_rename == true)
			{	echo json_encode(array("successCode"=>1,"response"=>"File successfully renamed","folderId"=>$folderId,"name"=>$name));	}
			else
			{	echo Success(0, $db->lastErrorMsg() . PHP_EOL);$db->close(); die;	}
			}
		
			else{ echo Success(0, "Missing POST parameters"); die; }
		
			}

	





		if($_GET['operation'] == 'remove'){
			$parameters = @json_decode(@file_get_contents("php://input"));
				if(isset($parameters->fileId))
		{
					$fileId = (int)$parameters->fileId;
					$fileId = $db->escapeString($fileId);
					$sql_file_remove = "DELETE FROM files WHERE fileId = '$fileId'";
			
					$sql_file_remove = $db->query($sql_file_remove);
			
					if($sql_file_remove == true)	
				{	echo Success(1, "Remove Successful", "fileId", $fileId); $db->close(); /* Madeline block */  die;	}
			else
				{ echo Success(0, $db->lastErrorMsg() . PHP_EOL); die; }
		}
		
		elseif(isset($parameters->folderId))
		{
			$folderId = (int)$parameters->folderId;
			$folderId = $db->escapeString($folderId);
			$sql_files_folder_remove = "DELETE FROM files WHERE parentId = '$folderId'";
			$sql_parent_folder_remove = "DELETE FROM folders WHERE parentId = '$folderId'";
			$sql_folder_remove = "DELETE FROM folders WHERE folderId = '$folderId'";
			
			
			$sql_files_folder_remove = $db->query($sql_files_folder_remove);
			$sql_parent_folder_remove = $db->query($sql_parent_folder_remove);
			$sql_folder_remove = $db->query($sql_folder_remove);
			
			if($sql_files_folder_remove == true AND $sql_parent_folder_remove == true AND $sql_folder_remove == true)	
			{	echo Success(1, "Remove Successful", "folderId", $folderId); $db->close(); /* Madeline block */ die;	}
			else
			{ echo Success(0, $db->lastErrorMsg() . PHP_EOL); die; }
		}

			else {  echo Success(0, "Missing POST parameters"); die; }
		
			}
		
		
		
		
		
		
		
	}
?>
