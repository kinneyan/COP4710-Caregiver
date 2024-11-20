<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $data = json_decode(file_get_contents('php://input'), true);

    // $conn = new mysqli("localhost", "kinneyan", "test", "caregivers");
    $conn = new mysqli("localhost", "root", "AlpBet2002", "caregivers");

    if ($conn->connect_error) {
        returnWithVerdict("Connection Error: " . $conn->connect_error);
        exit();
    }

    // Validate required fields
    if (empty($data['username']) || empty($data['password']) || empty($data['email']) || empty($data['firstName']) || empty($data['lastName'])) {
        returnWithVerdict("Missing required fields: username, password, email, firstName, lastName are mandatory.");
        exit();
    }

    // Ensure optional fields are set to default values if not provided
    $address = isset($data['address']) ? $data['address'] : null;
    $phone = isset($data['phone']) ? $data['phone'] : null;
    $available_hours = isset($data['available_hours']) ? $data['available_hours'] : 0;

    // Check if the username is unique
    $stmt = $conn->prepare("SELECT username FROM users WHERE username=?");
    $stmt->bind_param("s", $data["username"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        returnWithVerdict("Username already taken.");
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();

    // Insert the new user into the database
    $stmt2 = $conn->prepare("INSERT INTO users (username, password, email, fname, lname, address, phone, balance, available_hours) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 2000, ?)");
    $stmt2->bind_param(
        "ssssssdi",
        $data['username'],
        $data['password'], // NOTE: Consider hashing the password for security
        $data['email'],
        $data['firstName'],
        $data['lastName'],
        $address,
        $phone,
        $available_hours
    );

    $res = $stmt2->execute();

    if ($res) {
        returnWithVerdict("New user created successfully.");
    } else {
        returnWithVerdict("Database Error: " . $stmt2->error);
    }

    $stmt2->close();
    $conn->close();

    // Helper functions
    function sendResultInfoAsJson($obj) {
        header("Content-type: application/json");
        echo $obj;
    }

    function returnWithVerdict($verdict) {
        $retValue = json_encode(["verdict" => $verdict]);
        sendResultInfoAsJson($retValue);
    }
?>
