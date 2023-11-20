<?php 
    session_start(); 
    extract($_REQUEST);
    if (isset($_GET['from']) && isset($_GET['refid'])) {
        if (!filter_var($_GET['from'], FILTER_VALIDATE_URL) === false) {
            if (strlen($_GET['refid']) > 3) {
                if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
                    require("tools.php");
                    require("../database/sql-connect.php");
                    require("../database/tokens-db-function.php");
                    if (isset($_POST['token_submit'])) {
                        $from = $_GET['from'];
                        $token_id = $_SESSION['token_id'];
                        $website_url = host_name_extractor($_GET['from']);
                        $website_url = text_to_number($website_url);
                        $refid = $_GET['refid'];
                        $send_id = getRandomWord(10).$date_time;
                        $working = 1;
                        $date_made = $just_date;
                        $count = find_user_tokens($db, $token_id, $website_url);
                        if ($_GET['refid'] === "0001") {
                            if ($count === 1) {
                                login_token($db, $token_id, $website_url);
                            } else {
                                header("Location: {$from}?error=Please Register first");
                            }
                        } else {
                            if ($count === 1) {
                                header("Location: token.php?from={$_GET['from']}&refid=0001");
                            } else {
                                register_token($db, $token_id, $website_url, $refid, $send_id, $working, $date_made);
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
    <link rel="stylesheet" href="../css/token.css">
    <link rel="stylesheet" href="../css/scroll-bar.css">
    <title><?php if ($_GET['refid'] === "0001") { echo 'Login'; } else { echo 'Register';}?> - Database</title>
</head>
<body>
    <div class="main">
        <?php 
        if (isset($_GET['error'])) {
            echo '<div class="error">'.$_GET['error'].' <a href="../user/connection.php">fix this here</a></div>';
        }
        ?>  

        <h2><?php if ($_GET['refid'] === "0001") { echo 'Login'; } else { echo 'Register';}?></h2>
        <br>
        <b><?php echo $_SESSION['username']; ?></b>
        <div class="header">
            <div class="left"><?php echo host_name_extractor($_GET['from']);?></div>
            <div class="middle">🤝</div>
            <div class="right">database</div>
        </div>
        <form action="" method="post" autocomplete="on">
            <div class="form-group">
                <input type="submit" name="token_submit" class="sumbit" value="<?php if ($_GET['refid'] === "0001") { echo 'Login'; } else { echo 'Register';}?>">
            </div>
        </form>
    </div>
</body>
</html>
<?php
                    
                } else {
                    if (isset($_GET['secure_token'])) { 
                        header("Location: ../auth/login.php?from={$_GET['from']}&refid={$_GET['refid']}&secure_token");
                    } else {
                        header("Location: ../auth/login.php?from={$_GET['from']}&refid={$_GET['refid']}");
                    }
                    
                }
            } else {
                echo "$/refid length cant be smaller than 4 characters";
            }
        } else {
            echo("{$_GET['from']} is not a valid URL");
        }
    } else {
        echo "No $/refid and $/from found... ";
    }
?>