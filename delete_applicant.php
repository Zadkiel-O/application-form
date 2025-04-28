<?php
session_start();
require_once 'config.php';
require_once 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid applicant ID.";
    header('Location: admin_dashboard.php');
    exit;
}

$applicant_ID = (int)$_GET['id'];
$conn->begin_transaction();

try {
    $stmt = $conn->prepare("SELECT picture FROM " . TABLE_APPLICANTS . " WHERE applicant_ID = ?");
    $stmt->bind_param("i", $applicant_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        throw new Exception("Applicant not found.");
    }
    $applicant = $result->fetch_assoc();
    $stmt->close();

    if ($applicant['picture'] && file_exists($applicant['picture'])) {
        unlink($applicant['picture']);
    }

    $stmt = $conn->prepare("SELECT file_path FROM " . TABLE_SUPPORTING_DOCUMENTS . " WHERE application_ID IN (SELECT application_ID FROM " . TABLE_APPLICATIONS . " WHERE applicant_ID = ?)");
    $stmt->bind_param("i", $applicant_ID);
    $stmt->execute();
    $documents = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($documents as $doc) {
        if ($doc['file_path'] && file_exists($doc['file_path'])) {
            unlink($doc['file_path']);
        }
    }

    $tables = [
        TABLE_APPLICANT_COURSES => 'applicant_ID',
        TABLE_SUPPORTING_DOCUMENTS => 'application_ID IN (SELECT application_ID FROM ' . TABLE_APPLICATIONS . ' WHERE applicant_ID = ?)',
        TABLE_APPLICATIONS => 'applicant_ID',
        TABLE_ACADEMIC_BACKGROUND => 'applicant_ID',
        TABLE_GUARDIAN_INFO => 'applicant_ID',
        TABLE_PERSONAL_INFO => 'applicant_ID',
        TABLE_APPLICANTS => 'applicant_ID'
    ];

    foreach ($tables as $table => $condition) {
        $sql = "DELETE FROM $table WHERE $condition = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $applicant_ID);
        $stmt->execute();
        $stmt->close();
    }

    $log_sql = "INSERT INTO " . TABLE_AUDIT_LOG . " (action, user_id, applicant_id, timestamp) VALUES (?, ?, ?, NOW())";
    $log_stmt = $conn->prepare($log_sql);
    $action = "Deleted applicant";
    $user_id = $_SESSION['admin_id'];
    $log_stmt->bind_param("sii", $action, $user_id, $applicant_ID);
    $log_stmt->execute();
    $log_stmt->close();

    $conn->commit();
    $_SESSION['success_message'] = "Applicant deleted successfully!";
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error deleting applicant ID $applicant_ID: " . $e->getMessage());
    $_SESSION['error_message'] = "Failed to delete applicant.";
}

header('Location: admin_dashboard.php');
exit;
?>