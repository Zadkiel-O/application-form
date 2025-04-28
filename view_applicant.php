<?php
session_start();
require_once 'config.php';
require_once 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid applicant ID.";
    header("Location: admin_dashboard.php");
    exit;
}

$id = (int)$_GET['id'];

$sql = "SELECT a.*, p.*, g.*, ab.*, app.application_status, app.submission_date
        FROM " . TABLE_APPLICANTS . " a
        LEFT JOIN " . TABLE_PERSONAL_INFO . " p ON a.applicant_ID = p.applicant_ID
        LEFT JOIN " . TABLE_GUARDIAN_INFO . " g ON a.applicant_ID = g.applicant_ID
        LEFT JOIN " . TABLE_ACADEMIC_BACKGROUND . " ab ON a.applicant_ID = ab.applicant_ID
        LEFT JOIN " . TABLE_APPLICATIONS . " app ON a.applicant_ID = app.applicant_ID
        WHERE a.applicant_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
try {
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $_SESSION['error_message'] = "Applicant not found.";
        header("Location: admin_dashboard.php");
        exit;
    }
    $applicant = $result->fetch_assoc();
} catch (Exception $e) {
    error_log("Error fetching applicant ID $id: " . $e->getMessage());
    $_SESSION['error_message'] = "Error loading applicant details.";
    header("Location: admin_dashboard.php");
    exit;
}
$stmt->close();

