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

    if (empty($data['id'])) {
        sendErrorResponse("Missing required field: recipient ID.");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, user_id, fname, lname, age, address, notes FROM recipients WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        sendSuccessResponse($row);
    } else {
        sendErrorResponse("No recipient found with the given ID.");
    }

    $stmt->close();
    $conn->close();

    function sendSuccessResponse($data) {
        echo json_encode(["success" => true, "data" => $data]);
        exit();
    }

    function sendErrorResponse($error) {
        echo json_encode(["success" => false, "error" => $error]);
        exit();
    }
?>
