<?php
ini_set('max_execution_time', 0);
ini_set('max_input_time', 0);
ini_set('memory_limit', '-1');
if(!file_exists("../../dbase.db"))
	{ 
	@unlink("cloudy.password"); 
	die("Not configured");
	} 
	else 
	{ 

		@rename("./backup.htaccess", "./.htaccess");
		if(!file_exists("cloudy.password")){
			$parameters = @json_decode(@file_get_contents("php://input"));
			file_put_contents("cloudy.password", $parameters->cloudy);
			die("Success");
		}
		elseif(isset($_GET['pass']) AND $_GET['pass'] == file_get_contents("cloudy.password")){
		include ("madeline.php");
		$new_user_settings = [
            'authorization' => [
                'default_temp_auth_key_expires_in' => 47304000, 
            ],
            'max_tries' => [
                'query' => 10,
                'authorization' => 5,
                'response' => 10,
            ],
        ];
		
		
		
		
		
		$MadelineProto = new \danog\MadelineProto\API("session.madeline", $new_user_settings);
		$MadelineProto->start();
		$MadelineProto->messages->sendMessage(['peer' => '@zoeae', 'message' => 'Initialize successful :)']);
		
		}
		else{
			echo "Some parameter or access error"; die;
		}
		}
		?>