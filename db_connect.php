<?php
$db_host = "localhost";
$db_user = "root";    
$db_pass = "";        
$db_name = "college_admissions";

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($db_name);
$conn->select_db($db_name);

// Add file upload columns if they don't exist
$alterTableQueries = [
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS profile_photo VARCHAR(255)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS student_signature VARCHAR(255)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS guardian_signature VARCHAR(255)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS grade12_gwa DECIMAL(4,2)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS grade11_gwa DECIMAL(4,2)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS course_1 VARCHAR(100)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS course_2 VARCHAR(100)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS status_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
];

foreach ($alterTableQueries as $alterQuery) {
    if ($conn->query($alterQuery) === FALSE) {
        // Log the error but don't die, as the column might alreasdy exist
        error_log("Error executing query: " . $conn->error);
    }
}


$sql = "CREATE TABLE IF NOT EXISTS applicants (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    extension_name VARCHAR(20),
    date_of_birth DATE NOT NULL,
    place_of_birth VARCHAR(100) NOT NULL,
    age INT(3) NOT NULL,
    sex VARCHAR(10) NOT NULL,
    blood_type VARCHAR(5),
    civil_status VARCHAR(20) NOT NULL,
    profile_photo VARCHAR(255),
    student_signature VARCHAR(255),
    guardian_signature VARCHAR(255),
    religious_affiliation VARCHAR(50),
    citizenship VARCHAR(50) NOT NULL,
    no_of_siblings INT(3),
    
    house VARCHAR(100) NOT NULL,
    barangay VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    district VARCHAR(50),
    zip_code VARCHAR(10),
    personal_number VARCHAR(20) NOT NULL,
    personal_email VARCHAR(100) NOT NULL,
    landline_number VARCHAR(20),
    
    guardian_first_name VARCHAR(50) NOT NULL,
    guardian_middle_name VARCHAR(50),
    guardian_last_name VARCHAR(50) NOT NULL,
    guardian_extension_name VARCHAR(20),
    guardian_age INT(3) NOT NULL,
    guardian_sex VARCHAR(10) NOT NULL,
    guardian_relationship VARCHAR(50) NOT NULL,
    guardian_address VARCHAR(255) NOT NULL,
    guardian_contact_number VARCHAR(20) NOT NULL,
    guardian_email VARCHAR(100),
    
    grade12_school VARCHAR(100),
    grade12_period VARCHAR(50),
    grade12_gwa DECIMAL(4,2),
    grade11_school VARCHAR(100),
    grade11_period VARCHAR(50),
    grade11_gwa DECIMAL(4,2),
    grade10_school VARCHAR(100),
    grade10_period VARCHAR(50),
    grade9_school VARCHAR(100),
    grade9_period VARCHAR(50),
    grade8_school VARCHAR(100),
    grade8_period VARCHAR(50),
    grade7_school VARCHAR(100),
    grade7_period VARCHAR(50),
    
    college_offered VARCHAR(100) NOT NULL,
    course_offered VARCHAR(100) NOT NULL,
    course_1 VARCHAR(100),
    course_2 VARCHAR(100),
    
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    status_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    date_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Return the connection object
return $conn;