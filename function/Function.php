<?php 
//一些自製function 放在這邊


function printArr($arr){
	echo '<pre>', print_r($arr, true), '</pre>';
}



function SQLStr($str){
	
	if($str==''){
		$str = 'NULL';
	}else{
		$str = "'".$str."'";
	}

	return $str;
}


function SQLStrIn($str){
	$str_arr = explode(',',$str);

	$str =  '';
	for($s=0;$s<count($str_arr);$s++){
		if($str_arr[$s]==''){
			$str .= 'NULL';
		}else{
			$str .= "'".$str_arr[$s]."'";
		}

		if($s<count($str_arr)-1){
			$str .= ',';
		}
	}

	return $str;
}

function SQLNum($str){
	
	if($str==''){
		$str = '0';
	}else{
		$str = SQLStr($str);
	}

	return $str;
}

function like($str,$neddle){
	if(!is_int(strpos($str, $neddle))){
		return false;
	}else{
		return true;
	}
}


function getInsertStr($table_name,$col_arr,$type){

	$insert_sql = ''; $value_sql ='';

	if($type=='all'){
		$insert_sql = 'INSERT '.$table_name;
		$insert_sql .= '(';

		$value_sql  = ' VALUES ( ';	

		foreach($col_arr as $column => $value){
			$insert_sql.= $column.',';

			$value_sql .= $value.',';
		}

		$insert_sql = substr($insert_sql,0,-1).')';
		$value_sql = substr($value_sql,0,-1).')';

		$insert_sql .= $value_sql.';';

	}else if($type=='head'){

		$insert_sql = 'INSERT '.$table_name;
		$insert_sql .= '(';

		foreach($col_arr as $column => $value){
			$insert_sql.= $column.',';
		}

		$insert_sql = substr($insert_sql,0,-1).') VALUES ';

	}else if($type=='body'){
		
		$insert_sql = '(';
		foreach($col_arr as $column => $value){
			$insert_sql.= $value.',';

		}

		$insert_sql = substr($insert_sql,0,-1).')';

	}

	return $insert_sql;
}



function getUpdateStr($table_name,$col_arr,$where){

	$update_sql = '';

	$update_sql = 'UPDATE '.$table_name.' SET ';
	foreach($col_arr as $column => $value){
		$update_sql .= $column.' = '.$value.',';
	}

	$update_sql = substr($update_sql,0,-1);

	$update_sql .= ' WHERE '.$where.';';

	return $update_sql;
}



function getColumnValue($table,$column,$where='(1=1)',$print_sql=false){
	$sql = ' SELECT '.$column.' FROM '.$table.' WHERE '.$where;

	if($print_sql){
		echo $sql;
	}

	$rs = query($sql);
	$arr = fetch_array($rs);
	if(isset($arr[0])){
		$field = $rs->fetch_field();
		$field_name = $field->name;

		return $arr[0][$field_name];
	}else{
		return '';
	}
}

function getMoreColumnValue($table,$column,$where='(1=1)',$print_sql=false){
	$sql = ' SELECT '.$column.' FROM '.$table.' WHERE '.$where;

	if($print_sql){
		echo $sql;
	}

	$rs = query($sql);
	$arr = fetch_array($rs);
	if(isset($arr[0])){
		// $field = $rs->fetch_field();
		// $field_name = $field->name;

		return $arr[0];
	}else{
		return '';
	}
}


function getOptions($table,$id,$column,$where='(1=1)',$default,$empty,$print_sql=false){

	
	$return_str = '';

	if($empty!=''){
		$return_str .= '<option value="" >'.$empty.'</option>';
	}


	$sql = ' SELECT '.$id.','.$column.' FROM '.$table.' WHERE '.$where;

	if($print_sql){
		echo $sql;
	}

	$rs = query($sql);
	$arr = fetch_array($rs);

	for($a=0;$a<count($arr);$a++){
		$row = $arr[$a];

		$val = $row[$id];
		$text = $row[$column];

		$return_str .= '<option ';

		if($default==$val){
			$return_str .= 'selected';
		}

		$return_str .= ' value="'.$val.'">';
		$return_str .= $text;
		$return_str .= '</option>';

	}

	return $return_str;

}

function getUUID(){
	$sql = 'SELECT uuid() AS id ';
	$rs = query($sql);
	$arr = fetch_array($rs);
	return $arr[0]['id'];
}


function getTreeLevel($table,$id,$id_col,$parent_col,$another_where,$print_sql=false){
	$tree_level=2;
	$parent='';
	$i=0;

	while(true):
		if($i==100)break;

		$parent=getColumnValue($table,$parent_col,$id_col.'='.SQLStr($id).$another_where,$print_sql);
		if($parent!=''){
			$tree_level++;
		}else{
			break;
		}

		$i++;
	endwhile;

	return $tree_level;
}

function dateYouWant($date,$change,$format){
	return date($format,strtotime($change,strtotime($date)));
}


//強迫刪除資料夾
function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}



//取得單號
function getNo($id,$code_type,$code_date){


	query('INSERT get_no(from_id,code_type,code_date)  VALUES ('.SQLStr($id).','.SQLStr($code_type).','.SQLStr($code_date).')');


	$sql = 'SELECT * FROM get_no WHERE code_type ='.SQLStr($code_type).' AND DATE_FORMAT(code_date,"%Y-%m") = '.SQLStr(date('Y-m',strtotime($code_date))).' ORDER BY id';
	$rs = query($sql);
	$data = fetch_array($rs);

	// echo $sql;
	// print_r($data);
	$no=1;
	for($d=0;$d<count($data);$d++){
		if($data[$d]['from_id'] == $id){
			$no=$d+1;
			break;
		}
	}

	// $code = $code_type.date('Ymd',strtotime($code_date)).substr('000000'.$no,-3);

	// return $code;

	return $no;

}


?>