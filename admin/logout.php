<?php
	session_start();

	if(!empty($_SESSION['username'])){
		session_destroy();
		header('location: login');
	}else{
		header('location: login');
	}
?>