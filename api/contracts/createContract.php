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

    if (empty($data['caregiver_id']) || empty($data['hiring_user_id']) || empty($data['recipient_id']) || empty($data['start_date']) || empty($data['end_date']) || empty($data['daily_hours']) || empty($data['rate'])) {
        sendErrorResponse("Missing required fields: caregiver_id, hiring_user_id, recipient_id, start_date, end_date, daily_hours, and rate are mandatory.");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO contracts (caregiver_id, hiring_user_id, recipient_id, start_date, end_date, daily_hours, rate, approved, date_created) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, false, NOW())");
    $stmt->bind_param("iiissdd", $data['caregiver_id'], $data['hiring_user_id'], $data['recipient_id'], $data['start_date'], $data['end_date'], $data['daily_hours'], $data['rate']);

    if ($stmt->execute()) {
        sendSuccessResponse("Contract created successfully.");
    } else {
        sendErrorResponse("Failed to create contract: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    function sendSuccessResponse($message) {
        header("Content-type: application/json");
        echo json_encode(["success" => true, "message" => $message]);
        exit();
    }

    function sendErrorResponse($error) {
        header("Content-type: application/json");
        echo json_encode(["success" => false, "error" => $error]);
        exit();
    }
?>
