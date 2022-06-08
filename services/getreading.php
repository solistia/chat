<?php 
	require 'pusher/trigger.php';
	require 'cloudinary/trigger.php';
	require_once 'service.php';
	$services = new Services();


	if(isset($_POST['userid'])){
		$update_message = $services->update_table_chat_message_reading($_POST['userid']);
		$condition = 'WHERE userid!="'.$_POST['userid'].'"';
	}else{
		$condition = '';
	}

	
	$userlist = $services->select_table_chat_message($condition);

	$data;
	foreach($userlist as $row){
		if($row['count'] > 0){	
			$count = '<div class="new bg-red" id="'.$row['userid'].'count" bis_skin_checked="1"><span class="count_num">'.$row['count'].'</span></div>';
		}else{
			$count = '';
		}

		$data = $data.'<a href="#" onclick="getMessage(this.id)" class="filterDiscussions all read single user_list" id="'.$row['userid'].'" data-toggle="list" role="tab">
							<img class="avatar-md" src="../dist/img/avatars/images.png" data-toggle="tooltip" data-placement="top" title="Keith" alt="avatar">
							'.$count.'
							<div class="data">
								<h5>'.$row['userid'].'</h5>
								<p>NOTE</p>
							</div>
						</a>';
	}

	echo $data;
?>