<?php 
	require 'pusher/trigger.php';
	require 'cloudinary/trigger.php';
  require_once 'service.php';
  $services = new Services();

	//upload File
	if(!empty($_FILES['fileupload']) && !empty($_POST['userid'])){
	  
      $fileName = $_FILES['fileupload']['tmp_name'];
      $userid = $_POST['userid'];
      $date = datetimenow;

			if(isset($_POST['sendto'])){
				$sendto = $_POST['userid'];
				$reading = 'y';
			}else{
				$sendto = 'system';
				$reading = 'n';
			}

			if(isset($_POST['admin_sent'])){
				$admin_sent = $_POST['admin_sent'];
			}else{
				$admin_sent = '';
			}

      $allowed_extension = array(
        "png",
        "jpg",
        "jpeg",
        "mp4"
      );     

      $fileExt = pathinfo($_FILES['fileupload']['name'], PATHINFO_EXTENSION);

      if($fileExt == "mp4"){
        $folder = 'chat/upload/vid/'.uniqid();
        $file_type="video"; 
      }else{
        $folder = 'chat/upload/img/'.uniqid(); 
        $file_type="image";
      }

      if (! in_array($fileExt, $allowed_extension)) {
        $response = array(
            "type" => "error",
            "message" => "รองรับไฟล์ภาพนามสกุล JPG, JPEG, PNG, MP4 เท่านั้น"
        );
      }else if (($_FILES["fileupload"]["size"] > 20000000)) {
        $response = array(
            "type" => "error",
            "message" => "จำกัดขนาดไฟล์สูงสุด = 20MB"
        );
      }else{

      	$return = cloudinary_trigger($fileName, $file_type, $folder);

      	if($return['secure_url']){
      		$message = $return['secure_url'];
      		$save_chat = $services->insert_table_chat_message($userid, $message, $file_type, $sendto, $date, $admin_sent, $reading);

	 				$data = array('message' => $message, 'type' => $file_type, 'sendto' => $sendto, 'date'=> $date, 'userid' => $_POST['userid']);

	 				$chanel = array($_POST['userid'], 'system');
					pusher_trigger($data, $chanel, 'message');

	        $response = array(    
	        	"type" => "success",
	          "message" => ''
	        ); 

      	}else{
	        $response = array(    
	        	"type" => "error",
	          "message" => "Upload fail"
	        ); 
      	}
      }

      echo json_encode($response);
	}

	//Send Message
	if(isset($_POST['message']) && !empty($_POST['userid'])){

		$userid = $_POST['userid'];
		$message = $_POST['message'];
		$type = 'message';
		$date = datetimenow;

		if(isset($_POST['sendto'])){
			$sendto = $_POST['userid'];
			$reading = 'y';
		}else{
			$sendto = 'system';
			$reading = 'n';
		}

		if(isset($_POST['admin_sent'])){
			$admin_sent = $_POST['admin_sent'];
		}else{
			$admin_sent = '';
		}

		$save_chat = $services->insert_table_chat_message($userid, $message, $type, $sendto, $date, $admin_sent, $reading);
		if($save_chat){

				$data = array('message' => $message, 'type' => $type, 'sendto' => $sendto, 'date' => $date, 'userid' => $_POST['userid']);

				$chanel = array($_POST['userid'], 'system');
				$pusher = pusher_trigger($data, $chanel, 'message');

        $response = array(    
        	"type" => "success",
          "message" => ''
        );

		}else{

        $response = array(
            "type" => "error",
            "message" => "Can't save to database"
        );

		}
		
		echo json_encode($response);
	}
?>