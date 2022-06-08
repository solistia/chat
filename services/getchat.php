<?php 
	require 'pusher/trigger.php';
	require 'cloudinary/trigger.php';
	require_once 'service.php';
	$services = new Services();

	if(!empty($_POST['userid'])){
		$userid = $_POST['userid'];

		if(isset($_POST['from'])){
			$from = $_POST['from'];
		}else{
			$from = 'user';
		}
		
		if(isset($_POST['limit'])){
			$limit = $_POST['limit'];
		}else{
			$limit = 15;
		}

		if(isset($_POST['offset'])){
			$offset = $_POST['offset'];
		}else{
			$offset = 0;
		}

		if(isset($_POST['order'])){
			$order = $_POST['order'];
		}else{
			$order = 'ASC';
		}

		$message = $services->select_table_chat_message_from_user($userid, $limit, $offset, $order);
		$data;
		foreach($message as $row){

			if($row['type'] == 'message'){
				$msg = '<pre>'.$row['message'].'</pre>';
			}

			if($row['type'] == 'image'){
				$msg = '<img src="'.$row['message'].'" width="150">';
			}

			if($row['type'] == 'video'){
				$msg = '<video width="320" height="240" controls><source src="'.$row['message'].'" type="audio/mpeg">Your browser does not support the audio element.</audio>';
			}

			if($from == 'admin'){

				if($row['send_to'] == 'system'){
					$dir = '';
				}else{
					$dir = 'me';
				}

				$data = $data.'<div class="message '.$dir.'" bis_skin_checked="1"><div class="text-main" bis_skin_checked="1">
								<div class="text-group '.$dir.'" bis_skin_checked="1">
										<div class="text '.$dir.'" bis_skin_checked="1">
												'.$msg.'
											</div>
										</div>
									<span>'.$row['datetime'].'</span>
								</div>
							</div>';			
			}else{

				if($row['send_to'] == 'system'){
					$dir = 'me';
				}else{
					$dir = '';
				}

				$data = $data.'<div class="message '.$dir.'" bis_skin_checked="1"><div class="text-main" bis_skin_checked="1">
								<div class="text-group '.$dir.'" bis_skin_checked="1">
										<div class="text '.$dir.'" bis_skin_checked="1">
												'.$msg.'
											</div>
										</div>
									<span>'.$row['datetime'].'</span>
								</div>
							</div>';	
			}
		}

		echo $data;
	}
?>