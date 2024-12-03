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

    $stmt = $conn->prepare("SELECT id, contract_id, rating, notes, date_created FROM reviews WHERE user_id = ?");
    $stmt->bind_param("i", $data['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    sendSuccessResponse(["reviews" => $reviews]);

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
