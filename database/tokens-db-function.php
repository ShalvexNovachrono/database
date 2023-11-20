<?php 

    require("sql-connect.php");

    function find_user_tokens($db, $token_id, $website_url) {
        $result = $db->query("SELECT * FROM tokens");
        $count = 0;
        
        foreach ($result as $row) { 
            if ($row['token_id'] === $token_id && $row['website_url'] === $website_url) {  
                $count = 1;
            }
        }
        return $count;
    }

    function find_token_details($db, $token_id, $website_url) {
        $result = $db->query("SELECT * FROM tokens");
        
        foreach ($result as $row) { 
            if ($row['token_id'] === $token_id && $row['website_url'] === $website_url) {
                break;
            }
        }
        return $row;
    }

    function user_connections($db, $token_id) {
        $result = $db->query("SELECT * FROM tokens WHERE token_id = '".$token_id."'");
        return $result;
    }
    
    function register_token($db, $token_id, $website_url, $refid, $send_id, $working, $date_made) {
        $stmt = $db->prepare(
            "INSERT INTO tokens (token_id, website_url, refid, send_id, working, date_made) 
            VALUES (:token_id, :website_url, :refid, :send_id, :working, :date_made)"
        );
        
        $stmt->bindParam(':token_id', $token_id, PDO::PARAM_STR);
        $stmt->bindParam(':website_url', $website_url, PDO::PARAM_STR);
        $stmt->bindParam(':refid', $refid, PDO::PARAM_STR);
        $stmt->bindParam(':send_id', $send_id, PDO::PARAM_STR);
        $stmt->bindParam(':working', $working, PDO::PARAM_STR);
        $stmt->bindParam(':date_made', $date_made, PDO::PARAM_STR);
        
        $stmt->execute();
        header("Location: {$_GET['from']}?refid={$refid}&id={$send_id}&username={$_SESSION['username']}&register");
    }

    function login_token($db, $token_id, $website_url) {
        $result = $db->query("SELECT * FROM tokens WHERE website_url = '".$website_url."'");
        $count = 0;
        foreach ($result as $row) { 
            if ($row['token_id'] === $token_id && $row['working'] === "1") {
                $count = 1;
                if (isset($_GET["secure_token"])) {
                  login_token_post_push($_GET['from'], $row['refid'], $row['send_id']);
                } else {
                  header("Location: {$_GET['from']}?refid={$row['refid']}&id={$row['send_id']}&login");
                }
            }
        }
        if ($count == 0) {
            header("Location: token.php?error=this url is disabled by you&from={$_GET['from']}&refid={$_GET['refid']}");
        }

    }

    function working($db, $token_id, $website_url, $n) {
        $stmt = $db->prepare("Update tokens SET working = :working WHERE token_id = :token_id AND website_url = :website_url");
        $stmt->bindParam(':token_id', $token_id, PDO::PARAM_STR);
        $stmt->bindParam(':website_url', $website_url, PDO::PARAM_STR);
        $stmt->bindParam(':working', $n, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: connection.php");
    }

    function delete_connection($db, $token_id, $delete) {
        $stmt = $db->prepare("DELETE FROM tokens WHERE token_id = :token_id AND website_url = :website_url");
        $stmt->bindParam(':token_id', $token_id, PDO::PARAM_STR);
        $stmt->bindParam(':website_url', $delete, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: connection.php");

    }

    // secure token - database v4

    function login_token_post_push($url, $refid, $send_id) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url."?secure_token");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
          "Content-Type: application/x-www-form-urlencoded",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
       
          
        $post_data = "refid={$refid}&id={$send_id}&login=true";
      
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
      
        $resp = curl_exec($curl);
        curl_close($curl);
        header("Location: ".$url."?secure_code=".$resp);
        // var_dump($resp);
    }
