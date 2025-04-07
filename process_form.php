<?php
require_once 'db_connection.php'; 

function validateInput($data, &$errors) {
    $validated = [];

    if (empty($data['first_name']) || !preg_match("/^[a-zA-Z\s]+$/", $data['first_name'])) {
        $errors[] = "Valid first name is required.";
    } else {
        $validated['first_name'] = $data['first_name'];
    }

    if (empty($data['last_name']) || !preg_match("/^[a-zA-Z\s]+$/", $data['last_name'])) {
        $errors[] = "Valid last name is required.";
    } else {
        $validated['last_name'] = $data['last_name'];
    }

    $validated['middle_name'] = $data['middle_name'] ?? "";
    $validated['extension_name'] = $data['extension_name'] ?? "N/A";

    if (empty($data['birthday']) || !DateTime::createFromFormat('Y-m-d', $data['birthday'])) {
        $errors[] = "Valid birthday (YYYY-MM-DD) is required.";
    } else {
        $validated['birthday'] = $data['birthday'];
        $birthDate = new DateTime($data['birthday']);
        $today = new DateTime('today');
        $validated['age'] = $birthDate->diff($today)->y;
    }

    $validated['country_of_birth'] = $data['country_of_birth'] ?? "";
    $validated['sex'] = in_array($data['sex'], ['Male', 'Female', 'Other']) ? $data['sex'] : "";
    $validated['blood_type'] = $data['blood_type'] ?? "";
    $validated['civil_status'] = in_array($data['civil_status'], ['Single', 'Married', 'Divorced', 'Widowed']) ? $data['civil_status'] : "";
    $validated['religious_affiliation'] = $data['religious_affiliation'] ?? "";
    $validated['citizenship'] = $data['citizenship'] ?? "";
    $validated['number_of_siblings'] = isset($data['number_of_siblings']) ? (int)$data['number_of_siblings'] : 0;

    if (empty($data['email_address']) || !filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email address is required.";
    } else {
        $validated['email_address'] = $data['email_address'];
    }

    if (empty($data['contact_number']) || !preg_match("/^\d{10,12}$/", $data['contact_number'])) {
        $errors[] = "Valid contact number (10-12 digits) is required.";
    } else {
        $validated['contact_number'] = $data['contact_number'];
    }

    return $validated;
}

function handleFileUpload($file, &$errors) {
    if (isset($file) && $file['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $file['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array(strtolower($filetype), $allowed)) {
            $new_name = uniqid() . "." . $filetype;
            $upload_dir = "uploads/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
                return $upload_dir . $new_name;
            } else {
                $errors[] = "Error uploading file.";
            }
        } else {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }
    return null;
}

function insertData($conn, $applicant_data, $address_data, $guardian_data, $education_data, $course_data) {
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("
            INSERT INTO applicants (
                first_name, last_name, middle_name, extension_name, 
                birthday, country_of_birth, age, sex, blood_type, 
                civil_status, religious_affiliation, citizenship, 
                number_of_siblings, picture, email_address, contact_number
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssssississsss",
            $applicant_data['first_name'],
            $applicant_data['last_name'],
            $applicant_data['middle_name'],
            $applicant_data['extension_name'],
            $applicant_data['birthday'],
            $applicant_data['country_of_birth'],
            $applicant_data['age'],
            $applicant_data['sex'],
            $applicant_data['blood_type'],
            $applicant_data['civil_status'],
            $applicant_data['religious_affiliation'],
            $applicant_data['citizenship'],
            $applicant_data['number_of_siblings'],
            $applicant_data['picture'],
            $applicant_data['email_address'],
            $applicant_data['contact_number']
        );
        $stmt->execute();
        $applicant_id = $conn->insert_id;
        $stmt->close();

        if (!empty($address_data['address']) || !empty($address_data['city'])) {
            $stmt = $conn->prepare("
                INSERT INTO applicant_address (
                    applicant_id, address, barangay, city, district, region, zip_code
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "issssss",
                $applicant_id,
                $address_data['address'],
                $address_data['barangay'],
                $address_data['city'],
                $address_data['district'],
                $address_data['region'],
                $address_data['zip_code']
            );
            $stmt->execute();
            $stmt->close();
        }

        if (!empty($guardian_data['guardian_first_name']) || !empty($guardian_data['guardian_last_name'])) {
            $stmt = $conn->prepare("
                INSERT INTO guardian_info (
                    applicant_id, first_name, middle_name, last_name, extension_name,
                    age, sex, relationship, contact_number, email_address
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "issssissss",
                $applicant_id,
                $guardian_data['guardian_first_name'],
                $guardian_data['guardian_middle_name'],
                $guardian_data['guardian_last_name'],
                $guardian_data['guardian_extension_name'],
                $guardian_data['guardian_age'],
                $guardian_data['guardian_sex'],
                $guardian_data['guardian_relationship'],
                $guardian_data['guardian_contact_number'],
                $guardian_data['guardian_email_address']
            );
            $stmt->execute();
            $stmt->close();
        }

        $stmt = $conn->prepare("
            INSERT INTO education_history (
                applicant_id, grade12_school, grade12_year, grade12_gwa,
                grade11_school, grade11_year, grade11_gwa,
                grade10_school, grade10_year,
                grade9_school, grade9_year,
                grade8_school, grade8_year,
                grade7_school, grade7_year
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issdssdsssssss",
            $applicant_id,
            $education_data['grade12_school_name'],
            $education_data['grade12_academic_year'],
            $education_data['grade12_GWA'],
            $education_data['grade11_school_name'],
            $education_data['grade11_academic_year'],
            $education_data['grade11_GWA'],
            $education_data['grade10_school_name'],
            $education_data['grade10_academic_year'],
            $education_data['grade9_school_name'],
            $education_data['grade9_academic_year'],
            $education_data['grade8_school_name'],
            $education_data['grade8_academic_year'],
            $education_data['grade7_school_name'],
            $education_data['grade7_academic_year']
        );
        $stmt->execute();
        $stmt->close();

        // Insert Course Data
        $stmt = $conn->prepare("
            INSERT INTO course_application (
                applicant_id, first_choice, second_choice, application_status, submission_date
            ) VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issss",
            $applicant_id,
            $course_data['first_course'],
            $course_data['second_course'],
            $course_data['application_status'],
            $course_data['submission_date']
        );
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        return $applicant_id;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $applicant_data = validateInput($_POST, $errors);
    $applicant_data['picture'] = handleFileUpload($_FILES['picture'], $errors);

    if (!empty($errors)) {
        // Display errors and exit
        echo "<div style='background-color: #ffcccc; padding: 15px; margin-bottom: 20px; border-radius: 5px;'>";
        echo "<h3>Please correct the following errors:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul><a href='javascript:history.back()'>Go Back</a></div>";
        exit;
    }

    try {
        $applicant_id = insertData($conn, $applicant_data, $_POST['address_data'], $_POST['guardian_data'], $_POST['education_data'], $_POST['course_data']);
        echo "<h1>Application Submitted Successfully! Your Application ID: $applicant_id</h1>";
    } catch (Exception $e) {
        echo "<h1>Error Submitting Application: " . htmlspecialchars($e->getMessage()) . "</h1>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>
