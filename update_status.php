<?php
$conn = require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['applicant_id']) && isset($_POST['status'])) {
    $applicant_id = (int)$_POST['applicant_id'];
    $status = $_POST['status'];
    
    // Validate status
    $allowed_statuses = ['pending', 'approved', 'rejected'];
    if (!in_array($status, $allowed_statuses)) {
        die("Invalid status");
    }

    // Add the status column if it doesn't exist
    $addColumnQuery = "ALTER TABLE applicants 
                      ADD COLUMN IF NOT EXISTS `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                      ADD COLUMN IF NOT EXISTS `status_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    $conn->query($addColumnQuery);

    // Update the status
    $sql = "UPDATE applicants SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $applicant_id);

    if ($stmt->execute()) {
        header("Location: view_applicant.php?id=" . $applicant_id);
        exit;
    } else {
        die("Error updating status: " . $conn->error);
    }
} else {
    die("Invalid request");
}
?>
