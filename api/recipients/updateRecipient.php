<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $data = json_decode(file_get_contents('php://input'), true);

    $conn = new mysqli("localhost", "caregiversapi", "api", "caregivers");
    
    if ($conn->connect_error) {
        sendErrorResponse("Connection failed: " . $conn->connect_error);
        exit();
    }

    if (empty($data['id'])) {
        sendErrorResponse("Missing required field: recipient ID.");
        exit();
    }

    $fields = [];
    $params = [];
    $types = "";

    if (isset($data['fname'])) {
        $fields[] = "fname = ?";
        $params[] = $data['fname'];
        $types .= "s";
    }
    if (isset($data['lname'])) {
        $fields[] = "lname = ?";
        $params[] = $data['lname'];
        $types .= "s";
    }
    if (isset($data['age'])) {
        $fields[] = "age = ?";
        $params[] = $data['age'];
        $types .= "i";
    }
    if (isset($data['address'])) { // Added address field
        $fields[] = "address = ?";
        $params[] = $data['address'];
        $types .= "s";
    }
    if (isset($data['notes'])) {
        $fields[] = "notes = ?";
        $params[] = $data['notes'];
        $types .= "s";
    }

    if (empty($fields)) {
        sendErrorResponse("No fields to update.");
        exit();
    }

    $params[] = $data['id'];
    $types .= "i";

    $sql = "UPDATE recipients SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            sendSuccessResponse("Recipient updated successfully.");
        } else {
            sendErrorResponse("No recipient found with the given ID or no changes made.");
        }
    } else {
        sendErrorResponse("Failed to update recipient: " . $stmt->error);
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
