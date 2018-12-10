<?php
$print_sql=0;
$errMsg = '';
$file_name = '../../log/'.basename(__FILE__).date('YmdHis').'.txt';
ini_set("memory_limit","4096M");

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
$dir_prefix='../../detail/';
$url = 'http://'.$_SERVER['HTTP_HOST'].'/pic_test/detail';
$url_log = 'http://'.$_SERVER['HTTP_HOST'].'/pic_test/thumb';
$dir_prefix_log = '../../thumb/';

/*$dir_prefix='../../upload/';
$url = 'http://'.$_SERVER['HTTP_HOST'].'/pic_test/upload';
$url_log = 'http://'.$_SERVER['HTTP_HOST'].'/pic_test/thumb';
$dir_prefix_log = '../../thumb/';*/


if($type=='select'){
	
	//圖片類型
	$sql_pic_type = " SELECT * FROM pic_type WHERE (1=1) ";
	$rs_pic_type = query($sql_pic_type);
	$pic_type = fetch_array($rs_pic_type);
	$aPicTypeArr = array();
	
	foreach ($pic_type as $key => $value) {
		$aPicTypeArr[$pic_type[$key]['id']] = $pic_type[$key]['type_name'];
	}

	//選項名稱
	$sql_select_item = " SELECT * FROM select_item WHERE (1=1) ";
	$rs_select_item = query($sql_select_item);
	$select_item = fetch_array($rs_select_item);
	$aSelectItemArr = array();
	
	foreach ($select_item as $key => $value) {
		$aSelectItemArr[$select_item[$key]['id']] = $select_item[$key]['name'];
	}


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

	//圖片類型
	foreach ($arr as $key => $value) {
		$arr[$key]['pic_type_name']  = $aPicTypeArr[$value['pic_type_id']];
	}

	//選項名稱
	foreach ($arr as $key => $value) {
		$arr[$key]['is_album_name']  = $aSelectItemArr[$value['is_album']];
	}

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

    

	// sql_log($file_name,serialize($params));
	$check_name = getColumnValue($table_name,'pic_name','pic_name='.SQLStr($pic_name));
	if($check_name!=''){

		$errMsg = _('名稱').' '._('重複,不允許儲存').'!';
	}

	
	$check_uniform_number = getColumnValue($table_name,'uniform_number','uniform_number='.SQLStr($uniform_number));
	if($check_uniform_number!=''){

		$errMsg = _('統一編號').' '._('重複,不允許儲存').'!';
	}

	//確認圖片
	$img_1 = $params['img_file_path1'];
	$x = (!empty($img_1['tmp_name']))?1:0;
	$check_pic = $x;
	if($check_pic==0){
		$errMsg = _('圖片上傳有缺').' '._('不允許儲存').'!';
	}
	
	/*
	$img_1 = $params['img_file_path1'];
	$img_2 = $params['img_file_path2'];
	$img_3 = $params['img_file_path3'];
	
	$x = (!empty($img_1['tmp_name']))?4:0;
	$y = (!empty($img_2['tmp_name']))?2:0;
	$z = (!empty($img_3['tmp_name']))?1:0;
	
	$check_pic = $x+$y+$z;
	if($check_pic==0 || $check_pic==1 || $check_pic==2 || $check_pic==3 || $check_pic==5){
		$errMsg = _('圖片上傳有缺').' '._('不允許儲存').'!';
	}*/
	// if($print_sql)sql_log($file_name,"cp");

	if($img_1['name']!=""){
		$tmp_img_name = $img_1['name'];
		$check_img_sql = " SELECT * FROM pic WHERE org_img1='$tmp_img_name' ";
		$rs = query($check_img_sql);
		$arr = fetch_array($rs);
		if(count($arr)>0){
			$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
		}
	}
	/*
	//判斷多張圖片
	if($img_1['name']!=""){
		$tmp_img_name = $img_1['name'];
		$check_img_sql = " SELECT * FROM pic WHERE org_img1='$tmp_img_name' OR org_img2='$tmp_img_name' OR org_img3='$tmp_img_name' ";
		$rs = query($check_img_sql);
		$arr = fetch_array($rs);
		if(count($arr)>0){
			$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
		}
	}

	if($img_2['name']!=""){
		$tmp_img_name = $img_2['name'];
		$check_img_sql = " SELECT * FROM pic WHERE org_img1='$tmp_img_name' OR org_img2='$tmp_img_name' OR org_img3='$tmp_img_name' ";
		$rs = query($check_img_sql);
		$arr = fetch_array($rs);
		if(count($arr)>0){
			$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
		}
	}

	if($img_3['name']!=""){
		$tmp_img_name = $img_3['name'];
		$check_img_sql = " SELECT * FROM pic WHERE org_img1='$tmp_img_name' OR org_img2='$tmp_img_name' OR org_img3='$tmp_img_name' ";
		$rs = query($check_img_sql);
		$arr = fetch_array($rs);
		if(count($arr)>0){
			$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
		}
	}*/




	$api_arr = array();
	// $errMsg =123;
	if($errMsg==''){
		
		query('BEGIN');

		//確認圖片
		$img_file_1 = $params['img_file_path1'];	 
	    $size_1 = getimagesize($img_file_1["tmp_name"]);
		

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

					$img_file = $params[$field_name];	 
	    			$size = getimagesize($img_file["tmp_name"]);

					$params['org_img'.substr($field_name,-1)] = $files['name'];	
					//解決中文無法上傳
					$files['name'] = ($files['name']);
					// $files['name'] = md5($files['name']);

					$upload_file_name = $dir_path.$files["name"];
					// unlink($id.'_'.$files["name"]);
					move_uploaded_file($files["tmp_name"],$upload_file_name);
					chmod($upload_file_name, 0777);
					// if($pic_link==''){
						$pic_link_arr[] = $url.'/'.$files['name'];
					// }
					

					//============在傳一次縮圖 1280x720
					switch ($files['type']) {
			            case 'image/jpeg': 
			            	$ext = ".jpg"; 
							
							// 取得上傳圖片
			            	$src = imagecreatefromjpeg($upload_file_name);
			            	break;
			            case 'image/png': 
			            	$ext = ".png"; 
			            	// 取得上傳圖片
			            	$src = imagecreatefrompng($upload_file_name);
			            break;
			            default: 
			            	$ext = ''; 

			            break;
			        }

			        $newwidth = "640";
			        $newheight = "360";
				        
			        // $newwidth = "1280";
			        // $newheight = "720";
			        // Load
					$thumb = imagecreatetruecolor($newwidth, $newheight);

					// 開始縮圖
					imagecopyresampled($thumb, $src, 0, 0, 0, 0, $newwidth, $newheight, $size[0], $size[1]);
					// $source = imagecreatefromjpeg($url.'/'.$files['name']);//  /tmp/phpxOA1TK

					// 儲存縮圖到指定  目錄
					if($files['type']=="image/jpeg"){
						imagejpeg($thumb, $dir_prefix_log.$files['name']);
					}else if($files['type']=="image/png"){
						imagepng($thumb, $dir_prefix_log.$files['name']);
					}
					

					chmod($dir_prefix_log.$files['name'], 0777);
 					
					// 圖片寬高
					$params[$field_name] = $files['name'];	

					$owidth  = $size[0]; 
					$oheight = $size[1];
					
					$w_name = "width_".substr($field_name,-1);
					$h_name = "height_".substr($field_name,-1);
					
					// if($field_name=="img_file_path1"){
					// 	$w_name = "width_1";
					// 	$h_name = "height_1";
					// }else if($field_name=="img_file_path2"){
					// 	$w_name = "width_2";
					// 	$h_name = "height_2";
					// }else if($field_name=="img_file_path3"){
					// 	$w_name = "width_3";
					// 	$h_name = "height_3";
					// }
					$params[$w_name] = $owidth;	
					$params[$h_name] = $oheight;
					// 圖片寬高end
				}else{
					$params[$field_name]="";
				}
				

			}
			/*
			else if(like($field_name,'width')){
				// $files = $params['img_file_path1'];
				// $size_1 = getimagesize($files["tmp_name"]);
				$owidth  = $size_1[0]; 
				$params[$field_name] = $owidth;	

			}else if(like($field_name,'height')){
				// $files = $params['img_file_path1'];
				// $size_1 = getimagesize($files["tmp_name"]);
				$oheight = $size_1[1];
				$params[$field_name] = $oheight;	
			}*/
				
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

		query('COMMIT');
		//新增一份到detail
		// $pic_main_id = getColumnValue($table_name,'id','uniform_number='.SQLStr($uniform_number).' LIMIT 1');

		query('BEGIN');

		$sql = 'SELECT *'
		.' FROM '.$table_name
		.' WHERE (1=1) AND uniform_number='.SQLStr($uniform_number).' LIMIT 1';

		$rs = query($sql);
		$rs_pic = fetch_array($rs);
		$arr_pic = $rs_pic[0];

		$sql_inset_detail = " INSERT pic_detail (pic_main_id,uniform_number,pic_detail_name,pic_no,img_file_path1,org_img1,width_1,height_1,create_id,create_name,create_time) VALUE (";
		$sql_inset_detail .="'".$arr_pic['id']."','".$uniform_number."','".$pic_name."' ,'1' ,'".$params['img_file_path1']."' ,'".$params['org_img1']."' ,'".$arr_pic['width_1']."','".$arr_pic['height_1']."','".$params['create_id']."','".$params['create_name']."' ,'".$params['create_time']."') ";
		query($sql_inset_detail);


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

