<?php 

if(!empty($_GET['returl_url'])){
	$returl_url = $_GET['returl_url'];
}else{
	$returl_url = '#';
}

if(!empty($_GET['token'])){
	$token = $_GET['token'];
}else{
	header('location: '. $returl_url);
	die('not allowed');
}

//start include require file
require_once 'services/service.php';
$services = new Services();
$services2 = new Services2();

//login
$condition = 'idlogin = "'.$token.'"';
$login = $services2->select_table_user($condition);

if(!$login){
	header('location: '. $returl_url);
	die('not allowed');
}

$userid = $login['playerusername'];
$agent_name = $login['agentname_betflix'];
$fullname = $login['namesurname'];

//creatre chat user
$user = $services->select_table_chat_user($userid);
if(!$user){
	$create_user = $services->insert_table_chat_user($userid, $fullname, $agent_name, $datetimenow, $datetimenow); 
}

?>
<head>
	<meta charset="utf-8">
	<title>Swipe – The Simplest Chat Platform</title>
	<meta name="description" content="#">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap core CSS -->
	<link href="dist/css/lib/bootstrap.min.css" type="text/css" rel="stylesheet">
	<link href="dist/css/image_reloader.css" type="text/css" rel="stylesheet">
	<!-- Swipe core CSS -->
	<link href="dist/css/swipe.min.css" type="text/css" rel="stylesheet">
	<!-- Favicon -->
	<link href="dist/img/favicon.png" type="image/png" rel="icon">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript" ></script>	
		<script src="https://js.pusher.com/7.1/pusher.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js" type="text/javascript" ></script>
	<script src="dist/js/jquery.imageReloader.js" type="text/javascript" ></script>		
</head>
<body>
	<main>
		<div class="layout">
			<div class="main">
				<div class="tab-content" id="nav-tabContent">
					<!-- Start of Babble -->
					<div class="babble tab-pane fade active show" id="list-chat" role="tabpanel" aria-labelledby="list-chat-list">
						<!-- Start of Chat -->
						<div class="chat" id="chat1">
							<div class="top">
								<div class="container">
									<div class="col-md-12">
										<div class="inside">
											<a href="#"></a>
											<div class="data">
												<h5><a href="<?= $returl_url ?>">HOME</a></h5>
												<span><?= $fullname ?></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="content" id="content">
								<div class="container" id="message_area_container">
									<div class="col-md-12" id="message_area">																									
										<!-- MESSAGE AREA -->
									
									</div>
								</div>
							</div>
							<div class="container">
								<div class="col-md-12">
									<div class="bottom">
										<div class="position-relative w-100">
											<textarea id="send-message" name="message" class="form-control" rows="1" placeholder="Type Here ...."></textarea>
											<button type="submit" id="send_message" class="btn send"><i class="material-icons">send</i></button>
										</div>
										<form id="send-file">
											<label>
												<input type="hidden" name="userid" value="<?= $userid ?>">
												<input type="file" name="fileupload" accept="image/png, image/jpeg, video/mp4" onchange="uploadFile(this)"  >
												<span class="btn attach d-sm-block">
													<i class="material-icons">attach_file</i>
												</span>
											</label> 												
										</form>

									</div>
								</div>
							</div>
						</div>
						<!-- End of Chat -->
					</div>
					<!-- End of Babble -->
				</div>
			</div>
		</div> <!-- Layout -->
	</main>

	<script type="text/javascript">
		var offset = 15;
		var audio = new Audio('dist/sound/line.mp3');

	  	$(function(){
		    // Enable pusher logging - don't include this in production
		    //Pusher.logToConsole = true;

			$('#content').scroll(function() {
				if($('#content').scrollTop() == 0){
		            getMoreMessage();	
				}
			});			    

		    var pusher = new Pusher('04f1f2f158fedc82e415', {
		      cluster: 'ap1'
		    });

		    var channel = pusher.subscribe('<?= $userid ?>');

		    channel.bind('message', function(data) {
		    	var message;
		    	var dir;
		    	if(data.type == 'message'){
		    		message = '<pre>'+data.message+'</pre>';
		    	}

		    	if(data.type == 'image'){
		    		message = '<img src="'+data.message+'" class="slow-images" width="150">';
		    	}

		    	if(data.type == 'video'){
		    		message = '<video width="320" height="240" controls><source src="'+data.message+'" type="video/mp4">Your browser does not support the video tag.</video>';
		    	}

		    	if(data.sendto == 'system'){
		    		dir = 'me';
		    	}else{
		    		dir = '';
		    		audio.play();
		    	}

		    	var append = '<div class="message '+dir+'" bis_skin_checked="1"><div class="text-main" bis_skin_checked="1">'+
							'<div class="text-group '+dir+'" bis_skin_checked="1">'+
									'<div class="text '+dir+'" bis_skin_checked="1">'+
											message+
										'</div>'+
									'</div>'+
								'<span>'+data.date+'</span>'+
							'</div>'+
						'</div>';

				$('#message_area').append(append);
				$("#content").animate({ scrollTop: $('#message_area_container').prop("scrollHeight")}, 1000);
				$(".slow-images").imageReloader();
		    });		    

		    //send message
			$("#send-message").keypress(function (e) {

			    if(e.which === 13 && !e.shiftKey && $(this).val() && $(this).val().trim()) {
			        e.preventDefault();
			        $message = $(this);
				    $.ajax({
				        url: 'services/post',  
				        type: 'POST',
				        data: {'message': $message.val().trim(), 'userid': '<?= $userid ?>'},
				        //Ajax events
				        success: function(data){
				        	$message.val('');
				        	//console.log(data);
				        }
				    });
			    }
		    }); 


			$('#send_message').click(function(){
			    $message = $('#send-message');
			    console.log($message);
			    if($message.val() && $message.val().trim()){
				    $.ajax({
				        url: 'services/post',  
				        type: 'POST',
				        data: {'message': $message.val().trim(), 'userid': '<?= $userid ?>'},
				        //Ajax events
				        success: function(data){
				        	$message.val('');
				        	//console.log(data);
				        }
			    	});		    	
			    }
			});

			// getmessage
		    $.ajax({
		        url: 'services/getchat',  
		        type: 'POST',
		        data: {'userid': '<?= $userid ?>', 'order': 'ASC'},
		        //Ajax events
		        success: function(data){
		        	$('#message_area').append(data);
		        	var lastMsg = $('.message:last');
					$("#content").animate({ scrollTop: $('#message_area_container').prop("scrollHeight")+1000}, 1500);
		        }
		    });

		});	

	  	//get more message
	    function getMoreMessage(){
		    $.ajax({
		        url: 'services/getchat',  
		        type: 'POST',
		        data: {'userid': '<?= $userid ?>', 'order': 'ASC', 'offset': offset},
		        //Ajax events
		        success: function(data){
		        	if(data){
			        	offset = offset +15;
			            var firstMsg = $('.message:first');
			            $('#message_area').prepend(data);
			            $('#content').scrollTop(firstMsg.offset().top);			        		
		        	}	   
		        }
		    });
	    } 

	    //upload file
		function uploadFile(){
	    var form = document.getElementById('send-file');
			var formData = new FormData(form);
			$('#fileupload').val('');
		    $.ajax({
		        url: 'services/post',  
		        type: 'POST',
		        data: formData,
		        processData: false,
		        contentType: false,
		        //Ajax events
		        success: function(data){
		        	var data = JSON.parse(data);

		        	if(data.type != 'success'){
		        		alert(data.message);
		        	}
		        }
		    });
		} 
			
	</script>

</body>


