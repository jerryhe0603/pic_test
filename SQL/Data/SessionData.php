<?php

$user_account = $params['user_account'];
$user_password = $params['user_password'];

// $table_name = 'user';

$sql = 'SELECT * FROM user WHERE status=1 AND user_id= '.SQLStr($user_account).' AND password='.SQLStr($user_password);

if($print_sql){
	echo $sql;
}

$rs = query($sql);
$arr = fetch_array($rs);
