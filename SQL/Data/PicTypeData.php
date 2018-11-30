<?php

$print_sql=0;
$errMsg = '';
$file_name = '../../log/'.basename(__FILE__).date('YmdHis').'.txt';

if (!function_exists('sql_log')) {
	function sql_log($file_name,$sql){
		$file = fopen($file_name,'a+');
		fwrite($file,'sql:'.$sql."\r\n");
		fclose($file);
	}
}

// if($print_sql)sql_log($file_name,"cp");
// $_FILES["file"]["error"]
// $_FILES["file"]["name"]
// $_FILES["file"]["type"]
// $_FILES["file"]["size"]
// $_FILES["file"]["tmp_name"]

//上傳檔案的路徑
$dir_prefix='../../upload/';
$url = 'http://'.$_SERVER['HTTP_HOST'].'/WiCareERP/upload';


if($type=='select'){

	// sql_log($file_name,'start:'.date('Y-m-d H:i:s')."\r\n");
	$sql = 'SELECT *'
		.' FROM '.$table_name
		.' WHERE (1=1) AND status=1 ';

	$cond = '';$is_order=false;$limit='';
	foreach($params as $key=> $value){

		if($key=='LIMIT'){
			$limit = ' '.$key.' '.$value;
		}else if($key == 'ORDER BY' ){
			$cond .= ' ORDER BY '.$value;
			$is_order = true;
			
		}else{
			$cond .= ' AND ( '.$value.' ) ';
		}
	}


	$sql .= $cond;

	if(!$is_order){
		$sql .= ' ORDER BY '.$table_name.'.item_index';
	}

	$sql .= $limit;


	if($print_sql)sql_log($file_name,$sql);


	$rs = query($sql);
	$arr = fetch_array($rs);

	// sql_log($file_name,'end:'.date('Y-m-d H:i:s')."\r\n");
}else if($type=='select_count'){


	$sql = 'SELECT count('.$table_name.'.id) AS count '
		.' FROM '.$table_name
		.' WHERE (1=1) ';

	$cond = '';
	foreach($params as $key=> $value){

 		if($key == 'ORDER BY' ){
			$cond .= ' ORDER BY '.$value;
			$is_order = true;
			
		}else{
			$cond .= ' AND ( '.$value.' ) ';
		}
	}

	$sql .= $cond;


	if($print_sql)sql_log($file_name,$sql);


	$rs = query($sql);
	$arr = fetch_array($rs);

	$return_str = $arr[0]['count'];

}else if($type=='insert'){

	extract($params);

	if($type_name){
		$check_name = getColumnValue($table_name,'type_name','type_name='.SQLStr($type_name));
		if($check_name!=''){

			$errMsg = _('名稱').' '._('重複,不允許儲存').'!';

		}
	}else{
		$errMsg = _('名稱').' '._('不可為空,不允許儲存').'!';
	}
	
	/*
	if($type_name_en){

		if(preg_match("/^[\x7f-\xff]+$/",$type_name_en)){
	     $errMsg = '請輸入英文';
	}

	}else{
		$errMsg = _('英文名稱').' '._('不可為空,不允許儲存').'!';
	}*/

	/*
	if($type_name_en){

		if(!preg_match('/^[a-zA-Z]+$/',$type_name_en)){
		    $errMsg = '只能輸入英文';
		}

	}else{
		$errMsg = _('英文名稱').' '._('不可為空,不允許儲存').'!';
	}*/

	// if($print_sql)sql_log($file_name,"cp");


	// $errMsg =123;
	if($errMsg==''){
		$sql_max_type =" SELECT MAX(item_index) as last_type FROM pic_type ";
		$rs = query($sql_max_type);
		$arr = fetch_array($rs);
		// $errMsg = "幾個".$arr[0]['last_type']."+1=".($arr[0]['last_type']+1);
		$last_type = ($arr[0]['last_type']+1);
		query('BEGIN');


		$sql_insert = ' INSERT '.$table_name.' (';

		$sql_value = '(';

		$sql = "SELECT * from ".$table_name." ORDER BY id LIMIT 1 ";
		$rs = query($sql);
		$field_count = $rs->field_count;

		$i=0;$pic_link_arr = array();
		while($field = $rs->fetch_field()){

			$field_name = $field->name;

			$sql_insert.= $field_name;
			

			if(!isset($params[$field_name])){
				$params[$field_name]='';
			}

			//指定某些欄位的值
			if($field_name=='id'){
				// $params[$field_name]=$id;

			}else if(like($field_name,'create_')){
				if($field_name=='create_id'){
					$params[$field_name]=$_SESSION['user_id'];
					$params['modify_id']=$_SESSION['user_id'];
				}else if($field_name=='create_name'){
					$params[$field_name]=$_SESSION['user_name'];
					$params['modify_name']=$_SESSION['user_name'];
				}else if($field_name=='create_time'){
					$params[$field_name]=date('Y-m-d H:i:s');
					$params['modify_time']=date('Y-m-d H:i:s');
				}

			}else if(like($field_name,'status')){
				
				$params[$field_name] = 1;	
				
			}else if(like($field_name,'item_index')){
				$params[$field_name] = $last_type;
			}
			// else if(like($field_name,'item_index')){
				
			// 	$params[$field_name] = 1;	
				
			// }
				
			$sql_value .= SQLStr($params[$field_name]);	

			if($i<$field_count-1){
				$sql_insert .= ',';
				$sql_value .= ',';
			}

			$i++;
		}

		$sql_insert .= ' ) VALUES ';
		$sql_value .= ' );';

		$sql_insert .= $sql_value;
		

			// if($print_sql)sql_log($file_name,'params:'.serialize($params));
			if($print_sql)sql_log($file_name,$sql_insert);

		query($sql_insert);

		$return_str = $type_name;

		query('COMMIT');

		//新增商品------------------------------------------------------------------

		// echo 'msg:'.$return_arr['msg'];
		// echo 'data:'.printArr($return_arr['data']);

	}
	

}else if($type=='update'){

	extract($params);
	
	$id=$master_id;


	// $check_code = getColumnValue($table_name,'code','code='.SQLStr($code).' AND id !='.SQLStr($id));
	// if($check_code!=''){

	// 	$errMsg = _('編號').' '._('重複,不允許儲存').'!';
	// }else{

		$check_name = getColumnValue($table_name,'type_name','type_name='.SQLStr($type_name).' AND id !='.SQLStr($id));
		if($check_name!=''){

			$errMsg = _('名稱').' '._('重複,不允許儲存').'!';

		}
	// }
	
	/*
	if($type_name_en){

		if(!preg_match('/^[a-zA-Z]+$/',$type_name_en)){
		    $errMsg = '只能輸入英文';
		}

	}else{
		$errMsg = _('英文名稱').' '._('不可為空,不允許儲存').'!';
	}*/


	if($errMsg==''){
		query('BEGIN');


	
		

		$update_sql = ' UPDATE '.$table_name.' SET ';


		$sql = "SELECT * from ".$table_name." ORDER BY id LIMIT 1 ";
		$rs = query($sql);
		$field_count = $rs->field_count;

		$i=0;
		while($field = $rs->fetch_field()){

			$field_name = $field->name;

			if($field_name=='id')continue;
			// if($field_name=='item_index')continue;
			if($field_name!='id' && !like($field_name,'create_') && $field_name!='status' ){
				$update_sql .= $field_name;
			
				if(!isset($params[$field_name])){
					$params[$field_name]='';
				}

				//指定某些欄位的值

				if(like($field_name,'modify_')){
					if($field_name=='modify_id'){
						$params[$field_name]=$_SESSION['user_id'];
					}else if($field_name=='modify_name'){
						$params[$field_name]=$_SESSION['user_name'];
					}else if($field_name=='modify_time'){
						$params[$field_name]=date('Y-m-d H:i:s');
					}
				}



				$update_sql .= ' = ';
					
				$update_sql .= SQLStr($params[$field_name]);	
				
				$update_sql .= ',';

			}else if(like($field_name,'status')){
				$update_sql .= $field_name;

				$params[$field_name] = 1;


				$update_sql .= ' = ';
					
				$update_sql .= SQLNum($params[$field_name]);	
				
				$update_sql .= ',';

			}


			$i++;
		}

		$update_sql = substr($update_sql,0, -1);

		$update_sql .= ' WHERE id = '.SQLStr($id);

			if($print_sql)sql_log($file_name,'params:'.serialize($params));
			if($print_sql)sql_log($file_name,$update_sql);

		query($update_sql);


		//修改商品------------------------------------------------------------------

		query('COMMIT');
	}//end if err_msg
	$return_str = $id;

}else if($type=='delete'){
	

	extract($params);
	
	$id=$master_id;

	$sql = 'SELECT * FROM pic WHERE pic_type_id='.SQLStr($id);
		$rs = query($sql);
	// $arr = fetch_array($rs);
	$rowcount=mysqli_num_rows($rs);
	if($rowcount>0){
		$errMsg = "不能刪除,此類別".$rowcount."個圖檔使用中";
		// $errMsg = _('此類別圖檔使用中').'!';
	}else{
		query('BEGIN');

		$sql = 'DELETE FROM pic_type WHERE id='.SQLStr($id);
		//把圖檔狀態更改
		// $sql = 'UPDATE pic_type SET status=2  WHERE id='.SQLStr($id);
		// 	if($print_sql)sql_log($file_name,$sql);
		query($sql);


		query('COMMIT');
	}
	

}

