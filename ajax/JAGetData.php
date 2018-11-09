<?php
// $no_header=true;
// include('../Header.php');
include_once('../Config.php');
include_once('../SQL/Data/SQLData.php');
include_once('../SQL/SQL_Connect.php');
include_once('../function/Function.php');

$key = $_REQUEST['key'];

if(isset($_REQUEST['master_id'])){
	$master_id = $_REQUEST['master_id'];
}


if(isset($_REQUEST['empty_value'])){
	$empty_value = $_REQUEST['empty_value'];
}

if(isset($_REQUEST['echo_type'])){
	$echo_type = $_REQUEST['echo_type'];
}





// $file = fopen('../log/JAGetData.txt_'.date('YmdHis').'.txt','a+');
// fwrite($file,'serialize:'."\r\n".serialize(($_REQUEST))."\r\n");
// fclose($file);

if($key == '' )exit;

if($key=='get_stock_category_select'){

	$tree_arr = array();

	//是否塞空值空值
	if($empty_value!=''){
		$arr['id']='';
		$arr['text']=$empty_value;
		$tree_arr[] = $arr;
	}
	

	//開始塞資料
	$sql = 'SELECT * FROM stock_category WHERE (1=1) ';

	if($master_id!=''){

		$sql .= ' AND id <> '.SQLStr($master_id);
	}

	$sql .= ' ORDER BY code ';


	// $file = fopen('../log/JAGetData.txt_'.date('YmdHis').'.txt','a+');
	// fwrite($file,'sql:'.$sql."\r\n");
	// fclose($file);
	$rs = query($sql);
	$arr = fetch_array($rs);
	for($a=0;$a<count($arr);$a++){
		$row = $arr[$a];

		$space='';
		// for($t=0;$t<$row['tree_level'];$t++){
		// 	$space .= '&nbsp;';
		// }

		$row['text'] = $space.$row['code'].'-'.$row['name'];


		$tree_arr[] = $row;
		/*
		if($row['parent']==''){
			$tree_arr[] = $row;
			// unset($arr[$a]);
		}
		*/
	}
	if($echo_type=='json'){
		echo json_encode($tree_arr);
	}

	/*	
	$max_level = getColumnValue('stock_category','MAX(tree_level)','(1=1)');

	// echo 'max_level:'.$max_level;
	for($m=2;$m<=$max_level;$m++){

		echo 'level:'.$m.' - --------------------------------------------<BR>';
		// echo 'old:'.'<BR>'.printArr($round_arr);
		for($a=0;$a<count($arr);$a++){
			$row = $arr[$a];
			$row['text'] = $row['name'];

			if($row['tree_level']!=$m)continue;

			for($t=0;$t<count($tree_arr);$t++){
				$tree_row = $tree_arr[$t];

				if($m>2){
					while(isset($tree_row['children'])){
						$sub_row = $tree_row['children'];

					}
				}else{
					if($tree_row['tree_level']!=($m-1))continue;

					if($tree_row['id'] == $row['parent']){
						$tree_arr[$t]['children'][] = $row;
					}
				}
				

			}

			// unset($arr[$a]);
		}

		// echo 'new:'.printArr($tree_arr).'<BR>';

		// echo 'last:'.printArr($arr).'<BR>';


		echo '++++++++ +++++++  end level:'.$m.' - +++++++++++++++++++++++++++<BR>';

	}
	

	printArr($tree_arr);
	*/

}else if($key=='getColumnValue'){

	$table = $_REQUEST['table'];
	$col = $_REQUEST['col'];
	$where = $_REQUEST['where'];
	$print_sql = ($_REQUEST['print_sql']=='false')?false:true;

	$return = getColumnValue($table,$col,$where,$print_sql);

	if(!$print_sql){
		echo $return;
	}



}else{

	$value_col = $_REQUEST['value_col'];
	$text_col = $_REQUEST['text_col'];
	$where_cond = $_REQUEST['where_cond'];
	
	$table = (isset($_REQUEST['table_name']))?$_REQUEST['table_name']:$key;
	// $table = $key;

	$return_arr = array();

	//是否塞空值空值
	if($empty_value!=''){
		$arr['id']='';
		$arr['text']=$empty_value;
		$arr[$text_col]=$empty_value;
		$return_arr[] = $arr;
	}
	

	//開始塞資料
	$sql = 'SELECT '.$value_col.','.$text_col.' FROM '.$table.' WHERE '.$where_cond;


	// $file = fopen('../log/JAGetData.txt_'.date('YmdHis').'.txt','a+');
	// fwrite($file,'sql:'.$sql."\r\n");
	// fclose($file);
	$rs = query($sql);
	$arr = fetch_array($rs);
	for($a=0;$a<count($arr);$a++){
		$row = $arr[$a];

		if(!isset($row['text'])){
			$row['text'] = $row[$text_col];
		}

		$return_arr[] = $row;
		
	}
	if($echo_type=='json'){
		echo json_encode($return_arr);
	}
}






?>
