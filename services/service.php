<?php
require_once "setting.php";
require_once "service2.php";
class Services
{
    public function __construct()
    {
        try
        {
            $this->conn = new PDO("mysql:host=" . host_sv . ";dbname=" . db_sv, username_sv, password_sv);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
            date_default_timezone_set('Asia/Bangkok');
            
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
    
    public function __destruct()
    {
       $this->conn = null;
    }


    public function select_table_chat_user($userid){
        $query = 'SELECT * FROM chat_user WHERE userid = "'.$userid.'";';
        
    	try
    	{

    	    $res = $this->conn->prepare($query);
    	    $res->execute();
    	   
    	    $result = $res->fetch(PDO::FETCH_ASSOC);
    	}
    	catch (PDOException $e)
    	{
            return false; 
    	}
    
    	return $result;
    }

     public function insert_table_chat_user($userid, $name, $agent_name, $created, $updated){
       $query = 'INSERT INTO chat_user (userid, name, agent_name, created, updated) VALUES (?, ?, ?, ?, ?);';      
        try
        {
            $res = $this->conn->prepare($query);
            $values = array($userid, $name, $agent_name, $created, $updated);
          
            $res->execute($values);
        }
        
        catch (PDOException $e)
        {
           echo $e->getMessage();
           //die();
           return false; 
        }
    
        return true;      
    }   

    function update_table_chat_chat_user($userid, $set){
        $query = 'UPDATE chat_user SET '.$set.' WHERE userid ="'.$userid.'";';

        try
        {
            /* Prepare step (can be done only once) */
            $res = $this->conn->prepare($query);

            /* Execute step, with the array of values */
            $res->execute();

        }
        
        catch (PDOException $e)
        {
           /* If there is an error an exception is thrown */
           echo $e->getMessage();
           die();
           return false;
        }
    
        return true;     
    }

    public function select_table_sound($tag){
        $query = 'SELECT * FROM sound_warning WHERE tag = "'.$tag.'";';
        
        try
        {

            $res = $this->conn->prepare($query);
            $res->execute();
           
            $result = $res->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            return false; 
        }
    
        return $result;
    }

   public function select_table_sound_all(){
        $query = 'SELECT * FROM sound_warning ;';
        
        try
        {

            $res = $this->conn->prepare($query);
            $res->execute();
           
            $result = $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            return false; 
        }
    
        return $result;
    }

    public function insert_table_chat_message($userid, $message, $type, $sendto, $date, $admin_sent, $reading){
       $query = 'INSERT INTO chat_message (userid , message, type, send_to, datetime, admin_sent, reading) VALUES (?, ?, ?, ?, ?, ?, ?);';   	
    	try
    	{
    	    $res = $this->conn->prepare($query);
    	    $values = array($userid, $message, $type, $sendto, $date, $admin_sent, $reading);
    	  
	        $res->execute($values);
    	}
    	
    	catch (PDOException $e)
    	{
    	   echo $e->getMessage();
    	   //die();
    	   return false; 
    	}
    
    	return true;      
    }

    public function select_table_chat_message_from_user($userid, $limit, $offset, $order){

        $query = 'SELECT * FROM (SELECT * FROM chat_message WHERE userid = "'.$userid.'" ORDER BY id DESC LIMIT '.$offset.','.$limit.') AS subquery ORDER BY id '.$order.';';
        
        try
        {

            $res = $this->conn->prepare($query);
            $res->execute();
           
            $result = $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return false; 
        }
    
        return $result;
    }

    function update_table_chat_message_reading($userid){
        $query = 'UPDATE chat_message SET reading = "y" WHERE userid ="'.$userid.'" AND reading = "n";';

        try
        {
            /* Prepare step (can be done only once) */
            $res = $this->conn->prepare($query);

            /* Execute step, with the array of values */
            $res->execute();

        }
        
        catch (PDOException $e)
        {
           /* If there is an error an exception is thrown */
           echo $e->getMessage();
           die();
           return false;
        }
    
        return true;     
    }

    public function select_table_chat_admin($username){
        $query = 'SELECT * FROM user_admin WHERE username = "'.$username.'";';
        
        try
        {

            $res = $this->conn->prepare($query);
            $res->execute();
           
            $result = $res->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            return false; 
        }
    
        return $result;       
    }

    function update_table_chat_chat_admin($username, $set){
        $query = 'UPDATE user_admin SET '.$set.' WHERE username ="'.$username.'";';

        try
        {
            /* Prepare step (can be done only once) */
            $res = $this->conn->prepare($query);

            /* Execute step, with the array of values */
            $res->execute();

        }
        
        catch (PDOException $e)
        {
           /* If there is an error an exception is thrown */
           echo $e->getMessage();
           die();
           return false;
        }
    
        return true;     
    }

    public function select_table_chat_message($condition){
        $query = 'SELECT chat_message.*, SUM(CASE WHEN chat_message.reading = "n" THEN 1 ELSE 0 END) as count, chat_user.name, chat_user.note, chat_user.username, chat_user.agent_name FROM chat_message INNER JOIN chat_user ON chat_message.userid=chat_user.userid '.$condition.' GROUP BY userid ORDER BY count DESC;';

        try
        {

            $res = $this->conn->prepare($query);
            $res->execute();
           
            $result = $res->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            //return false; 
        }
    
        return $result;       

    }
     
}