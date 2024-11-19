<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $id = 0;
    $firstName = "";
    $lastName = "";

    $data = json_decode(file_get_contents('php://input'), true);

    $conn = new mysqli("localhost", "kinneyan", "test", "caregivers");

    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        $stmt = $conn->prepare("SELECT id, fname, lname FROM users WHERE username=? AND password=?");
        $stmt->bind_param("ss", $data["username"], $data["password"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $flag = 0;

        while ($row = $result->fetch_assoc()) {
            if (
                strcmp($row['username'], $data['username']) == 0 && 
                strcmp($row['password'], $data['password']) == 0
            ) {
                returnWithInfo($row['fname'], $row['lname'], $row['id']);
                $flag = 1;
                break;
            }
        }

        if (!$flag) {
            returnWithError("No Records Found");
        }

        $stmt->close();
        $conn->close();
    }

    function sendResultInfoAsJson($obj) {
        header("Content-type: application/json");
        echo $obj;
    }

    function returnWithError($err) {
        $retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    function returnWithInfo($firstName, $lastName, $id) {
        $retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
        sendResultInfoAsJson($retValue);
    }
?>
