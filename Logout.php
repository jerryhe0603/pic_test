<?php 
	
	// include_once('config.php');
	// include_once('SQL/SQL_Connect.php');
	include_once('function/Function.php');

	// include_once('session.php');


	// include_once('header.php');

	session_start();
	session_unset();
	session_destroy();

		// printArr($_SESSION);
	header('Location:index.php');
	exit;	

?>

