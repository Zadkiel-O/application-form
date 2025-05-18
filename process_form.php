<?php
session_start(); // **VERY IMPORTANT: Start the session**

$response = [
    'success' => false,
    'message' => '',
    'redirect' => 'success.php'
];

// Function to handle file upload
function handleFileUpload($file, $uploadDir = 'uploads/') {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload failed', 'filename' => ''];
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed.', 'filename' => ''];
    }

    // Validate file size (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File size must be less than 2MB', 'filename' => ''];
    }

    // Create unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid() . '.' . $extension;
    $targetPath = $uploadDir . $uniqueName;

    // Create uploads directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'message' => 'File uploaded successfully', 'filename' => $uniqueName];
    }

    return ['success' => false, 'message' => 'Failed to move uploaded file', 'filename' => ''];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {    // Retrieve data from the session or POST data
    $formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : $_POST;
    
    // Get database connection
    $conn = require_once 'db_connect.php';
    
    if ($conn->connect_error) {
        $_SESSION['error_message'] = "Connection failed: " . $conn->connect_error;
        header("Location: admission_form.php");
        exit();
    }
    
    // Select the database again to ensure we're using it
    $conn->select_db("college_admissions");
      // Debug form data
    error_log("Form Data: " . print_r($formData, true));

    // Count our parameters to ensure they match
    $fields = [
        'first_name', 'last_name', 'middle_name', 'extension_name', 'date_of_birth', 'place_of_birth',
        'age', 'sex', 'blood_type', 'civil_status', 'religious_affiliation', 'citizenship', 'no_of_siblings',
        'house', 'barangay', 'city', 'district', 'zip_code', 'personal_number', 'personal_email', 'landline_number',
        'guardian_first_name', 'guardian_middle_name', 'guardian_last_name', 'guardian_extension_name',
        'guardian_age', 'guardian_sex', 'guardian_relationship', 'guardian_address', 'guardian_contact_number',
        'guardian_email', 'grade12_school', 'grade12_period', 'grade12_gwa', 'grade11_school', 'grade11_period',
        'grade11_gwa', 'grade10_school', 'grade10_period', 'grade9_school', 'grade9_period', 'grade8_school',
        'grade8_period', 'grade7_school', 'grade7_period', 'college_offered', 'course_offered', 'course_1',
        'course_2', 'profile_photo', 'student_signature', 'guardian_signature'
    ];
    
    // Create the SQL query dynamically
    $columns = implode(", ", $fields);
    $placeholders = str_repeat("?,", count($fields) - 1) . "?";
    $sql = "INSERT INTO applicants ($columns) VALUES ($placeholders)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // Create references for bind_param
        $types = str_repeat("s", count($fields)); // Default all to string
        $params = array($types);
        
        // Create references for each parameter
        foreach ($fields as $field) {
            if ($field === 'age' || $field === 'guardian_age' || $field === 'no_of_siblings') {
                $types[count($params)-1] = 'i'; // Integer
            } elseif ($field === 'grade11_gwa' || $field === 'grade12_gwa') {
                $types[count($params)-1] = 'd'; // Double
            }
            
            // Get the value, using photo if the field is profile_photo
            $value = ($field === 'profile_photo') ? ($formData['photo'] ?? '') : ($formData[$field] ?? '');
            $params[] = &$value;
        }
        
        // Bind parameters using call_user_func_array
        call_user_func_array(array($stmt, 'bind_param'), $params);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Application submitted successfully!";
            $stmt->close();
            $conn->close();
            header("Location: success.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error submitting application: " . $stmt->error;
            $stmt->close();
            $conn->close();
            header("Location: admission_form.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error preparing statement: " . $conn->error;
        $conn->close();
        header("Location: admission_form.php");
        exit();
    }
} else {
    header("Location: admission_form.php");
    exit();
}