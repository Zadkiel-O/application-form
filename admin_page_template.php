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
<html lang="en" class="font-[Roboto] h-full flex flex-1 overflow-auto box-border">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN | Applicant Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
</head>

<body class="font-[Roboto] h-full flex flex-1 overflow-auto box-border bg-gray-100">
    <?php if ($success_message): ?>
    <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        <span class="block sm:inline"><?php echo $success_message; ?></span>
    </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
    <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
        <span class="block sm:inline"><?php echo $error_message; ?></span>
    </div>
    <?php endif; ?>

    <!-- Custom Admin Sidebar -->
    <aside class="max-sm:w-0 max-sm:px-0 w-20 bg-[linear-gradient(to_bottom,_#6a11cb,_#a044ff)] text-white pt-3 pb-4 flex flex-col gap-4 [transition:width_0.3s_ease] overflow-auto sm:hover:w-72 sm:hover:items-start group">
        <nav class="flex flex-col w-full overflow-auto">
            <div class="text-white no-underline flex items-center whitespace-nowrap overflow-hidden [transition:background_0.3s] rounded-md pt-3 pb-2 font-bold text-xl leading-[1.2]">
                <img class="ml-4 mr-2 w-12 h-auto max-w-none" src="general-template/assets/core/TomYang-Logo.png" alt="Logo"><span name="sidebar-text" class="opacity-0 [transition:opacity_0.3s_ease,_margin-left_0.3s_ease] ml-0 sm:group-hover:opacity-100 sm:group-hover:ml-1">TOM YANG<br>UNIVERSITY</span>
            </div>
            <div class="mt-3 overflow-y-auto overflow-x-hidden">
                <!-- Admin Navigation Buttons -->
                <div class="mb-2 ml-4 mr-6 text-white no-underline flex items-center font-medium whitespace-nowrap overflow-hidden p-3 bg-[rgba(255,_255,_255,_0.3)] rounded-md">
                    <img class="w-5 [transition:margin_0.3s_ease] sm:group-hover:mr-3 max-w-none" src="general-template/assets/core/Applications-Icon.png">
                    <span name="sidebar-text" class="text-base opacity-0 [transition:opacity_0.3s_ease,_margin-left_0.3s_ease] ml-0 sm:group-hover:opacity-100 sm:group-hover:ml-1 font-bold">Applicant Form</span>
                </div>
                <a href="admin_dashboard.php" class="mb-2 ml-4 mr-6 text-white no-underline flex items-center font-medium whitespace-nowrap overflow-hidden p-3 [transition:background_0.3s] rounded-md hover:bg-[rgba(0,_0,_0,_0.3)]">
                    <img class="w-5 [transition:margin_0.3s_ease] sm:group-hover:mr-3 max-w-none" src="general-template/assets/core/Dashboard-Icon.png">
                    <span name="sidebar-text" class="text-base opacity-0 [transition:opacity_0.3s_ease,_margin-left_0.3s_ease] ml-0 sm:group-hover:opacity-100 sm:group-hover:ml-1">Dashboard</span>
                </a>
                <a href="logout.php" class="mb-2 ml-4 mr-6 text-white no-underline flex items-center font-medium whitespace-nowrap overflow-hidden p-3 [transition:background_0.3s] rounded-md hover:bg-[rgba(0,_0,_0,_0.3)]">
                    <img class="w-5 [transition:margin_0.3s_ease] sm:group-hover:mr-3 max-w-none" src="general-template/assets/core/Logout-Icon.png">
                    <span name="sidebar-text" class="text-base opacity-0 [transition:opacity_0.3s_ease,_margin-left_0.3s_ease] ml-0 sm:group-hover:opacity-100 sm:group-hover:ml-1">Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <section class="flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        <!-- Header from template -->
        <header class="bg-[linear-gradient(to_right,_#6a11cb,_#a044ff)] h-24 flex items-center justify-between pr-10 py-3">
            <div class="flex items-center">
                <button id="menu-button" class="sm:hidden ml-3 p-3 min-w-11 [transition:background_0.3s] rounded-md hover:bg-[rgba(0,_0,_0,_0.3)]"><img class="h-5 cursor-pointer filter brightness-0 invert" src="general-template/assets/core/Menu-Icon.png" alt="Menu"></button>
                <h1 class="max-sm:ml-3 ml-7 whitespace-nowrap text-ellipsis text-3xl text-white font-bold">Applicant Management</h1>
            </div>
            <div class="flex">
                <img class="h-5 ml-5 cursor-pointer filter brightness-0 invert" src="general-template/assets/core/Phone-Icon.png" alt="Phone">
                <img class="h-5 ml-5 cursor-pointer filter brightness-0 invert" src="general-template/assets/core/Notification-Icon.png" alt="Notifications">
                <img class="h-5 ml-5 cursor-pointer filter brightness-0 invert" src="general-template/assets/core/Profile-Icon.png" alt="Profile">
            </div>
        </header>

        <main class="flex flex-col h-full overflow-auto">
            <div class="bg-white border border-solid border-black rounded-xl rounded-tr-none rounded-br-none m-3 px-6 py-5 overflow-auto">
                <!-- Main content -->
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
                            <a href="admin_page_template.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Clear
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="bg-white rounded-lg overflow-x-auto">
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
        </main>

        <!-- Footer from template -->
        <div class="bg-white rounded-xl m-3 px-6 py-3 mt-auto">
            <p class="text-center text-gray-600">&copy; <?php echo date('Y'); ?> Tom Yang University. All rights reserved.</p>
        </div>
    </section>

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

        // Mobile menu toggle
        document.getElementById('menu-button')?.addEventListener('click', function() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('max-sm:w-0');
            sidebar.classList.toggle('max-sm:w-64');
        });

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