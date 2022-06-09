<?php
class Services2
{
    public function __construct()
    {
        try
        {
            $this->conn = new PDO("mysql:host=" . host_sv2 . ";dbname=" . db_sv2, username_sv2, password_sv2);
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

    public function select_table_user($condition){
        $query = 'SELECT * FROM users WHERE '.$condition.';';
        
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

}