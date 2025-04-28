<?php
session_start();
require_once 'config.php';
require_once 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$applicant_ID = "";
$error_message = "";
$success_message = "";
$applicant_data = [];
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $applicant_ID = (int)$_GET['id'];
    $sql = "SELECT a.*, p.*, g.*, ab.* 
            FROM " . TABLE_APPLICANTS . " a 
            LEFT JOIN " . TABLE_PERSONAL_INFO . " p ON a.applicant_ID = p.applicant_ID 
            LEFT JOIN " . TABLE_GUARDIAN_INFO . " g ON a.applicant_ID = g.applicant_ID 
            LEFT JOIN " . TABLE_ACADEMIC_BACKGROUND . " ab ON a.applicant_ID = ab.applicant_ID 
            WHERE a.applicant_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $applicant_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $applicant_data = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Applicant not found!";
        header('Location: admin_dashboard.php');
        exit;
    }
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid request. Applicant ID is required.";
    header('Location: admin_dashboard.php');
    exit;
}

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
    if (empty($data['email_address']) || !filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email address is required.";
    } else {
        $validated['email_address'] = $data['email_address'];
    }
    if (empty($data['contact_number']) || !preg_match("/^\+?\d{10,15}$/", $data['contact_number'])) {
        $errors[] = "Valid contact number (10-15 digits) is required.";
    } else {
        $validated['contact_number'] = $data['contact_number'];
    }
    $validated['middle_name'] = $data['middle_name'] ?? "";
    $validated['extension_name'] = in_array($data['extension_name'], ['Jr.', 'Sr.', 'III', 'IV', 'N/A']) ? $data['extension_name'] : "N/A";
    $validated['birthday'] = $data['birthday'] ?? "";
    $validated['country_of_birth'] = $data['country_of_birth'] ?? "";
    $validated['age'] = isset($data['age']) && $data['age'] >= 0 ? (int)$data['age'] : 0;
    $validated['sex'] = in_array($data['sex'], ['Male', 'Female', 'Other']) ? $data['sex'] : "";
    $validated['blood_type'] = in_array($data['blood_type'], ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']) ? $data['blood_type'] : "";
    $validated['civil_status'] = in_array($data['civil_status'], ['Single', 'Married', 'Divorced', 'Widowed']) ? $data['civil_status'] : "";
    $validated['religious_affiliation'] = $data['religious_affiliation'] ?? "";
    $validated['citizenship'] = $data['citizenship'] ?? "";
    $validated['number_of_siblings'] = isset($data['number_of_siblings']) && $data['number_of_siblings'] >= 0 ? (int)$data['number_of_siblings'] : 0;
    return $validated;
}

