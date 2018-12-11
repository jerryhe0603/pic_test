<?php 
include_once('./Config.php');
include_once('./SQL/Data/SQLData.php');
include_once('./SQL/SQL_Connect.php');
include_once('./function/Function.php');
include_once('./Session.php');


$pic_main_id = $_GET['pic_main_id'];

if(!$pic_main_id){
    echo "無主檔ID,請重新登入";
}

$aPicDetailArr = array();
$pic_detail_sql = "SELECT * FROM pic_detail WHERE pic_main_id='$pic_main_id' ";
// echo $pic_detail_sql;
$rs_detail_sql = query($pic_detail_sql);

$pic_detail = fetch_array($rs_detail_sql);

foreach ($pic_detail as $key => $value) {
    $aPicDetailArr = $pic_detail;
}


// echo "<pre>"; print_r($aPicDetailArr);exit;

?>

<head>
	<script type="text/javascript" src="js/jquery-3.2.1.js?<?php echo rand() ?>"></script>
	<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
	<script src="js/dropzone.js"></script>


    <script type="text/javascript" src="js/jquery-3.2.1.js?<?php echo rand() ?>"></script>
    <!-- <script src="Scripts/jquery-1.11.1.min.js"></script> -->
    <script src="http://blueimp.github.io/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
    <script src="http://blueimp.github.io/jQuery-File-Upload/js/jquery.fileupload.js"></script>
     
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="css/jquery.fileupload.css" rel="stylesheet" />
    <link href="css/jquery.fileupload-ui.css" rel="stylesheet" />

</head>
<style type="text/css">
    .previewImg {width:80px;height:60px}
</style>
<script type='text/javascript'>
	/*
    Dropzone.autoDiscover = false;

   $(document).ready(function () {

        $("#img_upload_dlg").dropzone({
            maxFiles: 20000,
            maxFilesize: 20000,
            url: "ajax/JAGetFileData.php/",
            success: function (file, response) {
            	if(response!=''){
            		console.log(response);
            		alert(response);
            	}
                // console.log(response);
            }
        });
   });*/

</script>

