<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $id = 0;
    $firstName = "";
    $lastName = "";

    // Decode incoming JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if required fields are present
    if (empty($data['username']) || empty($data['password'])) {
        returnWithError("Missing username or password.");
        exit();
    }

    // Database connection
    $conn = new mysqli("localhost", "kinneyan", "test", "caregivers");
    // $conn = new mysqli("localhost", "root", "AlpBet2002", "caregivers");

    if ($conn->connect_error) {
        returnWithError("Connection Error: " . $conn->connect_error);
        exit();
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, fname, lname FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $data["username"], $data["password"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // User found
        returnWithInfo($row['fname'], $row['lname'], $row['id']);
    } else {
        // No matching record
        returnWithError("No Records Found");
    }

    $stmt->close();
    $conn->close();

    // Helper functions
    function sendResultInfoAsJson($obj) {
        header("Content-type: application/json");
        echo $obj;
    }

    function returnWithError($err) {
        $retValue = json_encode([
            "id" => 0,
            "firstName" => "",
            "lastName" => "",
            "error" => $err
        ]);
        sendResultInfoAsJson($retValue);
    }

    function returnWithInfo($firstName, $lastName, $id) {
        $retValue = json_encode([
            "id" => $id,
            "firstName" => $firstName,
            "lastName" => $lastName,
            "error" => ""
        ]);
        sendResultInfoAsJson($retValue);
    }
?>
