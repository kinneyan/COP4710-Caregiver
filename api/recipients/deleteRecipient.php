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

    $stmt = $conn->prepare("DELETE FROM recipients WHERE id = ?");
    $stmt->bind_param("i", $data['id']);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            sendSuccessResponse("Recipient deleted successfully.");
        } else {
            sendErrorResponse("No recipient found with the given ID.");
        }
    } else {
        sendErrorResponse("Failed to delete recipient: " . $stmt->error);
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