<script>
     $(function () {
         var filesList = []; //用來存放所選的圖片檔案Array
 
         $('#fileupload').fileupload({
             autoUpload: false,// 自動上傳False.
             url: 'ajax/JAGetFileData.php', //上傳圖片的API位置
             // url: 'xxx.com/UploadFileAPI', //上傳圖片的API位置
             dataType: 'json',
             add: function (e, data) { //選擇圖片後會跑入這個事件
 // console.log(data);
                 //將圖片讀取出來並且組成Html塞到內容區塊
                 filesList.push(data.files[0]);
                 var reader = new FileReader();
                 reader.onload = function (e) {

                     $('#presentation > .files').append(
                         $(
                         '<tr class="template-upload">' +
                         // '<tr class="template-upload fade in">' +
                             '<td><span class="preview"><img src="' + e.target.result + '" class="previewImg" /></span></td>' +
                             '<td><p class="name">' + data.files[0].name + '</p><strong class="error text-danger"></strong></td>' +
                             // '<td><p class="size">' + (data.files[0].size / 1000) + 'k</p></td>' +
                             '<td><button class="btn btn-warning cancel imgCancel"><span>取消</span></button><button class="btn btn-warning deleted imgDeleted" id="'+ data.files[0].name +'" disabled><span>刪除</span></button></td>' +
                             // '<td><button class="btn btn-warning cancel imgCancel"><span>取消</span></button></td>' +
                         '</tr>'
                         )
                     );
                 }
                 reader.readAsDataURL(data.files[0]);
             },
             progressall: function (e, data) {
                 //控制上傳的時候會跑的進度Bar
                 var progress = parseInt(data.loaded / data.total * 100, 10);
                 $('#progress .progress-bar').css(
                     'width',
                     progress + '%'
                 );
                 $('.progress-bar').val(progress + '%');
             }
         });

        //圖片取消
        $('body').on('click', '.imgCancel', function () {

           var target = $(this).parents('tr.template-upload'); //找出要取消的照片是第幾張
           filesList.splice(target.index(), 1);  //記得要把filesList中的圖片也移除掉
           target.remove();
        });

        //圖片刪除
        $('body').on('click', '.imgDeleted', function () {
            var org_img =$(this).attr('id');
            var pic_main_id = <?php echo $pic_main_id ?>;
            var url = "ajax/JADelFileData.php";
            $.ajax({
                url: url,
                data:{
                    org_img:org_img,
                    pic_main_id:pic_main_id
                    },
                type: 'POST',
                dataType:'json',
                success: function(response) {
                    console.log(response);
                   
                    var target = $(this).parents('tr.template-upload'); //找出要取消的照片是第幾張
                    filesList.splice(target.index(), 1);  //記得要把filesList中的圖片也移除掉
                    target.remove();
                }
            });

          
        });
        // submit 註冊 click 事件
        $('.submit').on('click', function (e) {
            e.preventDefault();
            //判斷filesList,有選圖片才送
            if (filesList.length > 0) {
                $('#fileupload,.imgCancel,.submit').prop('disabled', true);
                $('#fileupload,.imgDeleted,.submit').prop('disabled', true);
                

                // fileupload api: send, 將 filesList 的檔案送至指定的 url.
                $('#fileupload').fileupload('send', { files: filesList ,

                    url: 'ajax/JAGetFileData.php' ,
                    success:function (result, textStatus, jqXHR) {
                        //呼叫成功Call Back的地方
                        console.log(result);
                    },
                    error:function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR.responseText);
                    },
                    complete:function (result, textStatus, jqXHR) {
                        
                        //傳送完成後,讓Submit按鈕重新開啟
                        $('#fileupload,.submit').prop('disabled', false);
                        $('#fileupload,.imgDeleted,.submit').prop('disabled', false);
                        filesList.length = [];
                    }
                    /*,
                    progressall: function (e, data) {
                         //控制上傳的時候會跑的進度Bar
                         var progress = parseInt(data.loaded / data.total * 100, 10);
                         $('#progress .progress-bar').css(
                             'width',
                             progress + '%'
                         );
                    }*/
                });
                    
            }
        });
     });

     
        
 </script>

<style type="text/css">
    
.modal-content {
  width: 750px;
  
}
</style>

<form id="img_upload_dlg" name='img_upload_dlg' method="post" enctype="multipart/form-data" >
	
	<!-- <div class="fitem"> -->
	<!-- <div id=myid class="fallback">
	    <input id="file" name="file" type="file"  />
  	</div> -->
    
    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
     <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span class="btn btn-success fileinput-button">
                        <span>選擇圖片</span>
                        <!-- The file input field used as target for the file upload widget -->
                        <input id="fileupload" type="file" name="images[]" multiple="">
                    </span>
                    <span class="btn btn-primary submit">
                        <span>開始上傳圖片</span>
                    </span>
                     
 
                     
 
                    <div id="progress" class="progress">
                        <div class="progress-bar progress-bar-success"></div>
                    </div>
                    <table role="presentation" class="table table-striped" id="presentation" width="100%" height="90%">
                        <?php 
                            foreach ($aPicDetailArr as $key => $value) {
                                $org_img = $value['org_img1'];
                                ?>
                                <tr class="template-upload" width="90%">
                                    <td><span class="preview"><img src="detail/<?php echo $org_img ?>" class="previewImg" /></span></td>
                                    <td><p class="name"><?php echo $org_img ?></p><strong class="error text-danger"></strong></td>
                                    <td>
                                        <button class="btn btn-warning deleted imgDeleted" id="<?php echo $org_img ?>" ><span>刪除</span></button>
                                        <!-- <input id class="btn btn-warning deleted imgDeleted" value="刪除"> -->
                                        <!-- <button class="btn btn-warning deleted imgCancel"><span>刪除</span></button> -->
                                    </td>
                                </tr>
                        <?php     
                            }
                        ?>
                        <tbody class="files"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button> -->
                </div>
            </div>
        </div>
    </div>

	<input id="select_id" name="select_id" type='hidden' class='' value='1'  default_value='1' />
</form>
