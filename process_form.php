<?php
session_start();
require_once 'config.php';
require_once 'db_connect.php';

$errors = [];

// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     $errors[] = "Invalid CSRF token.";
//     $_SESSION['errors'] = $errors;
    // header("Location: admission_form.php");
//     exit;
// }

// $required_fields = [
//     'first_name', 'last_name', 'birthday', 'country_of_birth', 'age', 'sex',
//     'civil_status', 'citizenship', 'email_address', 'contact_number',
//     'address', 'city', 'region', 'zip_code',
//     'guardian_first_name', 'guardian_last_name', 'guardian_relationship',
//     'guardian_contact_number', 'grade12_school_name', 'grade12_academic_year',
//     'grade12_GWA', 'grade11_school_name', 'grade11_academic_year', 'grade11_GWA',
//     'first_course'
// ];

// foreach ($required_fields as $field) {
//     if (empty($_POST[$field])) {
//         $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
//     }
// }

// if (!filter_var($_POST['email_address'], FILTER_VALIDATE_EMAIL)) {
//     $errors[] = "Invalid email address.";
// }
// if (!empty($_POST['guardian_email_address']) && !filter_var($_POST['guardian_email_address'], FILTER_VALIDATE_EMAIL)) {
//     $errors[] = "Invalid guardian email address.";
// }

// if (!preg_match('/^\+?\d{10,15}$/', $_POST['contact_number'])) {
//     $errors[] = "Contact number must be 10-15 digits.";
// }
// if (!empty($_POST['guardian_contact_number']) && !preg_match('/^\+?\d{10,15}$/', $_POST['guardian_contact_number'])) {
//     $errors[] = "Guardian contact number must be 10-15 digits.";
// }

// if (!empty($_POST['first_course']) && !empty($_POST['second_course']) && $_POST['first_course'] === $_POST['second_course']) {
//     $errors[] = "First and second course choices must be different.";
// }

// $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
// $max_size = 2 * 1024 * 1024; // 2MB
// $upload_dir = UPLOAD_DIR;
// $files = ['picture', 'document_transcript', 'document_birth', 'document_id'];

// foreach ($files as $file) {
//     if (!empty($_FILES[$file]['name'])) {
//         if ($_FILES[$file]['size'] > $max_size) {
//             $errors[] = ucfirst(str_replace('_', ' ', $file)) . " exceeds 2MB limit.";
//         }
//         if (!in_array($_FILES[$file]['type'], $allowed_types)) {
//             $errors[] = ucfirst(str_replace('_', ' ', $file)) . " has an invalid type. Only JPG, PNG, GIF, and PDF are allowed.";
//         }
//     }
// }

