<?php
if(isset($_FILES['files']) && $_FILES['files']['error'] == 0){
    // $upload_folder = dirname(dirname(__FILE__))."/uploads"; 
    $upload_path = "../upload/".$_FILES['files']['name'];
    move_uploaded_file($_FILES['files']['tmp_name'], $upload_path);
    echo '{"status":"success"}';    //顯示的狀態 是否上傳成功
    exit;
}
echo '{"status":"error"}';      
exit;
?>