<?php
$db_host = "localhost";
$db_user = "root";    
$db_pass = "";        
$db_name = "college_applications";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $applicant_data = [];
    $address_data = [];
    $guardian_data = [];
    $education_data = [];
    $course_data = [];
    
    if (empty($_POST['first_name']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['first_name'])) {
        $errors[] = "Valid first name is required.";
    } else {
        $applicant_data['first_name'] = $_POST['first_name'];
    }
    
    if (empty($_POST['last_name']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['last_name'])) {
        $errors[] = "Valid last name is required.";
    } else {
        $applicant_data['last_name'] = $_POST['last_name'];
    }
    
    $applicant_data['middle_name'] = isset($_POST['middle_name']) ? $_POST['middle_name'] : "";
    
    $applicant_data['extension_name'] = isset($_POST['extension_name']) ? $_POST['extension_name'] : "N/A";
    
    if (empty($_POST['birthday']) || !DateTime::createFromFormat('Y-m-d', $_POST['birthday'])) {
        $errors[] = "Valid birthday (YYYY-MM-DD) is required.";
    } else {
        $applicant_data['birthday'] = $_POST['birthday'];
        $birthDate = new DateTime($_POST['birthday']);
        $today = new DateTime('today');
        $applicant_data['age'] = $birthDate->diff($today)->y;
    }
    
    if (empty($_POST['country_of_birth'])) {
        $errors[] = "Country of birth is required.";
    } else {
        $applicant_data['country_of_birth'] = $_POST['country_of_birth'];
    }
    
    if (empty($_POST['sex']) || !in_array($_POST['sex'], ['Male', 'Female', 'Other'])) {
        $errors[] = "Valid sex value is required.";
    } else {
        $applicant_data['sex'] = $_POST['sex'];
    }
    
    $applicant_data['blood_type'] = isset($_POST['blood_type']) ? $_POST['blood_type'] : "";
    
    if (empty($_POST['civil_status']) || !in_array($_POST['civil_status'], ['Single', 'Married', 'Divorced', 'Widowed'])) {
        $errors[] = "Valid civil status is required.";
    } else {
        $applicant_data['civil_status'] = $_POST['civil_status'];
    }
    
    $applicant_data['religious_affiliation'] = isset($_POST['religious_affiliation']) ? $_POST['religious_affiliation'] : "";
    
    if (empty($_POST['citizenship'])) {
        $errors[] = "Citizenship is required.";
    } else {
        $applicant_data['citizenship'] = $_POST['citizenship'];
    }
    
    $applicant_data['number_of_siblings'] = isset($_POST['number_of_siblings']) ? (int)$_POST['number_of_siblings'] : 0;
    
    if (empty($_POST['email_address']) || !filter_var($_POST['email_address'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email address is required.";
    } else {
        $applicant_data['email_address'] = $_POST['email_address'];
    }
    
    if (empty($_POST['contact_number']) || !preg_match("/^\d{10,12}$/", $_POST['contact_number'])) {
        $errors[] = "Valid contact number (10-12 digits) is required.";
    } else {
        $applicant_data['contact_number'] = $_POST['contact_number'];
    }
    
    $picture_name = "";
    if(isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['picture']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            $new_name = uniqid() . "." . $filetype;
            $upload_dir = "uploads/";
            
            if(!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if(move_uploaded_file($_FILES['picture']['tmp_name'], $upload_dir . $new_name)) {
                $applicant_data['picture'] = $upload_dir . $new_name;
            } else {
                $errors[] = "Error uploading file.";
            }
        } else {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG and GIF are allowed.";
        }
    }
    
    $address_data['address'] = isset($_POST['address']) ? $_POST['address'] : "";
    $address_data['barangay'] = isset($_POST['barangay']) ? $_POST['barangay'] : "";
    $address_data['city'] = isset($_POST['city']) ? $_POST['city'] : "";
    $address_data['district'] = isset($_POST['district']) ? $_POST['district'] : "";
    $address_data['region'] = isset($_POST['region']) ? $_POST['region'] : "";
    $address_data['zip_code'] = isset($_POST['zip_code']) ? $_POST['zip_code'] : "";
    
    $guardian_data['guardian_first_name'] = isset($_POST['guardian_first_name']) ? $_POST['guardian_first_name'] : "";
    $guardian_data['guardian_middle_name'] = isset($_POST['guardian_middle_name']) ? $_POST['guardian_middle_name'] : "";
    $guardian_data['guardian_last_name'] = isset($_POST['guardian_last_name']) ? $_POST['guardian_last_name'] : "";
    $guardian_data['guardian_extension_name'] = isset($_POST['guardian_extension_name']) ? $_POST['guardian_extension_name'] : "N/A";
    $guardian_data['guardian_age'] = isset($_POST['guardian_age']) ? (int)$_POST['guardian_age'] : 0;
    $guardian_data['guardian_sex'] = isset($_POST['guardian_sex']) ? $_POST['guardian_sex'] : "";
    $guardian_data['guardian_relationship'] = isset($_POST['guardian_relationship']) ? $_POST['guardian_relationship'] : "";
    $guardian_data['guardian_contact_number'] = isset($_POST['guardian_contact_number']) ? $_POST['guardian_contact_number'] : "";
    $guardian_data['guardian_email_address'] = isset($_POST['guardian_email_address']) ? $_POST['guardian_email_address'] : "";
    
    $education_data['grade12_school_name'] = isset($_POST['grade12_school_name']) ? $_POST['grade12_school_name'] : "";
    $education_data['grade12_academic_year'] = isset($_POST['grade12_academic_year']) ? $_POST['grade12_academic_year'] : "";
    $education_data['grade12_GWA'] = isset($_POST['grade12_GWA']) ? $_POST['grade12_GWA'] : null;
    $education_data['grade11_school_name'] = isset($_POST['grade11_school_name']) ? $_POST['grade11_school_name'] : "";
    $education_data['grade11_academic_year'] = isset($_POST['grade11_academic_year']) ? $_POST['grade11_academic_year'] : "";
    $education_data['grade11_GWA'] = isset($_POST['grade11_GWA']) ? $_POST['grade11_GWA'] : null;
    $education_data['grade10_school_name'] = isset($_POST['grade10_school_name']) ? $_POST['grade10_school_name'] : "";
    $education_data['grade10_academic_year'] = isset($_POST['grade10_academic_year']) ? $_POST['grade10_academic_year'] : "";
    $education_data['grade9_school_name'] = isset($_POST['grade9_school_name']) ? $_POST['grade9_school_name'] : "";
    $education_data['grade9_academic_year'] = isset($_POST['grade9_academic_year']) ? $_POST['grade9_academic_year'] : "";
    $education_data['grade8_school_name'] = isset($_POST['grade8_school_name']) ? $_POST['grade8_school_name'] : "";
    $education_data['grade8_academic_year'] = isset($_POST['grade8_academic_year']) ? $_POST['grade8_academic_year'] : "";
    $education_data['grade7_school_name'] = isset($_POST['grade7_school_name']) ? $_POST['grade7_school_name'] : "";
    $education_data['grade7_academic_year'] = isset($_POST['grade7_academic_year']) ? $_POST['grade7_academic_year'] : "";
    
    $course_data['first_course'] = isset($_POST['first_course']) ? $_POST['first_course'] : "";
    $course_data['second_course'] = isset($_POST['second_course']) ? $_POST['second_course'] : "";
    $course_data['application_status'] = "Pending";
    $course_data['submission_date'] = date('Y-m-d');
    
    if (!empty($errors)) {
        echo "<div style='background-color: #ffcccc; padding: 15px; margin-bottom: 20px; border-radius: 5px;'>";
        echo "<h3>Please correct the following errors:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        echo "<a href='javascript:history.back()' style='display: inline-block; background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;'>Go Back</a>";
        echo "</div>";
        exit;
    }
    
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
        
        if (!$stmt) {
            throw new Exception("Error preparing applicant statement: " . $conn->error);
        }
        
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
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing applicant statement: " . $stmt->error);
        }
        
        $applicant_id = $conn->insert_id;
        $stmt->close();
        
        if (!empty($address_data['address']) || !empty($address_data['city'])) {
            $stmt = $conn->prepare("
                INSERT INTO applicant_address (
                    applicant_id, address, barangay, city, district, region, zip_code
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            if (!$stmt) {
                throw new Exception("Error preparing address statement: " . $conn->error);
            }
            
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
            
            if (!$stmt->execute()) {
                throw new Exception("Error executing address statement: " . $stmt->error);
            }
            
            $stmt->close();
        }
        
        if (!empty($guardian_data['guardian_first_name']) || !empty($guardian_data['guardian_last_name'])) {
            $stmt = $conn->prepare("
                INSERT INTO guardian_info (
                    applicant_id, first_name, middle_name, last_name, extension_name,
                    age, sex, relationship, contact_number, email_address
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            if (!$stmt) {
                throw new Exception("Error preparing guardian statement: " . $conn->error);
            }
            
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
            
            if (!$stmt->execute()) {
                throw new Exception("Error executing guardian statement: " . $stmt->error);
            }
            
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
        
        if (!$stmt) {
            throw new Exception("Error preparing education statement: " . $conn->error);
        }
        
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
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing education statement: " . $stmt->error);
        }
        
        $stmt->close();
        
        $stmt = $conn->prepare("
            INSERT INTO course_application (
                applicant_id, first_choice, second_choice, application_status, submission_date
            ) VALUES (?, ?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            throw new Exception("Error preparing course statement: " . $conn->error);
        }
        
        $stmt->bind_param(
            "issss", 
            $applicant_id,
            $course_data['first_course'], 
            $course_data['second_course'], 
            $course_data['application_status'],
            $course_data['submission_date']
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing course statement: " . $stmt->error);
        }
        
        $stmt->close();
        
        $conn->commit();
        
        echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Application Submitted</title>
                <script src='https://cdn.tailwindcss.com'></script>
            </head>
            <body class='bg-gray-100 flex flex-col justify-center items-center min-h-screen'>
                <div class='bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center'>
                    <div class='text-green-500 mb-4'>
                        <svg xmlns='http://www.w3.org/2000/svg' class='h-16 w-16 mx-auto' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' />
                        </svg>
                    </div>
                    <h1 class='text-2xl font-bold mb-4'>Application Submitted Successfully!</h1>
                    <p class='text-gray-600 mb-6'>Thank you for your application. Your application has been received and is now being processed.</p>
                    <p class='text-gray-600 mb-6'>Your Application ID: <strong>$applicant_id</strong></p>
                    <a href='index.php' class='inline-block bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition duration-300'>Return to Homepage</a>
                </div>
            </body>
            </html>";
        
    } catch (Exception $e) {
        $conn->rollback();
        
        echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Application Error</title>
                <script src='https://cdn.tailwindcss.com'></script>
            </head>
            <body class='bg-gray-100 flex flex-col justify-center items-center min-h-screen'>
                <div class='bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center'>
                    <div class='text-red-500 mb-4'>
                        <svg xmlns='http://www.w3.org/2000/svg' class='h-16 w-16 mx-auto' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' />
                        </svg>
                    </div>
                    <h1 class='text-2xl font-bold mb-4'>Error Submitting Application</h1>
                    <p class='text-gray-600 mb-6'>There was an error processing your application: " . htmlspecialchars($e->getMessage()) . "</p>
                    <a href='javascript:history.back()' class='inline-block bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition duration-300'>Go Back and Try Again</a>
                </div>
            </body>
            </html>";
    }
    
    $conn->close();
} else {
    header("Location: index.php");
    exit;
}
?>
