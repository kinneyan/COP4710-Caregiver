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
        sendErrorResponse("Missing required field: review ID.");
        exit();
    }

    // Optional fields for update
    $rating = isset($data['rating']) ? $data['rating'] : null;
    $notes = isset($data['notes']) ? $data['notes'] : null;

    if ($rating === null && $notes === null) {
        sendErrorResponse("No fields provided for update.");
        exit();
    }

    $fields = [];
    $params = [];
    $types = "";

    if ($rating !== null) {
        $fields[] = "rating = ?";
        $params[] = $rating;
        $types .= "i";
    }
    if ($notes !== null) {
        $fields[] = "notes = ?";
        $params[] = $notes;
        $types .= "s";
    }

    $params[] = $data['id'];
    $types .= "i";

    $sql = "UPDATE reviews SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        sendErrorResponse("Failed to prepare statement: " . $conn->error);
        exit();
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            sendSuccessResponse("Review updated successfully.");
        } else {
            sendErrorResponse("No review found with the given ID or no changes made.");
        }
    } else {
        sendErrorResponse("Failed to update review: " . $stmt->error);
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
