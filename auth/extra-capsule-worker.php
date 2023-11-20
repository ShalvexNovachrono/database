<?php
    header('Content-Type: application/json');
    session_start(); 
    extract($_REQUEST);


    $db = new PDO('sqlite:db.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    require("tools.php");

    require("../database/sql-connect.php");
    require("../database/capsule-db-function.php");
    require("../database/extra-capsule-db-function.php");


    if (isset($_POST['aPlus'])) {
        if (isset($_POST['userID']) && isset($_POST['extraCapsuleID']) && isset($_POST['extraCapsuleData'])) {
            if (strlen($_POST['extraCapsuleID']) === 20) {

                $userID = text_to_number(validate($_POST['userID']));
                $extraCapsuleID = text_to_number(validate($_POST['extraCapsuleID']));
                list($indexNumber, $exist) = getNumberForIndexNumber($db, $userID, $extraCapsuleID);
                $extraCapsuleData = zipText(text_to_number(validate($_POST['extraCapsuleData'])));
                $date = date('Y-m-d H:i:s');
                if (strlen(validate($_POST['extraCapsuleData'])) >= 3) {
                    if (isset($_POST['indexNumber'])) {
                        if (is_int((int)$_POST['indexNumber'])) {
                            $indexNumber = validate((int)$_POST['indexNumber']); 
                            list($count, $exist) = checkIfCertainIndexExist($db, $userID, $extraCapsuleID, $indexNumber);
                            if ($count === 1 && $exist === TRUE) {
                                $stmt = $db->prepare("Update extraCapsule SET extraCapsuleData = :extraCapsuleData, extraCapsuleUpdateDateTime = :extraCapsuleUpdateDateTime WHERE userID = :userID AND extraCapsuleID = :extraCapsuleID AND indexNumber = :indexNumber");
                
                                $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
                                $stmt->bindParam(':extraCapsuleID', $extraCapsuleID, PDO::PARAM_STR);
                                $stmt->bindParam(':indexNumber', $indexNumber, PDO::PARAM_STR);
                                $stmt->bindParam(':extraCapsuleData', $extraCapsuleData, PDO::PARAM_STR);
                                $stmt->bindParam(':extraCapsuleUpdateDateTime', $date, PDO::PARAM_STR);
                                
                                $stmt->execute();
                                
                                if ($stmt->execute()) {
                                    echo json_encode(array("success" => "Data updated successfully \ "));
                                } else {
                                    echo json_encode(array("error" => "An error occurred while updating data"));
                                }
                            } else {
                                echo json_encode(array("error" => "The [indexNumber] found number is [".$count."]. If found number equal 0 then [extraCapsule] with that [indexNumber] cant be found. If found number more then 1 then its a bug please report the bug."));
                            }
                        } else {
                            echo json_encode(array("error" => "The [indexNumber] should be a integer."));
                        }
                    } else {
                        
                        if ($exist === TRUE) {
                            $stmt = $db->prepare("INSERT INTO extraCapsule (userID, extraCapsuleID, indexNumber, extraCapsuleData, extraCapsuleDateTime, extraCapsuleUpdateDateTime) VALUES (:userID, :extraCapsuleID, :indexNumber, :extraCapsuleData, :extraCapsuleDateTime, :extraCapsuleUpdateDateTime)");
                            $stmt->bindParam(':userID', $userID);
                            $stmt->bindParam(':extraCapsuleID', $extraCapsuleID);
                            $stmt->bindParam(':indexNumber', $indexNumber);
                            $stmt->bindParam(':extraCapsuleData', $extraCapsuleData);
                            $stmt->bindParam(':extraCapsuleDateTime', $date);
                            $stmt->bindParam(':extraCapsuleUpdateDateTime', $date);
                
                            if ($stmt->execute()) {
                                echo json_encode(array("success" => "Data added successfully /"));
                            } else {
                                echo json_encode(array("error" => "An error occurred while adding data"));
                            }
                        } else {
                            echo json_encode(array("error" => "The extraCapsule that your looking for does not exist as [userID] [extraCapsuleID] can't be found together."));
                        }
                    }
                } else {
                    echo json_encode(array("error" => "The [extraCapsuleData] length should be atleast 3+ characters."));
                }
            } else {
                echo json_encode(array("error" => "The [extraCapsuleID] length should be 20."));
            }
        } else {
            echo json_encode(array("error" => "Make sure to meet the post(aPlus, userID, extraCapsuleID, extraCapsuleData or indexNumber *(only if editing a certain extracapsule) ) requirements"));
        }
    } elseif (isset($_POST['cPlus'])) {
        if (isset($_POST['userID']) && isset($_POST['extraCapsuleID']) && isset($_POST['extraCapsuleData'])) {
            if (strlen($_POST['extraCapsuleID']) === 20) {

                list($userID, $userIDExist) = checkIfUserIDExist($db, text_to_number(validate($_POST['userID'])));
                $extraCapsuleID = text_to_number(validate($_POST['extraCapsuleID']));
                list($indexNumber, $exist) = getNumberForIndexNumber($db, $userID, $extraCapsuleID);
                $extraCapsuleData = zipText(text_to_number(validate($_POST['extraCapsuleData'])));
                $date = date('Y-m-d H:i:s');
                if ($userIDExist === TRUE) {
                    if ($indexNumber === 0 && $exist === FALSE) {
                        $stmt = $db->prepare("INSERT INTO extraCapsule (userID, extraCapsuleID, indexNumber, extraCapsuleData, extraCapsuleDateTime, extraCapsuleUpdateDateTime) VALUES (:userID, :extraCapsuleID, :indexNumber, :extraCapsuleData, :extraCapsuleDateTime, :extraCapsuleUpdateDateTime)");
                        $stmt->bindParam(':userID', $userID);
                        $stmt->bindParam(':extraCapsuleID', $extraCapsuleID);
                        $stmt->bindParam(':indexNumber', $indexNumber);
                        $stmt->bindParam(':extraCapsuleData', $extraCapsuleData);
                        $stmt->bindParam(':extraCapsuleDateTime', $date);
                        $stmt->bindParam(':extraCapsuleUpdateDateTime', $date);
            
                        if ($stmt->execute()) {
                            echo json_encode(array("success" => "Data added successfully"));
                        } else {
                            echo json_encode(array("error" => "An error occurred while adding data"));
                        }
                    } else {
                        echo json_encode(array("error" => "The [extraCapsuleID] exist with the [userID]."));
                    }
                } else {
                    echo json_encode(array("error" => "[userID] does not exist."));
                }
            }  else {
                echo json_encode(array("error" => "The [extraCapsuleID] length should be 20."));
            }
        } else {
            echo json_encode(array("error" => "Make sure to meet the post(cPlus, userID, extraCapsuleID, extraCapsuleData) requirements"));
        }
    } elseif (isset($_POST['rPlus'])) {

        if (isset($_POST['userID']) && isset($_POST['extraCapsuleID'])) {
            if (strlen($_POST['extraCapsuleID']) === 20) {
                if (isset($_POST['indexNumber'])) {
                    if (is_int((int)$_POST['indexNumber'])) {
                        // allow to view certain extracapsule sub capsule
                        $userID = text_to_number(validate($_POST['userID']));
                        $indexNumber = (int)$_POST['indexNumber'];
                        $extraCapsuleID = text_to_number(validate($_POST['extraCapsuleID']));
                        $byitself = isset($_POST["byitself"]);
                        list($count, $exist, $data) = getDataIfCertainIndexExist($db, $userID, $extraCapsuleID, $indexNumber, $byitself);
                        if ($count === 1 && $exist === TRUE) {
                            if ($byitself) {
                                echo html_entity_decode($data);
                            } else {
                                echo json_encode($data);
                            }
                        } else {
                            echo json_encode(array("error" => "The found number is [".$count."], if count number is 0 then it(extracapsule sub capsule) does not exist and if more than 1 then its a bug and report it soon."));
                        }
                    } else {
                        echo json_encode(array("error" => "The [indexNumber] should be a integer."));
                    }
                } else {
                    // allow to view certain extracapsule with all sub capsule
                    $userID = text_to_number(validate($_POST['userID']));
                    $extraCapsuleID = text_to_number(validate($_POST['extraCapsuleID']));
                    $byitself = isset($_POST["byitself"]);
                    list($exist, $data) = getDataIfExist($db, $userID, $extraCapsuleID, $byitself);
                    if ($exist === TRUE) {
                        echo (json_encode($data));
                    } else {
                        echo json_encode(array("error" => "The found number is [".$count."], if count number is 0 then it(extracapsule sub capsule) does not exist and if more than 1 then its a bug and report it soon."));
                    }

                }
            }  else {
                echo json_encode(array("error" => "The [extraCapsuleID] length should be 20."));
            }
        } else {
            echo json_encode(array("error" => "Make sure to meet the post(rPlus, userID, extraCapsuleID or byitself *(show data on its own) ) requirements"));
        }
    } elseif (isset($_POST['dPlus'])) {
        if (isset($_POST['userID']) && isset($_POST['extraCapsuleID'])) {
            if (strlen($_POST['extraCapsuleID']) === 20) {
                $userID = text_to_number(validate($_POST['userID']));
                $extraCapsuleID = text_to_number(validate($_POST['extraCapsuleID']));
                list($indexNumber, $exist) = getNumberForIndexNumber($db, $userID, $extraCapsuleID);
                        
                if ($exist === TRUE) {
                    $stmt = $db->prepare("DELETE FROM extraCapsule WHERE userID = :userID AND extraCapsuleID = :extraCapsuleID");
                    $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
                    $stmt->bindParam(':extraCapsuleID', $extraCapsuleID, PDO::PARAM_STR);
                    $stmt->execute();
        
                    if ($stmt->execute()) {
                        echo json_encode(array("success" => "Data deleted successfully"));
                    } else {
                        echo json_encode(array("error" => "An error occurred while deleting data"));
                    }
                } else {
                    echo json_encode(array("error" => "The extraCapsule that your looking for does not exist as [userID] [extraCapsuleID] can't be found together."));
                }
            } else {
                echo json_encode(array("error" => "The [extraCapsuleID] length should be 20."));
            }
        } else {
            echo json_encode(array("error" => "Make sure to meet the post(aPlus, userID, extraCapsuleID) requirements"));
        }
    } else {
        echo json_encode(array("error" => "Make sure to meet the post(aPlus, cPlus, rPlus) requirements"));
    }
?>
