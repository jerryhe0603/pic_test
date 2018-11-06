<?php

//資料庫連接


$mysqli = new mysqli(DB_ADDR, DB_USER, DB_PASSWORD, DB_NAME);


function sql_error($sql=''){
	global $mysqli;


	die("DB error: ".'<BR>'.$mysqli->error.'<BR>'.'SQL:'.$sql);
}



if ($mysqli->connect_errno) {
	printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

//各檔案要資料 就使用這個 2017.09.06 改成各自宣告
// $SQL_data = new SQLData();


	
function query($sql){
	global $mysqli;

	$mysqli->query('SET NAMES "UTF8";');
	$mysqli->query('SET sql_mode="";');

	$rs = $mysqli->query($sql);

	if($rs === false)sql_error($sql);


	return $rs;
}


function fetch_array($result){
	$arr = array();

	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$arr[] = $row;
	}

	return $arr;
}

?>