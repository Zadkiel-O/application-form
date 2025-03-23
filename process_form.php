<?php
$conn = require_once 'db_connect.php';

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function sanitize_input($data) {
        global $conn;
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $conn->real_escape_string($data);
    }
    
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $middle_name = sanitize_input($_POST['middle_name']);
    $extension_name = sanitize_input($_POST['extension_name']);
    $date_of_birth = sanitize_input($_POST['date_of_birth']);
    $place_of_birth = sanitize_input($_POST['place_of_birth']);
    $age = (int)sanitize_input($_POST['age']);
    $sex = sanitize_input($_POST['sex']);
    $blood_type = sanitize_input($_POST['blood_type']);
    $civil_status = sanitize_input($_POST['civil_status']);
    $religious_affiliation = sanitize_input($_POST['religious_affiliation']);
    $citizenship = sanitize_input($_POST['citizenship']);
    $no_of_siblings = (int)sanitize_input($_POST['no_of_siblings']);
    
    $house = sanitize_input($_POST['house']);
    $barangay = sanitize_input($_POST['barangay']);
    $city = sanitize_input($_POST['city']);
    $district = sanitize_input($_POST['district']);
    $zip_code = sanitize_input($_POST['zip_code']);
    $personal_number = sanitize_input($_POST['personal_number']);
    $personal_email = sanitize_input($_POST['personal_email']);
    $landline_number = sanitize_input($_POST['landline_number']);
    
    $guardian_first_name = sanitize_input($_POST['guardian_first_name']);
    $guardian_middle_name = sanitize_input($_POST['guardian_middle_name']);
    $guardian_last_name = sanitize_input($_POST['guardian_last_name']);
    $guardian_extension_name = sanitize_input($_POST['guardian_extension_name']);
    $guardian_age = (int)sanitize_input($_POST['guardian_age']);
    $guardian_sex = sanitize_input($_POST['guardian_sex']);
    $guardian_relationship = sanitize_input($_POST['guardian_relationship']);
    $guardian_address = sanitize_input($_POST['guardian_address']);
    $guardian_contact_number = sanitize_input($_POST['guardian_contact_number']);
    $guardian_email = sanitize_input($_POST['guardian_email']);
    
    $grade12_school = sanitize_input($_POST['grade12_school']);
    $grade12_period = sanitize_input($_POST['grade12_period']);
    $grade11_school = sanitize_input($_POST['grade11_school']);
    $grade11_period = sanitize_input($_POST['grade11_period']);
    $grade10_school = sanitize_input($_POST['grade10_school']);
    $grade10_period = sanitize_input($_POST['grade10_period']);
    $grade9_school = sanitize_input($_POST['grade9_school']);
    $grade9_period = sanitize_input($_POST['grade9_period']);
    $grade8_school = sanitize_input($_POST['grade8_school']);
    $grade8_period = sanitize_input($_POST['grade8_period']);
    $grade7_school = sanitize_input($_POST['grade7_school']);
    $grade7_period = sanitize_input($_POST['grade7_period']);
    
    $college_offered = sanitize_input($_POST['college_offered']);
    $course_offered = sanitize_input($_POST['course_offered']);
    
    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['photo']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . '.' . $filetype;
            $upload_dir = 'uploads/';
            
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if(move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                $photo = $upload_dir . $new_filename;
            }
        }
    }
    
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
    
    if($stmt) {
        $stmt->bind_param(
            "ssssssississsssssssssssssissssssssssssssss", 
            $first_name, $last_name, $middle_name, $extension_name, $date_of_birth, $place_of_birth, 
            $age, $sex, $blood_type, $civil_status, $religious_affiliation, $citizenship, $no_of_siblings, $photo,
            $house, $barangay, $city, $district, $zip_code, $personal_number, $personal_email, $landline_number,
            $guardian_first_name, $guardian_middle_name, $guardian_last_name, $guardian_extension_name, 
            $guardian_age, $guardian_sex, $guardian_relationship, $guardian_address, $guardian_contact_number, $guardian_email,
            $grade12_school, $grade12_period, $grade11_school, $grade11_period, $grade10_school, $grade10_period,
            $grade9_school, $grade9_period, $grade8_school, $grade8_period, $grade7_school, $grade7_period,
            $college_offered, $course_offered
        );
        
        
        if($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Application submitted successfully!";
            // $response['redirect'] = "success.php";
        } else {
            $response['message'] = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $response['message'] = "Error: " . $conn->error;
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
        }
    }
}
?>