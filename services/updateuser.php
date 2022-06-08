<?php 
	require 'pusher/trigger.php';
	require 'cloudinary/trigger.php';
	require_once 'service.php';
	$services = new Services();

	if(isset($_POST['name'])){
		$userid = $_POST['userid'];
		$set = 'name="'.$_POST['name'].'"';
		$update_name = $services->update_table_chat_chat_user($userid, $set);

		if($update_name){
			$response = array(
		        "type" => "success",
		        "message" => "",
		        'element' => $_POST['userid'].'name',
		        'element2' => 'current_user_name',
		        'userid' => $_POST['userid'],
		        'value' => $_POST['name']			
			);
			pusher_trigger($response, 'system', 'update');
		}else{
			$response = array(
		        "type" => "error",
		        "message" => "database error"			
			);			
		}

		echo json_encode($response);
	}

	if(isset($_POST['note'])){
		$userid = $_POST['userid'];
		$set = 'note="'.$_POST['note'].'"';
		$update_note = $services->update_table_chat_chat_user($userid, $set);

		if($update_note){
			$response = array(
		        "type" => "success",
		        "message" => "",
		        'element' => $_POST['userid'].'note',
		        'element2' => 'current_user_note',
		        'userid' => $_POST['userid'],
		        'value' => $_POST['note']			
			);
			pusher_trigger($response, 'system', 'update');
		}else{
			$response = array(
		        "type" => "error",
		        "message" => "database error"			
			);			
		}

		echo json_encode($response);
	}

?>