<?php
error_reporting(0);
include('./inc/db.php');
include('./inc/func.php');
if(!isset($_GET['link'])){
	echo Success(0, "Missing GET parameter");
	die;
}
	
$link = CleanStr($_GET['link']);
$pass = CleanStr($_GET['pass']);
$link = $db->escapeString(CleanStr($link));
$link_sql = "SELECT link, fileId, password, count FROM links WHERE link='$link'";
$link_result = $db->query($link_sql);
$link_row = $link_result->fetchArray(SQLITE3_ASSOC);
if(!isset($link_row['link'])){
	echo "Link not found";
}
else{
	
		if($link_row['password'] == NULL){
		
			$count = (int)$link_row['count']; $count++;
			$sql_count_update = "UPDATE links SET count = '$count' WHERE link = '" . $link_row['link'] . "'";
			$db->query($sql_count_update);
		
		echo "Downloading"; die;
	}
	
		elseif($pass == $link_row['password'])
		{
			$count = (int)$link_row['count'];  $count++;
			$sql_count_update = "UPDATE links SET count = '$count' WHERE link = '" . $link_row['link']."'";
			$db->query($sql_count_update);
		//../../
		
		echo "Downloading with password";
		
		die;
		}
		else
		{
		echo <<<EOLY
<center>
<form action="sharelink.php" method="GET">
  <p>Password:<br>
<input type="text" name="pass" value=""><br>
<input type="hidden" name="link" value="$link"><br>
    <input type="submit" value="Download"">
  </p>
</form>
</center>
EOLY;


	$db->close();
	die;
		}
}


?>
