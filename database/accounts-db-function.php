<?php 

    require("sql-connect.php");

    function count_account($db) {
        $result = $db->query("SELECT * FROM users");
        $count = 0;
        
        foreach ($result as $row) { 
            //echo number_to_text($row['username']). " ";
            $count += 1;
        }
        return $count;
    }

    function find_user($db, $username) {
        $result = $db->query("SELECT * FROM users");
        $count = 0;
        
        foreach ($result as $row) { 
            if ($row['username'] === $username) {  
                $count = 1;
            }
        }
        return $count;
    }


    function register($db, $username, $password, $id, $token_id, $key_id) {
        $result = $db->query("SELECT * FROM users");
        $count = 0;
        foreach ($result as $row) { 
            if ($row['username'] === $username) {  
                $count = 1;
            }
        }
    
        if ($count === 1) {
            if (isset($_GET['from']) && isset($_GET['refid'])) {
                if (isset($_GET['secure_token'])) { 
                    header("Location: register.php?error=account is taken&from={$_GET['from']}&refid={$_GET['refid']}&secure_token");
                } else {
                    header("Location: register.php?error=account is taken&from={$_GET['from']}&refid={$_GET['refid']}");
                }
            } else {
                header("Location: register.php?error=account is taken");
            }
        } else {
            $stmt = $db->prepare(
                "INSERT INTO users (id, username, key_id, token_id) 
                VALUES (:id, :username, :key_id, :token_id)"
            );
            
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':key_id', $key_id, PDO::PARAM_STR);
            exec('curl $REPLIT_DB_URL -d \''.$key_id.'='.$password.'\''); // saves password in repldb
            $stmt->bindParam(':token_id', $token_id, PDO::PARAM_STR);
            
            $stmt->execute();
            if (isset($_GET['from']) && isset($_GET['refid'])) {
                if (isset($_GET['secure_token'])) { 
                    header("Location: login.php?error=Please Login now&from={$_GET['from']}&refid={$_GET['refid']}&secure_token");
                } else {
                    header("Location: login.php?error=Please Login now&from={$_GET['from']}&refid={$_GET['refid']}");
                }
            } else {
                header("Location: login.php?error=Please Login now");
            }
        }
    }

    function login($db, $username, $password) {

        $result = $db->query("SELECT * FROM users");
        $count = 0;
        
        
        foreach ($result as $row) { 
            if ($row['username'] === $username) {
                $retval=null;
                exec('curl $REPLIT_DB_URL/'.$row['key_id'], $retval);
                $hashed_password = $retval[0];
                // var_dump($hashed_password);
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = number_to_text($row['username']);
                    $_SESSION['token_id'] = $row['token_id'];
                    
                    $user_id = $row['id'];
                    clear_quick_login($db, $user_id);
    
                    $count = 1;
                    if (isset($_GET['from']) && isset($_GET['refid'])) {
                        if (isset($_GET['secure_token'])) { 
                            header("Location: token.php?from={$_GET['from']}&refid={$_GET['refid']}&secure_token");
                        } else {
                            header("Location: token.php?from={$_GET['from']}&refid={$_GET['refid']}");
                        }
                    } else {
                        header("Location: login.php?home");
                    }
                }
                if ($retval[0] === md5($password)) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = number_to_text($row['username']);
                    $_SESSION['token_id'] = $row['token_id'];
                    
                    $user_id = $row['id'];
                    clear_quick_login($db, $user_id);
    
                    $count = 1;
                    if (isset($_GET['from']) && isset($_GET['refid'])) {
                        if (isset($_GET['secure_token'])) { 
                            header("Location: token.php?from={$_GET['from']}&refid={$_GET['refid']}&secure_token");
                        } else {
                            header("Location: token.php?from={$_GET['from']}&refid={$_GET['refid']}");
                        }
                    } else {
                        header("Location: login.php?home");
                    }
                }
            }
        }
    
        if ($count === 0) {
            if (isset($_GET['from']) && isset($_GET['refid'])) {
                if (isset($_GET['secure_token'])) { 
                    header("Location: login.php?error=username or password does not match&from={$_GET['from']}&refid={$_GET['refid']}&secure_token");
                } else {
                    header("Location: login.php?error=username or password does not match&from={$_GET['from']}&refid={$_GET['refid']}");
                }
            } else {
                header("Location: login.php?error=username or password does not match");
            }
        }
    }

    function change_password($db, $id, $new_password) {
        $result = $db->query("SELECT * FROM users");
        
        foreach ($result as $row) { 
            if ($row['id'] === $id) {  
                exec('curl $REPLIT_DB_URL -d \''.$row['key_id'].'='.$new_password.'\'');
                break;
            }
        }
        header("Location: index.php?user");
    }

    function check_password($db, $id, $current_password) {
        $result = $db->query("SELECT * FROM users");
        $count = 0;
        
        foreach ($result as $row) { 
            if ($row['id'] === $id) {  
              $retval=null;
              exec('curl $REPLIT_DB_URL/'.$row['key_id'], $retval);
              $hashed_password = $retval[0];
              if (password_verify($current_password, $hashed_password)) {
                  $count = 1;
              }
              if ($retval[0] === md5($current_password)) {
                  $count = 1;
              }
            }
        }
    
        return $count;
    }

    function get_key_id($db, $id) {
        $result = $db->query("SELECT * FROM users");
        $x = "";
        
        foreach ($result as $row) { 
            if ($row['id'] === $id) {  
                $x = $row['key_id'];
                return $x;
                break;
            }
        }
    }

    function delete_account($db, $id, $username, $token_id) {
        $key_id = get_key_id($db, $id);
        exec('curl -XDELETE $REPLIT_DB_URL/'.$key_id);
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id AND username = :username");
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = $db->prepare("DELETE FROM tokens WHERE token_id = :token_id");
        $stmt->bindParam(':token_id', $token_id, PDO::PARAM_STR);
        $stmt->execute();

        
        $result = $db->query("SELECT * FROM container");
        
        foreach ($result as $row) { 
            if ($row["user_id"] === $id) {
                $result2 = $db->query("SELECT * FROM capsule");
                foreach ($result as $row) { 
                    if ($row2["container_id"] === $row["container_id"]) {
                        $stmt = $db->prepare("DELETE FROM capsule WHERE container_id = :container_id");
                        $stmt->bindParam(':container_id', $container_id, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }
                $stmt = $db->prepare("DELETE FROM container WHERE user_id = :user_id AND container_id = :container_id");
                $stmt->bindParam(':user_id', $id, PDO::PARAM_STR);
                $stmt->bindParam(':container_id', $container_id, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
      
        header("Location: ../pages/logout.php");
    }


    # apps-api-auth login/register for apps

    function login_apa($db, $username, $password) {
        $result = $db->query("SELECT * FROM users");
        $count = 0;
        
        foreach ($result as $row) { 
            if ($row['username'] === $username) {
                $retval=null;
                exec('curl $REPLIT_DB_URL/'.$row['key_id'], $retval);
                $hashed_password = $retval[0];
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = number_to_text($row['username']);
                    $_SESSION['token_id'] = $row['token_id'];
                    $user_id = $row['id'];
                    clear_quick_login($db, $user_id);
                    $count = 1;
                    echo "{\"user_id\": \"".$_SESSION['id']."\"}";
                }
                if ($retval[0] === md5($password)) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = number_to_text($row['username']);
                    $_SESSION['token_id'] = $row['token_id'];
                    $user_id = $row['id'];

                    clear_quick_login($db, $user_id);
                    $count = 1;
                    echo "{\"user_id\": \"".$_SESSION['id']."\"}";
                }
            }
        }
    
        if ($count === 0) {
            echo "{\"error\": \"username or password does not match.\"}";
        }
    }

    function register_apa($db, $username, $password, $id, $token_id, $key_id) {
        $result = $db->query("SELECT * FROM users");
        $count = 0;
        foreach ($result as $row) { 
            if ($row['username'] === $username) {  
                $count = 1;
            }
        }
    
        if ($count === 1) {
            echo "{\"error\": \"account is taken\"}";
        } else {
            $stmt = $db->prepare(
                "INSERT INTO users (id, username, key_id, token_id) 
                VALUES (:id, :username, :key_id, :token_id)"
            );
            
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':key_id', $key_id, PDO::PARAM_STR);
            exec('curl $REPLIT_DB_URL -d \''.$key_id.'='.$password.'\''); // saves password in repldb
            $stmt->bindParam(':token_id', $token_id, PDO::PARAM_STR);
            
            $stmt->execute();
          
            echo "{\"user_id\": \"".$id."\"}";
        }
    }

    # quick login

    function quick_login($db, $quickLogin_code) {

        $deleteQuickLogin_code = $db->prepare("DELETE FROM quickLogin WHERE date_made <= :current_date_time");
        $deleteQuickLogin_code->bindParam(":current_date_time", time());
        $deleteQuickLogin_code->execute();
      
        $result = $db->query("SELECT * FROM quickLogin");
        $count = 0;
        $user_id = "";
        foreach ($result as $row) { 
           if ($row['quickLogin_code'] === $quickLogin_code) {
                $user_id = $row['user_id'];
                clear_quick_login($db, $user_id);
                $count = 1;
                break;
            }
        }
    
        if ($count === 0) {
            if (isset($_GET['from']) && isset($_GET['refid'])) {
                header("Location: login.php?quickLogin&error=quick code does not match&from={$_GET['from']}&refid={$_GET['refid']}");
            } else {
                header("Location: login.php?quickLogin&error=quick code does not match");
            }
        } else {
            $result2 = $db->query("SELECT * FROM users");
        
            foreach ($result2 as $row2) { 
                if ($row2['id'] === $user_id) {
                    $_SESSION['id'] = $row2['id'];
                    $_SESSION['username'] = number_to_text($row2['username']);
                    $_SESSION['token_id'] = $row2['token_id'];

                    if (isset($_GET['from']) && isset($_GET['refid'])) {
                        if (isset($_GET['secure_token'])) { 
                            header("Location: token.php?from={$_GET['from']}&refid={$_GET['refid']}&secure_token");
                        } else {
                            header("Location: token.php?from={$_GET['from']}&refid={$_GET['refid']}");
                        }
                    } else {
                        header("Location: login.php?home");
                    }
                }
            }
        }
    }

    function clear_quick_login($db, $user_id) {
        $stmt = $db->prepare("DELETE FROM quickLogin WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->execute();
    }

    function create_quickLogin_code($db, $user_id, $quickLogin_code, $date) {
        
        $result = $db->query("SELECT * FROM quickLogin");
        $count = 0;
        foreach ($result as $row) { 
            if ($row['user_id'] === $user_id) {
                $count = 1;
                break;
            }
        }
        $hash_code = md5($quickLogin_code);
        if ($count === 0) {
            $stmt = $db->prepare(
                "INSERT INTO quickLogin (user_id, quickLogin_code, date_made) 
                VALUES (:user_id, :quickLogin_code, :date_made)"
            );
            
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':quickLogin_code', $hash_code, PDO::PARAM_STR);
            $stmt->bindParam(':date_made', $date, PDO::PARAM_STR);
            
            $stmt->execute();
        } else {
            $stmt = $db->prepare("Update quickLogin SET quickLogin_code = :quickLogin_code WHERE user_id = :user_id");
        
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':quickLogin_code', $hash_code, PDO::PARAM_STR);
            
            $stmt->execute();
        }

        echo $quickLogin_code;
    }


    function login_qr_code($db, $code) {
        $result = $db->query("SELECT * FROM qrcode");
        $count = 0;
        foreach ($result as $row) { 
          
            if ($row['code'] === $code && $row['approved'] === "True") {
                $_SESSION['id'] = $row['user_id'];
                $_SESSION['username'] = gzuncompress($row['username']);
                $_SESSION['token_id'] = gzuncompress($row['token_id']);
                echo "approved";
                $count = 1;
                break;
            }
        }
      
        if ($count !== 1) {
            echo "not approved";
        } else {
            delete_qr_code_by_code($db, $code);
        }
 
    }

    function create_and_update_qr_code($db, $code, $user_id, $username, $token_id, $approved, $expire_time, $type) {
        $deleteQRcode = $db->prepare("DELETE FROM qrcode WHERE expire_time <= :current_time");
        $deleteQRcode->bindParam(":current_time", time(), PDO::PARAM_INT);
        $deleteQRcode->execute();
        $result = $db->query("SELECT * FROM qrcode");
        foreach ($result as $row) { 
            if ($row['code'] === $code) {
                $count = 1;
                break;
            }
        }
        if ($count === null && $type === 0) {
            $stmt = $db->prepare("INSERT INTO qrcode (code, user_id, username, token_id, approved, expire_time) VALUES (:code, :user_id, :username, :token_id, :approved, :expire_time)");
            
            $stmt->bindParam(':code', $code, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':token_id', $token_id, PDO::PARAM_STR);
            $stmt->bindParam(':approved', $approved, PDO::PARAM_STR);
            $stmt->bindParam(':expire_time', $expire_time, PDO::PARAM_STR);
            $stmt->execute();
            
            header("Location: qr-code.php?wait={$code}");
        } elseif ($count === 1) {
            $stmt = $db->prepare("Update qrcode SET user_id = :user_id, username = :username, token_id = :token_id, approved = :approved WHERE code = :code");
  
            $stmt->bindParam(':code', $code, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':token_id', $token_id, PDO::PARAM_STR);
            $stmt->bindParam(':approved', $approved, PDO::PARAM_STR);
            $stmt->execute();
            header("Location: qr-code.php?alert=done");
        } else {
            //echo $count . " " . $code ." ". $user_id ." ". (gzuncompress($username)) ." ". gzuncompress($token_id) ." ". $approved ." ". $expire_time ." ". $type ."<br>";
        }
    }


    function delete_qr_code_by_code($db, $code) {
        $deleteQRcode = $db->prepare("DELETE FROM qrcode WHERE code = :code");
        $deleteQRcode->bindParam(":code", $code);
        $deleteQRcode->execute();
    }
