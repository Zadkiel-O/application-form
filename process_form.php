<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Merge session form data with POST data, preferring POST data
    $formData = array_merge(
        isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [],
        $_POST
    );
    
    // Get database connection
    $conn = require_once 'db_connect.php';
    
    if ($conn->connect_error) {
        $_SESSION['error_message'] = "Connection failed: " . $conn->connect_error;
        header("Location: admission_form.php");
        exit();
    }
    
    // Select the database
    $conn->select_db("college_admissions");
    
    // Debug log
    error_log("Form Data to be processed: " . print_r($formData, true));
    
    // Fields to be inserted
    $fields = [
        'first_name', 'last_name', 'middle_name', 'extension_name', 'date_of_birth', 'place_of_birth',
        'age', 'sex', 'blood_type', 'civil_status', 'religious_affiliation', 'citizenship', 'no_of_siblings',
        'house', 'barangay', 'city', 'district', 'zip_code', 'personal_number', 'personal_email', 'landline_number',
        'guardian_first_name', 'guardian_middle_name', 'guardian_last_name', 'guardian_extension_name',
        'guardian_age', 'guardian_sex', 'guardian_relationship', 'guardian_address', 'guardian_contact_number',
        'guardian_email', 'grade12_school', 'grade12_period', 'grade12_gwa', 'grade11_school', 'grade11_period',        'grade11_gwa', 'grade10_school', 'grade10_period', 'grade9_school', 'grade9_period', 'grade8_school',
        'grade8_period', 'grade7_school', 'grade7_period', 'course_1', 'course_2', 'profile_photo', 'student_signature',
        'guardian_signature'
    ];

    // Build SQL query
    $columns = implode(", ", $fields);
    $placeholders = str_repeat("?,", count($fields) - 1) . "?";
    $sql = "INSERT INTO applicants ($columns) VALUES ($placeholders)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // Create parameter types string and values array
        $types = '';
        $values = [];
        
        foreach ($fields as $field) {
            // Determine the type
            if (in_array($field, ['age', 'guardian_age', 'no_of_siblings'])) {
                $types .= 'i'; // integer
            } elseif (in_array($field, ['grade11_gwa', 'grade12_gwa'])) {
                $types .= 'd'; // double
            } else {
                $types .= 's'; // string
            }
            
            // Get the value
            $value = isset($formData[$field]) ? $formData[$field] : '';
            
            // Convert empty strings to appropriate value for numeric fields
            if (in_array($field, ['age', 'guardian_age', 'no_of_siblings']) && $value === '') {
                $value = null;
            }
            if (in_array($field, ['grade11_gwa', 'grade12_gwa']) && $value === '') {
                $value = null;
            }
            
            $values[] = $value;
        }
        
        // Bind parameters
        $stmt->bind_param($types, ...$values);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Clear the session data after successful insertion
            unset($_SESSION['form_data']);
            
            $_SESSION['success_message'] = "Application submitted successfully!";
            $stmt->close();
            $conn->close();
            header("Location: success.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error submitting application: " . $stmt->error;
            error_log("Database Error: " . $stmt->error);
            $stmt->close();
            $conn->close();
            header("Location: admission_form.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error preparing statement: " . $conn->error;
        error_log("Database Error: " . $conn->error);
        $conn->close();
        header("Location: admission_form.php");
        exit();
    }
} else {
    header("Location: admission_form.php");
    exit();
}
?>