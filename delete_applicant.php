<?php
$conn = require_once 'db_connect.php';

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = (int)$_POST['id'];

        $sql = "DELETE FROM applicants WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = "Applicant deleted successfully.";
                } else {
                    $response['message'] = "No applicant found with the provided ID.";
                }
            } else {
                $response['message'] = "Failed to execute the delete query.";
            }

            $stmt->close();
        } else {
            $response['message'] = "Failed to prepare the delete query.";
        }
    } else {
        $response['message'] = "Invalid or missing applicant ID.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);