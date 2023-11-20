<?php
    session_start(); 
    extract($_REQUEST);


    require("tools.php");
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        header("Location: ../user/index.php?home");
    }

    if (isset($_GET['home'])) {
        header("Location: ../user/index.php?home");
    }

    if (isset($_POST['login'])) {
        require("../database/sql-connect.php");
        require("../database/accounts-db-function.php");


        
        if (!isset($_GET['quickLogin'])) {
            $password = validate($_POST['password']);
            $username = validate($_POST['username']);
            
            $username = text_to_number($username);
            login($db, $username, $password);
        } else {
            if (strlen($_POST['quickLoginCode']) === 10) {
                $quickLoginCode = md5(validate($_POST['quickLoginCode']));
                quick_login($db, $quickLoginCode);
            } else {
                header("Location: login.php?quickLogin&error=quick login code has to be 10 characters");
            }
        }
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
    <link rel="stylesheet" href="../css/auth.css">
    <title>Login - Database</title>
</head>
<body>
    <div class="main">
        <?php 
        if (isset($_GET['error'])) {
            echo '<div class="error">'.$_GET['error'].'</div>';
        }
        ?>  

        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <form action="" method="post" autocomplete="on">
            <?php 
            if (!isset($_GET['quickLogin'])) {

            ?>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="">
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control ">
            </div>
            <?php
            } else {
            ?>
            <div class="form-group">
                <label>Quick Login Code</label>
                <input type="text" name="quickLoginCode" class="form-control" value="">
            </div>  
            <?php
            }
            ?>
            <div class="form-group">
                <input type="submit" name="login" class="sumbit" value="Login">
            </div>
            <p class="question">Don't have an account? <a href="register.php<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "?from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">Sign up now</a>.</p>
            
            <?php 
            if (!isset($_GET['quickLogin'])) {

            ?>
            <div class="question buttom-link"><a href="login.php?quickLogin&<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">Quick Login</a><div class="small-dot"></div><a href="qr-code.php?<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">QR-Code</a></div>
            <?php
            } else {
            ?>
            <div class="question buttom-link"><a href="login.php?<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">Login</a><div class="small-dot"></div><a href="qr-code.php?<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">QR-Code</a></div>
            <?php
            }
            ?>
            <div class="info">
              <a href="../pages/about.php">?</a>
              <p>By Nava Majumdar</p>
            </div>
        </form>
    </div>
</body>
</html>
