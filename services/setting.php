<?php 

    error_reporting(0);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    error_reporting(E_ALL|E_STRICT);
    error_reporting(E_ALL);
    ini_set("display_errors", 0);
    ini_set("error_reporting", E_ALL);
    error_reporting(E_ALL & ~E_NOTICE);
    
    //databasse
    define('host_sv','localhost');
    define('db_sv','chat');
    define('username_sv','root');
    define('password_sv','');


    //datetime
    date_default_timezone_set('Asia/Bangkok');
    define('timestamp',time());
    $datenow = (new DateTime($datetime))->format("Y-m-d");
    $timenow = (new DateTime($datetime))->format("H:i:s");
    $timenow2 = (new DateTime($datetime))->format("H:i");
    $startD1d = date('Y-m-d', strtotime('-1 day', timestamp));
    $datetimenow = $datenow." ".$timenow;
    define('datenow',$datenow);
    define('timenow',$timenow);
    define('datetimenow',$datetimenow);    
?>