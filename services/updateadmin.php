<?php 
    require 'pusher/trigger.php';
    require 'cloudinary/trigger.php';
    require_once 'service.php';
    $services = new Services();

    if(isset($_POST['sound'])){
        $username = $_POST['username'];
        $set = 'sound_warning="'.$_POST['sound'].'"';
        $update_sound = $services->update_table_chat_chat_admin($username, $set);

        if($update_sound){
            $sound = $services->select_table_sound($_POST['sound']);
            $response = array(
                "type" => "success",
                "message" => $sound['file']         
            );

        }else{
            $response = array(
                "type" => "error",
                "message" => "database error"           
            );          
        }

        echo json_encode($response);        
    }
?>