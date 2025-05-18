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
    $personal_email = $_POST['personal_email'];
    $personal_number = $_POST['personal_number'];
    $house = $_POST['house'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
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
        personal_email = ?,
        personal_number = ?,
        house = ?,
        barangay = ?,
        city = ?,
        course_1 = ?,
        course_2 = ?
        WHERE id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssssssssssi", 
        $first_name, $middle_name, $last_name, $extension_name,
        $date_of_birth, $place_of_birth, $age, $sex,
        $personal_email, $personal_number, $house,
        $barangay, $city, $course_1, $course_2, $id
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant</title>
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

    <div class="container mx-auto px-4 py-8">
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
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course 1</label>
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
                        <label class="block text-sm font-medium text-gray-700">Course 2</label>
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

                <div class="flex justify-end space-x-4">
                    <a href="admin_page.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
