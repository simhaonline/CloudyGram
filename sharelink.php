<?php
ob_start();
ini_set('max_execution_time', 0);
ini_set('max_input_time', 0);
ini_set('memory_limit', '-1');
error_reporting(0);
include ("./inc/db.php");
include ("./inc/func.php");
if(!isset($_GET['link'])){
	echo "Missing GET parameter";
	die();
}
	
$link = CleanStr($_GET['link']);
$pass = CleanStr($_GET['pass']);
$link = $db->escapeString(CleanStr($link));
$link_sql = "SELECT link, fileId, password FROM links WHERE link='$link'";
$link_result = $db->query($link_sql);
$link_row = $link_result->fetchArray(SQLITE3_ASSOC);

$link_filename = "SELECT name, filesize, count, date FROM files WHERE fileId='" . $link_row['fileId'] . "'" ;
$link_filename = $db->query($link_filename);
$link_filename_row = $link_filename->fetchArray(SQLITE3_ASSOC);

$fileId = (int)$link_row['fileId'];
$filename = $link_filename_row['name'];
$filesize = formatSize($link_filename_row['filesize']);
$filedate = date('d/m/Y g:i A',$link_filename_row['date']);
$count = (int)$link_filename_row['count']; $count++;

if(!isset($link_row['link'])){
	echo "Link not found";
}
else{

		if($link_row['password'] == NULL)
			{
			$sql_count_update = "UPDATE files SET count = '$count' WHERE fileId = '" . $fileId . "'";
			$db->query($sql_count_update);
			
			
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
			header('Content-Disposition: attachment; filename=' . $filename);
			
			$stream = fopen('php://output', 'w');
			$MadelineProto->downloadToStream($data['messages'][0], $stream);

			die();


			}
	
		elseif($pass == $link_row['password'])
		{

			$sql_count_update = "UPDATE files SET count = '$count' WHERE fileId = '" . $fileId . "'";
			$db->query($sql_count_update);

			include("./inc/madeline/madeline.php");
			$MadelineProto = new \danog\MadelineProto\API("./inc/madeline/session.madeline", $settings);
			$MadelineProto->start();
			$data = $MadelineProto->messages->getHistory([
			'peer' => $chat,
			'offset_id' => 0,
			'offset_date' => "$fileId",
			'add_offset' => 0,
			'limit' => 1,
			'max_id' => 0,
			'min_id' => 0,
			'hash' => 0,
			]);

			$info = $MadelineProto->getDownloadInfo($data['messages'][0]);
			header('Content-Length: '.$info['size']);
			header('Content-Type: '.$info['mime']);
			header('Content-Disposition: attachment; filename=' . $filename);

			$stream = fopen('php://output', 'w');
			$MadelineProto->downloadToStream($data['messages'][0], $stream);
		
		
		
			die();
		
		
		
		}
	else
			
		{
		$countemp = (int)$link_filename_row['count'];
		echo <<<EOLY
<center>
<form action="sharelink.php" method="GET">
  <p>Password:<br>
<input type="text" name="pass" value=""><br>
<input type="hidden" name="link" value="$link">
  <input type="submit" value="Download">
  <style>
  div {

  vertical-align: bottom;
  height: 100px;
  width: 150px;
}
</style>
  <br>
		<br><strong>File name</strong>: $filename <br><strong>Size</strong>: $filesize<br><strong>Uploaded on:</strong> $filedate<br><strong>Downloaded</strong>: $countemp times<br><br><div><strong>Links provided by </strong><strong><span style="color: #767171;">Cloudy</span></strong><strong><span style="color: #2E75B6;">Gram</span></strong></div>
  </p>
</form>
</center>
EOLY;


	$db->close();
	die();
		}
		
		
	}

?>