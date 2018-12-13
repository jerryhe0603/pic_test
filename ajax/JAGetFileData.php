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
$files = $_FILES['images'];

// $x = (!empty($files['tmp_name']))?1:0;
// $check_pic = $x;
// if($check_pic==0){
// 	$errMsg = _('圖片上傳有缺').' '._('不允許儲存').'!';
// }
$aFileArr = array();
$FileNum = count($files['name']);

for ($i=0; $i <$FileNum ; $i++) { 
	foreach ($files as $files_key => $files_value) {
		$aFileArr[$i][$files_key] = $files_value[$i];
	}
}
// print_r($aFileArr);exit;



foreach ($aFileArr as $files_key => $files_value) {
	$pic_main_id = $_REQUEST['select_id'];
	$uniform_number = getColumnValue("pic",'uniform_number',' id ='.SQLStr($pic_main_id));
	$pic_name = getColumnValue("pic",'pic_name',' id ='.SQLStr($pic_main_id));
	$pic_no = getColumnValue("pic_detail",' MAX(pic_no) ',' pic_main_id ='.SQLStr($pic_main_id));
	$pic_no = ($pic_no+1);

	// $img_file_path1 = ;
	// $org_img1 = ;
	// $width_1 = ;
	// $height_1 = ;
	$create_id = $_SESSION['user_id'];
	$create_name = $_SESSION['user_name'];
	$create_time = date('Y-m-d H:i:s');

	//確認圖片
    $size_1 = getimagesize($files_value["tmp_name"]);

    //上傳目錄
	$dir_path = $dir_prefix;
	if(!is_dir($dir_path)){
		mkdir($dir_path,0777,true);
	}

	$upload_file_name='';

	$size = getimagesize($files_value["tmp_name"]);
	$org_img1 = $files_value['name'];	
	$img_file_path1 = $files_value['name'];	
	// $params['org_img'.substr($field_name,-1)] = $files_value['name'];	
	//解決中文無法上傳
	$files_value['name'] = ($files_value['name']);
	// $files_value['name'] = md5($files_value['name']);

	$upload_file_name = $dir_path.$files_value["name"];
	// unlink($id.'_'.$files_value["name"]);
	move_uploaded_file($files_value["tmp_name"],$upload_file_name);
	chmod($upload_file_name, 0777);
	// if($pic_link==''){
		$pic_link_arr[] = $url.'/'.$files_value['name'];
	// }
	
	//============在傳一次縮圖 1280x720
	switch ($files_value['type']) {
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
	// $source = imagecreatefromjpeg($url.'/'.$files_value['name']);//  /tmp/phpxOA1TK

	// 儲存縮圖到指定  目錄
	if($files_value['type']=="image/jpeg"){
		imagejpeg($thumb, $dir_prefix_log.$files_value['name']);
	}else if($files_value['type']=="image/png"){
		imagepng($thumb, $dir_prefix_log.$files_value['name']);
	}

	chmod($dir_prefix_log.$files_value['name'], 0777);
			
	// 圖片寬高
	// $params[$field_name] = $files_value['name'];	

	$owidth  = $size[0]; 
	$oheight = $size[1];
	
	
	// }
	$width_1 = $owidth;
	$height_1 = $oheight;
	// $params[$w_name] = $owidth;	
	// $params[$h_name] = $oheight;
	// 圖片寬高end
	

	//如果圖名存在就覆蓋
	$check_pic_detail_org_img1 = getColumnValue("pic_detail",'org_img1',' org_img1 ='.SQLStr($org_img1).' AND pic_main_id='.SQLStr($pic_main_id) );

	if($check_pic_detail_org_img1){
		$Update_detail = " UPDATE  pic_detail SET width_1=".SQLStr($width_1).","
												." height_1=".SQLStr($height_1).","
												." modify_id=".SQLStr($create_id).","
												." modify_name=".SQLStr($create_name).","
												." modify_time=".SQLStr($create_time)
							." WHERE pic_main_id=".SQLStr($pic_main_id)." AND org_img1=".SQLStr($org_img1);

		query($Update_detail);
	}else{
		$sql_inset_detail = " INSERT pic_detail (pic_main_id,uniform_number,pic_detail_name,pic_no,img_file_path1,org_img1,width_1,height_1,create_id,create_name,create_time) VALUE (";
		$sql_inset_detail .="'".$pic_main_id."','".$uniform_number."','".$pic_name."' ,'".$pic_no."' ,'".$img_file_path1."' ,'".$org_img1."' ,'".$width_1."','".$height_1."','".$create_id."','".$create_name."' ,'".$create_time."') ";
		query($sql_inset_detail);
	}
	


}


// $file = fopen('../log/JAGetData.txt_'.date('YmdHis').'.txt','a+');
// fwrite($file,'serialize:'."\r\n".serialize(($_REQUEST))."\r\n");
// fclose($file);








?>
