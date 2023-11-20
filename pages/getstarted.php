<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/body.css">
    <link rel="stylesheet" href="../css/scroll-bar.css">
    <link rel="stylesheet" href="../css/home.css">
    <title>Get Started with Database features like login and data saver</title>
</head>
<body>
    <div class="main">

        <div class="container">
            <h1>How to connect <b>database v3</b> to your website?</h1><br>
            <div class="sub-container">
                <h2>Register:</h2>
                1) Send a get requst to https://database.nava10y.repl.co/auth/token.php?<b>from=['your web site url here']</b>&<b>refid=['a unique id']</b>.<br><br>
                2) You have will get a <b>query string</b> with https://your-website.com/<b>?refid=['a unique refid']</b>&<b>id=['a unique id']</b>&<b>username=['username']</b>&<b>register</b>.<br><br>
            </div>
            <div class="sub-container">
                <h2>Login:</h2>
                1) Send a get requst to <b>https://database.nava10y.repl.co/auth/token.php?from=['your web site url here']&refid=[0001]</b> but for the refid you have put '0001'.<br><br>
                2) You have will get a <b>query string</b> with https://your-website.com/<b>?refid=['a same unique id']</b><b>&id=['same user id']</b>&<b>login</b>.<br><br>
            </div>
        </div>
<!--         <div class="container">
            <h1>How to use our <b>database v3</b> to login/register in your apps/website?</h1><br>
            <div class="sub-container">
                <h2>Register:</h2>
                1) Send a POST requst to this url https://database.nava10y.repl.co/auth/register.php?<b>gameMode</b>.<br><br>
                2) You must inclued this <b>username</b> and <b>password</b>.<br><br>
                3) On the same url page you will get the <b>user_id</b> in a <b>json format</b>.<br>
            </div>
            <div class="sub-container">
                <h2>Login:</h2>
                1) Send a POST requst to this url https://database.nava10y.repl.co/auth/login.php?<b>gameMode</b>.<br><br>
                2) You must inclued this <b>username</b> and <b>password</b>.<br><br>
                3) On the same url page you will get the <b>user_id</b> in a <b>json format</b>.<br>
            </div>
            <div>
                <h2>Error: </h2>
                1) You can get a <b>error</b> in <b>json format</b>.<br>
            </div>
        </div> -->
        <div class="container">
            <h1>How to save/get your user data in/from <b>database v3</b>?</h1><br>
            <div class="sub-container">
                <h2>Capsule Container: </h2>
                1) You need to make a <b><a href="capsule.php">capsule container</a></b> that will store your user data.<br><br>
                2) You need to go <b><a href="capsule.php">here</a></b> then press on <b>Create Container</b> and put the name of the container.<br><br>
                3) You will get <b>container_id</b> then save this id.<br>
            </div>
            <div class="sub-container">
                <h2>Save Data: </h2>
                1) You need to make a GET requst <b>https://database.nava10y.repl.co/auth/login.php?make</b>&<b>user_id=[put_user_id_of_your_user]</b>&<b>container_id=[put_container_id]</b>&<b>capsule_data=[put_json_data]</b> that will store your user data.<br><br>
                2) When done it will give you a error saying success in a <b>json format</b>.
            </div>
            <div class="sub-container">
                <h2>Update Data: </h2>
                1) You need to make a GET requst <b>https://database.nava10y.repl.co/auth/login.php?update</b>&<b>user_id=[put_user_id_of_your_user]</b>&<b>container_id=[put_container_id]</b>&<b>capsule_data=[put_json_data]</b> that will store your user data.<br><br>
                2) When done it will give you a error saying success in a <b>json format</b>.
            </div>
            <div class="sub-container">
                <h2>Get Data: </h2>
                1) You need to make a GET requst <b>https://database.nava10y.repl.co/auth/login.php?show</b>&<b>user_id=[put_user_id_of_your_user]</b>&<b>container_id=[put_container_id]</b>.<br><br>
                2) You will get user json data in a <b>json format</b>
            </div>
        </div>
        <div class="container">
            <h1>How enable, disable and delete your account to the connected website?</h1><br>
            Press this <a href="../user/connection.php">Link</a> it will take you to a list.<br><br>
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