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
        sendErrorResponse("Missing required field: contract ID.");
        exit();
    }

    $fields = [];
    $params = [];
    $types = "";

    if (isset($data['start_date'])) {
        $fields[] = "start_date = ?";
        $params[] = $data['start_date'];
        $types .= "s";
    }
    if (isset($data['end_date'])) {
        $fields[] = "end_date = ?";
        $params[] = $data['end_date'];
        $types .= "s";
    }
    if (isset($data['daily_hours'])) {
        $fields[] = "daily_hours = ?";
        $params[] = $data['daily_hours'];
        $types .= "d";
    }
    if (isset($data['rate'])) {
        $fields[] = "rate = ?";
        $params[] = $data['rate'];
        $types .= "d";
    }
    if (isset($data['approved'])) {
        $fields[] = "approved = ?";
        $params[] = $data['approved'];
        $types .= "i";
    }

    if (empty($fields)) {
        sendErrorResponse("No fields to update.");
        exit();
    }

    $params[] = $data['id'];
    $types .= "i";

    $sql = "UPDATE contracts SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            sendSuccessResponse("Contract updated successfully.");
        } else {
            sendErrorResponse("No contract found with the given ID or no changes made.");
        }
    } else {
        sendErrorResponse("Failed to update contract: " . $stmt->error);
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
