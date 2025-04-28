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

// Create applicants table if it doesn't exist
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
    religious_affiliation VARCHAR(50),
    citizenship VARCHAR(50) NOT NULL,
    no_of_siblings INT(3),
    photo VARCHAR(255),
    
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
    grade11_school VARCHAR(100),
    grade11_period VARCHAR(50),
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
    
    date_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    application_status VARCHAR(20) DEFAULT 'Pending'
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Return the connection object
return $conn;