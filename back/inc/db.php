<?php
$db = new SQLite3("dbase.db");
$sql = 'SELECT appid, apphash, username, password, chat FROM user';
$result = $db->query($sql);
$row = $result->fetchArray(SQLITE3_ASSOC);
	$version = '1.3';
	$adminusername = $row['username'];
	$adminpassword = $row['password'];
	$chat = $row['chat'];
	$app_id = $row['appid'];
	$app_hash = $row['apphash'];
	$threads = 3;


?>