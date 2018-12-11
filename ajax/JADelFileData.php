<?php
/*刪除資料庫*/

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

$org_img = $_REQUEST['org_img'];
$pic_main_id = $_REQUEST['pic_main_id'];
$delete_pic_detail_sql = "DELETE FROM pic_detail WHERE pic_main_id='$pic_main_id' AND org_img1='$org_img' ";
query($delete_pic_detail_sql);
exit;



?>
