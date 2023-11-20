<?php 
    session_start(); 
    extract($_REQUEST);
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        header("Location: user/index.php?home");
    } else {
        header("Location: auth/login.php");
    }
?>
