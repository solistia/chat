<?php 
	require_once '../services/service.php';
	session_start();
	if($_SESSION['username']){
		header('location: index');
	}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript" ></script>	
<form id="login">
	<input type="text" name="username">
	<input type="password" name="password">
	<button type="submit">login</button>	
</form>

<script type="text/javascript">
	$(function() {
	    //hang on event of form with id=myform
	    $("#login").submit(function(e) {

	        //prevent Default functionality
	        e.preventDefault();
	        //do your own request an handle the results
		    $.ajax({
		        url: '../services/login',  
		        type: 'POST',
		        data: $('#login').serialize(),
		        //Ajax events
		        success: function(data){
		        	var data = JSON.parse(data);
		        	if(data.type == 'success'){
		        		window.location.href = 'index';
		        	}else{
		        		alert(data.message);
		        	}
		        }
		    });

	    });

	});
</script>