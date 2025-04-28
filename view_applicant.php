<?php
$conn = require_once 'db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_panel.php");
    exit;
}

$id = (int)$_GET['id'];

$sql = "SELECT * FROM applicants WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin_panel.php");
    exit;
}

$applicant = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicant - TOM YANG College</title>
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
            <a href="admin_panel.php" class="bg-gray-300 p-2 mt-2 text-center font-bold w-full rounded text-black">Applicant List</a>
            <a href="admin_dashboard.php" class="bg-purple-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-purple-700">Dashboard</a>
            <a href="logout.php" class="bg-red-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-red-700">Logout</a>
        </div>

       
        <div class="bg-purple-500 rounded-lg w-4/5 max-w-6xl mx-auto p-5 mt-4 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-white text-2xl font-bold">Applicant Details</h1>
                <div>
                    <a href="admin_panel.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-block mr-2">
                        Back to List
                    </a>
                    <a href="edit_applicant.php?id=<?php echo $id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                        Edit Details
                    </a>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg">
                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">A. Personal Information</h2>
                    
                    <div class="flex flex-wrap">
                        <div class="w-full md:w-3/4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="font-bold">Full Name:</p>
                                    <p><?php echo htmlspecialchars($applicant['first_name'] . ' ' . $applicant['middle_name'] . ' ' . $applicant['last_name'] . ' ' . $applicant['extension_name']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Date of Birth:</p>
                                    <p><?php echo date('F j, Y', strtotime($applicant['date_of_birth'])); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Place of Birth:</p>
                                    <p><?php echo htmlspecialchars($applicant['place_of_birth']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Age:</p>
                                    <p><?php echo $applicant['age']; ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Sex:</p>
                                    <p><?php echo htmlspecialchars($applicant['sex']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Blood Type:</p>
                                    <p><?php echo htmlspecialchars($applicant['blood_type']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Civil Status:</p>
                                    <p><?php echo htmlspecialchars($applicant['civil_status']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Religious Affiliation:</p>
                                    <p><?php echo htmlspecialchars($applicant['religious_affiliation']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Citizenship:</p>
                                    <p><?php echo htmlspecialchars($applicant['citizenship']); ?></p>
                                </div>
                                <div>
                                    <p class="font-bold">Number of Siblings:</p>
                                    <p><?php echo $applicant['no_of_siblings']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/4 flex justify-center md:justify-end">
                            <?php if(!empty($applicant['photo'])): ?>
                                <img src="<?php echo htmlspecialchars($applicant['photo']); ?>" alt="Applicant Photo" class="border-2 border-gray-300 w-40 h-48 object-cover">
                            <?php else: ?>
                                <div class="border-2 border-dashed border-gray-300 w-40 h-48 flex items-center justify-center text-gray-500">
                                    No Photo
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">B. Address and Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-bold">Complete Address:</p>
                            <p><?php echo htmlspecialchars($applicant['house'] . ', ' . $applicant['barangay'] . ', ' . $applicant['city'] . ', ' . $applicant['district']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Zip Code:</p>
                            <p><?php echo htmlspecialchars($applicant['zip_code']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Personal Number:</p>
                            <p><?php echo htmlspecialchars($applicant['personal_number']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Personal Email:</p>
                            <p><?php echo htmlspecialchars($applicant['personal_email']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Landline Number:</p>
                            <p><?php echo htmlspecialchars($applicant['landline_number']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">C. Guardian Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-bold">Guardian Name:</p>
                            <p><?php echo htmlspecialchars($applicant['guardian_first_name'] . ' ' . $applicant['guardian_middle_name'] . ' ' . $applicant['guardian_last_name'] . ' ' . $applicant['guardian_extension_name']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Age:</p>
                            <p><?php echo $applicant['guardian_age']; ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Sex:</p>
                            <p><?php echo htmlspecialchars($applicant['guardian_sex']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Relationship:</p>
                            <p><?php echo htmlspecialchars($applicant['guardian_relationship']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Guardian Contact:</p>
                            <p><?php echo htmlspecialchars($applicant['guardian_contact_number']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Guardian Email:</p>
                            <p><?php echo htmlspecialchars($applicant['guardian_email']); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Educational Information Section -->
                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">D. Educational Information</h2>
                    <!-- Education details -->
                </div>
                
                <!-- College/Course Information Section -->
                <div>
                    <h2 class="text-xl font-bold text-purple-800 mb-4">E. College and Course</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-bold">Selected College:</p>
                            <p><?php echo htmlspecialchars($applicant['college_offered']); ?></p>
                        </div>
                        <div>
                            <p class="font-bold">Selected Course:</p>
                            <p><?php echo htmlspecialchars($applicant['course_offered']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>