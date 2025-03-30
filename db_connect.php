<?php
$db_host = "localhost";
$db_user = "root";    
$db_pass = "";        
$db_name = "college_applications";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}


$conn->select_db($db_name);

$sql = "CREATE TABLE IF NOT EXISTS applicants (
    applicant_ID INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(40) NOT NULL,
    last_name VARCHAR(40) NOT NULL,
    middle_name VARCHAR(40),
    extension_name ENUM('Jr.', 'Sr.', 'III', 'IV', 'N/A') DEFAULT 'N/A',
    birthday DATE NOT NULL,
    country_of_birth VARCHAR(50) NOT NULL,
    age SMALLINT NOT NULL,
    sex ENUM('Male', 'Female', 'Other') NOT NULL,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'),
    civil_status ENUM('Single', 'Married', 'Divorced', 'Widowed') NOT NULL,
    religious_affiliation VARCHAR(20),
    citizenship VARCHAR(30) NOT NULL,
    number_of_siblings SMALLINT,
    picture VARCHAR(255),
    email_address VARCHAR(50) NOT NULL,
    contact_number VARCHAR(12) NOT NULL
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating applicants table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS guardian_info (
    guardian_ID INT AUTO_INCREMENT PRIMARY KEY,
    applicant_ID INT,
    guardian_first_name VARCHAR(40) NOT NULL,
    guardian_last_name VARCHAR(40) NOT NULL,
    guardian_middle_name VARCHAR(40),
    guardian_extension_name ENUM('Jr.', 'Sr.', 'III', 'IV', 'N/A') DEFAULT 'N/A',
    guardian_age SMALLINT NOT NULL,
    guardian_sex ENUM('Male', 'Female', 'Other') NOT NULL,
    guardian_relationship ENUM('Parent', 'Aunt', 'Uncle', 'Sibling', 'Other') NOT NULL,
    guardian_contact_number VARCHAR(12) NOT NULL,
    guardian_email_address VARCHAR(50),
    FOREIGN KEY (applicant_ID) REFERENCES applicants(applicant_ID) ON DELETE CASCADE
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating guardian_info table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS personal_info (
    personal_info_ID INT AUTO_INCREMENT PRIMARY KEY,
    applicant_ID INT,
    address VARCHAR(40) NOT NULL,
    barangay VARCHAR(30) NOT NULL,
    city VARCHAR(30) NOT NULL,
    district SMALLINT,
    region ENUM('NCR', 'Region I', 'Region II', 'Region III', 'CAR', 'Other'),
    zip_code SMALLINT,
    FOREIGN KEY (applicant_ID) REFERENCES applicants(applicant_ID) ON DELETE CASCADE
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating personal_info table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS academic_background (
    academic_background_ID INT AUTO_INCREMENT PRIMARY KEY,
    applicant_ID INT,
    grade12_school_name VARCHAR(100),
    grade12_academic_year VARCHAR(9),
    grade12_GWA DECIMAL(4,2),
    grade11_school_name VARCHAR(100),
    grade11_academic_year VARCHAR(9),
    grade11_GWA DECIMAL(4,2),
    grade10_school_name VARCHAR(100),
    grade10_academic_year VARCHAR(9),
    grade9_school_name VARCHAR(100),
    grade9_academic_year VARCHAR(9),
    grade8_school_name VARCHAR(100),
    grade8_academic_year VARCHAR(9),
    grade7_school_name VARCHAR(100),
    grade7_academic_year VARCHAR(9),
    FOREIGN KEY (applicant_ID) REFERENCES applicants(applicant_ID) ON DELETE CASCADE
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating academic_background table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS applications (
    application_ID INT AUTO_INCREMENT PRIMARY KEY,
    applicant_ID INT,
    application_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    submission_date DATE NOT NULL,
    FOREIGN KEY (applicant_ID) REFERENCES applicants(applicant_ID) ON DELETE CASCADE
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating applications table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS courses (
    course_ID SMALLINT AUTO_INCREMENT PRIMARY KEY,
    first_course ENUM('IT', 'CS', 'Engineering', 'Business', 'Other') NOT NULL,
    second_course ENUM('IT', 'CS', 'Engineering', 'Business', 'Other') NOT NULL,
    college_ID INT,
    FOREIGN KEY (college_ID) REFERENCES applications(application_ID) ON DELETE CASCADE
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating courses table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS supporting_documents (
    document_ID INT AUTO_INCREMENT PRIMARY KEY,
    application_ID INT,
    document_type ENUM('Transcript', 'ID', 'Birth Certificate', 'Other') NOT NULL,
    FOREIGN KEY (application_ID) REFERENCES applications(application_ID) ON DELETE CASCADE
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating supporting_documents table: " . $conn->error);
}


?>