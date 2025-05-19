<?php
$conn = require_once 'db_connect.php';

// Get applicant data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM applicants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applicant = $result->fetch_assoc();

    if (!$applicant) {
        die("Applicant not found");
    }
} else {
    die("No applicant ID provided");
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
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
    
    $personal_email = $_POST['personal_email'];
    $personal_number = $_POST['personal_number'];
    $house = $_POST['house'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $zip_code = $_POST['zip_code'];
    $landline_number = $_POST['landline_number'];
    
    // Guardian information
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
    
    // Educational information
    $grade12_school = $_POST['grade12_school'];
    $grade12_period = $_POST['grade12_period'];
    $grade12_gwa = $_POST['grade12_gwa'];
    $grade11_school = $_POST['grade11_school'];
    $grade11_period = $_POST['grade11_period'];
    $grade11_gwa = $_POST['grade11_gwa'];
    $grade10_school = $_POST['grade10_school'];
    $grade10_period = $_POST['grade10_period'];
    $grade9_school = $_POST['grade9_school'];
    $grade9_period = $_POST['grade9_period'];
    $grade8_school = $_POST['grade8_school'];
    $grade8_period = $_POST['grade8_period'];
    $grade7_school = $_POST['grade7_school'];
    $grade7_period = $_POST['grade7_period'];
    
    // College and course information
    $college_offered = $_POST['college_offered'];
    $course_offered = $_POST['course_offered'];
    $course_1 = $_POST['course_1'];
    $course_2 = $_POST['course_2'];

    $update_sql = "UPDATE applicants SET 
        first_name = ?,
        middle_name = ?,
        last_name = ?,
        extension_name = ?,
        date_of_birth = ?,
        place_of_birth = ?,
        age = ?,
        sex = ?,
        blood_type = ?,
        civil_status = ?,
        religious_affiliation = ?,
        citizenship = ?,
        no_of_siblings = ?,
        personal_email = ?,
        personal_number = ?,
        house = ?,
        barangay = ?,
        city = ?,
        district = ?,
        zip_code = ?,
        landline_number = ?,
        guardian_first_name = ?,
        guardian_middle_name = ?,
        guardian_last_name = ?,
        guardian_extension_name = ?,
        guardian_age = ?,
        guardian_sex = ?,
        guardian_relationship = ?,
        guardian_address = ?,
        guardian_contact_number = ?,
        guardian_email = ?,
        grade12_school = ?,
        grade12_period = ?,
        grade12_gwa = ?,
        grade11_school = ?,
        grade11_period = ?,
        grade11_gwa = ?,
        grade10_school = ?,
        grade10_period = ?,
        grade9_school = ?,
        grade9_period = ?,
        grade8_school = ?,
        grade8_period = ?,
        grade7_school = ?,
        grade7_period = ?,
        college_offered = ?,
        course_offered = ?,
        course_1 = ?,
        course_2 = ?
        WHERE id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssssssssissssssssssssssssssssssssssssssssssssi", 
        $first_name, $middle_name, $last_name, $extension_name,
        $date_of_birth, $place_of_birth, $age, $sex,
        $blood_type, $civil_status, $religious_affiliation, $citizenship, $no_of_siblings,
        $personal_email, $personal_number, $house,
        $barangay, $city, $district, $zip_code, $landline_number,
        $guardian_first_name, $guardian_middle_name, $guardian_last_name, $guardian_extension_name,
        $guardian_age, $guardian_sex, $guardian_relationship, $guardian_address,
        $guardian_contact_number, $guardian_email,
        $grade12_school, $grade12_period, $grade12_gwa,
        $grade11_school, $grade11_period, $grade11_gwa,
        $grade10_school, $grade10_period,
        $grade9_school, $grade9_period,
        $grade8_school, $grade8_period,
        $grade7_school, $grade7_period,
        $college_offered, $course_offered, $course_1, $course_2, $id
    );

    if ($stmt->execute()) {
        header("Location: admin_page.php?success=1");
        exit();
    } else {
        $error = "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="font-[Roboto] h-full flex flex-1 overflow-auto box-border">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant - TOM YANG College</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: '#B0B0FF',
                        container: '#B8D0B8',
                        header: '#7b0c8c7a',
                        section: '#000059',
                        cancel: '#FF0004',
                        proceed: '#00AB42'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
</head>
<body class="font-[Roboto] h-full flex flex-1 overflow-auto box-border bg-gray-100">
    <!-- Template-based Admin Sidebar -->
    <aside class="max-sm:w-0 max-sm:px-0 w-20 bg-[linear-gradient(to_bottom,_#6a11cb,_#a044ff)] text-white pt-3 pb-4 flex flex-col gap-4 [transition:width_0.3s_ease] overflow-auto sm:hover:w-72 sm:hover:items-start group">
        <nav class="flex flex-col w-full overflow-auto">
            <div class="text-white no-underline flex items-center whitespace-nowrap overflow-hidden [transition:background_0.3s] rounded-md pt-3 pb-2 font-bold text-xl leading-[1.2]">
                <img class="ml-4 mr-2 w-12 h-auto max-w-none" src="logo.png" alt="Logo"><span name="sidebar-text" class="opacity-0 [transition:opacity_0.3s_ease,_margin-left_0.3s_ease] ml-0 sm:group-hover:opacity-100 sm:group-hover:ml-1">TOM YANG<br>COLLEGE</span>
            </div>
            <div class="mt-3 overflow-y-auto overflow-x-hidden">
                <!-- Admin Navigation Items -->
                <a href="admin_page.php" class="mb-2 ml-4 mr-6 text-white no-underline flex items-center font-medium whitespace-nowrap overflow-hidden p-3 [transition:background_0.3s] rounded-md hover:bg-[rgba(0,_0,_0,_0.3)]">
                    <img class="w-5 [transition:margin_0.3s_ease] sm:group-hover:mr-3 max-w-none filter brightness-0 invert" src="assets/core/Applications-Icon.png" onerror="this.src='user.png'; this.onerror=null;">
                    <span name="sidebar-text" class="text-base opacity-0 [transition:opacity_0.3s_ease,_margin-left_0.3s_ease] ml-0 sm:group-hover:opacity-100 sm:group-hover:ml-1">Applicant Form</span>
                </a>
                <a href="logout.php" class="mb-2 ml-4 mr-6 text-white no-underline flex items-center font-medium whitespace-nowrap overflow-hidden p-3 [transition:background_0.3s] rounded-md hover:bg-[rgba(0,_0,_0,_0.3)]">
                    <img class="w-5 [transition:margin_0.3s_ease] sm:group-hover:mr-3 max-w-none filter brightness-0 invert" src="assets/core/Logout-Icon.png" onerror="this.src='user.png'; this.onerror=null;">
                    <span name="sidebar-text" class="text-base opacity-0 [transition:opacity_0.3s_ease,_margin-left_0.3s_ease] ml-0 sm:group-hover:opacity-100 sm:group-hover:ml-1">Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <section class="flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        <!-- Header from template -->
        <header class="bg-[linear-gradient(to_right,_#6a11cb,_#a044ff)] h-24 flex items-center justify-between pr-10 py-3">
            <div class="flex items-center">
                <button id="menu-button" class="sm:hidden ml-3 p-3 min-w-11 [transition:background_0.3s] rounded-md hover:bg-[rgba(0,_0,_0,_0.3)]">
                    <img class="h-5 cursor-pointer filter brightness-0 invert" src="assets/core/Menu-Icon.png" alt="Menu" onerror="this.src='phone.png'; this.onerror=null;">
                </button>
                <h1 class="max-sm:ml-3 ml-7 whitespace-nowrap text-ellipsis text-3xl text-white font-bold">Edit Applicant</h1>
            </div>
            <div class="max-sm:hidden flex">
                <img class="h-5 ml-5 cursor-pointer filter brightness-0 invert" src="assets/core/Phone-Icon.png" alt="Phone" onerror="this.src='phone.png'; this.onerror=null;">
                <img class="h-5 ml-5 cursor-pointer filter brightness-0 invert" src="assets/core/Notification-Icon.png" alt="Notifications" onerror="this.src='bell.png'; this.onerror=null;">
                <img class="h-5 ml-5 cursor-pointer filter brightness-0 invert" src="assets/core/Profile-Icon.png" alt="Profile" onerror="this.src='user.png'; this.onerror=null;">
            </div>
        </header>

        <main class="flex flex-col h-full overflow-auto">
            <div class="bg-white border border-solid border-black rounded-xl rounded-tr-none rounded-br-none m-3 px-6 py-5 overflow-auto">
                <!-- Main content -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h1 class="text-2xl font-bold mb-6">Edit Applicant</h1>
                    
                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" value="<?php echo htmlspecialchars($applicant['first_name']); ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                                <input type="text" name="middle_name" value="<?php echo htmlspecialchars($applicant['middle_name']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" value="<?php echo htmlspecialchars($applicant['last_name']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Extension Name</label>
                                <input type="text" name="extension_name" value="<?php echo htmlspecialchars($applicant['extension_name']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($applicant['date_of_birth']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Place of Birth</label>
                                <input type="text" name="place_of_birth" value="<?php echo htmlspecialchars($applicant['place_of_birth']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Age</label>
                                <input type="number" name="age" value="<?php echo htmlspecialchars($applicant['age']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sex</label>
                                <select name="sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                                    <option value="Male" <?php echo $applicant['sex'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo $applicant['sex'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Blood Type</label>
                                <input type="text" name="blood_type" value="<?php echo htmlspecialchars($applicant['blood_type']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Civil Status</label>
                                <input type="text" name="civil_status" value="<?php echo htmlspecialchars($applicant['civil_status']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Religious Affiliation</label>
                                <input type="text" name="religious_affiliation" value="<?php echo htmlspecialchars($applicant['religious_affiliation']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Citizenship</label>
                                <input type="text" name="citizenship" value="<?php echo htmlspecialchars($applicant['citizenship']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. of Siblings</label>
                                <input type="number" name="no_of_siblings" value="<?php echo htmlspecialchars($applicant['no_of_siblings']); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                        </div>

                        <!-- Address and Contact Information -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h2 class="text-lg font-semibold mb-4">Address and Contact Information</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Personal Email</label>
                                    <input type="email" name="personal_email" value="<?php echo htmlspecialchars($applicant['personal_email']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Personal Number</label>
                                    <input type="text" name="personal_number" value="<?php echo htmlspecialchars($applicant['personal_number']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">House/Building</label>
                                    <input type="text" name="house" value="<?php echo htmlspecialchars($applicant['house']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Barangay</label>
                                    <input type="text" name="barangay" value="<?php echo htmlspecialchars($applicant['barangay']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="city" value="<?php echo htmlspecialchars($applicant['city']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <input type="text" name="district" value="<?php echo htmlspecialchars($applicant['district']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Zip Code</label>
                                    <input type="text" name="zip_code" value="<?php echo htmlspecialchars($applicant['zip_code']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Landline Number</label>
                                    <input type="text" name="landline_number" value="<?php echo htmlspecialchars($applicant['landline_number']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h2 class="text-lg font-semibold mb-4">Guardian Information</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's First Name</label>
                                    <input type="text" name="guardian_first_name" value="<?php echo htmlspecialchars($applicant['guardian_first_name']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Middle Name</label>
                                    <input type="text" name="guardian_middle_name" value="<?php echo htmlspecialchars($applicant['guardian_middle_name']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Last Name</label>
                                    <input type="text" name="guardian_last_name" value="<?php echo htmlspecialchars($applicant['guardian_last_name']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Extension Name</label>
                                    <input type="text" name="guardian_extension_name" value="<?php echo htmlspecialchars($applicant['guardian_extension_name']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Age</label>
                                    <input type="number" name="guardian_age" value="<?php echo htmlspecialchars($applicant['guardian_age']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Sex</label>
                                    <select name="guardian_sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                        <option value="Male" <?php echo $applicant['guardian_sex'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo $applicant['guardian_sex'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Relationship</label>
                                    <input type="text" name="guardian_relationship" value="<?php echo htmlspecialchars($applicant['guardian_relationship']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Address</label>
                                    <input type="text" name="guardian_address" value="<?php echo htmlspecialchars($applicant['guardian_address']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Contact Number</label>
                                    <input type="text" name="guardian_contact_number" value="<?php echo htmlspecialchars($applicant['guardian_contact_number']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Guardian's Email</label>
                                    <input type="email" name="guardian_email" value="<?php echo htmlspecialchars($applicant['guardian_email']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                            </div>
                        </div>

                        <!-- Educational Information -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h2 class="text-lg font-semibold mb-4">Educational Information</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 12 School</label>
                                    <input type="text" name="grade12_school" value="<?php echo htmlspecialchars($applicant['grade12_school']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 12 Period</label>
                                    <input type="text" name="grade12_period" value="<?php echo htmlspecialchars($applicant['grade12_period']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 12 GWA</label>
                                    <input type="text" name="grade12_gwa" value="<?php echo htmlspecialchars($applicant['grade12_gwa']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 11 School</label>
                                    <input type="text" name="grade11_school" value="<?php echo htmlspecialchars($applicant['grade11_school']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 11 Period</label>
                                    <input type="text" name="grade11_period" value="<?php echo htmlspecialchars($applicant['grade11_period']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 11 GWA</label>
                                    <input type="text" name="grade11_gwa" value="<?php echo htmlspecialchars($applicant['grade11_gwa']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 10 School</label>
                                    <input type="text" name="grade10_school" value="<?php echo htmlspecialchars($applicant['grade10_school']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 10 Period</label>
                                    <input type="text" name="grade10_period" value="<?php echo htmlspecialchars($applicant['grade10_period']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 9 School</label>
                                    <input type="text" name="grade9_school" value="<?php echo htmlspecialchars($applicant['grade9_school']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 9 Period</label>
                                    <input type="text" name="grade9_period" value="<?php echo htmlspecialchars($applicant['grade9_period']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 8 School</label>
                                    <input type="text" name="grade8_school" value="<?php echo htmlspecialchars($applicant['grade8_school']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 8 Period</label>
                                    <input type="text" name="grade8_period" value="<?php echo htmlspecialchars($applicant['grade8_period']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 7 School</label>
                                    <input type="text" name="grade7_school" value="<?php echo htmlspecialchars($applicant['grade7_school']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grade 7 Period</label>
                                    <input type="text" name="grade7_period" value="<?php echo htmlspecialchars($applicant['grade7_period']); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                                </div>
                            </div>
                        </div>

                        <!-- College and Course Information -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h2 class="text-lg font-semibold mb-4">College and Course Information</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alternative Course 1</label>
                                    <select name="course_1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                                        <option value="">Select Course 1</option>
                                        <optgroup label="Technology Courses">
                                            <option value="Information Technology (IT)" <?php echo $applicant['course_1'] === 'Information Technology (IT)' ? 'selected' : ''; ?>>Information Technology (IT)</option>
                                            <option value="Computer Science" <?php echo $applicant['course_1'] === 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                            <option value="Computer Engineering" <?php echo $applicant['course_1'] === 'Computer Engineering' ? 'selected' : ''; ?>>Computer Engineering</option>
                                            <option value="Information Systems" <?php echo $applicant['course_1'] === 'Information Systems' ? 'selected' : ''; ?>>Information Systems</option>
                                        </optgroup>
                                        <optgroup label="Business Courses">
                                            <option value="Marketing Management" <?php echo $applicant['course_1'] === 'Marketing Management' ? 'selected' : ''; ?>>Marketing Management</option>
                                            <option value="Business Administration" <?php echo $applicant['course_1'] === 'Business Administration' ? 'selected' : ''; ?>>Business Administration</option>
                                            <option value="Accounting" <?php echo $applicant['course_1'] === 'Accounting' ? 'selected' : ''; ?>>Accounting</option>
                                            <option value="Economics" <?php echo $applicant['course_1'] === 'Economics' ? 'selected' : ''; ?>>Economics</option>
                                        </optgroup>
                                        <optgroup label="Education Courses">
                                            <option value="Special Education (SPED)" <?php echo $applicant['course_1'] === 'Special Education (SPED)' ? 'selected' : ''; ?>>Special Education (SPED)</option>
                                            <option value="Elementary Education" <?php echo $applicant['course_1'] === 'Elementary Education' ? 'selected' : ''; ?>>Elementary Education</option>
                                            <option value="Secondary Education" <?php echo $applicant['course_1'] === 'Secondary Education' ? 'selected' : ''; ?>>Secondary Education</option>
                                            <option value="Early Childhood Education" <?php echo $applicant['course_1'] === 'Early Childhood Education' ? 'selected' : ''; ?>>Early Childhood Education</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alternative Course 2</label>
                                    <select name="course_2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                                        <option value="">Select Course 2</option>
                                        <optgroup label="Technology Courses">
                                            <option value="Information Technology (IT)" <?php echo $applicant['course_2'] === 'Information Technology (IT)' ? 'selected' : ''; ?>>Information Technology (IT)</option>
                                            <option value="Computer Science" <?php echo $applicant['course_2'] === 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                            <option value="Computer Engineering" <?php echo $applicant['course_2'] === 'Computer Engineering' ? 'selected' : ''; ?>>Computer Engineering</option>
                                            <option value="Information Systems" <?php echo $applicant['course_2'] === 'Information Systems' ? 'selected' : ''; ?>>Information Systems</option>
                                        </optgroup>
                                        <optgroup label="Business Courses">
                                            <option value="Marketing Management" <?php echo $applicant['course_2'] === 'Marketing Management' ? 'selected' : ''; ?>>Marketing Management</option>
                                            <option value="Business Administration" <?php echo $applicant['course_2'] === 'Business Administration' ? 'selected' : ''; ?>>Business Administration</option>
                                            <option value="Accounting" <?php echo $applicant['course_2'] === 'Accounting' ? 'selected' : ''; ?>>Accounting</option>
                                            <option value="Economics" <?php echo $applicant['course_2'] === 'Economics' ? 'selected' : ''; ?>>Economics</option>
                                        </optgroup>
                                        <optgroup label="Education Courses">
                                            <option value="Special Education (SPED)" <?php echo $applicant['course_2'] === 'Special Education (SPED)' ? 'selected' : ''; ?>>Special Education (SPED)</option>
                                            <option value="Elementary Education" <?php echo $applicant['course_2'] === 'Elementary Education' ? 'selected' : ''; ?>>Elementary Education</option>
                                            <option value="Secondary Education" <?php echo $applicant['course_2'] === 'Secondary Education' ? 'selected' : ''; ?>>Secondary Education</option>
                                            <option value="Early Childhood Education" <?php echo $applicant['course_2'] === 'Early Childhood Education' ? 'selected' : ''; ?>>Early Childhood Education</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="admin_page.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </section>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-button')?.addEventListener('click', function() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('max-sm:w-0');
            sidebar.classList.toggle('max-sm:w-72');
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
