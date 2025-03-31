
<?php
$applicant_ID = "";
$error_message = "";
$success_message = "";

$conn = require_once 'db_connect.php';

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $applicant_ID = $_GET['id'];
    
    $sql = "SELECT a.*, p.*, g.* 
            FROM applicants a 
            LEFT JOIN personal_info p ON a.applicant_ID = p.applicant_ID 
            LEFT JOIN guardian_info g ON a.applicant_ID = g.applicant_ID 
            WHERE a.applicant_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $applicant_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $applicant_data = $result->fetch_assoc();
    } else {
        $error_message = "Applicant not found!";
    }
    $stmt->close();
} else {
    $error_message = "Invalid request. Applicant ID is required.";
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $applicant_ID = $_POST['applicant_ID'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);
    $extension_name = trim($_POST['extension_name']);
    $birthday = trim($_POST['birthday']);
    $country_of_birth = trim($_POST['country_of_birth']);
    $age = trim($_POST['age']);
    $sex = trim($_POST['sex']);
    $blood_type = trim($_POST['blood_type']);
    $civil_status = trim($_POST['civil_status']);
    $religious_affiliation = trim($_POST['religious_affiliation']);
    $citizenship = trim($_POST['citizenship']);
    $number_of_siblings = trim($_POST['number_of_siblings']);
    $email_address = trim($_POST['email_address']);
    $contact_number = trim($_POST['contact_number']);
    
    $address = trim($_POST['address']);
    $barangay = trim($_POST['barangay']);
    $city = trim($_POST['city']);
    $district = trim($_POST['district']);
    $region = trim($_POST['region']);
    $zip_code = trim($_POST['zip_code']);
    
    $guardian_first_name = trim($_POST['guardian_first_name']);
    $guardian_middle_name = trim($_POST['guardian_middle_name']);
    $guardian_last_name = trim($_POST['guardian_last_name']);
    $guardian_extension_name = trim($_POST['guardian_extension_name']);
    $guardian_age = trim($_POST['guardian_age']);
    $guardian_sex = trim($_POST['guardian_sex']);
    $guardian_relationship = trim($_POST['guardian_relationship']);
    $guardian_contact_number = trim($_POST['guardian_contact_number']);
    $guardian_email_address = trim($_POST['guardian_email_address']);
    
    if(empty($first_name) || empty($last_name) || empty($email_address)) {
        $error_message = "First name, last name, and email are required fields!";
    } else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address!";
    } else {
        $conn->begin_transaction();
        
        try {
            $sql = "UPDATE applicants SET 
                    first_name = ?, 
                    last_name = ?, 
                    middle_name = ?, 
                    extension_name = ?, 
                    birthday = ?, 
                    country_of_birth = ?, 
                    age = ?, 
                    sex = ?, 
                    blood_type = ?, 
                    civil_status = ?, 
                    religious_affiliation = ?, 
                    citizenship = ?, 
                    number_of_siblings = ?,
                    email_address = ?,
                    contact_number = ?
                    WHERE applicant_ID = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssisssssisii", 
                $first_name, 
                $last_name, 
                $middle_name, 
                $extension_name, 
                $birthday, 
                $country_of_birth, 
                $age, 
                $sex, 
                $blood_type, 
                $civil_status, 
                $religious_affiliation, 
                $citizenship, 
                $number_of_siblings,
                $email_address,
                $contact_number,
                $applicant_ID
            );
            $stmt->execute();
            
            $sql = "UPDATE personal_info SET 
                    address = ?, 
                    barangay = ?, 
                    city = ?, 
                    district = ?, 
                    region = ?,
                    zip_code = ? 
                    WHERE applicant_ID = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssisii", 
                $address, 
                $barangay, 
                $city, 
                $district, 
                $region,
                $zip_code,
                $applicant_ID
            );
            $stmt->execute();
            
            $sql = "UPDATE guardian_info SET 
                    guardian_first_name = ?, 
                    guardian_last_name = ?, 
                    guardian_middle_name = ?, 
                    guardian_extension_name = ?, 
                    guardian_age = ?, 
                    guardian_sex = ?, 
                    guardian_relationship = ?, 
                    guardian_contact_number = ?,
                    guardian_email_address = ?
                    WHERE applicant_ID = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssissssi", 
                $guardian_first_name, 
                $guardian_last_name, 
                $guardian_middle_name, 
                $guardian_extension_name, 
                $guardian_age, 
                $guardian_sex, 
                $guardian_relationship, 
                $guardian_contact_number,
                $guardian_email_address,
                $applicant_ID
            );
            $stmt->execute();
            
            $conn->commit();
            
            $success_message = "Applicant updated successfully!";
            
            $refresh_sql = "SELECT a.*, p.*, g.* 
                          FROM applicants a 
                          LEFT JOIN personal_info p ON a.applicant_ID = p.applicant_ID 
                          LEFT JOIN guardian_info g ON a.applicant_ID = g.applicant_ID 
                          WHERE a.applicant_ID = ?";
            $refresh_stmt = $conn->prepare($refresh_sql);
            $refresh_stmt->bind_param("i", $applicant_ID);
            $refresh_stmt->execute();
            $refresh_result = $refresh_stmt->get_result();
            $applicant_data = $refresh_result->fetch_assoc();
            $refresh_stmt->close();
            
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant - ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-purple-200">
    
    <div class="bg-indigo-200 flex justify-between items-center p-3 h-24">
        <img src="logo.png" alt="Logo" class="h-20 ml-3">
        <div class="mr-3 flex items-center">
            <img src="phone.png" alt="Phone" class="h-12 ml-4 cursor-pointer">
            <img src="bell.png" alt="Notifications" class="h-12 ml-4 cursor-pointer">
            <img src="user.png" alt="User Profile" class="h-12 ml-4 cursor-pointer">
        </div>
    </div>

    <div class="flex">
        
        <div class="bg-indigo-200 p-4 h-screen w-48 flex flex-col items-center">
            <h2 class="text-center font-bold text-2xl mb-5 text-black">ADMIN</h2>
            <div class="bg-gray-300 p-2 mt-2 text-center font-bold w-full rounded text-black">Applicant Form</div>
            <a href="admin_dashboard.php" class="bg-purple-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-purple-700">Dashboard</a>
            <a href="logout.php" class="bg-red-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-red-700">Logout</a>
        </div>

        <div class="flex-1 p-4 overflow-auto">
            <div class="bg-purple-500 rounded-lg max-w-6xl mx-auto p-5">
                <h1 class="text-white text-2xl font-bold mb-4">Edit Applicant</h1>
                
                <?php if(!empty($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!isset($error_message) || !empty($applicant_data)): ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $applicant_ID); ?>" class="bg-white p-6 rounded-lg">
                        <input type="hidden" name="applicant_ID" value="<?php echo $applicant_ID; ?>">
                        
                        <div class="mb-6">
                            <h2 class="text-lg font-bold mb-4 pb-2 border-b-2 border-gray-200">Personal Information</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name *</label>
                                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($applicant_data['first_name'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                
                                <div>
                                    <label for="middle_name" class="block text-gray-700 text-sm font-bold mb-2">Middle Name</label>
                                    <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($applicant_data['middle_name'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name *</label>
                                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($applicant_data['last_name'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                
                                <div>
                                    <label for="extension_name" class="block text-gray-700 text-sm font-bold mb-2">Extension Name</label>
                                    <select id="extension_name" name="extension_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="N/A" <?php echo (isset($applicant_data['extension_name']) && $applicant_data['extension_name'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                                        <option value="Jr." <?php echo (isset($applicant_data['extension_name']) && $applicant_data['extension_name'] == 'Jr.') ? 'selected' : ''; ?>>Jr.</option>
                                        <option value="Sr." <?php echo (isset($applicant_data['extension_name']) && $applicant_data['extension_name'] == 'Sr.') ? 'selected' : ''; ?>>Sr.</option>
                                        <option value="III" <?php echo (isset($applicant_data['extension_name']) && $applicant_data['extension_name'] == 'III') ? 'selected' : ''; ?>>III</option>
                                        <option value="IV" <?php echo (isset($applicant_data['extension_name']) && $applicant_data['extension_name'] == 'IV') ? 'selected' : ''; ?>>IV</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="birthday" class="block text-gray-700 text-sm font-bold mb-2">Date of Birth</label>
                                    <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($applicant_data['birthday'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="country_of_birth" class="block text-gray-700 text-sm font-bold mb-2">Country of Birth</label>
                                    <input type="text" id="country_of_birth" name="country_of_birth" value="<?php echo htmlspecialchars($applicant_data['country_of_birth'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="age" class="block text-gray-700 text-sm font-bold mb-2">Age</label>
                                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($applicant_data['age'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="sex" class="block text-gray-700 text-sm font-bold mb-2">Sex</label>
                                    <select id="sex" name="sex" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                        <option value="Male" <?php echo (isset($applicant_data['sex']) && $applicant_data['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (isset($applicant_data['sex']) && $applicant_data['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo (isset($applicant_data['sex']) && $applicant_data['sex'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="blood_type" class="block text-gray-700 text-sm font-bold mb-2">Blood Type</label>
                                    <select id="blood_type" name="blood_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                        <option value="A+" <?php echo (isset($applicant_data['blood_type']) && $applicant_data['blood_type'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                                        <option value="A-" <?php echo (isset($applicant_data['blood_type']) && $applicant_data['blood_type'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                                        <option value="B+" <?php echo (isset($applicant_data['blood_type']) && $applicant_data['blood_type'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                                        <option value="B-" <?php echo (isset($applicant_data['blood_type']) && $applicant_data['blood_type'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                                        <option value="O+" <?php echo (isset($applicant_data['blood_type']) && $applicant_data['blood_type'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                                        <option value="O-" <?php echo (isset($applicant_data['blood_type']) && $applicant_data['blood_type'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                                        <option value="AB+" <?php echo (isset($applicant_data['blood_type']) && $applicant_data['blood_type'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                        <option value="AB-" <?php echo (isset($applicant_data['blood_type']) && $applicant_data['blood_type'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="civil_status" class="block text-gray-700 text-sm font-bold mb-2">Civil Status</label>
                                    <select id="civil_status" name="civil_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                        <option value="Single" <?php echo (isset($applicant_data['civil_status']) && $applicant_data['civil_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                        <option value="Married" <?php echo (isset($applicant_data['civil_status']) && $applicant_data['civil_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                        <option value="Divorced" <?php echo (isset($applicant_data['civil_status']) && $applicant_data['civil_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                        <option value="Widowed" <?php echo (isset($applicant_data['civil_status']) && $applicant_data['civil_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="religious_affiliation" class="block text-gray-700 text-sm font-bold mb-2">Religious Affiliation</label>
                                    <input type="text" id="religious_affiliation" name="religious_affiliation" value="<?php echo htmlspecialchars($applicant_data['religious_affiliation'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="citizenship" class="block text-gray-700 text-sm font-bold mb-2">Citizenship</label>
                                    <input type="text" id="citizenship" name="citizenship" value="<?php echo htmlspecialchars($applicant_data['citizenship'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="number_of_siblings" class="block text-gray-700 text-sm font-bold mb-2">Number of Siblings</label>
                                    <input type="number" id="number_of_siblings" name="number_of_siblings" value="<?php echo htmlspecialchars($applicant_data['number_of_siblings'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h2 class="text-lg font-bold mb-4 pb-2 border-b-2 border-gray-200">Address Information</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">House/Building/Street</label>
                                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($applicant_data['address'] ?? ''); ?>" 
                                        class
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="barangay" class="block text-gray-700 text-sm font-bold mb-2">Barangay</label>
                                    <input type="text" id="barangay" name="barangay" value="<?php echo htmlspecialchars($applicant_data['barangay'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City</label>
                                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($applicant_data['city'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="district" class="block text-gray-700 text-sm font-bold mb-2">District</label>
                                    <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($applicant_data['district'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="region" class="block text-gray-700 text-sm font-bold mb-2">Region</label>
                                    <input type="text" id="region" name="region" value="<?php echo htmlspecialchars($applicant_data['region'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="zip_code" class="block text-gray-700 text-sm font-bold mb-2">Zip Code</label>
                                    <input type="text" id="zip_code" name="zip_code" value="<?php echo htmlspecialchars($applicant_data['zip_code'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h2 class="text-lg font-bold mb-4 pb-2 border-b-2 border-gray-200">Guardian Information</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="guardian_first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                                    <input type="text" id="guardian_first_name" name="guardian_first_name" value="<?php echo htmlspecialchars($applicant_data['guardian_first_name'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="guardian_middle_name" class="block text-gray-700 text-sm font-bold mb-2">Middle Name</label>
                                    <input type="text" id="guardian_middle_name" name="guardian_middle_name" value="<?php echo htmlspecialchars($applicant_data['guardian_middle_name'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="guardian_last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                                    <input type="text" id="guardian_last_name" name="guardian_last_name" value="<?php echo htmlspecialchars($applicant_data['guardian_last_name'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="guardian_extension_name" class="block text-gray-700 text-sm font-bold mb-2">Extension Name</label>
                                    <input type="text" id="guardian_extension_name" name="guardian_extension_name" value="<?php echo htmlspecialchars($applicant_data['guardian_extension_name'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="guardian_age" class="block text-gray-700 text-sm font-bold mb-2">Age</label>
                                    <input type="number" id="guardian_age" name="guardian_age" value="<?php echo htmlspecialchars($applicant_data['guardian_age'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="guardian_sex" class="block text-gray-700 text-sm font-bold mb-2">Sex</label>
                                    <select id="guardian_sex" name="guardian_sex" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                        <option value="Male" <?php echo (isset($applicant_data['guardian_sex']) && $applicant_data['guardian_sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (isset($applicant_data['guardian_sex']) && $applicant_data['guardian_sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo (isset($applicant_data['guardian_sex']) && $applicant_data['guardian_sex'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="guardian_relationship" class="block text-gray-700 text-sm font-bold mb-2">Relationship</label>
                                    <input type="text" id="guardian_relationship" name="guardian_relationship" value="<?php echo htmlspecialchars($applicant_data['guardian_relationship'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="guardian_contact_number" class="block text-gray-700 text-sm font-bold mb-2">Contact Number</label>
                                    <input type="text" id="guardian_contact_number" name="guardian_contact_number" value="<?php echo htmlspecialchars($applicant_data['guardian_contact_number'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div>
                                    <label for="guardian_email_address" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                                    <input type="email" id="guardian_email_address" name="guardian_email_address" value="<?php echo htmlspecialchars($applicant_data['guardian_email_address'] ?? ''); ?>" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Applicant
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html></div>