<?php
	
	$no_header = 1;
	$rootpath = dirname(__FILE__);
	set_include_path($rootpath);
	
	include_once('Config.php');
	include_once('css/EasyUI_CSS.php');
	include_once('SQL/Data/SQLData.php');
	include_once('SQL/SQL_Connect.php');

	include_once('function/Function.php');
	
	include_once('API/Pic_Interface.php');

	include_once('Session.php');
?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>圖片管理</title>

	<link rel=stylesheet type="text/css" href="css/theme/default/Default.css?<?php echo rand() ?>">
	<link rel=stylesheet type="text/css" href="css/theme/default/TopMenu.css?<?php echo rand() ?>">
	<script type="text/javascript" src="js/jquery-3.2.1.js?<?php echo rand() ?>"></script>
	<script type="text/javascript" src="js/Function.js?<?php echo rand() ?>"></script>

	<!--  easyui -->
	<link rel="stylesheet" type="text/css" href="js/easyui/themes/default/easyui.css?<?php echo rand() ?>">
	<link rel="stylesheet" type="text/css" href="js/easyui/themes/mobile.css?<?php echo rand() ?>">
	<link rel="stylesheet" type="text/css" href="js/easyui/themes/icon.css?<?php echo rand() ?>">
	<link rel="stylesheet" type="text/css" href="js/easyui/themes/color.css?<?php echo rand() ?>">
	<link rel="stylesheet" type="text/css" href="js/easyui/demo/demo.css?<?php echo rand() ?>">
	
	<script type="text/javascript" src="js/easyui/jquery.easyui.min.js?<?php echo rand() ?>"></script>
	<script type="text/javascript" src="js/easyui/jquery.easyui.mobile.js?<?php echo rand() ?>"></script>
	<script type="text/javascript" src="js/easyui/extension/datagrid-filter/datagrid-filter.js?<?php echo rand() ?>"></script>
	<script type="text/javascript" src="js/easyui/extension/datagrid-detailview.js?<?php echo rand() ?>"></script>

	<script type="text/javascript" src="js/ExtendEasyUI.js?<?php echo rand() ?>"></script>
	<!-- <script type='text/javascript'>
		$(function(){
			$("ul.navagation > li:has(ul) > a").append('<div class="arrow-bottom"></div>');
			$("ul.navagation > li ul li:has(ul) > a").append('<div class="arrow-right"></div>');
		});
	</script> -->
	
	<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
	<script src="js/dropzone.js"></script>
	<script src="./js/jquery.colorbox-min.js"></script>
	
	<script type='text/javascript'>
		
		function logout(){
			if(!confirm('<?php echo _('確定要登出嗎')?>?'))return;

			window.location.href="Logout.php";		
		}
	

	</script>
</head>
<body>

<table border="0" width="100%">
<tr>
	
	
	<td valign="center" class=navigation>
		<ul class=navigation style="text-align: left">
			<?php if ($_SESSION['user_id'] =="admin"){ ?>
				<li>
					<a href='./User.php'>使用者管理</a>
				</li>
			<?php } ?>
			<?php if ($_SESSION['user_id'] =="admin"){ ?>
				<li>
					<a href='./PicType.php'>圖片類別</a>
				</li>
				<!-- <li>
					<a href='./PicTypeOrder.php'>圖片類別順序</a>
				</li> -->
			<?php } ?>
			<li>
				<a href='./PicShow.php'>圖片管理</a>
			</li>
			<!-- <li>
				<a href='./PicDetailShow.php'>冊頁圖檔管理</a>
			</li> -->

			
		</ul>
	</td>
	<td align="right" valign="center">
		<span style='font-size:18px;font-weight:bold;'><BR><?php echo $_SESSION['user_name'] ?> </span>
	</td>
	<td valign="center" width="10%">
		<ul class=navigation>
			<li>
				<a href='javascript:logout();'><?php echo _('登出') ?></a>
				<!-- <a href='javascript:logout();' style='font-size:18px;font-weight:bold;'><?php echo _('登出') ?></a> -->
			</li>
		</ul>
	</td>
</tr>

</table>

<!-- <p style='height:50px'></p>
<hr>
<p style='height:20px'></p> -->

