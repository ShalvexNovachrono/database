<?php
    function checkForCapsuleContainer($db, $user_id, $container_id) {
        $result = $db->query("SELECT * FROM container WHERE user_id = '".$user_id."' AND container_id = '".$container_id."'");
        $count = 0;
        
        foreach ($result as $row) { 
            if ($row['user_id'] === $user_id && $row['container_id'] === $container_id) {  
                $count = 1;
            }
        }
        return $count;
    }

    function deleteCapsuleContainer($db, $user_id, $container_id) {
        $result = $db->query("SELECT * FROM container");
        
        foreach ($result as $row) { 
            if ($row['user_id'] === $user_id && checkForCapsuleContainer($db, $user_id, $container_id) === 1) {  
                $result2 = $db->query("SELECT * FROM capsule");
                foreach ($result2 as $row2) { 
                    if ($row2['container_id'] === $row['container_id']) {
                        $stmt = $db->prepare("DELETE FROM capsule WHERE container_id = :container_id");
                        $stmt->bindParam(':container_id', $container_id, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }
                
                $stmt = $db->prepare("DELETE FROM container WHERE user_id = :user_id AND container_id = :container_id");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $stmt->bindParam(':container_id', $container_id, PDO::PARAM_STR);
                $stmt->execute();
                header("Location: capsule.php");
                break;
            } else {
                header("Location: capsule.php?error=Container does not exist");
                break;
            }
        }
    }

    function makeCapsuleContainer($db, $user_id, $container_id, $container_name, $date_made) {
        try {
            if (checkForCapsuleContainer($db, $user_id, $container_id) === 0) {
                $stmt = $db->prepare(
                    "INSERT INTO container (user_id, container_id, container_name, date_made) 
                    VALUES (:user_id, :container_id, :container_name, :date_made)"
                );
                
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $stmt->bindParam(':container_id', $container_id, PDO::PARAM_STR);
                $stmt->bindParam(':container_name', $container_name, PDO::PARAM_STR);
                $stmt->bindParam(':date_made', $date_made, PDO::PARAM_STR);
                
                $stmt->execute();
                header("Location: capsule.php");
            } else {
                header("Location: capsule.php?error=Container does exist but you can't add the same one but you can update it");
            }
        } catch (Exception $ex) {
        	  echo $ex->getMessage();
        }
    }

    function getCapsuleContainer($db, $user_id) {
        $result = $db->query("SELECT * FROM container");
        
        foreach ($result as $row) { 
            if ($user_id === $row['user_id']) {  
                echo  "<div class=\"container_capsule\"><div class=\"container_name\"><h2>" . $row["container_name"] . "</h2><b>container_id: " . $row["container_id"] ."</b><form class=\"deleteContainerForm\" method=\"POST\"><input type=\"text\" class=\"hidden\" name=\"delete_Capsule_Container\" value=\"".$row['container_id']."\"><input type=\"submit\" class=\"delBtn\" onclick=\"checker()\" value=\"Delete\"></form></div><ul class=\"lists\">";
                $result2 = $db->query("SELECT * FROM capsule");
                foreach ($result2 as $row2) { 
                    if (number_to_text($row2['container_id']) === $row['container_id']) {  
                        echo "<li class=\"capsule\">user_id: " . number_to_text($row2['user_id']) . "</li>";
                    }
                }
                echo "</ul></div>";
            }
        }
    }


    function checkForCapsule($db, $user_id, $container_id) {
        $result = $db->query("SELECT * FROM capsule WHERE user_id = '".$user_id."' AND container_id = '".$container_id."'");
        $count = 0;
        
        foreach ($result as $row) { 
            if ($row['user_id'] === $user_id && $row['container_id'] === $container_id) {  
                $count = 1;
            }
        }
        return $count; 
    }

    function makeCapsule($db, $user_id, $container_id, $capsule_data) {
        try {
            if (checkForCapsule($db, $user_id, $container_id) === 0) {
                $stmt = $db->prepare(
                    "INSERT INTO capsule (user_id, container_id, capsule_data) 
                    VALUES (:user_id, :container_id, :capsule_data)"
                );
                
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $stmt->bindParam(':container_id', $container_id, PDO::PARAM_STR);
                $stmt->bindParam(':capsule_data', $capsule_data, PDO::PARAM_STR);
                
                $stmt->execute();
                echo "{\"success\": \"Success\"}";
            } else {
                $stmt = $db->prepare("Update capsule SET capsule_data = :capsule_data WHERE user_id = :user_id AND container_id = :container_id");
        
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $stmt->bindParam(':container_id', $container_id, PDO::PARAM_STR);
                $stmt->bindParam(':capsule_data', $capsule_data, PDO::PARAM_STR);
                
                $stmt->execute();
                echo "{\"success\": \"Success\"}";
            }
        } catch (Exception $ex) {
        	  echo $ex->getMessage();
        }
    }

    function getCapsule($db, $user_id, $container_id) {
        if (checkForCapsule($db, $user_id, $container_id) === 1) {
            $result = $db->query("SELECT * FROM capsule");
            
            foreach ($result as $row) { 
                if ($row['user_id'] === $user_id && $row['container_id'] === $container_id) {  
                    echo number_to_text($row["capsule_data"]);
                    break;
                }
            }
        } else {
            echo "{\"error\": \"capsule does not exist\"}";
        }
    }
?>