<?php

/**
 * Created by PhpStorm.
 * User: tommy
 * Date: 29/3/17
 * Time: 8:27 PM
 */
require_once ('dbconfig.php');
class User
{
    private $connection;
    public function __construct()
    {
        $database = new Database();
        $db = $database -> dbConnection();
        $this->connection = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->connection->prepare($sql);
        return $stmt;
    }

    // Use this function for sign up page
    public function register($userName,$userEmail,$userPass)
    {
        try
        {
            // get password from plain text, convert to hash
            $new_password = password_hash($userPass, PASSWORD_DEFAULT);
            $stmt = $this -> connection -> prepare("INSERT INTO users(user_name,user_email,user_pass) 
                                                                  VALUES (:userName,:userEmail,:userPass)");
            $stmt->bindParam(":userName",$userName);
            $stmt->bindParam(":userEmail",$userEmail);
            $stmt->bindParam(":userPass",$new_password);
            // push user insert to database
            $stmt->execute();
            return $stmt;

        }catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function login($userName,$userEmail,$userPass)
    {
        try{
            $stmt = $this->connection->prepare("SELECT user_id, user_name, user_email, user_pass 
                                                  FROM users WHERE user_name=:userName OR user_email=:userEmail");
            $stmt->execute(array(':userName'=>$userName,'userEmail'=>$userEmail));
            $userRow = $stmt -> fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount()==1)
            {
                // check when user login with correct password
                if(password_verify($userPass,$userRow['user_pass']))
                {
                    $_SESSION['user_session']=$userRow['user_id'];
                    return true;
                }
                else
                {
                    return false;
                }
            }

        }catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function is_loggedin()
    {
        if(isset($S_SESSION['user_session']))
        {
            return true;
        }
    }
    public function redirect($url)
    {
        header("Location: $url");
    }

    public function doLogout()
    {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
    }


}