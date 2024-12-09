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

    if (empty($data['caregiver_id'])) {
        sendErrorResponse("Missing required field: caregiver ID.");
        exit();
    }

    $stmt = $conn->prepare("SELECT r.id, r.contract_id, r.user_id, r.rating, r.notes, r.date_created 
                            FROM reviews r
                            JOIN contracts c ON r.contract_id = c.id
                            WHERE c.caregiver_id = ?");
    $stmt->bind_param("i", $data['caregiver_id']);
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
