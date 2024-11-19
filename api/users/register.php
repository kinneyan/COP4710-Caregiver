<?php


    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $data = json_decode(file_get_contents('php://input'), true);

    $conn = new mysqli("localhost", "kinneyan", "test", "caregivers");

    if ($conn->connect_error) {

        return returnWithError($conn->connect_error);

    } else {

        $unique = true;

        $stmt = $conn->prepare("SELECT username FROM users WHERE username=?");
        $stmt->bind_param("s", $data["username"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        

        while ($row = $result->fetch_assoc()) {

            // someone has same name as user trying to register
            if (strcmp($row["username"], $data["username"]) == 0) {
                $unique = false;
            }
        }

        if ($unique) {

            # add user if username is unique
            $stmt2 = $conn->prepare("INSERT INTO users (username, password, email, fname, lname, address, phone, balance, available_hours) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 2000, ?)");
            $stmt2->bind_param(
                "ssssssdi", 
                $data['username'], 
                $data['password'], 
                $data['email'], 
                $data['firstName'], 
                $data['lastName'], 
                $data['address'], 
                $data['phone'], 
                $data['available_hours']
            );
            $res = $stmt2->execute();
            $stmt2->close();

    
            if ($res) {
                
                returnWithVerdict("new user created");

            } else {
                returnWithVerdict($conn->error);
            }

        } else {
            returnWithVerdict("username was taken");
        }

    }


    function sendResultInfoAsJson($obj) {
        header("Content-type: application/json");
        echo $obj;
    }

    function returnWithVerdict($verdict) {
        $retValue = '{"verdict":"' . $verdict . '"}';
        sendResultInfoAsJson($retValue);
    }
?>
