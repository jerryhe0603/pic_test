<?php

	include('Config.php');
	include_once('function/Function.php');
	
	include('Session.php');
	// printArr($_SESSION);

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo SYSTEM_NAME ?></title>

	<link rel=stylesheet type="text/css" href="css/theme/default/Default.css">

	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
	<center>
		<h4><?php echo SYSTEM_NAME ?></h4>


		<form name=form1 action='index.php'  method='POST' target='_self'>
			<?php
				if(isset($_REQUEST['err_code'])){

					$err_msg=$error_code_arr[$_REQUEST['err_code']];
					echo '<font color=red>'.$err_msg.'</font>';
				}
			?>
			<table border=0	>
			<tr>
				<td><?php echo _('帳號') ?>: </td>
				<td><input type='text' name='user_account' value='' required /></td>
			</tr>

			<tr>
				<td><?php echo _('密碼') ?>: </td>
				<td><input type='password' name='user_password' value='' required  /></td>
			</tr>
			<!-- <tr>
				<td><?php echo _('語系') ?>: </td>
				<td>
					<select name='lang' >
						<option value='tw' selected >中文</option>
						<option value='en'  >English</option>
					</select>
				</td>

			</tr> -->
			<tr>
				<td colspan="2" align=center >
					<input type='submit' value='<?php echo _('登入') ?> ' />
				</td>
			</tr>
			<?php /*
			<tr>
				<td colspan="2" align=center >
					<BR>
					<img src='image/logo.jpg' width=30> <font size=2>Powered by <?php echo COMPANY_ENG_NAME ?></font>
				</td>
			</tr>*/ ?>

				
			</table>
		</form>

		<!-- <div style='padding-top:20%;padding-left:40% '>
			<img src='image/logo_no_bg.png' />
		</div> -->

		<!-- <div class=login  >
		</div> -->
	</center>

</body>
</html>