function handleFileUpload($file, $existing_path, &$errors) {
    if (isset($file) && $file['error'] == 0) {
        $max_size = 2 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            $errors[] = "File size exceeds 2MB limit.";
            return $existing_path;
        }
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = preg_replace("/[^a-zA-Z0-9._-]/", "", $file['name']);
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($filetype, $allowed)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            return $existing_path;
        }
        $new_name = uniqid() . "." . $filetype;
        $upload_dir = UPLOAD_DIR;
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
            if ($existing_path && file_exists($existing_path)) {
                unlink($existing_path);
            }
            return $upload_dir . $new_name;
        } else {
            $errors[] = "Error uploading file.";
        }
    }
    return $existing_path;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Invalid CSRF token.";
    } else {
        $applicant_ID = (int)$_POST['applicant_ID'];
        $errors = [];
        $applicant_data_post = validateInput($_POST, $errors);
        $applicant_data_post['picture'] = handleFileUpload($_FILES['picture'], $applicant_data['picture'], $errors);

        $personal_info = [
            'address' => trim($_POST['address'] ?? ''),
            'barangay' => trim($_POST['barangay'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'district' => trim($_POST['district'] ?? ''),
            'region' => trim($_POST['region'] ?? ''),
            'zip_code' => trim($_POST['zip_code'] ?? '')
        ];
        $guardian_data = [
            'guardian_first_name' => trim($_POST['guardian_first_name'] ?? ''),
            'guardian_last_name' => trim($_POST['guardian_last_name'] ?? ''),
            'guardian_middle_name' => trim($_POST['guardian_middle_name'] ?? ''),
            'guardian_extension_name' => trim($_POST['guardian_extension_name'] ?? 'N/A'),
            'guardian_age' => trim($_POST['guardian_age'] ?? 0),
            'guardian_sex' => trim($_POST['guardian_sex'] ?? ''),
            'guardian_relationship' => trim($_POST['guardian_relationship'] ?? ''),
            'guardian_contact_number' => trim($_POST['guardian_contact_number'] ?? ''),
            'guardian_email_address' => trim($_POST['guardian_email_address'] ?? '')
        ];

        if (!empty($errors)) {
            $error_message = implode("<br>", $errors);
        } else {
            $conn->begin_transaction();
            try {
                $stmt = $conn->prepare("SELECT applicant_ID FROM " . TABLE_APPLICANTS . " WHERE email_address = ? AND applicant_ID != ?");
                $stmt->bind_param("si", $applicant_data_post['email_address'], $applicant_ID);
                $stmt->execute();
                if ($stmt->get_result()->num_rows > 0) {
                    throw new Exception("Email address is already in use.");
                }
                $stmt->close();

                $sql = "UPDATE " . TABLE_APPLICANTS . " SET 
                        first_name = ?, last_name = ?, middle_name = ?, extension_name = ?, 
                        birthday = ?, country_of_birth = ?, age = ?, sex = ?, blood_type = ?, 
                        civil_status = ?, religious_affiliation = ?, citizenship = ?, 
                        number_of_siblings = ?, picture = ?, email_address = ?, contact_number = ?
                        WHERE applicant_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "ssssssisssssisssi",
                    $applicant_data_post['first_name'], $applicant_data_post['last_name'], 
                    $applicant_data_post['middle_name'], $applicant_data_post['extension_name'], 
                    $applicant_data_post['birthday'], $applicant_data_post['country_of_birth'], 
                    $applicant_data_post['age'], $applicant_data_post['sex'], $applicant_data_post['blood_type'], 
                    $applicant_data_post['civil_status'], $applicant_data_post['religious_affiliation'], 
                    $applicant_data_post['citizenship'], $applicant_data_post['number_of_siblings'], 
                    $applicant_data_post['picture'], $applicant_data_post['email_address'], 
                    $applicant_data_post['contact_number'], $applicant_ID
                );
                $stmt->execute();
                $stmt->close();

                $sql = "SELECT COUNT(*) as count FROM " . TABLE_PERSONAL_INFO . " WHERE applicant_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $applicant_ID);
                $stmt->execute();
                $count = $stmt->get_result()->fetch_assoc()['count'];
                if ($count > 0) {
                    $sql = "UPDATE " . TABLE_PERSONAL_INFO . " SET address = ?, barangay = ?, city = ?, district = ?, region = ?, zip_code = ? WHERE applicant_ID = ?";
                } else {
                    $sql = "INSERT INTO " . TABLE_PERSONAL_INFO . " (applicant_ID, address, barangay, city, district, region, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?)";
                }
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($count > 0 ? "sssisii" : "isssisi", 
                    $count > 0 ? $personal_info['address'] : $applicant_ID, $personal_info['address'], 
                    $personal_info['barangay'], $personal_info['city'], $personal_info['district'], 
                    $personal_info['region'], $personal_info['zip_code'], $count > 0 ? $applicant_ID : $personal_info['zip_code']
                );
                $stmt->execute();
                $stmt->close();

                $sql = "SELECT COUNT(*) as count FROM " . TABLE_GUARDIAN_INFO . " WHERE applicant_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $applicant_ID);
                $stmt->execute();
                $count = $stmt->get_result()->fetch_assoc()['count'];
                if ($count > 0) {
                    $sql = "UPDATE " . TABLE_GUARDIAN_INFO . " SET guardian_first_name = ?, guardian_last_name = ?, guardian_middle_name = ?, guardian_extension_name = ?, guardian_age = ?, guardian_sex = ?, guardian_relationship = ?, guardian_contact_number = ?, guardian_email_address = ? WHERE applicant_ID = ?";
                } else {
                    $sql = "INSERT INTO " . TABLE_GUARDIAN_INFO . " (applicant_ID, guardian_first_name, guardian_last_name, guardian_middle_name, guardian_extension_name, guardian_age, guardian_sex, guardian_relationship, guardian_contact_number, guardian_email_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                }
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($count > 0 ? "ssssissssi" : "issssissis", 
                    $count > 0 ? $guardian_data['guardian_first_name'] : $applicant_ID, $guardian_data['guardian_first_name'], 
                    $guardian_data['guardian_last_name'], $guardian_data['guardian_middle_name'], 
                    $guardian_data['guardian_extension_name'], $guardian_data['guardian_age'], 
                    $guardian_data['guardian_sex'], $guardian_data['guardian_relationship'], 
                    $guardian_data['guardian_contact_number'], $guardian_data['guardian_email_address'], 
                    $count > 0 ? $applicant_ID : $guardian_data['guardian_email_address']
                );
                $stmt->execute();
                $stmt->close();

                $log_sql = "INSERT INTO " . TABLE_AUDIT_LOG . " (action, user_id, applicant_id, timestamp) VALUES (?, ?, ?, NOW())";
                $log_stmt = $conn->prepare($log_sql);
                $action = "Updated applicant";
                $user_id = $_SESSION['admin_id'];
                $log_stmt->bind_param("sii", $action, $user_id, $applicant_ID);
                $log_stmt->execute();
                $log_stmt->close();

                $conn->commit();
                $success_message = "Applicant updated successfully!";

                $sql = "SELECT a.*, p.*, g.* 
                        FROM " . TABLE_APPLICANTS . " a 
                        LEFT JOIN " . TABLE_PERSONAL_INFO . " p ON a.applicant_ID = p.applicant_ID 
                        LEFT JOIN " . TABLE_GUARDIAN_INFO . " g ON a.applicant_ID = g.applicant_ID 
                        WHERE a.applicant_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $applicant_ID);
                $stmt->execute();
                $applicant_data = $stmt->get_result()->fetch_assoc();
                $stmt->close();
            } catch (Exception $e) {
                $conn->rollback();
                error_log("Error updating applicant ID $applicant_ID: " . $e->getMessage());
                $error_message = "Failed to update applicant: " . $e->getMessage();
            }
        }
    }
}

