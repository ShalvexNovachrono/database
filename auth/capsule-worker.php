<?php
    header('Content-Type: application/json');
    session_start(); 
    extract($_REQUEST);
    
    require("tools.php");
    require("../database/sql-connect.php");
    require("../database/capsule-db-function.php");

    if (isset($_GET['user_id']) && isset($_GET['container_id'])) {
        if (strlen($_GET['user_id']) < 4) {
            echo "{\"error\": \"user_id length need to be over 4 character.\"}";
        } else {
            $user_id = text_to_number(validate($_GET['user_id']));
            $container_id = text_to_number(validate($_GET['container_id']));
            getCapsule($db, $user_id, $container_id);
        }
    } else if (isset($_POST['user_id']) && isset($_POST['container_id']) && isset($_POST['capsule_data'])) {
        if (strlen($_POST['user_id']) < 4) {
            echo "{\"error\": \"user_id length need to be over 4 character.\"}";
        } else {
            $user_id = text_to_number(validate($_POST['user_id']));
            $container_id = text_to_number(validate($_POST['container_id']));
            $capsule_data = text_to_number($_POST['capsule_data']);
            makeCapsule($db, $user_id, $container_id, $capsule_data);
        }
    } else {
        echo "{\"error\": \"make sure to enter the get/post requirements\"}";
    }

?>