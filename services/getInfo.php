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

		    <div class="row">
		    	<div class="col-4">
		    		<label class="bold">playerusername</label>
		    	</div>
		    	<div class="col-7">		    	
	    			<label id="playerusername">'.$user['playerusername'].'</label>
	    		</div>      			
    		</div>
		    <div class="row">
		    	<div class="col-4">
		    		<label class="bold">betflikuser</label>
		    	</div>
		    	<div class="col-7">		    	
	    			<label id="betflikuser">'.$user['betflikuser'].'</label>
	    		</div>      			
    		</div>
		    <div class="row">
		    	<div class="col-4">
		    		<label class="bold">agentname_betflix </label>
		    	</div>
		    	<div class="col-7">		    	
	    			<label id="agentname_betflix ">'.$user['agentname_betflix'].'</label>
	    		</div>      			
    		</div>
		    <div class="row">
		    	<div class="col-4">
		    		<label class="bold">namesurname</label>
		    	</div>
		    	<div class="col-7">		    	
	    			<label id="namesurname">'.$user['namesurname'].'</label>
	    		</div>      			
    		</div>
		    <div class="row">
		    	<div class="col-4">
		    		<label class="bold">blankname</label>
		    	</div>
		    	<div class="col-7">	    	
	    			<label id="blankname">'.$user['blankname'].'</label> 
	    		</div>     			
    		</div>
		    <div class="row">
		    	<div class="col-4">
		    		<label class="bold">bankaccountnumber</label>
		    	</div>
		    	<div class="col-7">
	    			<label id="bankaccountnumber">'.$user['bankaccountnumber'].'</label>    	
	    		</div>  		
    		</div>    		    				
		    <div class="row">
		    	<div class="col-4">
		    		<label class="bold">recommender</label>
		    	</div>
		    	<div class="col-7">
	    			<label id="recommender">'.$user['recommender'].'</label>  
	    		</div>    			
    		</div>
		    <div class="row">
		    	<div class="col-4">
		    		<label class="bold">datetime_last </label>
		    	</div>
		    	<div class="col-7">
	    			<label id="datetime_last ">'.$user['datetime_last'].'</label>
	    		</div>    			
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