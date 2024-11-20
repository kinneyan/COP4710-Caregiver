<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $data = json_decode(file_get_contents('php://input'), true);

    // Connect to the database
    $conn = new mysqli("localhost", "kinneyan", "test", "caregivers");
    // $conn = new mysqli("localhost", "root", "AlpBet2002", "caregivers");

    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        // Check if user ID is provided
        if (!isset($data["id"])) {
            returnWithError("User ID is required.");
            exit();
        }

        // Build the query dynamically based on provided fields
        $fields = [];
        $params = [];
        $types = "";

        if (isset($data["username"])) {
            $fields[] = "username=?";
            $params[] = $data["username"];
            $types .= "s";
        }
        if (isset($data["password"])) {
            $fields[] = "password=?";
            $params[] = $data["password"];
            $types .= "s";
        }
        if (isset($data["email"])) {
            $fields[] = "email=?";
            $params[] = $data["email"];
            $types .= "s";
        }
        if (isset($data["fname"])) {
            $fields[] = "fname=?";
            $params[] = $data["fname"];
            $types .= "s";
        }
        if (isset($data["lname"])) {
            $fields[] = "lname=?";
            $params[] = $data["lname"];
            $types .= "s";
        }
        if (isset($data["address"])) {
            $fields[] = "address=?";
            $params[] = $data["address"];
            $types .= "s";
        }
        if (isset($data["phone"])) {
            $fields[] = "phone=?";
            $params[] = $data["phone"];
            $types .= "s";
        }
        if (isset($data["available_hours"])) {
            $fields[] = "available_hours=?";
            $params[] = $data["available_hours"];
            $types .= "d";
        }

        // Ensure there are fields to update
        if (empty($fields)) {
            returnWithError("No fields to update.");
            exit();
        }

        // Add the user ID for the WHERE clause
        $params[] = $data["id"];
        $types .= "i";

        // Construct the SQL query
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id=?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            returnWithError("Failed to prepare statement: " . $conn->error);
            exit();
        }

        // Bind the parameters dynamically
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                returnWithSuccess("User updated successfully.");
            } else {
                returnWithError("No changes made or user not found.");
            }
        } else {
            returnWithError("Failed to execute query: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
    }

    // Function to handle successful responses
    function returnWithSuccess($message) {
        $retValue = json_encode(array(
            "success" => true,
            "message" => $message
        ));
        sendResultInfoAsJson($retValue);
    }

    // Function to handle errors
    function returnWithError($err) {
        $retValue = json_encode(array(
            "success" => false,
            "error" => $err
        ));
        sendResultInfoAsJson($retValue);
    }

    // Function to send JSON response
    function sendResultInfoAsJson($obj) {
        header("Content-type: application/json");
        echo $obj;
    }
?>
