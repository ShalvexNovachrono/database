<?php
    session_start(); 
    extract($_REQUEST);

    require("../database/sql-connect.php");
    require("../database/accounts-db-function.php");
    require("../auth/tools.php");


    if (isset($_GET['home'])) {
        header("Location: ../user/index.php?home");
    }

    if (isset($_POST["qrcode"])) {  
        $code = validate($_POST["qrcode"]);
        login_qr_code($db, $code);
    } elseif (isset($_GET["scan_qrcode"])) {
      
        $code = validate($_GET["scan_qrcode"]);
        // var_dump($_SESSION);
        if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
            $user_id = $_SESSION['id'];
            $username = gzcompress($_SESSION['username']);
            $token_id = gzcompress($_SESSION['token_id']);
            $approved = "True";
            $expire_time = 0;
            $type = 1;  
            create_and_update_qr_code($db, $code, $user_id, $username, $token_id, $approved, $expire_time, $type);
        } else {
            header("Location: login.php");
        }
      
      
    } elseif (isset($_GET["alert"])) {
        if ($_GET["alert"] === "done") {}
            echo "<script type='text/javascript'>\nwindow.close();\n</script>";
    } else {
      
        if (!isset($_GET["wait"])) {
            $code = getRandomWord(10);
            $user_id = "";
            $username = "";
            $token_id = "";
            $approved = "False";
            $expire_time = time() + 120;
            $type = 0;  
            create_and_update_qr_code($db, $code, $user_id, $username, $token_id, $approved, $expire_time, $type);
        } elseif (isset($_GET["approved"])) {  
            if (isset($_GET['from']) && isset($_GET['refid'])) {
                if (isset($_GET['secure_token'])) { 
                    header("Location: token.php?from={$_GET['from']}&refid={$_GET['refid']}&secure_token");
                } else {
                    header("Location: token.php?from={$_GET['from']}&refid={$_GET['refid']}");
                }
            } else {
                header("Location: ?wait=00000&home");
            }
        }
    }


    if(isset($_GET["wait"]) || isset($_GET['alert'])) { 
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <title>QR code - Database</title>
</head>
<body>
    <div class="main">
        <?php 
        if (isset($_GET['error'])) {
            echo '<div class="error">'.$_GET['error'].'</div>';
        } elseif (isset($_GET['alert'])) {
            echo '<div class="error">'.$_GET['alert'].'</div>';
        }
        ?>  

        <h2>QR code</h2>
        <p style="margin-left: 24px;margin-right: 24px;">Please use a logged-in device to scan the qr code.</p>

        <form>
            <div class="form-group" id="qrCode">
                
            </div>
            <div class="form-group" >
                <input class="sumbit timer" id="timer" value="Timer" disabled>
            </div>
            <p class="question">Don't have an account? <a href="register.php<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "?from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">Sign up now</a>.</p>
            <div class="question buttom-link"><a href="login.php?quickLogin&<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">Quick Login</a><div class="small-dot"></div><a href="login.php?<?php if (isset($_GET['from']) && isset($_GET['refid'])) { echo "from={$_GET['from']}&refid={$_GET['refid']}"; } ?><?php if (isset($_GET['from'])) { echo "&secure_token"; } ?>">Login</a></div>
            
            <div class="info">
              <a href="../pages/about.php">?</a>
              <p>By Nava Majumdar</p>
            </div>
        </form>
    </div>
    <script>
      <?php
      if ($_GET["wait"] !== "00000") {
      ?>
      var qr = new QRCode(document.getElementById("qrCode"), "https://database.nava10y.repl.co/auth/qr-code.php?scan_qrcode=<?php echo validate($_GET["wait"]); ?>");

      setInterval(function() {
          $.ajax({
                  url:'https://database.nava10y.repl.co/auth/qr-code.php',
                  data: {qrcode: "<?php echo validate($_GET["wait"]);?>"},
                  method: 'post',
                  success: function(data) {
                      if (data === "approved") {
                          window.location.href = "https://database.nava10y.repl.co/auth/qr-code.php?wait=00000&approved";
                      } else {
                          console.log(data);
                      }
                  }
          })
      }, 1000);
      let counter = 120;

      setInterval(function() {
          if (counter <= 0) {
              window.location.href = "https://database.nava10y.repl.co/auth/qr-code.php";
              counter = 120;
          } else {
              counter--;
              document.getElementById("timer").value = counter;
          }
      }, 1000);
      
      <?php
      } else {
         echo "(´･ω･`)?";
      }
      ?>

  </script>
</body>
</html>
<?php
    }      
?>