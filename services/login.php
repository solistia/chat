<?php 
	require_once 'service.php';
	$services = new Services();
	session_start();

	if(!empty($_POST['username']) && !empty($_POST['password'])){

		$username = $_POST['username'];
		$password = $_POST['password'];
		$user = $services->select_table_chat_admin($username);

		if($user){
			if($user['password'] == md5($password)){

				$_SESSION['username'] = $user['username'];
				$_SESSION['name'] = $user['name'];

				$response = array(
		            "type" => "success",
		            "message" => ""
				);

			}else{
				$response = array(
		            "type" => "error",
		            "message" => "Password Incorect"
				);				
			}
		}else{
			$response = array(
	            "type" => "error",
	            "message" => "Username Incorect"
			);
		}

		echo json_encode($response);
	}
?>