// if (!empty($errors)) {
//     $_SESSION['errors'] = $errors;
//     header("Location: index.php");
//     exit;
// }

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("
        INSERT INTO " . TABLE_APPLICANTS . " (
            first_name, last_name, middle_name, extension_name, birthday,
            country_of_birth, age, sex, blood_type, civil_status,
            religious_affiliation, citizenship, number_of_siblings,
            email_address, contact_number
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssssisssssiss",
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['middle_name'],
        $_POST['extension_name'],
        $_POST['birthday'],
        $_POST['country_of_birth'],
        $_POST['age'],
        $_POST['sex'],
        $_POST['blood_type'],
        $_POST['civil_status'],
        $_POST['religious_affiliation'],
        $_POST['citizenship'],
        $_POST['number_of_siblings'],
        $_POST['email_address'],
        $_POST['contact_number']
    );
    $stmt->execute();
    $applicant_id = $conn->insert_id;
    $stmt->close();

    $picture_path = null;
    if (!empty($_FILES['picture']['name'])) {
        $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
        $picture_path = $upload_dir . 'picture_' . $applicant_id . '_' . time() . '.' . $ext;
        if (!move_uploaded_file($_FILES['picture']['tmp_name'], $picture_path)) {
            throw new Exception("Failed to upload picture.");
        }
        $stmt = $conn->prepare("UPDATE " . TABLE_APPLICANTS . " SET picture = ? WHERE applicant_ID = ?");
        $stmt->bind_param("si", $picture_path, $applicant_id);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("
        INSERT INTO " . TABLE_PERSONAL_INFO . " (
            applicant_ID, address, barangay, city, district, region, zip_code
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "issssss",
        $applicant_id,
        $_POST['address'],
        $_POST['barangay'],
        $_POST['city'],
        $_POST['district'],
        $_POST['region'],
        $_POST['zip_code']
    );
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO " . TABLE_GUARDIAN_INFO . " (
            applicant_ID, guardian_first_name, guardian_last_name, guardian_middle_name,
            guardian_extension_name, guardian_age, guardian_sex, guardian_relationship,
            guardian_contact_number, guardian_email_address
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "isssssssss",
        $applicant_id,
        $_POST['guardian_first_name'],
        $_POST['guardian_last_name'],
        $_POST['guardian_middle_name'],
        $_POST['guardian_extension_name'],
        $_POST['guardian_age'],
        $_POST['guardian_sex'],
        $_POST['guardian_relationship'],
        $_POST['guardian_contact_number'],
        $_POST['guardian_email_address']
    );
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO " . TABLE_ACADEMIC_BACKGROUND . " (
            applicant_ID, grade12_school_name, grade12_academic_year, grade12_GWA,
            grade11_school_name, grade11_academic_year, grade11_GWA,
            grade10_school_name, grade10_academic_year,
            grade9_school_name, grade9_academic_year,
            grade8_school_name, grade8_academic_year,
            grade7_school_name, grade7_academic_year
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "isssdssdsssssss",
        $applicant_id,
        $_POST['grade12_school_name'],
        $_POST['grade12_academic_year'],
        $_POST['grade12_GWA'],
        $_POST['grade11_school_name'],
        $_POST['grade11_academic_year'],
        $_POST['grade11_GWA'],
        $_POST['grade10_school_name'],
        $_POST['grade10_academic_year'],
        $_POST['grade9_school_name'],
        $_POST['grade9_academic_year'],
        $_POST['grade8_school_name'],
        $_POST['grade8_academic_year'],
        $_POST['grade7_school_name'],
        $_POST['grade7_academic_year']
    );
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO " . TABLE_APPLICATIONS . " (
            applicant_ID, application_status, submission_date
        ) VALUES (?, 'Pending', NOW())
    ");
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $application_id = $conn->insert_id;
    $stmt->close();

    if (!empty($_POST['first_course'])) {
        $stmt = $conn->prepare("
            SELECT course_ID FROM " . TABLE_COURSE_LIST . " WHERE course_name = ?
        ");
        $stmt->bind_param("s", $_POST['first_course']);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();
        $stmt->close();

        if ($course) {
            $stmt = $conn->prepare("
                INSERT INTO " . TABLE_APPLICANT_COURSES . " (
                    applicant_ID, course_ID, preference_order
                ) VALUES (?, ?, 1)
            ");
            $stmt->bind_param("ii", $applicant_id, $course['course_ID']);
            $stmt->execute();
            $stmt->close();
        }
    }

    if (!empty($_POST['second_course'])) {
        $stmt = $conn->prepare("
            SELECT course_ID FROM " . TABLE_COURSE_LIST . " WHERE course_name = ?
        ");
        $stmt->bind_param("s", $_POST['second_course']);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();
        $stmt->close();

        if ($course) {
            $stmt = $conn->prepare("
                INSERT INTO " . TABLE_APPLICANT_COURSES . " (
                    applicant_ID, course_ID, preference_order
                ) VALUES (?, ?, 2)
            ");
            $stmt->bind_param("ii", $applicant_id, $course['course_ID']);
            $stmt->execute();
            $stmt->close();
        }
    }

    $document_types = [
        'document_transcript' => 'Transcript',
        'document_birth' => 'Birth Certificate',
        'document_id' => 'Valid ID'
    ];

    foreach ($document_types as $field => $type) {
        if (!empty($_FILES[$field]['name'])) {
            $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
            $file_path = $upload_dir . $field . '_' . $applicant_id . '_' . time() . '.' . $ext;
            if (!move_uploaded_file($_FILES[$field]['tmp_name'], $file_path)) {
                throw new Exception("Failed to upload $type.");
            }
            $stmt = $conn->prepare("
                INSERT INTO " . TABLE_SUPPORTING_DOCUMENTS . " (
                    application_ID, document_type, file_path
                ) VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iss", $application_id, $type, $file_path);
            $stmt->execute();
            $stmt->close();
        }
    }

    $action = "Applicant submitted application";
    $user_id = 0; 
    $stmt = $conn->prepare("
        INSERT INTO " . TABLE_AUDIT_LOG . " (
            action, user_id, applicant_id, timestamp
        ) VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param("sii", $action, $user_id, $applicant_id);
    $stmt->execute();
    $stmt->close();
    
    $conn->commit();

    $_SESSION['success_message'] = "Application submitted successfully!";
    // $_SESSION['success'] = true;
    // header("Location: index.php");
    // exit;

} catch (Exception $e) {
    $conn->rollback();
    $errors[] = "Error processing application: " . $e->getMessage();
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit;
}
?>