<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    // Decode the incoming JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    // Database connection
    $conn = new mysqli("localhost", "caregiversapi", "api", "caregivers");
    
    if ($conn->connect_error) {
        sendErrorResponse("Connection failed: " . $conn->connect_error);
        exit();
    }

    // Validate required fields
    if (empty($data['contract_id']) || empty($data['user_id']) || empty($data['rating'])) {
        sendErrorResponse("Missing required fields: contract_id, user_id, and rating are mandatory.");
        exit();
    }

    // Validate rating range (1 to 5)
    if ($data['rating'] < 1 || $data['rating'] > 5) {
        sendErrorResponse("Invalid rating. Rating must be between 1 and 5.");
        exit();
    }

    // Optional notes field
    $notes = isset($data['notes']) ? $data['notes'] : null;

    // Check if the contract exists
    $stmt = $conn->prepare("SELECT id FROM contracts WHERE id = ? AND hiring_user_id = ?");
    $stmt->bind_param("ii", $data['contract_id'], $data['user_id']);
    $stmt->execute();
    $contractResult = $stmt->get_result();

    if ($contractResult->num_rows === 0) {
        sendErrorResponse("No valid contract found for the given contract_id and user_id.");
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Insert the review into the reviews table
    $stmt = $conn->prepare("INSERT INTO reviews (contract_id, user_id, rating, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $data['contract_id'], $data['user_id'], $data['rating'], $notes);
    if ($stmt->execute()) {
        sendSuccessResponse("Review submitted successfully.");
    } else {
        sendErrorResponse("Failed to submit review: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    // Helper functions
    function sendSuccessResponse($message) {
        echo json_encode([
            "success" => true,
            "message" => $message
        ]);
        exit();
    }

    function sendErrorResponse($error) {
        echo json_encode([
            "success" => false,
            "error" => $error
        ]);
        exit();
    }
?>
