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

    if (empty($_POST['first_name']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['first_name'])) {
        $errors[] = "Valid first name is required.";
    }

    if (empty($_POST['last_name']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['last_name'])) {
        $errors[] = "Valid last name is required.";
    }

    if (empty($_POST['email_address']) || !filter_var($_POST['email_address'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email address is required.";
    }

    if (empty($_POST['contact_number']) || !preg_match("/^\d{10,12}$/", $_POST['contact_number'])) {
        $errors[] = "Valid contact number (10-12 digits) is required.";
    }

    if (empty($_POST['birthday']) || !DateTime::createFromFormat('Y-m-d', $_POST['birthday'])) {
        $errors[] = "Valid birthday (YYYY-MM-DD) is required.";
    }

    if (empty($_POST['sex']) || !in_array($_POST['sex'], ['Male', 'Female', 'Other'])) {
        $errors[] = "Valid sex value is required.";
    }

    if (empty($_POST['civil_status']) || !in_array($_POST['civil_status'], ['Single', 'Married', 'Divorced', 'Widowed'])) {
        $errors[] = "Valid civil status is required.";
    }

    if (empty($_POST['citizenship'])) {
        $errors[] = "Citizenship is required.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO applicants (first_name, last_name, middle_name, extension_name, birthday, country_of_birth, age, sex, blood_type, civil_status, religious_affiliation, citizenship, number_of_siblings, picture, email_address, contact_number)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssisisssssss", 
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
        $_POST['picture'], 
        $_POST['email_address'], 
        $_POST['contact_number']
    );

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Application submitted successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
