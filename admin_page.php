<?php
$conn = require_once 'db_connect.php';

// Get filter values
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the query
$sql = "SELECT id, first_name, middle_name, last_name, extension_name, personal_email, profile_photo, 
               student_signature, guardian_signature, status, status_updated_at 
        FROM applicants 
        WHERE 1=1";

if ($status_filter) {
    $sql .= " AND status = '" . $conn->real_escape_string($status_filter) . "'";
}

if ($search) {
    $sql .= " AND (first_name LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR middle_name LIKE '%" . $conn->real_escape_string($search) . "%'
              OR last_name LIKE '%" . $conn->real_escape_string($search) . "%'
              OR extension_name LIKE '%" . $conn->real_escape_string($search) . "%'
              OR personal_email LIKE '%" . $conn->real_escape_string($search) . "%')";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);

// Function to check if file exists
function fileExists($filename) {
    $filepath = "uploads/" . $filename;
    return file_exists($filepath) && is_file($filepath);
}

function getStatusClass($status) {
    switch($status) {
        case 'approved':
            return 'bg-green-100';
        case 'rejected':
            return 'bg-red-100';
        default:
            return 'bg-yellow-100';
    }
}

// Check for success/error messages
$success_message = isset($_GET['success']) ? "Applicant successfully deleted." : "";
$error_message = isset($_GET['error']) ? "Error deleting applicant." : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-purple-200">
    <?php if ($success_message): ?>
    <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <span class="block sm:inline"><?php echo $success_message; ?></span>
    </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
    <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <span class="block sm:inline"><?php echo $error_message; ?></span>
    </div>
    <?php endif; ?>

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

        <div class="bg-purple-500 rounded-lg w-4/5 max-w-6xl mx-auto p-5 mt-4">
            <h1 class="text-white text-2xl font-bold mb-4">Applicant Management</h1>
            
            <div class="bg-white p-4 rounded-lg mb-4">
                <form method="GET" class="flex gap-4 items-center">
                    <div class="flex-1">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                               class="w-full p-2 border border-gray-300 rounded" 
                               placeholder="Search applicants...">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-gray-700">Status:</label>
                        <select name="status" class="p-2 border border-gray-300 rounded" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Filter
                    </button>
                    <?php if ($search || $status_filter): ?>
                        <a href="admin_page.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Clear
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="bg-white rounded-lg overflow-hidden">
                <table class="w-full border-2 border-black">
                    <thead class="bg-purple-700 text-white">
                        <tr>
                            <th class="border border-black p-2 text-center">ID</th>
                            <th class="border border-black p-2 text-center">Applicant Full Name</th>
                            <th class="border border-black p-2 text-center">E-Mail Address</th>
                            <th class="border border-black p-2 text-center">Status</th>
                            <th class="border border-black p-2 text-center">Profile Photo</th>
                            <th class="border border-black p-2 text-center">Student Signature</th>
                            <th class="border border-black p-2 text-center">Guardian Signature</th>
                            <th class="border border-black p-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()):
                                $fullName = $row["first_name"] . " " . 
                                        ($row["middle_name"] ? $row["middle_name"] . " " : "") . 
                                        $row["last_name"] . " " . 
                                        ($row["extension_name"] ? $row["extension_name"] : "");
                                
                                $statusClass = getStatusClass($row["status"] ?? 'pending');
                        ?>
                            <tr class="<?php echo $statusClass; ?> hover:bg-gray-50">
                                <td class="border border-black p-2 text-center"><?php echo $row["id"]; ?></td>
                                <td class="border border-black p-2 text-center"><?php echo htmlspecialchars($fullName); ?></td>
                                <td class="border border-black p-2 text-center"><?php echo htmlspecialchars($row["personal_email"]); ?></td>
                                <td class="border border-black p-2 text-center font-semibold">
                                    <?php echo ucfirst($row["status"] ?? 'pending'); ?>
                                </td>
                                <td class="border border-black p-2 text-center">
                                    <?php if ($row["profile_photo"] && fileExists($row["profile_photo"])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($row["profile_photo"]); ?>" 
                                             class="w-16 h-16 object-cover mx-auto cursor-pointer hover:scale-150 transition-transform" 
                                             onclick="showImageModal(this.src)">
                                    <?php else: ?>
                                        <span class="text-red-500">No photo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="border border-black p-2 text-center">
                                    <?php if ($row["student_signature"] && fileExists($row["student_signature"])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($row["student_signature"]); ?>" 
                                             class="w-16 h-16 object-cover mx-auto cursor-pointer hover:scale-150 transition-transform" 
                                             onclick="showImageModal(this.src)">
                                    <?php else: ?>
                                        <span class="text-red-500">No signature</span>
                                    <?php endif; ?>
                                </td>
                                <td class="border border-black p-2 text-center">
                                    <?php if ($row["guardian_signature"] && fileExists($row["guardian_signature"])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($row["guardian_signature"]); ?>" 
                                             class="w-16 h-16 object-cover mx-auto cursor-pointer hover:scale-150 transition-transform" 
                                             onclick="showImageModal(this.src)">
                                    <?php else: ?>
                                        <span class="text-red-500">No signature</span>
                                    <?php endif; ?>
                                </td>
                                <td class="border border-black p-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <a href="view_applicant.php?id=<?php echo $row["id"]; ?>" 
                                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            View
                                        </a>
                                        <a href="edit_applicant.php?id=<?php echo $row["id"]; ?>" 
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Edit
                                        </a>
                                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded delete-btn" 
                                                data-id="<?php echo $row["id"]; ?>">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        } else {
                            echo "<tr><td colspan='8' class='text-center py-4 text-gray-500'>No applicants found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none;">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="relative bg-white p-2 rounded-lg">
                <button onclick="closeImageModal()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl font-bold">&times;</button>
                <img id="modalImage" src="" alt="Enlarged Image" class="max-w-3xl max-h-[80vh] object-contain">
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none;">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full mx-4">
                <h3 class="text-xl font-bold mb-4">Confirm Delete</h3>
                <p class="mb-4">Are you sure you want to delete this applicant? This action cannot be undone.</p>
                <div class="flex justify-end gap-4">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <form id="deleteForm" action="delete_applicant.php" method="POST" class="inline">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image Modal Functions
        function showImageModal(src) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Delete Modal Functions
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteModal').style.display = 'block';
            });
        });

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const imageModal = document.getElementById('imageModal');
            const deleteModal = document.getElementById('deleteModal');
            const imageModalContent = imageModal.querySelector('.relative');
            const deleteModalContent = deleteModal.querySelector('.bg-white');

            if (event.target === imageModal && !imageModalContent.contains(event.target)) {
                closeImageModal();
            }
            if (event.target === deleteModal && !deleteModalContent.contains(event.target)) {
                closeDeleteModal();
            }
        }

        // Auto-hide success/error messages after 3 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.fixed.top-4.right-4');
            messages.forEach(msg => {
                msg.style.display = 'none';
            });
        }, 3000);
    </script>
</body>
</html>

<?php
$conn->close();
?>