// $files = $params['img_file_path1'];	 
// $size = getimagesize($files["tmp_name"]);

	// $check_code = getColumnValue($table_name,'code','code='.SQLStr($code).' AND id !='.SQLStr($id));
	// if($check_code!=''){

	// 	$errMsg = _('編號').' '._('重複,不允許儲存').'!';
	// }else{

		$check_name = getColumnValue($table_name,'pic_name','pic_name='.SQLStr($pic_name).' AND id !='.SQLStr($id));
		if($check_name!=''){

			$errMsg = _('名稱').' '._('重複,不允許儲存').'!';

		}
	// }
	// 

	$check_uniform_number = getColumnValue($table_name,'uniform_number','uniform_number='.SQLStr($uniform_number).' AND id !='.SQLStr($id));
	if($check_uniform_number!=''){

		$errMsg = _('統一編號').' '._('重複,不允許儲存').'!';

	}

	//確認圖片
	$img_1 = isset($params['img_file_path1'])?$params['img_file_path1']:"";
	/*
	$img_2 = isset($params['img_file_path2'])?$params['img_file_path2']:"";
	$img_3 = isset($params['img_file_path3'])?$params['img_file_path3']:"";
	*/

	//判斷多張圖片
	if($img_1!="" &&$img_1['name']!=""){
		$tmp_img_name = $img_1['name'];
		$check_img_sql = " SELECT * FROM pic WHERE (org_img1='$tmp_img_name') AND id <> ".SQLStr($id);
		$rs = query($check_img_sql);
		$arr = fetch_array($rs);
		if(count($arr)>0){
			$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
		}
	}
	/*
	if($img_1!="" &&$img_1['name']!=""){
		$tmp_img_name = $img_1['name'];
		$check_img_sql = " SELECT * FROM pic WHERE (org_img1='$tmp_img_name' OR org_img2='$tmp_img_name' OR org_img3='$tmp_img_name') AND id <> ".SQLStr($id);
		$rs = query($check_img_sql);
		$arr = fetch_array($rs);
		if(count($arr)>0){
			$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
		}
	}
		if($img_2 !="" && $img_2['name']!=""){
		$tmp_img_name = $img_2['name'];
		$check_img_sql = " SELECT * FROM pic WHERE (org_img1='$tmp_img_name' OR org_img2='$tmp_img_name' OR org_img3='$tmp_img_name') AND id <> ".SQLStr($id);
		$rs = query($check_img_sql);
		$arr = fetch_array($rs);
		if(count($arr)>0){
			$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
		}
	}

	if($img_3 !="" && $img_3['name']!=""){
		$tmp_img_name = $img_3['name'];
		$check_img_sql = " SELECT * FROM pic WHERE (org_img1='$tmp_img_name' OR org_img2='$tmp_img_name' OR org_img3='$tmp_img_name') AND id <> ".SQLStr($id);
		$rs = query($check_img_sql);
		$arr = fetch_array($rs);
		if(count($arr)>0){
			$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
		}
	}*/


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

					$img_file = $params[$field_name];	 
	    			$size = getimagesize($img_file["tmp_name"]);

					$upload_file_name='';
					if($files['name']!=''){

						$params['org_img'.substr($field_name,-1)] = $files['name'];	
						//解決中文無法上傳
						$files['name'] = ($files['name']);
						// $files['name'] = md5($files['name']);

						$upload_file_name = $dir_path.'/'.$files["name"];
						// unlink($id.'_'.$files["name"]);
						move_uploaded_file($files["tmp_name"],$upload_file_name);
						chmod($upload_file_name, 0777);

						//============在傳一次縮圖 1280x720
					
						// 取得上傳圖片
						// $src = imagecreatefromjpeg($upload_file_name);

				        switch ($files['type']) {
			            case 'image/jpeg': 
			            	$ext = ".jpg"; 
							
							// 取得上傳圖片
			            	$src = imagecreatefromjpeg($upload_file_name);
			            	break;
			            case 'image/png': 
			            	$ext = ".png"; 
			            	// 取得上傳圖片
			            	$src = imagecreatefrompng($upload_file_name);
			            break;
			            default: 
			            	$ext = ''; 

			            break;
			        }

				        $newwidth = "640";
				        $newheight = "360";
				        // $newwidth = "1280";
				        // $newheight = "720";
				        // Load
						$thumb = imagecreatetruecolor($newwidth, $newheight);

						// 開始縮圖
						imagecopyresampled($thumb, $src, 0, 0, 0, 0, $newwidth, $newheight, $size[0], $size[1]);
						// $source = imagecreatefromjpeg($url.'/'.$files['name']);//  /tmp/phpxOA1TK

						// 儲存縮圖到指定  目錄
						if($files['type']=="image/jpeg"){
							imagejpeg($thumb, $dir_prefix_log.$files['name']);
						}else if($files['type']=="image/png"){
							imagepng($thumb, $dir_prefix_log.$files['name']);
						}
					
						chmod($dir_prefix_log.$files['name'], 0777);
						
	 					//=======================

						// 圖片寬高
						$params[$field_name] = $files['name'];	

						$owidth  = $size[0]; 
						$oheight = $size[1];
												
						$w_name = "width_".substr($field_name,-1);
						$h_name = "height_".substr($field_name,-1);

						$params[$w_name] = $owidth;	
						$params[$h_name] = $oheight;
						// 圖片寬高end
						
						$params[$field_name] = $files['name'];	

					}else{
						$params[$field_name]="";
					}

					$update_sql .= ' = ';
					$update_sql .= SQLStr($params[$field_name]);
					$update_sql .= ',';
					$update_sql .= 'org_img'.substr($field_name,-1).'='.SQLStr($params['org_img'.substr($field_name,-1)]).',';
					$update_sql .= 'width_'.substr($field_name,-1).' = '.SQLStr($params['width_'.substr($field_name,-1)]).',';
					$update_sql .= 'height_'.substr($field_name,-1).' = '.SQLStr($params['height_'.substr($field_name,-1)]).',';


				}//end if isset

			}
			/*
			else if(like($field_name,'width')){

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
			}*/


			$i++;
		}

		$update_sql = substr($update_sql,0, -1);

		$update_sql .= ' WHERE id = '.SQLStr($id);

			if($print_sql)sql_log($file_name,'params:'.serialize($params));
			if($print_sql)sql_log($file_name,$update_sql);

		query($update_sql);
		query('COMMIT');

		query('BEGIN');
		//更新到detail
		$check_main = getColumnValue("pic_detail",'detail_id',' pic_no=1 AND uniform_number='.SQLStr($uniform_number).' LIMIT 1');

		if(!$check_main){
			
			//新增一份到detail
			// $pic_main_id = getColumnValue($table_name,'id','uniform_number='.SQLStr($uniform_number).' LIMIT 1');


			$sql = 'SELECT *'
			.' FROM '.$table_name
			.' WHERE (1=1)  AND uniform_number='.SQLStr($uniform_number).' LIMIT 1';

			$rs = query($sql);
			$rs_pic = fetch_array($rs);
			$arr_pic = $rs_pic[0];

			$sql_inset_detail = " INSERT pic_detail (pic_main_id,uniform_number,pic_detail_name,pic_no,img_file_path1,org_img1,width_1,height_1,create_id,create_name,create_time) VALUE (";
			$sql_inset_detail .="'".$arr_pic['id']."','".$uniform_number."','".$pic_name."' ,'1' ,'".$arr_pic['img_file_path1']."' ,'".$arr_pic['org_img1']."' ,'".$arr_pic['width_1']."','".$arr_pic['height_1']."','".$arr_pic['create_id']."','".$arr_pic['create_name']."' ,'".$arr_pic['create_time']."') ";
			query($sql_inset_detail);
			
			
		}else{
			$sql = 'SELECT *'
			.' FROM pic'
			.' WHERE (1=1) AND id='.SQLStr($id);
			$rs = query($sql);
			$arr_pic = fetch_array($rs);

			$Update_detail = " UPDATE  pic_detail SET uniform_number=".SQLStr($arr_pic[0]['uniform_number']).","
													." pic_detail_name=".SQLStr($arr_pic[0]['pic_name']).","
													." img_file_path1=".SQLStr($arr_pic[0]['img_file_path1']).","
													." org_img1=".SQLStr($arr_pic[0]['org_img1']).","
													." width_1=".SQLStr($arr_pic[0]['width_1']).","
													." height_1=".SQLStr($arr_pic[0]['height_1']).","
													." modify_id=".SQLStr($arr_pic[0]['modify_id']).","
													." modify_name=".SQLStr($arr_pic[0]['modify_name']).","
													." modify_time=".SQLStr($arr_pic[0]['modify_time'])
								." WHERE pic_main_id=".SQLStr($id)." AND pic_no=1 ";

			query($Update_detail);

			$Update_detail = " UPDATE  pic_detail SET uniform_number=".SQLStr($arr_pic[0]['uniform_number']).","
												." modify_time=".SQLStr($arr_pic[0]['modify_time'])
							." WHERE pic_main_id=".SQLStr($id);

			query($Update_detail);
		}

		

		//修改商品------------------------------------------------------------------


		

		query('COMMIT');
	}//end if err_msg
	$return_str = $id;


}else if($type=='delete'){

	extract($params);
	
	$id=$master_id;


	query('BEGIN');

	//刪除檔案-----------------------------------------------------------------------

	$img_file_path1 = getColumnValue($table_name,'img_file_path1','id='.SQLStr($id));

	$sql = 'DELETE FROM '.$table_name.' WHERE id='.SQLStr($id);
		if($print_sql)sql_log($file_name,$sql);
	query($sql);
	// unlink('../../detail/'.$img_file_path1);
	// unlink('../../thumb/'.$img_file_path1);

	//刪除相關冊頁
	$sql = 'DELETE FROM pic_detail WHERE pic_main_id='.SQLStr($id);
	query($sql);

	query('COMMIT');

	//刪除圖片實體路徑
	// unlink('../../upload/e5f54a4715dd0411286952ccc5ae4487');
	// rmdir_recursive('../../upload/'.$img_file_path1);

	
}

