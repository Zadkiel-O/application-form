<?php
$conn = require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    
    // Get the file paths before deleting the record
    $sql = "SELECT profile_photo, student_signature, guardian_signature FROM applicants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $files = $result->fetch_assoc();
    
    // Delete the record
    $sql = "DELETE FROM applicants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Delete associated files if they exist
        foreach ($files as $file) {
            if ($file) {
                $filepath = "uploads/" . $file;
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
            }
        }
        
        header("Location: admin_page.php?success=1");
        exit;
    } else {
        header("Location: admin_page.php?error=1");
        exit;
    }
} else {
    header("Location: admin_page.php");
    exit;
}
?>
