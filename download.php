<?php
ob_start();
include ("./inc/db.php");
include ("./inc/func.php");
ini_set('max_execution_time', 0);
ini_set('max_input_time', 0);
ini_set('memory_limit', '-1');

function current_host(){
	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
	$url = str_replace($scriptname, '', $url);
	return $url;
		}

	if(!LoggedIn()){
		header('Location: ' . current_host() . "login.php");
		echo Success(0, "Your login session expired, please re-login");
		die();
}

		$parameters = @json_decode(@file_get_contents("php://input"));
		if(isset($parameters->fileId)){
			$fileId = (int)$parameters->fileId;
			$fileId = $db->escapeString($fileId);
			$results_download = $db->query("SELECT name FROM files WHERE fileId = '$fileId'"); 
			$results_download_row = $results_download->fetchArray(SQLITE3_ASSOC);
			
			include("./inc/madeline/madeline.php");
			$MadelineProto = new \danog\MadelineProto\API("./inc/madeline/session.madeline", $settings);
			$MadelineProto->start();
			$data = $MadelineProto->messages->getHistory([
			'peer' => $chat,
			'offset_id' => 0,
			'offset_date' => $fileId,
			'add_offset' => 0,
			'limit' => 1,
			'max_id' => 0,
			'min_id' => 0,
			'hash' => 0,
			]);

			$info = $MadelineProto->getDownloadInfo($data['messages'][0]);
			header('Content-Length: '.$info['size']);
			header('Content-Type: '.$info['mime']);
			header('Content-Disposition: attachment; filename=' . $results_download_row['name']);
			
			$stream = fopen('php://output', 'w');
			$MadelineProto->downloadToStream($data['messages'][0], $stream);

			die();
			
			
			
			
		}
		else
		{
			echo Success(0, "Missing POST parameters");
		}
			