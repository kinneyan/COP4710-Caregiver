<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $data = json_decode(file_get_contents('php://input'), true);

    // Connect to the database
    $conn = new mysqli("localhost", "caregiversapi", "api", "caregivers");

    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        // Check if user ID is provided
        if (!isset($data["id"])) {
            returnWithError("User ID is required.");
            exit();
        }

        // Prepare the query to fetch user details
        $stmt = $conn->prepare("SELECT id, username, email, fname, lname, address, phone, balance, available_hours, date_created, last_login_date FROM users WHERE id = ?");
        $stmt->bind_param("i", $data["id"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            returnWithUserInfo($row);
        } else {
            returnWithError("User not found.");
        }

        $stmt->close();
        $conn->close();
    }

    // Function to send the user data as JSON
    function returnWithUserInfo($row) {
        $retValue = json_encode(array(
            "id" => $row["id"],
            "username" => $row["username"],
            "email" => $row["email"],
            "firstName" => $row["fname"],
            "lastName" => $row["lname"],
            "address" => $row["address"],
            "phone" => $row["phone"],
            "balance" => $row["balance"],
            "available_hours" => $row["available_hours"],
            "date_created" => $row["date_created"],
            "last_login_date" => $row["last_login_date"],
            "error" => ""
        ));
        sendResultInfoAsJson($retValue);
    }

    // Function to handle errors
    function returnWithError($err) {
        $retValue = json_encode(array(
            "id" => 0,
            "username" => "",
            "email" => "",
            "firstName" => "",
            "lastName" => "",
            "address" => "",
            "phone" => "",
            "balance" => 0,
            "available_hours" => 0,
            "date_created" => "",
            "last_login_date" => "",
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
