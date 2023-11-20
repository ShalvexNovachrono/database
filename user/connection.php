<?php 
    session_start(); 
    extract($_REQUEST);
    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        require("../database/sql-connect.php");
        require("../database/tokens-db-function.php");
        require("../auth/tools.php");
        if (isset($_GET['working']) && isset($_GET['refid'])) {
            $refid = $_GET['refid'];
            $token_id = $_SESSION['token_id'];
            $website_url = $_GET['website_url'];
            $row = find_token_details($db, $token_id, $website_url);
            if ($row['working'] === "1") {
                working($db, $token_id, $website_url, 0);
            } else {
                working($db, $token_id, $website_url, 1);
            }
        }
        
        if (isset($_GET['delete'])) {
            $delete = $_GET['delete'];
            $token_id = $_SESSION['token_id'];
            delete_connection($db, $token_id, $delete);
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
    <title>Connection</title>
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
    <div class="main">
<?php 
    $token_id = $_SESSION['token_id'];
    $results = user_connections($db, $token_id);
    foreach ($results as $row) {
?>

        <div class="connection w<?php echo $row['working']; ?>">
            <div class="details">
                <div class="url"><?php echo number_to_text($row['website_url']); ?></div>
                <div class="date"><?php echo $row['date_made']; ?></div>
            </div>
            <div class="btn-menu">
                <a href="?working&refid=<?php echo $row['refid']; ?>&website_url=<?php echo $row['website_url']; ?>"><button class="btn btn-w<?php echo $row['working']; ?>"><?php if ($row['working'] === "1") { echo "Disable"; } else { echo "Enable"; } ?></button></a>
                <a onclick="checker()" href="?delete=<?php echo $row['website_url']; ?>"><button class="delete">Delete</button></a>
            </div>
        </div>

<?php
    }
?>
    </div>
    
    <script>
        function checker() {
            var result = confirm('Are you sure you want to delete this like connection?');
            if (result == false) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>
<?php
    } else {
        header("Location: ../auth/login.php");
    }
?>