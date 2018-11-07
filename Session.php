<?php

	$user_account = '';
	$user_password = '';

	extract($_POST);

	session_start();

	// printArr($_SESSION);	
	// printArr($_POST);
	
	/*
	$_SESSION["user_id"] = "admin";
	$_SESSION["user_name"] = "系統管理者";
	$_SESSION["password"] = "63be44247e729779c381a0eca56df8fb";
	$_SESSION["is_void"] = 0;
	$_SESSION["last_login_time"] = "";
	$_SESSION["description"] = "";
	$_SESSION["create_id"] = "admin";
	$_SESSION["create_name"] = "系統管理者";
	$_SESSION["create_time"] = "2017-09-25 14:35:14";
	$_SESSION["lang"] = "en";*/
	
	if(!isset($_SESSION['user_id'])){

		//確認是否有填帳號密碼
		if($user_account!='' && $user_password!=''){

			if(MCRYPT=='md5'){
				$user_password = md5($user_password);
			}	

				//初始化

			$Session_SQL_data = new SQLData('SessionData','user');
			// $SQL_data->setDataType('SessionData');
			$Session_SQL_data->setParams(array('user_account'=>$user_account,'user_password'=>$user_password));

			$arr = $Session_SQL_data->getData('select',false);

			
			if(count($arr)==0){
				//config.php
				// echo 123;exit;
				header('Location:Login.php?err_code=1');exit;
			}

			if($arr[0]['user_id']!=''){
				foreach($arr[0] as $key=>$value){

					$_SESSION[$key] = $value;
					
				}
			}

			// $_SESSION['lang'] = $lang;


			unset($arr);

		}else{
			// echo 'self:'.$_SERVER['PHP_SELF'];
			if(!like($_SERVER['PHP_SELF'],'Login')){
				header('Location:Login.php');exit;
			}

		}
		
	}else{
		//避免重複登入
		if(like($_SERVER['PHP_SELF'],'Login')){
			header('Location:index.php?');exit;
		}	
	}

	
?>