function renderInput($label, $id, $name, $value = '', $type = 'text', $required = false) {
    $value = htmlspecialchars($value ?? '');
    $requiredAttr = $required ? 'required' : '';
    return "
        <div>
            <label for='$id' class='block text-gray-700 text-sm font-bold mb-2'>$label</label>
            <input type='$type' id='$id' name='$name' value='$value' 
                class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline' $requiredAttr>
        </div>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant - TOM YANG College</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .section-content.hidden { display: none; }
    </style>
</head>
<body class="bg-purple-200">
    <div class="bg-indigo-200 flex justify-between items-center p-3 h-24">
        <img src="logo.png" alt="Logo" class="h-20 ml-3">
        <div class="mr-3 flex items-center">
            <img src="phone.png" alt="Phone" class="h-12 ml-4 cursor-pointer" aria-label="Contact Support">
            <img src="bell.png" alt="Notifications" class="h-12 ml-4 cursor-pointer" aria-label="Notifications">
            <img src="user.png" alt="User Profile" class="h-12 ml-4 cursor-pointer" aria-label="User Profile">
        </div>
    </div>

    <div class="flex">
        <div class="bg-indigo-200 p-4 h-screen w-48 flex flex-col items-center">
            <h2 class="text-center font-bold text-2xl mb-5 text-black">ADMIN</h2>
            <a href="admin_dashboard.php" class="bg-purple-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-purple-700">Dashboard</a>
            <a href="logout.php" class="bg-red-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-red-700">Logout</a>
        </div>

        <div class="flex-1 p-4 overflow-auto">
            <div class="bg-purple-500 rounded-lg max-w-6xl mx-auto p-5">
                <h1 class="text-white text-2xl font-bold mb-4">Edit Applicant</h1>

                <?php if (!empty($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $applicant_ID); ?>" class="bg-white p-6 rounded-lg" enctype="multipart/form-data">
                    <input type="hidden" name="applicant_ID" value="<?php echo $applicant_ID; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                    <div class="mb-6">
                        <h2 class="text-lg font-bold mb-4 pb-2 border-b-2 border-gray-200 cursor-pointer" onclick="toggleSection('personal-info')">Personal Information</h2>
                        <div id="personal-info" class="section-content grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                            echo renderInput('First Name *', 'first_name', 'first_name', $applicant_data['first_name'] ?? '', 'text', true);
                            echo renderInput('Last Name *', 'last_name', 'last_name', $applicant_data['last_name'] ?? '', 'text', true);
                            echo renderInput('Email Address *', 'email_address', 'email_address', $applicant_data['email_address'] ?? '', 'email', true);
                            echo renderInput('Contact Number *', 'contact_number', 'contact_number', $applicant_data['contact_number'] ?? '', 'tel', true);
                            ?>
                            <div>
                                <label for="sex" class="block text-gray-700 text-sm font-bold mb-2">Sex</label>
                                <select id="sex" name="sex" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Select</option>
                                    <option value="Male" <?php echo ($applicant_data['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($applicant_data['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($applicant_data['sex'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-lg font-bold mb-4 pb-2 border-b-2 border-gray-200 cursor-pointer" onclick="toggleSection('address-info')">Address Information</h2>
                        <div id="address-info" class="section-content grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                            echo renderInput('Address', 'address', 'address', $applicant_data['address'] ?? '');
                            echo renderInput('City', 'city', 'city', $applicant_data['city'] ?? '');
                            ?>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-lg font-bold mb-4 pb-2 border-b-2 border-gray-200 cursor-pointer" onclick="toggleSection('guardian-info')">Guardian Information</h2>
                        <div id="guardian-info" class="section-content grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                            echo renderInput('Guardian First Name', 'guardian_first_name', 'guardian_first_name', $applicant_data['guardian_first_name'] ?? '');
                            echo renderInput('Guardian Last Name', 'guardian_last_name', 'guardian_last_name', $applicant_data['guardian_last_name'] ?? '');
                            ?>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Applicant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSection(sectionId) {
            document.getElementById(sectionId).classList.toggle('hidden');
        }
        setTimeout(() => {
            const success = document.querySelector('.bg-green-100');
            if (success) success.style.display = 'none';
        }, 3000);
    </script>
</body>
</html>