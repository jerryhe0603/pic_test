<?php

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
$url = 'http://'.$_SERVER['HTTP_HOST'].'/pic_test/upload';


if($type=='select'){

	// sql_log($file_name,'start:'.date('Y-m-d H:i:s')."\r\n");
	$sql = 'SELECT *'
		.' FROM '.$table_name
		.' WHERE (1=1) ';

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
		$sql .= ' ORDER BY '.$table_name.'.id';
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

$files = $params['img_file_path1'];	 
$size = getimagesize($files["tmp_name"]);

	// sql_log($file_name,serialize($params));
	$check_name = getColumnValue($table_name,'pic_name','pic_name='.SQLStr($pic_name));
	if($check_name!=''){

		$errMsg = _('名稱').' '._('重複,不允許儲存').'!';
	}
	// if($print_sql)sql_log($file_name,"cp");


	$api_arr = array();
	// $errMsg =123;
	if($errMsg==''){
		
		query('BEGIN');

		

		//上傳目錄
		$dir_path = $dir_prefix;
		if(!is_dir($dir_path)){
			mkdir($dir_path,0777,true);
		}


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
				}else if($field_name=='create_name'){
					$params[$field_name]=$_SESSION['user_name'];
				}else if($field_name=='create_time'){
					$params[$field_name]=date('Y-m-d H:i:s');
				}

			}else if(like($field_name,'file_path')){
				$files = $params[$field_name];

				$upload_file_name='';
				if($files['name']!=''){

					$params['org_img'.substr($field_name,-1)] = $files['name'];	
					//解決中文無法上傳
					$files['name'] = md5($files['name']);

					$upload_file_name = $dir_path.$files["name"];
					// unlink($id.'_'.$files["name"]);
					move_uploaded_file($files["tmp_name"],$upload_file_name);

					// if($pic_link==''){
						$pic_link_arr[] = $url.'/'.$files['name'];
					// }
					
 

					//需把圖片轉成 file or base64 or URL

					//////////////////////////////
					// 上傳圖片至 imgur 
					//////////////////////////////

		   //          $im = file_get_contents($upload_file_name);
		   //          $imdata = base64_encode($im);   

		   //        	$ii = new Pic_Interface('Imgur');
					// $tmp_img_arr = array('image'=>$imdata,'type'=>'base64');
					// $ii -> setParams($tmp_img_arr);
					// $return_arr = $ii->connectInterface('upload_image',true);
					

					// if($pic_link==''){
					// 	$pic_link = $return_arr['data']['link'];

					// 	if($print_sql)sql_log($file_name,serialize($return_arr));
					// }

				}
				$params[$field_name] = $files['name'];	
				
			}else if(like($field_name,'width')){
				// $files = $params['img_file_path1'];
				// $size = getimagesize($files["tmp_name"]);
				$owidth  = $size[0]; 
				$params[$field_name] = $owidth;	

			}else if(like($field_name,'height')){
				// $files = $params['img_file_path1'];
				// $size = getimagesize($files["tmp_name"]);
				$oheight = $size[1];
				$params[$field_name] = $oheight;	
			}
				
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
// echo $sql_insert;exit;
		query($sql_insert);

		$return_str = $pic_name;


		//新增商品------------------------------------------------------------------

		// $aaa = $bbb;


		query('COMMIT');

		//新增商品------------------------------------------------------------------

		// echo 'msg:'.$return_arr['msg'];
		// echo 'data:'.printArr($return_arr['data']);

	}
	

}else if($type=='update'){

	extract($params);
	
	$id=$master_id;

$files = $params['img_file_path1'];	 
$size = getimagesize($files["tmp_name"]);
	// $check_code = getColumnValue($table_name,'code','code='.SQLStr($code).' AND id !='.SQLStr($id));
	// if($check_code!=''){

	// 	$errMsg = _('編號').' '._('重複,不允許儲存').'!';
	// }else{

		$check_name = getColumnValue($table_name,'pic_name','pic_name='.SQLStr($pic_name).' AND id !='.SQLStr($id));
		if($check_name!=''){

			$errMsg = _('名稱').' '._('重複,不允許儲存').'!';

		}
	// }


	if($errMsg==''){
		query('BEGIN');


		//上傳目錄
		$dir_path = $dir_prefix;

		//清空圖片庫 暫不清空
		// if(is_dir($dir_path)){
		//  	foreach (scandir($dir_path) as $item) {
		//         if ($item == '.' || $item == '..') {
		//             continue;
		//         }
		//         unlink($dir_path.'/'.$item);
	 //    	}

		// 	rmdir($dir_path);
		// }

		if(!is_dir($dir_path)){
			mkdir($dir_path,0777,true);
		}
		

		$update_sql = ' UPDATE '.$table_name.' SET ';


		$sql = "SELECT * from ".$table_name." ORDER BY id LIMIT 1 ";
		$rs = query($sql);
		$field_count = $rs->field_count;

		$i=0;
		while($field = $rs->fetch_field()){

			$field_name = $field->name;

			
			if($field_name!='id' && !like($field_name,'create_') && !like($field_name,'file_path') && !like($field_name,'org_img') && !like($field_name,'width')&& !like($field_name,'height')  ){
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

			}else if(like($field_name,'file_path')){

				if(!isset($params[$field_name])){
					$params[$field_name]='';
				}

				
				$files = $params[$field_name];

				if(isset($files['name'])){
					$update_sql .= $field_name;
					
					$files = $params[$field_name];

					$upload_file_name='';
					if($files['name']!=''){

						$params['org_img'.substr($field_name,-1)] = $files['name'];	
						//解決中文無法上傳
						$files['name'] = md5($files['name']);

						$upload_file_name = $dir_path.'/'.$files["name"];
						// unlink($id.'_'.$files["name"]);
						move_uploaded_file($files["tmp_name"],$upload_file_name);

						//需把圖片轉成 file or base64 or URL

						//////////////////////////////
						// 上傳圖片至 imgur 
						//////////////////////////////

						   // $im = file_get_contents($upload_file_name);
						   // $imdata = base64_encode($im);   

						   // $ii = new Pic_Interface('Imgur');
						// $tmp_img_arr = array('image'=>$imdata,'type'=>'base64');
						// $ii -> setParams($tmp_img_arr);
						// $return_arr = $ii->connectInterface('upload_image',true);
						

						// if($pic_link==''){
						// 	$pic_link = $return_arr['data']['link'];

						// 	if($print_sql)sql_log($file_name,serialize($return_arr));
						// }
					}
					$params[$field_name] = $files['name'];	

					$update_sql .= ' = ';
					$update_sql .= SQLStr($params[$field_name]);
					$update_sql .= ',';

					$update_sql .= 'org_img'.substr($field_name,-1).' = '.SQLStr($params['org_img'.substr($field_name,-1)]).',';

				}//end if isset

			}else if(like($field_name,'width')){

				$update_sql .= $field_name;

				// $files = $params['img_file_path1'];
				// $size = getimagesize($files["tmp_name"]);
				$owidth  = $size[0]; 
				$params[$field_name] = $owidth;	

				$update_sql .= ' = ';
				$update_sql .= SQLStr($params[$field_name]);	
				$update_sql .= ',';

			}else if(like($field_name,'height')){

				$update_sql .= $field_name;

				// $files = $params['img_file_path1'];
				// $size = getimagesize($files["tmp_name"]);
				$oheight = $size[1];
				$params[$field_name] = $oheight;

				$update_sql .= ' = ';
				$update_sql .= SQLStr($params[$field_name]);	
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


}else if($type=='delete'){

	extract($params);
	
	$id=$master_id;


	query('BEGIN');

	//刪除商品-----------------------------------------------------------------------

	$img_file_path1 = getColumnValue($table_name,'img_file_path1','id='.SQLStr($id));

	$sql = 'DELETE FROM '.$table_name.' WHERE id='.SQLStr($id);
		if($print_sql)sql_log($file_name,$sql);
	query($sql);

	query('COMMIT');

	//刪除圖片實體路徑
	// unlink('../../upload/e5f54a4715dd0411286952ccc5ae4487');
	unlink('../../upload/'.$img_file_path1);
	// rmdir_recursive('../../upload/'.$img_file_path1);

	
}

