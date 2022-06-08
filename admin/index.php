<?php 
session_start();
require_once '../services/service.php';
$services = new Services();

if(empty($_SESSION['username'])){
	header('location: login');
	die();
}

$user = $services->select_table_chat_admin($_SESSION['username']);
$sound = $services->select_table_sound($user['sound_warning']);
$chat = $services->select_table_chat_message('');
?>
<head>
	<meta charset="utf-8">
	<title>Swipe – The Simplest Chat Platform</title>
	<meta name="description" content="#">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap core CSS -->
	<link href="../dist/css/lib/bootstrap.min.css" type="text/css" rel="stylesheet">
	<link href="../dist/css/image_reloader.css" type="text/css" rel="stylesheet">
	<!-- Swipe core CSS -->
	<link href="../dist/css/swipe.min.css" type="text/css" rel="stylesheet">
	<!-- Favicon -->
	<link href="dist/img/favicon.png" type="image/png" rel="icon">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript" ></script>	
	<script src="https://js.pusher.com/4.1/pusher.min.js"></script>	
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js" type="text/javascript" ></script>
	<script src="../dist/js/jquery.imageReloader.js" type="text/javascript" ></script>
	<script src="../dist/js/jquery.playSound.js" type="text/javascript" ></script>	

</head>
<body>
	<main>
		<div class="layout">
			<!-- Start of Sidebar -->
			<div class="sidebar" id="sidebar">
				<div class="container">
					<div class="col-md-12">
						<div class="tab-content">
							<!-- Start of Discussions -->
							<div id="discussions" class="tab-pane fade active show">					
								<div class="discussions">
									<h1>ADMIN : <?= $user['name'] ?></h1>
									<button>เปิดเสียงแจ้งเตือน</button>
									<h1>Discussions</h1>
									<div class="list-group" id="chats" role="tablist">
										<div class="user_list_active"></div>
										<div class="user_list_not_active">
										<?php 
											foreach($chat as $row){
										?>
										<a href="#" onclick="getMessage(this.id)" class="filterDiscussions all read single user_list" id="<?= $row['userid']  ?>" data-toggle="list" role="tab">
											<img class="avatar-md" src="../dist/img/avatars/images.png" data-toggle="tooltip" data-placement="top" title="Keith" alt="avatar">
											<?php if($row['count'] > 0){?> 
												<div class="new bg-red" id="<?= $row['userid'].'count' ?>" bis_skin_checked="1">
													<span class="count_num"><?= $row['count'] ?></span>
												</div>
											<?php } ?>
											<div class="data">
												<h5 id="<?= $row['userid'].'name' ?>"><?= $row['name'] ?></h5>
												<p id="<?= $row['userid'].'note' ?>"><?= $row['note'] ?></p>
											</div>
										</a>
										<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<!-- End of Discussions -->
						</div>
					</div>
				</div>
			</div>
			<!-- End of Sidebar -->
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
											<div class="data">
												<h5><input type="text" name="" id="current_user_name" style="border: none;font-weight: bold;"></h5>
												<p><input type="text" name="" id="current_user_note" style="border: none;color: #bdbac2;"></p>
											</div>

										</div>
										<a href="logout" class="btn float-right">logout</a>
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
										<form class="position-relative w-100">
											<textarea id="send-message" class="form-control" placeholder="Start typing for reply..." rows="1"></textarea>
											<input type="text" name="name" id="user_name" hidden value="Keith Morris">
											<!--<button class="btn emoticons"><i class="material-icons">insert_emoticon</i></button>-->
											<!--<button type="submit" id="send_message" class="btn send"><i class="material-icons">send</i></button>-->
										</form>
										<form id="send-file">
											<label>
												<input type="hidden" id="current_user" name="userid" value="">
												<input type="hidden" id="sendto" name="sendto" value="<?= $userid ?>">
												<input type="hidden" id="admin_sent" name="admin_sent" value="<?= $_SESSION['username'] ?>">
												<input type="file" name="fileupload" accept="image/png, image/jpeg, video/mp4" onchange="uploadFile(this)"  >
												<span class="btn attach d-sm-block d-none">
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
	  var current;
	  var offset = 15;
	  var audio = new Audio('../dist/sound/<?= $sound['file'] ?>');

	  $(function(){
	  		//load more
			$('#content').scroll(function() {
				if($('#content').scrollTop() == 0 && $('#message_area_container').prop("scrollHeight") > 0){
		            getMoreMessage();
				}
			});
			// initial pusher
		    var pusher = new Pusher('04f1f2f158fedc82e415', {
		      cluster: 'ap1'
		    });

		    var channel = pusher.subscribe('system');

		    //new message
		    channel.bind('message', function(data) {
		    	if(current == data.userid){
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
			    		dir = '';
			    	}else{
			    		dir = 'me';
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
		    	}

		    	if(data.sendto == 'system'){
		    		audio.play();
		    	}

				getReading();
		    });

		    //on update user
		    channel.bind('update', function(data) {
		    	if(current == data.userid){
		    		$('#'+data.element).text(data.value);
		    		$('#'+data.element2).val(data.value);
		    	}
		    	getReading();
		    });

		    //send message
			$("#send-message").keypress(function (e) {
			    if(e.which === 13 && !e.shiftKey && $(this).val() && $(this).val().trim()) {
			        e.preventDefault();
			        $message = $(this);
				    $.ajax({
				        url: '../services/post',  
				        type: 'POST',
				        data: {'message': $message.val().trim(), 'userid': current, 'sendto': current, 'admin_sent': '<?= $_SESSION['username'] ?>'},
				        //Ajax events
				        success: function(data){
				        	$message.val('');
				        	//console.log(data);
				        }
				    });
			    }
		    });

			//update name
		    $("#current_user_name").blur(function () {
		        if(current){
		        	var name = $(this).val();
				    $.ajax({
				        url: '../services/updateuser',  
				        type: 'POST',
				        data: {'userid': current, 'name': name},
				        //Ajax events
				        success: function(data){
				        	var data = JSON.parse(data);

				        	if(data.type == 'success'){
				        		$('#'+current+'name').text(name);
				        	}

				        	console.log(data);
				        }
				    });
		        }
		    });

		    //update Note
		    $("#current_user_note").blur(function () {
		        if(current){
		        	var note = $(this).val();
				    $.ajax({
				        url: '../services/updateuser',  
				        type: 'POST',
				        data: {'userid': current, 'note': note},
				        //Ajax events
				        success: function(data){
				        	var data = JSON.parse(data);

				        	if(data.type == 'success'){
				        		$('#'+current+'note').text(note);
				        	}

				        	console.log(data);
				        }
				    });
		        }
		    });		    
		});	
	  	//get message
	    function getMessage(id){
	    	current = id;
	    	$('#current_user').val(current);
	    	$('#sendto').val(current);
	    	$('#message_area').html('');

	    	$("#"+id+'count').detach();
	    	var element = $("#"+id).detach();
			$(".user_list_active").html(element);

			getReading();

		    $.ajax({
		        url: '../services/getchat',  
		        type: 'POST',
		        data: {'userid': current, 'from': 'admin', 'order': 'ASC'},
		        //Ajax events
		        success: function(data){
		        	offset = 15;
		        	$('#message_area').append(data);
		        	var lastMsg = $('.message:last');
		        	$('#current_user_name').val($('#'+id+'name').text());
		        	$('#current_user_note').val($('#'+id+'note').text());
					$("#content").animate({ scrollTop: $('#message_area_container').prop("scrollHeight")+1000}, 1500);
		        }
		    });
	    } 
	    //get more message
	    function getMoreMessage(){
		    $.ajax({
		        url: '../services/getchat',  
		        type: 'POST',
		        data: {'userid': current, 'from': 'admin', 'order': 'ASC', 'offset': offset},
		        //Ajax events
		        success: function(data){
		        	//var data = JSON.parse(data);
		        	if(data){
			        	offset = offset +15;
			            var firstMsg = $('.message:first');
			            $('#message_area').prepend(data);
			            $('#content').scrollTop(firstMsg.offset().top);			        		
		        	}	        
		        }
		    });
	    } 	    

	    //get count reading
	    function getReading(){
		    $.ajax({
		        url: '../services/getreading',  
		        type: 'POST',
		        data: {'userid': current},
		        //Ajax events
		        success: function(data){
		        	$('.user_list_not_active').html(data);
		        }
		    });
	    }	  

	    //upload file
		function uploadFile(){
	    	var form = document.getElementById('send-file');
			var formData = new FormData(form);
			$('#fileupload').val('');
		    $.ajax({
		        url: '../services/post',  
		        type: 'POST',
		        data: formData,
		        processData: false,
		        contentType: false,
		        //Ajax events
		        success: function(data){
		        	//console.log(data);
		        }
		    });
		} 
			
	</script>
</body>