$sql = "SELECT cl.course_name, ac.preference_order
        FROM " . TABLE_APPLICANT_COURSES . " ac
        JOIN " . TABLE_COURSE_LIST . " cl ON ac.course_ID = cl.course_ID
        WHERE ac.applicant_ID = ?
        ORDER BY ac.preference_order";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$sql = "SELECT document_type, file_path
        FROM " . TABLE_SUPPORTING_DOCUMENTS . "
        WHERE application_ID = (SELECT application_ID FROM " . TABLE_APPLICATIONS . " WHERE applicant_ID = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$documents = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$log_sql = "INSERT INTO " . TABLE_AUDIT_LOG . " (action, user_id, applicant_id, timestamp) VALUES (?, ?, ?, NOW())";
$log_stmt = $conn->prepare($log_sql);
$action = "Viewed applicant";
$user_id = $_SESSION['admin_id'];
$log_stmt->bind_param("sii", $action, $user_id, $id);
$log_stmt->execute();
$log_stmt->close();

$photo_path = !empty($applicant['picture']) && file_exists($applicant['picture']) ? htmlspecialchars($applicant['picture']) : '';

function renderField($label, $value, $isDate = false) {
    $value = $isDate && $value ? date('F j, Y', strtotime($value)) : htmlspecialchars($value ?: 'N/A');
    return "
        <div>
            <p class='font-bold'>$label:</p>
            <p>$value</p>
        </div>
    ";
}
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
            <img src="phone.png" alt="Phone" class="h-12 ml-4 cursor-pointer" aria-label="Contact Support">
            <img src="bell.png" alt="Notifications" class="h-12 ml-4 cursor-pointer" aria-label="Notifications">
            <img src="user.png" alt="User Profile" class="h-12 ml-4 cursor-pointer" aria-label="User Profile">
        </div>
    </div>

    <div class="flex">
        <div class="bg-indigo-200 p-4 h-screen w-48 flex flex-col items-center">
            <h2 class="text-center font-bold text-2xl mb-5 text-black">ADMIN</h2>
            <a href="admin_dashboard.php" class="bg-gray-500 p-2 mt-2 text-center font-bold w-full rounded text-white">Applicant List</a>
            <a href="logout.php" class="bg-red-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-red-700">Logout</a>
        </div>

        <div class="bg-purple-500 rounded-lg w-4/5 max-w-6xl mx-auto p-5 mt-4 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-white text-2xl font-bold">Applicant Details</h1>
                <div>
                    <a href="admin_dashboard.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-block mr-2 focus:outline-none focus:ring-2 focus:ring-gray-700">
                        Back to List
                    </a>
                    <a href="edit_applicant.php?id=<?php echo $id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block focus:outline-none focus:ring-2 focus:ring-blue-700">
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
                                <?php
                                echo renderField('Full Name', $applicant['first_name'] . ' ' . $applicant['middle_name'] . ' ' . $applicant['last_name'] . ' ' . $applicant['extension_name']);
                                echo renderField('Birthday', $applicant['birthday'], true);
                                echo renderField('Country of Birth', $applicant['country_of_birth']);
                                echo renderField('Age', $applicant['age']);
                                echo renderField('Sex', $applicant['sex']);
                                echo renderField('Blood Type', $applicant['blood_type']);
                                echo renderField('Civil Status', $applicant['civil_status']);
                                echo renderField('Religious Affiliation', $applicant['religious_affiliation']);
                                echo renderField('Citizenship', $applicant['citizenship']);
                                echo renderField('Number of Siblings', $applicant['number_of_siblings']);
                                ?>
                            </div>
                        </div>
                        <div class="w-full md:w-1/4 flex justify-center md:justify-end">
                            <?php if ($photo_path): ?>
                                <img src="<?php echo $photo_path; ?>" alt="Photo of <?php echo htmlspecialchars($applicant['first_name'] . ' ' . $applicant['last_name']); ?>" class="border-2 border-gray-300 w-40 h-48 object-cover rounded">
                            <?php else: ?>
                                <div class="border-2 border-dashed border-gray-300 w-40 h-48 flex items-center justify-center text-gray-500 rounded">
                                    No Photo
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">B. Address and Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        echo renderField('Complete Address', $applicant['address'] . ', ' . $applicant['barangay'] . ', ' . $applicant['city'] . ', ' . $applicant['region']);
                        echo renderField('Zip Code', $applicant['zip_code']);
                        echo renderField('Contact Number', $applicant['contact_number']);
                        echo renderField('Email Address', $applicant['email_address']);
                        ?>
                    </div>
                </div>

                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">C. Guardian Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        echo renderField('Guardian Name', $applicant['guardian_first_name'] . ' ' . $applicant['guardian_middle_name'] . ' ' . $applicant['guardian_last_name'] . ' ' . $applicant['guardian_extension_name']);
                        echo renderField('Age', $applicant['guardian_age']);
                        echo renderField('Sex', $applicant['guardian_sex']);
                        echo renderField('Relationship', $applicant['guardian_relationship']);
                        echo renderField('Contact Number', $applicant['guardian_contact_number']);
                        echo renderField('Email Address', $applicant['guardian_email_address']);
                        ?>
                    </div>
                </div>

                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">D. Academic Background</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        echo renderField('Grade 12 School', $applicant['grade12_school_name']);
                        echo renderField('Grade 12 GWA', $applicant['grade12_GWA']);
                        echo renderField('Grade 11 School', $applicant['grade11_school_name']);
                        echo renderField('Grade 11 GWA', $applicant['grade11_GWA']);
                        echo renderField('Grade 10 School', $applicant['grade10_school_name']);
                        echo renderField('Grade 9 School', $applicant['grade9_school_name']);
                        echo renderField('Grade 8 School', $applicant['grade8_school_name']);
                        echo renderField('Grade 7 School', $applicant['grade7_school_name']);
                        ?>
                    </div>
                </div>

                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">E. Application Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        echo renderField('Application Status', $applicant['application_status']);
                        echo renderField('Submission Date', $applicant['submission_date'], true);
                        ?>
                        <div>
                            <p class="font-bold">Course Preferences:</p>
                            <ul>
                                <?php foreach ($courses as $course): ?>
                                    <li><?php echo htmlspecialchars($course['course_name']) . ' (' . ($course['preference_order'] == 1 ? 'First Choice' : 'Second Choice') . ')'; ?></li>
                                <?php endforeach; ?>
                                <?php if (empty($courses)): ?>
                                    <li>N/A</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="border-b-2 border-purple-500 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-purple-800 mb-4">F. Supporting Documents</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($documents as $doc): ?>
                            <div>
                                <p class="font-bold"><?php echo htmlspecialchars($doc['document_type']); ?>:</p>
                                <a href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="text-blue-500 hover:underline">View Document</a>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($documents)): ?>
                            <p>No documents uploaded.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>