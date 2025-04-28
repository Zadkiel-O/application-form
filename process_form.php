<?php
session_start(); // **VERY IMPORTANT: Start the session**
$conn = require_once 'db_connect.php';

$response = [
    'success' => false,
    'message' => '',
    'redirect' => 'success.php'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the session
    if (isset($_SESSION['form_data'])) {
        $_POST = $_SESSION['form_data'];  // Use session data
        unset($_SESSION['form_data']);    // Clear session data after use
    } else {
        $errors[] = "No form data found in session.";
    }
    
    // Validation function
    function validate_input($conn, $data, $field_name, $required = true) {
        if ($required && empty($data)) {
            return ["success" => false, "message" => "Field '$field_name' is required.", "value" => ""];
        }
        
        // Sanitize data
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $sanitized = $conn->real_escape_string($data);
        return ["success" => true, "message" => "", "value" => $sanitized];
    }
    
    // Validation errors array
    $errors = [];
    
    // Validate personal information
    $first_name_result = validate_input($conn, $_POST['first_name'] ?? '', 'First Name');
    if (!$first_name_result["success"]) {
        $errors[] = $first_name_result["message"];
    }
    $first_name = $first_name_result["value"];

    $last_name_result = validate_input($conn, $_POST['last_name'] ?? '', 'Last Name');
    if (!$last_name_result["success"]) {
        $errors[] = $last_name_result["message"];
    }
    $last_name = $last_name_result["value"];
    
    $middle_name_result = validate_input($conn, $_POST['middle_name'] ?? '', 'Middle Name', false);
    $middle_name = $middle_name_result["value"];
    
    $extension_name_result = validate_input($conn, $_POST['extension_name'] ?? '', 'Extension Name', false);
    $extension_name = $extension_name_result["value"];
    
    $date_of_birth_result = validate_input($conn, $_POST['date_of_birth'] ?? '', 'Date of Birth');
    if (!$date_of_birth_result["success"]) {
        $errors[] = $date_of_birth_result["message"];
    }
    $date_of_birth = $date_of_birth_result["value"];
    
    $place_of_birth_result = validate_input($conn, $_POST['place_of_birth'] ?? '', 'Place of Birth');
    if (!$place_of_birth_result["success"]) {
        $errors[] = $place_of_birth_result["message"];
    }
    $place_of_birth = $place_of_birth_result["value"];
    
    $age = filter_var($_POST['age'] ?? '', FILTER_VALIDATE_INT);
    if ($age === false || $age < 1) {
        $errors[] = "Age must be a valid number.";
    }
    
    $sex_result = validate_input($conn, $_POST['sex'] ?? '', 'Sex');
    if (!$sex_result["success"]) {
        $errors[] = $sex_result["message"];
    }
    $sex = $sex_result["value"];
    
    $blood_type_result = validate_input($conn, $_POST['blood_type'] ?? '', 'Blood Type', false);
    $blood_type = $blood_type_result["value"];
    
    $civil_status_result = validate_input($conn, $_POST['civil_status'] ?? '', 'Civil Status');
    if (!$civil_status_result["success"]) {
        $errors[] = $civil_status_result["message"];
    }
    $civil_status = $civil_status_result["value"];
    
    $religious_affiliation_result = validate_input($conn, $_POST['religious_affiliation'] ?? '', 'Religious Affiliation', false);
    $religious_affiliation = $religious_affiliation_result["value"];
    
    $citizenship_result = validate_input($conn, $_POST['citizenship'] ?? '', 'Citizenship');
    if (!$citizenship_result["success"]) {
        $errors[] = $citizenship_result["message"];
    }
    $citizenship = $citizenship_result["value"];
    
    $no_of_siblings = filter_var($_POST['no_of_siblings'] ?? '', FILTER_VALIDATE_INT);
    if ($no_of_siblings === false) {
        $no_of_siblings = 0;
    }
    
    // Validate address information
    $house_result = validate_input($conn, $_POST['house'] ?? '', 'House/Address');
    if (!$house_result["success"]) {
        $errors[] = $house_result["message"];
    }
    $house = $house_result["value"];
    
    $barangay_result = validate_input($conn, $_POST['barangay'] ?? '', 'Barangay');
    if (!$barangay_result["success"]) {
        $errors[] = $barangay_result["message"];
    }
    $barangay = $barangay_result["value"];
    
    $city_result = validate_input($conn, $_POST['city'] ?? '', 'City/Municipality');
    if (!$city_result["success"]) {
        $errors[] = $city_result["message"];
    }
    $city = $city_result["value"];
    
    $district_result = validate_input($conn, $_POST['district'] ?? '', 'District', false);
    $district = $district_result["value"];
    
    $zip_code_result = validate_input($conn, $_POST['zip_code'] ?? '', 'Zip Code', false);
    $zip_code = $zip_code_result["value"];
    
    $personal_number_result = validate_input($conn, $_POST['personal_number'] ?? '', 'Personal Number');
    if (!$personal_number_result["success"]) {
        $errors[] = $personal_number_result["message"];
    }
    $personal_number = $personal_number_result["value"];
    
    $personal_email_result = validate_input($conn, $_POST['personal_email'] ?? '', 'Personal Email');
    if (!$personal_email_result["success"]) {
        $errors[] = $personal_email_result["message"];
    }
    $personal_email = $personal_email_result["value"];
    
    // Validate email format
    if (!filter_var($personal_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format for Personal Email.";
    }
    
    $landline_number_result = validate_input($conn, $_POST['landline_number'] ?? '', 'Landline Number', false);
    $landline_number = $landline_number_result["value"];
    
    // Validate guardian information
    $guardian_first_name_result = validate_input($conn, $_POST['guardian_first_name'] ?? '', 'Guardian First Name');
    if (!$guardian_first_name_result["success"]) {
        $errors[] = $guardian_first_name_result["message"];
    }
    $guardian_first_name = $guardian_first_name_result["value"];
    
    $guardian_middle_name_result = validate_input($conn, $_POST['guardian_middle_name'] ?? '', 'Guardian Middle Name', false);
    $guardian_middle_name = $guardian_middle_name_result["value"];
    
    $guardian_last_name_result = validate_input($conn, $_POST['guardian_last_name'] ?? '', 'Guardian Last Name');
    if (!$guardian_last_name_result["success"]) {
        $errors[] = $guardian_last_name_result["message"];
    }
    $guardian_last_name = $guardian_last_name_result["value"];
    
    $guardian_extension_name_result = validate_input($conn, $_POST['guardian_extension_name'] ?? '', 'Guardian Extension Name', false);
    $guardian_extension_name = $guardian_extension_name_result["value"];
    
    $guardian_age = filter_var($_POST['guardian_age'] ?? '', FILTER_VALIDATE_INT);
    if ($guardian_age === false || $guardian_age < 1) {
        $errors[] = "Guardian Age must be a valid number.";
    }
    
    $guardian_sex_result = validate_input($conn, $_POST['guardian_sex'] ?? '', 'Guardian Sex');
    if (!$guardian_sex_result["success"]) {
        $errors[] = $guardian_sex_result["message"];
    }
    $guardian_sex = $guardian_sex_result["value"];
    
    $guardian_relationship_result = validate_input($conn, $_POST['guardian_relationship'] ?? '', 'Guardian Relationship');
    if (!$guardian_relationship_result["success"]) {
        $errors[] = $guardian_relationship_result["message"];
    }
    $guardian_relationship = $guardian_relationship_result["value"];
    
    $guardian_address_result = validate_input($conn, $_POST['guardian_address'] ?? '', 'Guardian Address');
    if (!$guardian_address_result["success"]) {
        $errors[] = $guardian_address_result["message"];
    }
    $guardian_address = $guardian_address_result["value"];
    
    $guardian_contact_number_result = validate_input($conn, $_POST['guardian_contact_number'] ?? '', 'Guardian Contact Number');
    if (!$guardian_contact_number_result["success"]) {
        $errors[] = $guardian_contact_number_result["message"];
    }
    $guardian_contact_number = $guardian_contact_number_result["value"];
    
    $guardian_email_result = validate_input($conn, $_POST['guardian_email'] ?? '', 'Guardian Email', false);
    $guardian_email = $guardian_email_result["value"];
    
    // Validate guardian email format if provided
    if (!empty($guardian_email) && !filter_var($guardian_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format for Guardian Email.";
    }
    
    // Validate educational information
    $grade12_school_result = validate_input($conn, $_POST['grade12_school'] ?? '', 'Grade 12 School', false);
    $grade12_school = $grade12_school_result["value"];
    
    $grade12_period_result = validate_input($conn, $_POST['grade12_period'] ?? '', 'Grade 12 Period', false);
    $grade12_period = $grade12_period_result["value"];
    
    $grade11_school_result = validate_input($conn, $_POST['grade11_school'] ?? '', 'Grade 11 School', false);
    $grade11_school = $grade11_school_result["value"];
    
    $grade11_period_result = validate_input($conn, $_POST['grade11_period'] ?? '', 'Grade 11 Period', false);
    $grade11_period = $grade11_period_result["value"];
    
    $grade10_school_result = validate_input($conn, $_POST['grade10_school'] ?? '', 'Grade 10 School', false);
    $grade10_school = $grade10_school_result["value"];
    
    $grade10_period_result = validate_input($conn, $_POST['grade10_period'] ?? '', 'Grade 10 Period', false);
    $grade10_period = $grade10_period_result["value"];
    
    $grade9_school_result = validate_input($conn, $_POST['grade9_school'] ?? '', 'Grade 9 School', false);
    $grade9_school = $grade9_school_result["value"];
    
    $grade9_period_result = validate_input($conn, $_POST['grade9_period'] ?? '', 'Grade 9 Period', false);
    $grade9_period = $grade9_period_result["value"];
    
    $grade8_school_result = validate_input($conn, $_POST['grade8_school'] ?? '', 'Grade 8 School', false);
    $grade8_school = $grade8_school_result["value"];
    
    $grade8_period_result = validate_input($conn, $_POST['grade8_period'] ?? '', 'Grade 8 Period', false);
    $grade8_period = $grade8_period_result["value"];
    
    $grade7_school_result = validate_input($conn, $_POST['grade7_school'] ?? '', 'Grade 7 School', false);
    $grade7_school = $grade7_school_result["value"];
    
    $grade7_period_result = validate_input($conn, $_POST['grade7_period'] ?? '', 'Grade 7 Period', false);
    $grade7_period = $grade7_period_result["value"];
    
    // Validate course information
    $college_offered_result = validate_input($conn, $_POST['college_offered'] ?? '', 'College');
    if (!$college_offered_result["success"]) {
        $errors[] = $college_offered_result["message"];
    }
    $college_offered = $college_offered_result["value"];
    
    $course_offered_result = validate_input($conn, $_POST['course_offered'] ?? '', 'Course');
    if (!$course_offered_result["success"]) {
        $errors[] = $course_offered_result["message"];
    }
    $course_offered = $course_offered_result["value"];
    
    // Process photo upload
    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['photo']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . '.' . $filetype;
            $upload_dir = 'uploads/';
            
            // Create upload directory with proper permissions if it doesn't exist
            if(!is_dir($upload_dir)) {
                if(!mkdir($upload_dir, 0755, true)) {
                    $errors[] = "Failed to create upload directory.";
                }
            } elseif(!is_writable($upload_dir)) {
                chmod($upload_dir, 0755);
                if(!is_writable($upload_dir)) {
                    $errors[] = "Upload directory is not writable.";
                }
            }
            
            if(empty($errors) && move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                $photo = $upload_dir . $new_filename;
            } else {
                $errors[] = "Failed to upload photo.";
            }
        } else {
            $errors[] = "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
        }
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        $response['message'] = "Validation errors: " . implode(", ", $errors);
    } else {
        // Insert data into database
        $sql = "INSERT INTO applicants (
            first_name, last_name, middle_name, extension_name, date_of_birth, place_of_birth, 
            age, sex, blood_type, civil_status, religious_affiliation, citizenship, no_of_siblings, photo,
            house, barangay, city, district, zip_code, personal_number, personal_email, landline_number,
            guardian_first_name, guardian_middle_name, guardian_last_name, guardian_extension_name, 
            guardian_age, guardian_sex, guardian_relationship, guardian_address, guardian_contact_number, guardian_email,
            grade12_school, grade12_period, grade11_school, grade11_period, grade10_school, grade10_period,
            grade9_school, grade9_period, grade8_school, grade8_period, grade7_school, grade7_period,
            college_offered, course_offered
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param(
                "ssssssiiiissssssssssssssssssssssssssssssssssss", 
                $first_name, $last_name, $middle_name, $extension_name, $date_of_birth, $place_of_birth,
                $age, $sex, $blood_type, $civil_status, $religious_affiliation, $citizenship, $no_of_siblings, $photo,
                $house, $barangay, $city, $district, $zip_code, $personal_number, $personal_email, $landline_number,
                $guardian_first_name, $guardian_middle_name, $guardian_last_name, $guardian_extension_name,
                $guardian_age, $guardian_sex, $guardian_relationship, $guardian_address, $guardian_contact_number, $guardian_email,
                $grade12_school, $grade12_period, $grade11_school, $grade11_period, $grade10_school, $grade10_period,
                $grade9_school, $grade9_period, $grade8_school, $grade8_period, $grade7_school, $grade7_period,
                $college_offered, $course_offered
            );
        
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Application submitted successfully!";
            } else {
                $response['message'] = "Execution Error: " . $stmt->error;
            }
        
            $stmt->close();
        } else {
            $response['message'] = "Preparation Error: " . $conn->error;
        }
    }
    
    $conn->close();
    
    // Return JSON response for AJAX or redirect for form submit
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        if($response['success']) {
            header("Location: " . $response['redirect']);
            exit;
        } else {
            // Store error message in session to display it after redirect
            session_start();
            $_SESSION['form_error'] = $response['message'];
            header("Location: admission_form.php");
            exit;
        }
    }
}