<?php
    function logout(){
        $db = database();
        $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
        $name = [];
        $account_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        $date1 = $_SESSION['date'];
        $login = $_SESSION['login'];
        $logout  = $date->format("h:ia");
        
        $sql = $db->query("INSERT INTO logs(account_id, date, login, logout, role_id) VALUES('$account_id', '$date1', '$login', '$logout', '$role')");
        if($sql){
            session_destroy();
            header("Location: https://oscrs-bulsusc.com/login");
            exit();
        }
        else{
            echo mysqli_error($db);
        }
    }
    function encrypt($value, $key){
        $encryptKey = base64_decode($key);
        $pseudoBytes = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($value, 'aes-256-cbc', $encryptKey, 0, $pseudoBytes);
        return base64_encode($encrypted.'::'.$pseudoBytes);
    }

    function decrypt($value, $key){
        $encryptKey = base64_decode($key);
        list($encryptedValue, $pseudoBytes) = array_pad(explode('::', base64_decode($value), 2), 2, null);
        return openssl_decrypt($encryptedValue, 'aes-256-cbc', $encryptKey, 0, $pseudoBytes);
    }

    function clearSessions(){
        unset($_SESSION['add-accepted']);
        unset($_SESSION['edit-accepted']);
        unset($_SESSION['employeeNo']);
        unset($_SESSION['studentno']);
        unset($_SESSION['lastName']);
        unset($_SESSION['firstName']);
        unset($_SESSION['middleName']);
        unset($_SESSION['birthday']);
        unset($_SESSION['email']);
        unset($_SESSION['contact']);
        unset($_SESSION['course']);
        unset($_SESSION['year']);
        unset($_SESSION['section']);
        unset($_SESSION['address']);
        unset($_SESSION['submitted-request']);
    }
    
    function auth(){
        if(!isset($_SESSION['user_id'])){
            header("Location: https://oscrs-bulsusc.com/login");
            exit(); 
        }
        else if($_SESSION['role'] != "1"){
            $db = database();
            $sql = $db->query("SELECT link FROM roles WHERE role_id = '".$_SESSION['role']."'");
            if($sql){
                $link = $sql->fetch_array(MYSQLI_ASSOC); 
                header("Location: ../".$link['link']."?id=".$_SESSION['user_id']);
            }
        }
    }

    function auth1(){
        if(isset($_SESSION['user_id'])){
            if(isset($_SESSION['role'])){
                $db = database();
                $sql = $db->query("SELECT link FROM roles WHERE role_id = '".$_SESSION['role']."'");
                if($sql){
                    $link = $sql->fetch_array(MYSQLI_ASSOC);
                    header("Location: ".$link['link']."?id=".$_SESSION['user_id']);
                }
            } 
        }
    }
    
    function clearValues(){
        unset($_SESSION['studentno']);
        unset($_SESSION['lastName']);
        unset($_SESSION['firstName']);
        unset($_SESSION['middleName']);
        unset($_SESSION['birthday']);
        unset($_SESSION['email']);
        unset($_SESSION['contact']);
        unset($_SESSION['course']);
        unset($_SESSION['year']);
        unset($_SESSION['section']);
        unset($_SESSION['address']);
        unset($_SESSION['submitted-request']);
    }
?>