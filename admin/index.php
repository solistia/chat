<?php 
session_start();
require_once '../services/service.php';
$services = new Services();
//$services2 = new Services2();

if(empty($_SESSION['username'])){
	header('location: login');
	die();
}

$user = $services->select_table_chat_admin($_SESSION['username']);
$sound = $services->select_table_sound($user['sound_warning']);
$allsound = $services->select_table_sound_all();
?>
<head>
	<meta charset="utf-8">
	<title>Swipe – The Simplest Chat Platform</title>
	<meta name="description" content="#">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap core CSS -->
	<link href="../dist/css/lib/bootstrap.min.css" type="text/css" rel="stylesheet">
	<link href="../dist/css/image_reloader.css" type="text/css" rel="stylesheet">
	<link href="../dist/css/style.css" type="text/css" rel="stylesheet">
	<link rel="stylesheet" href="../dist/css/simple-lightbox.css?v2.8.0" />
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
	<!-- POP UP -->
	<div id="popup1" class="overlay">
	  <div class="popup">
	    <h2 id="user_info_name">User Info</h2>
	    <a class="close" onclick="closeOverley()">&times;</a>
	    <div class="content" id="user_info">
			<!-- USER INFO -->      		    		   		 		
	    </div>
	  </div>
	</div>

	<main>
		<div class="layout">
			<!-- Start of Sidebar -->
			<div class="sidebar" id="sidebar">

				<div class="container">
					<div class="col-md-12">
						<div class="tab-content">
							<a class="close openSidebar" onclick="closeSidebar()">&times;</a>
							<!-- Start of Discussions -->
							<div id="discussions" class="tab-pane fade active show">					
								<div class="discussions">
									<h1>ADMIN : <?= $user['name'] ?></h1>
									<label>เลือกเสียง</label>
									<select id="sound_select" onchange="updateSound(this)">

										<?php 
											foreach($allsound as $row){
												if($user['sound_warning'] == $row['tag']){
													echo '<option value="'.$row['tag'].'" selected>'.$row['tag'].'</option>';
												}else{
													echo '<option value="'.$row['tag'].'">'.$row['tag'].'</option>';
												}
											}
										?>

									</select>

									<h1>Discussions</h1>
									<div class="list-group" id="chats" role="tablist">
										<div class="user_list_active">
											
											<!-- CURRENT USER -->

										</div>
										<div class="user_list_not_active">


											<!-- USER LIST -->


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
								<!-- HEADER -->
								<div class="container">
									<div class="col-md-12">
										<div class="inside">
											<div class="data openSidebar" onclick="openSidebar()">
												<i class="material-icons">dehaze</i>
											</div>
											<div class="data">
												<h5 id="current_user_name" onclick="getInfo()" style="cursor: pointer;"></h5>
												<p><input type="text" name="" id="current_user_note" style="border: none;color: #bdbac2;"></p>
											</div>

										</div>
										<a href="logout" class="btn float-right">logout</a>
									</div>
								</div>
								<!-- END HEADER -->
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
											<textarea id="send-message" class="form-control" placeholder="Type Here..." rows="1"></textarea>
											<!--<button class="btn emoticons"><i class="material-icons">insert_emoticon</i></button>-->
											<button id="send_message" class="btn send"><i class="material-icons">send</i></button>
										</div>
										<form id="send-file">
											<label>
												<input type="hidden" id="current_user" name="userid" value="">
												<input type="hidden" id="sendto" name="sendto" value="<?= $userid ?>">
												<input type="hidden" id="admin_sent" name="admin_sent" value="<?= $_SESSION['username'] ?>">
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
	<script src="../dist/js/simple-lightbox.js?v2.8.0"></script>
	<script type="text/javascript">
	  var current;
	  var offset = 15;
	  var audio = new Audio('../dist/sound/<?= $sound['file'] ?>');
	  var $gallery = new SimpleLightbox('.zoomimg', {});
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
			    		message = '<a href="'+data.message+'" class="zoomimg"><img src="'+data.message+'" class="slow-images" width="150" ></a>';
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

					if(data.type == 'image'){
						$gallery.refresh();
					}

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
				        	var data = JSON.parse(data);
				        	if(data.type != 'success'){
				        		alert(data.message);
				        	}				        	
				        }
				    });
			    }
		    });

			$('#send_message').click(function(){
			    $message = $('#send-message');
			    console.log($message);
			    if($message.val() && $message.val().trim()){
				    $.ajax({
				        url: '../services/post',  
				        type: 'POST',
				        data: {'message': $message.val().trim(), 'userid': current, 'sendto': current, 'admin_sent': '<?= $_SESSION['username'] ?>'},
				        //Ajax events
				        success: function(data){
				        	$message.val('');
				        	var data = JSON.parse(data);
				        	if(data.type != 'success'){
				        		alert(data.message);
				        	}
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

		    //start page with get userlist
		    getReading();	    
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
		        	$('#current_user_name').text($('#'+id+'name').text());
		        	$('#current_user_note').val($('#'+id+'note').text());
					$("#content").animate({ scrollTop: $('#message_area_container').prop("scrollHeight")+5000}, 1500);
					$gallery.refresh();
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
			            $gallery.refresh();		        		
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
		function uploadFile(file){

			if(file.files[0].size > 10485760){
				alert("File is too big!");
				return false;
			}

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
		        	var data = JSON.parse(data);

		        	if(data.type != 'success'){
		        		alert(data.message);
		        	}
		        }
		    });
		} 
			
	    //get user info
	    function getInfo(){
		    $.ajax({
		        url: '../services/getInfo',  
		        type: 'POST',
		        data: {'userid': current},
		        //Ajax events
		        success: function(data){
		        	var data = JSON.parse(data);

	        		$('#user_info').html(data.message);
	        		$('#popup1').addClass('overlay-visible');
		        }
		    });
	    }	

	    //update sound warning
	   	function updateSound(select){

		    $.ajax({
		        url: '../services/updateadmin',  
		        type: 'POST',
		        data: {'username': '<?= $_SESSION['username'] ?>', 'sound': select.value},
		        //Ajax events
		        success: function(data){
		        	var data = JSON.parse(data);
		        	if(data.type == 'success'){
		        		audio = new Audio('../dist/sound/'+data.message);
		        		audio.play();
		        	}else{
		        		alert(data.message);
		        	}
		        }
		    });
	   	}

	   	function closeSidebar(){
			$('.sidebar').toggle({ direction: "left" }, 1000);

	   	}

	   	function openSidebar(){
			$('.sidebar').toggle({ direction: "left" }, 1000);	
	   	}

    	function closeOverley (){
	    	$('.overlay').removeClass('overlay-visible');
	    }			
	</script>
</body>