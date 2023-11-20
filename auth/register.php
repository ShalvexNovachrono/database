<?php 
    session_start(); 
    extract($_REQUEST);
    require("tools.php");
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        header("Location: ../user/index.php?home");
    }

    if (isset($_POST['register'])) {
        require("../database/sql-connect.php");
        require("../database/accounts-db-function.php");
        $password = validate($_POST['password']);
        $username = validate($_POST['username']);
        if (empty($password)) {
            header("Location: register.php?error=password cant be empty");
        } elseif (strlen($password) < 6 ) {
            header("Location: register.php?error=password length has to be between 6 - 20");
        } elseif (strlen($password) > 20 ) {
            header("Location: register.php?error=password length has to be between 6 - 20");
        } elseif (empty($username)) {
            header("Location: register.php?error=username cant be empty");
        } elseif (strlen($username) < 3 ) {
            header("Location: register.php?error=username length has to be between 3 - 24");
        } elseif (strlen($username) > 24 ) {
            header("Location: register.php?error=username length has to be between 3 - 24");
        } else {
            //$options = ["memory_cost" => 16, "time_cost" => 2, "threads"=> 1 ];
            //$password   = password_hash(validate($_POST['password']), PASSWORD_ARGON2I, $options);
            $password = md5(validate($_POST['password']));
            //if (password_verify(validate($_POST['password']), $password)) {
                // var_dump(password_get_info($password));
                $username = text_to_number($username);
                $id = getRandomWord(10).$date_time;
                $token_id = getRandomWord(10).$date_time;
                $auth_id = getRandomWord(10).$date_time;
                $key_id = getRandomWord(10).$date_time;
                register($db, $username, $password, $id, $token_id, $key_id);
            //} else {
            //    header("Location: register.php?error=password : error 1");
            //}
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
    <title>Register - Database</title>
</head>
<body>
    <div class="main">
        <?php 
        if (isset($_GET['error'])) {
            echo '<div class="error">'.$_GET['error'].'</div>';
        }
        ?>  
        <h2>Register</h2>
        <p>Please fill in your credentials to Register.</p>

        <form action="" method="post" autocomplete="on">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="">
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control ">
            </div>
            <div class="form-group">
                <input type="submit" name="register" class="sumbit" value="Register">
            </div>
            <p class="question">Already have an account? <a href="login.php<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "?from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">Sign In now</a>.</p>
            <div class="info">
              <a href="../pages/about.php">?</a>
              <p>By Nava Majumdar</p>
            </div>
        </form>
    </div>
</body>
</html>
