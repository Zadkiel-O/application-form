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
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
        }

        .close {
            position: absolute;
            right: 25px;
            top: 10px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-purple-200">
    <!-- Image Preview Modal -->
    <div id="imageModal" class="modal flex items-center justify-center">
        <span class="close">&times;</span>
        <img id="modalImage" class="modal-content">
    </div>

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
            <a href="admin_page.php" class="bg-gray-300 p-2 mt-2 text-center font-bold w-full rounded text-black">Applicant List</a>
            <a href="admin_dashboard.php" class="bg-purple-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-purple-700">Dashboard</a>
            <a href="logout.php" class="bg-red-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-red-700">Logout</a>
        </div>

        <div class="flex-1 p-8">
            <div class="bg-purple-500 rounded-lg max-w-6xl mx-auto p-5 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-white text-2xl font-bold">Applicant Details</h1>
                    <div>
                        <a href="admin_page.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-block mr-2">
                            Back to List
                        </a>
                        <a href="edit_applicant.php?id=<?php echo $id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                            Edit Details
                        </a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
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
                                        <p class="font-bold">No. of Siblings:</p>
                                        <p><?php echo $applicant['no_of_siblings']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/4 flex flex-col items-center">
                                <p class="font-bold mb-2">Profile Photo:</p>
                                <?php if(!empty($applicant['profile_photo']) && file_exists("uploads/" . $applicant['profile_photo'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($applicant['profile_photo']); ?>" 
                                         alt="Applicant Photo" 
                                         class="border-2 border-gray-300 w-40 h-48 object-cover cursor-pointer hover:opacity-80 transition-opacity"
                                         onclick="showModal(this.src)">
                                <?php else: ?>
                                    <div class="border-2 border-dashed border-gray-300 w-40 h-48 flex items-center justify-center text-gray-500">
                                        No Photo Available
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="border-b-2 border-purple-500 pb-4 mb-6">
                        <h2 class="text-xl font-bold text-purple-800 mb-4">B. Applicant's Address and Contact Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-bold">House/Room/Bldg:</p>
                                <p><?php echo htmlspecialchars($applicant['house']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Barangay:</p>
                                <p><?php echo htmlspecialchars($applicant['barangay']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">City:</p>
                                <p><?php echo htmlspecialchars($applicant['city']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">District:</p>
                                <p><?php echo htmlspecialchars($applicant['district']); ?></p>
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
                                <p class="font-bold">Guardian's Full Name:</p>
                                <p><?php echo htmlspecialchars($applicant['guardian_first_name'] . ' ' . $applicant['guardian_middle_name'] . ' ' . $applicant['guardian_last_name'] . ' ' . $applicant['guardian_extension_name']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Guardian's Age:</p>
                                <p><?php echo htmlspecialchars($applicant['guardian_age']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Guardian's Sex:</p>
                                <p><?php echo htmlspecialchars($applicant['guardian_sex']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Guardian's Relationship:</p>
                                <p><?php echo htmlspecialchars($applicant['guardian_relationship']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Guardian's Address:</p>
                                <p><?php echo htmlspecialchars($applicant['guardian_address']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Guardian's Contact Number:</p>
                                <p><?php echo htmlspecialchars($applicant['guardian_contact_number']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Guardian's Email:</p>
                                <p><?php echo htmlspecialchars($applicant['guardian_email']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="border-b-2 border-purple-500 pb-4 mb-6">
                        <h2 class="text-xl font-bold text-purple-800 mb-4">D. Educational Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-bold">Grade 12 School:</p>
                                <p><?php echo htmlspecialchars($applicant['grade12_school']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 12 Period:</p>
                                <p><?php echo htmlspecialchars($applicant['grade12_period']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 12 GWA:</p>
                                <p><?php echo htmlspecialchars($applicant['grade12_gwa']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 11 School:</p>
                                <p><?php echo htmlspecialchars($applicant['grade11_school']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 11 Period:</p>
                                <p><?php echo htmlspecialchars($applicant['grade11_period']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 11 GWA:</p>
                                <p><?php echo htmlspecialchars($applicant['grade11_gwa']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 10 School:</p>
                                <p><?php echo htmlspecialchars($applicant['grade10_school']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 10 Period:</p>
                                <p><?php echo htmlspecialchars($applicant['grade10_period']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 9 School:</p>
                                <p><?php echo htmlspecialchars($applicant['grade9_school']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 9 Period:</p>
                                <p><?php echo htmlspecialchars($applicant['grade9_period']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 8 School:</p>
                                <p><?php echo htmlspecialchars($applicant['grade8_school']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 8 Period:</p>
                                <p><?php echo htmlspecialchars($applicant['grade8_period']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 7 School:</p>
                                <p><?php echo htmlspecialchars($applicant['grade7_school']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Grade 7 Period:</p>
                                <p><?php echo htmlspecialchars($applicant['grade7_period']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="border-b-2 border-purple-500 pb-4 mb-6">
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
                            <div>
                                <p class="font-bold">Alternative Course 1:</p>
                                <p><?php echo htmlspecialchars($applicant['course_1']); ?></p>
                            </div>
                            <div>
                                <p class="font-bold">Alternative Course 2:</p>
                                <p><?php echo htmlspecialchars($applicant['course_2']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="border-b-2 border-purple-500 pb-4 mb-6">
                        <h2 class="text-xl font-bold text-purple-800 mb-4">F. Signatures</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-bold mb-2">Student Signature:</p>
                                <?php if(!empty($applicant['student_signature']) && file_exists("uploads/" . $applicant['student_signature'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($applicant['student_signature']); ?>" 
                                         alt="Student Signature" 
                                         class="border-2 border-gray-300 w-40 h-32 object-contain cursor-pointer hover:opacity-80 transition-opacity"
                                         onclick="showModal(this.src)">
                                <?php else: ?>
                                    <div class="border-2 border-dashed border-gray-300 w-40 h-32 flex items-center justify-center text-gray-500">
                                        No Signature Available
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-bold mb-2">Guardian Signature:</p>
                                <?php if(!empty($applicant['guardian_signature']) && file_exists("uploads/" . $applicant['guardian_signature'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($applicant['guardian_signature']); ?>" 
                                         alt="Guardian Signature" 
                                         class="border-2 border-gray-300 w-40 h-32 object-contain cursor-pointer hover:opacity-80 transition-opacity"
                                         onclick="showModal(this.src)">
                                <?php else: ?>
                                    <div class="border-2 border-dashed border-gray-300 w-40 h-32 flex items-center justify-center text-gray-500">
                                        No Signature Available
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-purple-800 mb-4">G. Application Status</h2>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="flex items-center space-x-4">
                                <form action="update_status.php" method="POST" class="flex items-center space-x-4">
                                    <input type="hidden" name="applicant_id" value="<?php echo $id; ?>">
                                    <select name="status" class="p-2 border border-gray-300 rounded" onchange="this.form.submit()">
                                        <option value="pending" <?php echo (isset($applicant['status']) && $applicant['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="approved" <?php echo (isset($applicant['status']) && $applicant['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                                        <option value="rejected" <?php echo (isset($applicant['status']) && $applicant['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                    <span class="font-bold">Current Status: </span>
                                    <span class="<?php 
                                        echo isset($applicant['status']) ? 
                                            ($applicant['status'] == 'approved' ? 'text-green-600' : 
                                            ($applicant['status'] == 'rejected' ? 'text-red-600' : 'text-yellow-600')) 
                                            : 'text-yellow-600'; 
                                    ?>">
                                        <?php echo isset($applicant['status']) ? ucfirst($applicant['status']) : 'Pending'; ?>
                                    </span>
                                </form>
                            </div>
                            <?php if (isset($applicant['status_updated_at'])): ?>
                                <div class="text-sm text-gray-600">
                                    Last Updated: <?php echo date('F j, Y g:i A', strtotime($applicant['status_updated_at'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById("imageModal");
        const modalImg = document.getElementById("modalImage");
        const span = document.getElementsByClassName("close")[0];

        function showModal(src) {
            modal.style.display = "flex";
            modalImg.src = src;
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>