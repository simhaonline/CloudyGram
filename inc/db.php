<?php

$db = new SQLite3("dbase.db");
$sql = 'SELECT username, password, chat FROM user';
$result = $db->query($sql);
$row = $result->fetchArray(SQLITE3_ASSOC);
////Madeline settings////
$threads = 3;
$settings = ['upload' =>[
	'parallel_chunks' => $threads,
	],
	'download' =>[
	'parallel_chunks' => $threads,
	],
	'max_tries' => [
                'query' => 10,
                'authorization' => 5,
                'response' => 10,
            ],
];
$chat = $row['chat'];
/////End of block//////
	$version = '1.3';
	$time = time();
	$adminusername = $row['username'];
	$adminpassword = $row['password'];	



?>