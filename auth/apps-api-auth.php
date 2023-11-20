<?php
    header('Content-Type: application/json');
    session_start(); 
    extract($_REQUEST);
    require("tools.php");

    if (isset($_POST['login'])) {
        require("../database/sql-connect.php");
        require("../database/accounts-db-function.php");
        $password = validate($_POST['password']);
        $username = validate($_POST['username']);
        
        $username = text_to_number($username);
        login_apa($db, $username, $password);
    } else if (isset($_POST['register'])) {
        require("../database/sql-connect.php");
        require("../database/accounts-db-function.php");
        $password = validate($_POST['password']);
        $username = validate($_POST['username']);;
        if (empty($password)) {
            echo "{\"error\": \"password cant be empty\"}";
        } elseif (strlen($password) < 6 ) {
            echo "{\"error\": \"password length has to be between 6 - 20\"}";
        } elseif (strlen($password) > 20 ) {
            echo "{\"error\": \"password length has to be between 6 - 20\"}";
        } elseif (empty($username)) {
            echo "{\"error\": \"username cant be empty\"}";
        } elseif (strlen($username) < 3 ) {
            echo "{\"error\": \"username length has to be between 3 - 24\"}";
        } elseif (strlen($username) > 24 ) {
            echo "{\"error\": \"username length has to be between 3 - 24\"}";
        } else {
            //$options = ["memory_cost" => 16, "time_cost" => 2, "threads"=> 1 ];
            //$password = password_hash(validate($_POST['password']), PASSWORD_ARGON2I, $options);
            //if (password_verify($_POST['password'], $password)) {
                $password = md5($password);
                $username = text_to_number($username);
                $id = getRandomWord(10).$date_time;
                $token_id = getRandomWord(10).$date_time;
                $auth_id = getRandomWord(10).$date_time;
                $key_id = getRandomWord(10).$date_time;
                register_apa($db, $username, $password, $id, $token_id, $key_id);
            //} else {
                //echo "{\"error\": \"password : error 1\"}";
            //}
        }
    } else {
        echo "{\"error\": \"make sure to enter the post requirements\"}";
    }
?>