<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_admissions";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $extension_name = $_POST['extension_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $place_of_birth = $_POST['place_of_birth'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $blood_type = $_POST['blood_type'];
    $civil_status = $_POST['civil_status'];
    $religious_affiliation = $_POST['religious_affiliation'];
    $citizenship = $_POST['citizenship'];
    $no_of_siblings = $_POST['no_of_siblings'];
    $house = $_POST['house'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $zip_code = $_POST['zip_code'];
    $personal_number = $_POST['personal_number'];
    $personal_email = $_POST['personal_email'];
    $landline_number = $_POST['landline_number'];
    $guardian_first_name = $_POST['guardian_first_name'];
    $guardian_middle_name = $_POST['guardian_middle_name'];
    $guardian_last_name = $_POST['guardian_last_name'];
    $guardian_extension_name = $_POST['guardian_extension_name'];
    $guardian_age = $_POST['guardian_age'];
    $guardian_sex = $_POST['guardian_sex'];
    $guardian_relationship = $_POST['guardian_relationship'];
    $guardian_address = $_POST['guardian_address'];
    $guardian_contact_number = $_POST['guardian_contact_number'];
    $guardian_email = $_POST['guardian_email'];
    $college_offered = $_POST['college_offered'];
    $course_offered = $_POST['course_offered'];

    $sql = "INSERT INTO applicants (first_name, last_name, middle_name, extension_name, date_of_birth, place_of_birth, age, sex, blood_type, civil_status, religious_affiliation, citizenship, no_of_siblings, house, barangay, city, district, zip_code, personal_number, personal_email, landline_number, guardian_first_name, guardian_middle_name, guardian_last_name, guardian_extension_name, guardian_age, guardian_sex, guardian_relationship, guardian_address, guardian_contact_number, guardian_email, college_offered, course_offered)
            VALUES ('$first_name', '$last_name', '$middle_name', '$extension_name', '$date_of_birth', '$place_of_birth', '$age', '$sex', '$blood_type', '$civil_status', '$religious_affiliation', '$citizenship', '$no_of_siblings', '$house', '$barangay', '$city', '$district', '$zip_code', '$personal_number', '$personal_email', '$landline_number', '$guardian_first_name', '$guardian_middle_name', '$guardian_last_name', '$guardian_extension_name', '$guardian_age', '$guardian_sex', '$guardian_relationship', '$guardian_address', '$guardian_contact_number', '$guardian_email', '$college_offered', '$course_offered')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

$conn->close();
?>
