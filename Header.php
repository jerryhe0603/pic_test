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

</head>
<body>


