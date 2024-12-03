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

    if (empty($data['user_id'])) {
        sendErrorResponse("Missing required field: user ID.");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, fname, lname, age, notes FROM recipients WHERE user_id = ?");
    $stmt->bind_param("i", $data['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $recipients = [];
    while ($row = $result->fetch_assoc()) {
        $recipients[] = $row;
    }

    sendSuccessResponse(["recipients" => $recipients]);

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
