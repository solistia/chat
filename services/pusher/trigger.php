<?php
require 'setting.php';
require 'vendor/autoload.php';

function pusher_trigger($data, $chanel, $event){
    global $pusher_chanel;
    global $pusher_event;  
    global $pusher_app_id;
    global $pusher_key;
    global $pusher_secret;
    global $pusher_cluster;
    
    $options = array(
        'cluster' => $pusher_cluster,
        'useTLS' => true
    );
    
    $pusher = new Pusher\Pusher(
        $pusher_key,
        $pusher_secret,
        $pusher_app_id,
        $options
    );
    
    if($pusher->trigger($chanel, $event, $data)) {

        return 'success';

    } else {

        return 'error';

    }      
}

?>