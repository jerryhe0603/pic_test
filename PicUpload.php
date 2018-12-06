<head>
	<script type="text/javascript" src="js/jquery-3.2.1.js?<?php echo rand() ?>"></script>
	<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
	<script src="js/dropzone.js"></script>

</head>

<script type='text/javascript'>
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
   });

</script>


<form id="img_upload_dlg" name='img_upload_dlg' method="post" class="dropzone" enctype="multipart/form-data" >
	
	<!-- <div class="fitem"> -->
	<div id=myid class="fallback">
	    <input id="file" name="file" type="file"  />
  	</div>

	<input id="select_id" name="select_id" type='hidden' class='' value='1'  default_value='1' />
</form>