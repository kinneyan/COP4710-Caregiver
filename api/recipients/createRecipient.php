<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $data = json_decode(file_get_contents('php://input'), true);

    $conn = new mysqli("localhost", "kinneyan", "test", "caregivers");

    if ($conn->connect_error) {
        sendErrorResponse("Connection failed: " . $conn->connect_error);
        exit();
    }

    if (empty($data['user_id']) || empty($data['fname']) || empty($data['lname']) || empty($data['age']) || empty($data['address'])) {
        sendErrorResponse("Missing required fields: user_id, fname, lname, age, and address are mandatory.");
        exit();
    }

    $notes = isset($data['notes']) ? $data['notes'] : null;

    $stmt = $conn->prepare("INSERT INTO recipients (user_id, fname, lname, age, address, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $data['user_id'], $data['fname'], $data['lname'], $data['age'], $data['address'], $notes);

    if ($stmt->execute()) {
        sendSuccessResponse("Recipient created successfully.");
    } else {
        sendErrorResponse("Failed to create recipient: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    function sendSuccessResponse($message) {
        echo json_encode(["success" => true, "message" => $message]);
        exit();
    }

    function sendErrorResponse($error) {
        echo json_encode(["success" => false, "error" => $error]);
        exit();
    }
?>
