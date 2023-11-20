<?php 
    session_start(); 
    extract($_REQUEST);
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        require("../database/sql-connect.php");
        require("../database/capsule-db-function.php");
        require("../auth/tools.php");
        $user_id = $_SESSION['id'];

        if (isset($_POST['delete_Capsule_Container'])) {
            $container_id = validate($_POST['delete_Capsule_Container']);
            deleteCapsuleContainer($db, $user_id, $container_id);
        }
        if (isset($_POST['container_name'])) {
            $container_name = validate($_POST['container_name']);
            $container_id = getRandomWord(10).$date_time;
            makeCapsuleContainer($db, $user_id, $container_id, $container_name, $just_date);
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/body.css">
    <link rel="stylesheet" href="../css/nav-bar.css">
    <link rel="stylesheet" href="../css/scroll-bar.css">
    <link rel="stylesheet" href="../css/connection.css">
    <link rel="stylesheet" href="../css/capsule.css">
    <title>Capsule</title>
</head>
<body>
<?php
    include "../pages/nav.php";
?>
    <?php 
    if (isset($_GET['error'])) {
        echo '<div class="error">'.$_GET['error'].'</div>';
    }
    ?>  
    <div id="hidden_form">
        <div class="center">
            <h2>Capsule Container Maker</h2>
            <form action="" method="post">
                <input type="text" name="container_name" placeholder="Name of Container">
                <input type="submit" value="Make Container">
                <input type="button" onclick="closeHiddenForm()" value="Close">
            </form>
        </div>
    </div>   
    <div class="main">
        <?php 
            getCapsuleContainer($db, $user_id);
        ?>
        <button class="floating_bottom_left_button" onclick="openHiddenForm()">Create Container</button>
    </div>
    <script src="../js/capsule.js"></script>
</body>
</html>
<?php
    } else {
        header("Location: ../auth/login.php");
    }
?>