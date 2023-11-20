<?php 
    session_start(); 
    extract($_REQUEST);
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        require("../database/sql-connect.php");
        require("../database/accounts-db-function.php");
        require("../auth/tools.php");
        if (isset($_POST['change_password'])) {
            $current_password = validate($_POST['current_password']);
            $new_password = validate($_POST['new_password']);
            $confirm_new_password = validate($_POST['confirm_new_password']);
            if (empty($new_password)) {
                header("Location: index.php?user&error=new password cant be empty");
            } elseif (strlen($new_password) < 6 ) {
                header("Location: index.php?user&error=new password length has to be between 6 - 20");
            } elseif (strlen($new_password) > 20 ) {
                header("Location: index.php?user&error=new password length has to be between 6 - 20");
            } elseif ($new_password !== $confirm_new_password) {
                header("Location: index.php?user&error=new passwords dont match");
            } else {
                $id = $_SESSION['id'];
                $new_password = password_hash($new_password, PASSWORD_ARGON2I);
                $count = check_password($db, $id, $current_password);
                if ($count === 0) {
                    header("Location: index.php?user&error=passwords dont match");
                } else {
                    change_password($db, $id, $new_password);
                }
            }
        }
        if (isset($_POST['delete_user'])) {
            $id = $_SESSION['id'];
            $username = text_to_number($_SESSION['username']);
            $current_password = validate($_POST['password']);
            $token_id = $_SESSION['token_id'];
            $check = check_password($db, $id, $current_password);
            if ($check === 1) {
                delete_account($db, $id, $username, $token_id);
            } else {
                header("Location: index.php?user&error_del=passwords does not match, account password need to match before account is deleted.");
            }
        }
        if (isset($_GET['user'])) {
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
    <link rel="stylesheet" href="../css/user.css">
    <title>User</title>
</head>
<body>
<?php
    include "../pages/nav.php";
?>
    <div class="main">

        <?php 
        if (isset($_GET['error'])) {
            echo '<div class="error">'.$_GET['error'].'</div>';
        }
        ?>   
        <form action="" method="post" autocomplete="on">
            <div class="form-title">Change Password</div>
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control" value="">
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_new_password" class="form-control" value="">
            </div>
            <div class="form-group">
                <input type="submit" name="change_password" class="submit" value="Change Password">
            </div>
        </form>
        <a href="index.php?quickLogin">Quick Login</a>
        <br>

          <?php
          //echo $_SESSION['id'] . " " . $_SESSION['username'] . " " . $_SESSION['token_id'];
          //delete_account($db, $_SESSION['id'], $_SESSION['username'], $_SESSION['token_id'])
          ?>

        <?php 
        if (isset($_GET['error_del'])) {
            echo '<div class="error">'.$_GET['error_del'].'</div>';
        }
        ?>  
        <form action="" method="post" autocomplete="off">
            <div class="form-title">Delete account</div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="">
            </div>
            <div class="form-group delete-btn">
                <input type="submit" name="delete_user" class="btn" value="Delete account">
            </div>
        </form>
    </div>
</body>
</html>
<?php   } elseif (isset($_GET['quickLogin'])) {  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/body.css">
    <link rel="stylesheet" href="../css/nav-bar.css">
    <link rel="stylesheet" href="../css/user.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/qlcode-worker.js"></script>
    <title>Quick Login</title>
</head>
<body>
<?php
    include "../pages/nav.php";
?>
    <div class="main">
        <div class="container">
            <h1>Quick Login Code: </h1>
            <p id="qlcode">NaN</p>
            <p id="timer">60</p>
        </div>
    </div>
</body>
</html>
<?php     
        } elseif (isset($_GET['home'])) {
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
    <link rel="stylesheet" href="../css/home.css">
    <title>Home</title>
</head>
<body>
<?php
    include "../pages/nav.php";
?>
    <div class="main">

        <div class="container">
            <h1>About</h1><br>
            This website is clone(kinda) of <b>login/register with google</b> but this website is where you login to.<br><br>
            This website saves its user data to <b>.sqlite</b> file. The file is encryted.<br><br>
            Password is stored in replit database. Click <a href="https://replit.com/@Nava10Y/PHP-Replit-Database-Demo-Example?v=1">here</a> connect your php website to Replit Database<br><br>
            There is <b><?php echo count_account($db); ?></b> account on this database.<br><br>
            <b>By Nava Majumdar</b><br><br>
            <b><a href="https://client-database.nava10y.repl.co/index.php">Demo</a></b><br><br>
        </div>
        <div class="container">
            <h1>How to connect <b>database v4</b> to your website?</h1><br>
            <div class="sub-container">
                <h2>Register:</h2>
                1) Send a get requst to https://database.nava10y.repl.co/auth/token.php?<b>from=['your web site url here']</b>&<b>refid=['a unique id']</b>.<br><br>
                2) You have will get a <b>query string</b> with https://your-website.com/<b>?refid=['a unique refid']</b>&<b>id=['a unique id']</b>&<b>username=['username']</b>&<b>register</b>.<br><br>
            </div>
            <div class="sub-container">
                <h2>Login: New</h2>
                1) Send a get requst to <b>https://database.nava10y.repl.co/auth/token.php?from=['your web site url here']&refid=[0001]&secure_token</b> but for the refid you have put '0001'.<br><br>
                2) You have will get a <b>POST</b> requst at https://your-website.com/?secure_token<br><br>
                2.1) This will be sent <b>refid</b>, <b>id</b> and <b>login</b> = true in the <b>POST</b> requst and after the data is valid you must retrun a <b>secure_code</b> in plain text that is saved in your site database<br><br>
                3) You have will get a <b>query string</b> with https://your-website.com/<b>?secure_code=['secure code']</b>.<br>
                <a href="https://replit.com/@Nava10Y/chat-n?v=1#auth/refid.php">refid.php</a> and <a href="https://replit.com/@Nava10Y/chat-n?v=1#database/accounts-db-function.php">accounts-db-function.php</a> can with your code.
            </div>
            <div class="sub-container">
                <h2>Login: OLD</h2>
                1) Send a get requst to <b>https://database.nava10y.repl.co/auth/token.php?from=['your web site url here']&refid=[0001]</b> but for the refid you have put '0001'.<br><br>
                2) You have will get a <b>query string</b> with https://your-website.com/<b>?refid=['a same unique id']</b><b>&id=['same user id']</b>&<b>login</b>.<br><br>
            </div>
        </div>
        <div class="container">
            <h1>How to use our <b>database v4</b> to login/register in your apps?</h1><br>
            <div class="sub-container">
                <h2>Register:</h2>
                1) Send a POST requst to this url <b>https://database.nava10y.repl.co/auth/apps-api-auth.php</b>.<br><br>
                2) You must inclued this <b>register</b>, <b>username</b> and <b>password</b>.<br><br>
                2.1) when posting make sure to make <b>register</b> = true<br><br>
                3) On the same url page you will get the <b>user_id</b> in a <b>json format</b>.<br>
            </div>
            <div class="sub-container">
                <h2>Login:</h2>
                1) Send a POST requst to this url <b>https://database.nava10y.repl.co/auth/apps-api-auth.php</b>.<br><br>
                2) You must inclued this <b>login</b>, <b>username</b> and <b>password</b>.<br><br>
                2.1) when posting make sure to make <b>login</b> = true<br><br>
                3) On the same url page you will get the <b>user_id</b> or error in a <b>json format</b>.<br>
            </div>
        </div>
        <div class="container">
            <h1>How to save/get your user data in/from <b>database v3</b>?</h1><br>
            <div class="sub-container">
                <h2>Capsule Container: </h2>
                1) You need to make a <b><a href="capsule.php">capsule container</a></b> that will store your user data.<br><br>
                2) You need to go <b><a href="capsule.php">here</a></b> then press on <b>Create Container</b> and put the name of the container.<br><br>
                3) You will get <b>container_id</b> then save this id.<br>
            </div>
            <div class="sub-container">
                <h2>Make/Update Data: </h2>
                1) You need to make a POST requst <b>https://database.nava10y.repl.co/auth/capsule-worker.php</b> that will store your user data.<br><br>
                2) You must inclued this <b>user_id</b>, <b>container_id</b> and <b>capsule_data</b>.<br><br>
                3) When done it will give you a error saying success in a <b>json format</b>.<br>
            </div>
            <div class="sub-container">
                <h2>Get Data: </h2>
                1) You need to make a GET requst <b>https://database.nava10y.repl.co/auth/capsule-worker.php?</b><b>user_id=[put_user_id_of_your_user]</b>&<b>container_id=[put_container_id]</b>.<br><br>
                2) You will get user json data in a <b>json format</b>.<br>
            </div>
        </div>
        <div class="container">
            <h1>How enable, disable and delete your account to the connected website?</h1><br>
            Press this <a href="connection.php">Link</a> it will take you to a list.<br><br>
        </div>
        <div class="container">
            <h1>Links that were useful in building the database.</h1><br>
            1) <a href="https://replit.com/@demcrepl/PHP-PDO-SQLite">https://replit.com/@demcrepl/PHP-PDO-SQLite</a><br><br>
            2) <a href="https://openwritings.net/pg/php/php-using-pdo-sqlite">https://openwritings.net/pg/php/php-using-pdo-sqlite</a><br><br>
            3) <a href="https://replit.com/@Nava10Y/PHP-Replit-Database-Demo-Example?v=1">How to connect to replit database? example by Nava</a><br><br>
        </div>

    </div>

</body>
</html>
<?php
        } else {
            header("Location: connection.php");
        }
    } else {
        header("Location: ../auth/login.php");
    }
?>