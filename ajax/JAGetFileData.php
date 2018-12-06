<?php
// $no_header=true;
// include('../Header.php');
include_once('../Config.php');
include_once('../SQL/Data/SQLData.php');
include_once('../SQL/SQL_Connect.php');
include_once('../function/Function.php');
include_once('../Session.php');

// print_r($_REQUEST);exit;
// print_r($_FILES);exit;
// $key = $_REQUEST['key'];

//上傳檔案的路徑
$dir_prefix='../detail/';
$url = 'http://'.$_SERVER['HTTP_HOST'].'/pic_test/detail';
$url_log = 'http://'.$_SERVER['HTTP_HOST'].'/pic_test/thumb';
$dir_prefix_log = '../thumb/';



$errMsg="";
//確認圖片
$files = $_FILES['file'];
$x = (!empty($files['tmp_name']))?1:0;
$check_pic = $x;
if($check_pic==0){
	$errMsg = _('圖片上傳有缺').' '._('不允許儲存').'!';
}

if($files['name']!=""){
	$tmp_img_name = $files['name'];
	$check_img_sql = " SELECT * FROM pic_detail WHERE org_img1='$tmp_img_name' ";
	$rs = query($check_img_sql);
	$arr = fetch_array($rs);
	if(count($arr)>0){
		$errMsg = '與'.$arr[0]['pic_name']._('圖片檔名重複').' '._('不允許儲存').'!';
	}
}




if($errMsg==""){
	$pic_main_id = $_REQUEST['select_id'];
	$uniform_number = getColumnValue("pic",'uniform_number',' id ='.SQLStr($pic_main_id));
	$pic_name = getColumnValue("pic",'pic_name',' id ='.SQLStr($pic_main_id));
	$pic_no = getColumnValue("pic_detail",' MAX(pic_no) ',' pic_main_id ='.SQLStr($pic_main_id));
	$pic_no = $pic_no+1;


	// $img_file_path1 = ;
	// $org_img1 = ;
	// $width_1 = ;
	// $height_1 = ;
	$create_id = $_SESSION['user_id'];
	$create_name = $_SESSION['user_name'];
	$create_time = date('Y-m-d H:i:s');

	//確認圖片
    $size_1 = getimagesize($files["tmp_name"]);
	

	//上傳目錄
	$dir_path = $dir_prefix;
	if(!is_dir($dir_path)){
		mkdir($dir_path,0777,true);
	}

	$upload_file_name='';
	if($files['name']!=''){

		$size = getimagesize($files["tmp_name"]);
		$org_img1 = $files['name'];	
		$img_file_path1 = $files['name'];	
		// $params['org_img'.substr($field_name,-1)] = $files['name'];	
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
		// $params[$field_name] = $files['name'];	

		$owidth  = $size[0]; 
		$oheight = $size[1];
		
		// $w_name = "width_".substr($field_name,-1);
		// $h_name = "height_".substr($field_name,-1);
		
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
		$width_1 = $owidth;
		$height_1 = $oheight;
		// $params[$w_name] = $owidth;	
		// $params[$h_name] = $oheight;
		// 圖片寬高end
	}



	// echo $pic_main_id."==".$uniform_number."==".$pic_name."==".$pic_no."==".$img_file_path1."==".$org_img1."==".$width_1."==".$height_1."==".$user_id."==".$user_name."==".$create_time;exit;
	// 新增資料到資料庫
	$sql_inset_detail = " INSERT pic_detail (pic_main_id,uniform_number,pic_detail_name,pic_no,img_file_path1,org_img1,width_1,height_1,create_id,create_name,create_time) VALUE (";
			$sql_inset_detail .="'".$pic_main_id."','".$uniform_number."','".$pic_name."' ,'".$pic_no."' ,'".$img_file_path1."' ,'".$org_img1."' ,'".$width_1."','".$height_1."','".$create_id."','".$create_name."' ,'".$create_time."') ";
			query($sql_inset_detail);
}else{

	echo $errMsg;exit;


}







// $file = fopen('../log/JAGetData.txt_'.date('YmdHis').'.txt','a+');
// fwrite($file,'serialize:'."\r\n".serialize(($_REQUEST))."\r\n");
// fclose($file);








?>
