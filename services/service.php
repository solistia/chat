<?php
require_once "setting.php";
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

    public function select_table_chat_message($condition){
        $query = 'SELECT *, SUM(CASE WHEN reading = "n" THEN 1 ELSE 0 END) as count FROM chat_message '.$condition.' GROUP BY userid ORDER BY count DESC;';

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
    /*
    public function insert_table_user_backend($username, $password, $class, $credit, $currency, $status, $note, $create_date, $update_date, $owner, $downline, $access, $role, $lang){
        $query = 'INSERT INTO user_backend (username, password, class, credit, currency, status, note, create_date, update_date, owner, downline, access, role, lang) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
        
    	try
    	{
   
    	    $res = $this->conn->prepare($query);
    	    $values = array($username, $password, $class, $credit, $currency, $status, $note, $create_date, $update_date, $owner, $downline, $access, $role, $lang);
    	   

	        $res->execute($values);

    	}
    	
    	catch (PDOException $e)
    	{
    	   //echo $e->getMessage();
    	   //die();
    	   return false; 
    	}
    
    	return true;        
    }
    
    public function select_table_user_backend_from_username($username){
        
        $query = 'SELECT * FROM user_backend WHERE username = "'.$username.'";';
        
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
    
    public function select_table_user_backend_from_username_in($username){
        
        $query = 'SELECT * FROM user_backend WHERE username IN ('.$username.');';
        
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
    
    public function select_table_user_backend_all($class, $owner, $role){

        $query = 'SELECT * FROM user_backend WHERE class ="'.$class.'" AND owner ="'.$owner.'" AND role = "'.$role.'" ORDER BY create_date ASC;';
        
    	try
    	{

    	    $res = $this->conn->prepare($query);
    	    $res->execute();
    	   
    	    $result = $res->fetchAll(PDO::FETCH_ASSOC);
    	}
    	catch (PDOException $e)
    	{
     	    //echo $e->getMessage();
    	    //die();   	    
            return false; 
    	}
    
    	return $result;
    	
    } 
    
    public function update_table_user_backend_credit($credit, $agent_name, $update_date){
        
        $query = 'UPDATE user_backend SET credit = '.$credit.', update_date = "'.$update_date.'" WHERE username ="'.$agent_name.'";';
    	try
    	{

    	    $res = $this->conn->prepare($query);


	        $res->execute();

    	}
    	
    	catch (PDOException $e)
    	{

    	   //echo $e->getMessage();
    	   //die();
    	   return false;
    	}
    
    	return true;        
    }*/
       
}