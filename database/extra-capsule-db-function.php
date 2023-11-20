<?php 
    function getNumberForIndexNumber($db, $userID, $extraCapsuleID) {
        $result = $db->query("SELECT * FROM extraCapsule WHERE userID = '".$userID."' AND extraCapsuleID = '".$extraCapsuleID."'");
        $count = 0;
        $exist = FALSE;
        
        foreach ($result as $row) { 
            if ($row['userID'] === $userID && $row['extraCapsuleID'] === $extraCapsuleID) {  
                $count = $row['indexNumber'] + 1;
                $exist = TRUE;
            }
        }
        return array($count, $exist);
    }

    function checkIfUserIDExist($db, $userID) {
        $result = $db->query("SELECT * FROM users");
        $exist = FALSE;
        
        foreach ($result as $row) { 
            if (text_to_number($row['id']) === $userID) {  
                $exist = TRUE;
                break;
            }
        }
        return array($userID, $exist);
    }

    function checkIfCertainIndexExist($db, $userID, $extraCapsuleID, $indexNumber) {
        $result = $db->query("SELECT * FROM extraCapsule WHERE userID = '".$userID."' AND extraCapsuleID = '".$extraCapsuleID."' AND indexNumber = '".$indexNumber."'");
        $count = 0;
        $exist = FALSE;
        foreach ($result as $row) { 
            if ((int)$indexNumber === (int)$row['indexNumber']) {  
                $count += 1;
                $exist = TRUE;
                break;
            }
        }
        return array($count, $exist);
    }

    function getDataIfCertainIndexExist($db, $userID, $extraCapsuleID, $indexNumber, $byitself) {
        $result = $db->query("SELECT * FROM extraCapsule WHERE userID = '".$userID."' AND extraCapsuleID = '".$extraCapsuleID."' AND indexNumber = '".$indexNumber."'");
        $count = 0;
        $data = "";
        $exist = FALSE;
        foreach ($result as $row) { 
            if ((int)$indexNumber === (int)$row['indexNumber']) {  
                $count += 1;
                if ($byitself) {
                    $data = html_entity_decode(number_to_text(upZipText($row['extraCapsuleData'])));
                } else {
                    
                    $text = html_entity_decode(number_to_text(upZipText($row['extraCapsuleData'])));
                    //$data = number_to_text(upZipText($row['extraCapsuleData']));
                    
                    $data = array(
                        'indexNumber' => (int)$row['indexNumber'],
                        'extraCapsuleData' => $text,
                        'extraCapsuleDateTime' => $row['extraCapsuleDateTime'],
                        'extraCapsuleUpdateDateTime' => $row['extraCapsuleUpdateDateTime']
                    );              
                }

                $exist = TRUE;
                break;
            }
        }
        return array($count, $exist, $data);
    }

    function getDataIfExist($db, $userID, $extraCapsuleID, $byitself) {
        $result = $db->query("SELECT * FROM extraCapsule WHERE userID = '".$userID."' AND extraCapsuleID = '".$extraCapsuleID."'");

        $data = [];
        
        $exist = FALSE;
        foreach ($result as $row) { 
            if ($byitself) {
                $text = html_entity_decode(number_to_text(upZipText($row['extraCapsuleData'])));
                $newArray = array((int)$row['indexNumber'] => [
                    'indexNumber'=> (int)$row['indexNumber'],
                    'extraCapsuleData' => $text
                ]);
                $data = array_merge($data, $newArray);
            } else {
                $text = html_entity_decode(number_to_text(upZipText($row['extraCapsuleData'])));

                        
                $newArray = array((int)$row['indexNumber'] => [
                    'indexNumber'=> (int)$row['indexNumber'],
                    'extraCapsuleData' => $text,
                    'extraCapsuleDateTime' => $row['extraCapsuleDateTime'],
                    'extraCapsuleUpdateDateTime' => $row['extraCapsuleUpdateDateTime']
                ]);
                $data = array_merge($data, $newArray);

                            
            }
            $exist = TRUE;
        }

        

        return array($exist, $data);
    }