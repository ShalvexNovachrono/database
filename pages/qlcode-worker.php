<?php 
    session_start(); 
    extract($_REQUEST);
    require("../auth/tools.php");
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        require("../database/sql-connect.php");
        require("../database/accounts-db-function.php");
        if (isset($_POST["qrcode"])) {
            $user_id = $_SESSION['id'];
            $quickLogin_code = getrandomWord(10);
            
            $date = time() + 60;
            create_quickLogin_code($db, $user_id, $quickLogin_code, $date);
        }
    }
?>