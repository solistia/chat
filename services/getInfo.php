<?php 
	require 'pusher/trigger.php';
	require 'cloudinary/trigger.php';
	require_once 'service.php';

	$services2 = new Services2();


	if(!empty($_POST['userid'])){
		$userid = $_POST['userid'];

		$condition = 'playerusername = "'.$userid.'"';
		$user = $services2->select_table_user($condition);


		$message ='

		    <div class="">
		    	<label class="bold">playerusername</label>
	    		<label id="playerusername">'.$user['playerusername'].'</label>    			
    		</div>
		    <div class="">
		    	<label class="bold">betflikuser</label>
	    		<label id="betflikuser">'.$user['betflikuser'].'</label>    			
    		</div>
		    <div class="">
		    	<label class="bold">agentname_betflix </label>
	    		<label id="agentname_betflix ">'.$user['agentname_betflix'].'</label>    			
    		</div>
		    <div class="">
		    	<label class="bold">namesurname</label>
	    		<label id="namesurname">'.$user['namesurname'].'</label>    			
    		</div>
		    <div class="">
		    	<label class="bold">blankname</label>
	    		<label id="blankname">'.$user['blankname'].'</label>    			
    		</div>
		    <div class="">
		    	<label class="bold">bankaccountnumber</label>
	    		<label id="bankaccountnumber">'.$user['bankaccountnumber'].'</label>    			
    		</div>    		    				
		    <div class="">
		    	<label class="bold">recommender</label>
	    		<label id="recommender">'.$user['recommender'].'</label>    			
    		</div>
		    <div class="">
		    	<label class="bold">datetime_last </label>
	    		<label id="datetime_last ">'.$user['datetime_last'].'</label>    			
    		</div>   
		';


		if($user){
			$response = array(
				'type' => 'success',
				'message' => $message
			);
		}else{
			$response = array(
				'type' => 'error',
				'message' => 'User not found'
			);			
		}

		echo json_encode($response);
	}

?>