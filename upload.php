<?php
ob_start();
include("./inc/db.php");
include("./inc/func.php");

function current_host(){
	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
	$url = str_replace($scriptname, '', $url);
	return $url;
		}

	if(!LoggedIn())	{
	
		header('Location: ' . current_host() . "login.php");
		die();	 
	
	}
ini_set('max_execution_time', 0);
ini_set('max_input_time', 0);
ini_set('memory_limit', '-1');


@$current_dir = (int)$_POST['dir'];
$target_dir = "tmp/";
@$target_file = $target_dir . basename($_FILES["cloudy_file"]["name"]);
$uploadOk = 1;
$FileType = pathinfo($target_file, PATHINFO_EXTENSION);
if (isset($_POST["submit"]) AND isset($_POST['dir'])) {

      if (file_exists($target_dir . basename($_FILES["cloudy_file"]["name"]))) {
       echo Success(0, "Couldn't delete previous files from /tmp directory");
        $uploadOk = 0; die();
    } 
    elseif($_FILES["cloudy_file"]["size"] > 1572864000) {
      echo Success(0, "Sorry, your file exceeding 1.5GB"); die();
        $uploadOk = 0; die();
    }
    elseif($uploadOk == 0) {
       echo Success(0, $_FILES['cloudy_file']['error']); die();
    } 
	else 
	{
        if (move_uploaded_file($_FILES["cloudy_file"]["tmp_name"], $target_file)) {
			include("./inc/madeline/madeline.php");
			
			$newfilename = CleanStr(basename($_FILES["cloudy_file"]["name"]));
			
			$MadelineProto = new \danog\MadelineProto\API("./inc/madeline/session.madeline", $settings);
			$MadelineProto->start();
			
			$sentMessage = $MadelineProto->messages->sendMedia([
			'peer' => $chat,
			'media' => [
			'_' => 'inputMediaUploadedDocument',
			'file' => "./tmp/$newfilename",
			'attributes' => [
            ['_' => 'documentAttributeImageSize'],
            ['_' => 'documentAttributeFilename', 'file_name' => $newfilename]
			]
			],
			]
			);
			$newfileId = (int)($sentMessage['updates']['2']['message']['date']) + 1;
			$newfilesize = $sentMessage['updates']['2']['message']['media']['document']['size'];
			$newfiledate = date('d/m/Y g:i A', $newfileId);
			
			
			$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . 'controller.php?thumb=';
			$scriptname = pathinfo(__FILE__, PATHINFO_FILENAME).".php";
			$url = str_replace($scriptname, '', $url);
			
			$new_file_extension = FileExtension($newfilename);
			
			if($new_file_extension == "jpg" OR $new_file_extension == "jpeg" OR $new_file_extension == "png")
			{ make_thumb("./tmp/$newfilename", "./thumbs/$newfileId".".jpg" ); $thumb = $url . $newfileId; } 
			else {	$thumb = null; }
			@rename("./thumbs/backup.htaccess", "./thumbs/.htaccess");
			
			
			$sql_new_file = "INSERT INTO files(fileId, filesize, name, extension, date, type, parentId, thumb) VALUES ('$newfileId', '$newfilesize', '$newfilename', '$new_file_extension', '$newfileId', 'file', '$current_dir', '$thumb')";
			$sql_new_file = $db->query($sql_new_file);
			if($sql_new_file == true) {	echo json_encode(array("Success"=>"1","response"=>"Upload Successful","data"=>array("fileId"=>"$newfileId","filesize"=>$newfilesize,"name"=>"$newfilename","date"=>"$newfiledate","extension"=>"$new_file_extension","type"=>"file","parentId"=>"$current_dir","thumb"=>"$thumb")));	}
			unlink("./tmp/$newfilename");
			
			
			
			
			
			
			
			
			
        }
    }
}
else	{ echo Success(0, "Missing POST parameters"); }

//---------------------------------------


if(isset($current_dir))	{	$getlink = "?dir=" . $current_dir;	} else {	$getlink = null;	}
echo 
<<<EOLY
<form action="upload.php" method="post" id="myForm" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="cloudy_file" id="cloudy_file">
	<input type="hidden" name="dir" id="dir">
    <button name="submit" class="btn btn-primary" type="submit" value="submit">Upload File</button>
 </form>
EOLY;

?>
