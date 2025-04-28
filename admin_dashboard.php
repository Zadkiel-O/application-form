<?php
session_start();
require_once 'config.php';
require_once 'db_connect.php';

// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit;
// }

$sql = "SELECT a.applicant_ID, a.first_name, a.last_name, a.email_address, app.application_status
        FROM applicants a
        LEFT JOIN applications app ON a.applicant_ID = app.applicant_ID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TOM YANG College</title>
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

        <div class="flex-1 p-4">
            <div class="bg-purple-500 rounded-lg max-w-6xl mx-auto p-5">
                <h1 class="text-white text-2xl font-bold mb-4">Applicant List</h1>
                
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white p-6 rounded-lg overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['applicant_ID']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['email_address']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['application_status'] ?: 'Pending'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="view_applicant.php?id=<?php echo $row['applicant_ID']; ?>" class="text-blue-500 hover:underline">View</a>
                                        <a href="edit_applicant.php?id=<?php echo $row['applicant_ID']; ?>" class="text-green-500 hover:underline ml-2">Edit</a>
                                        <a href="delete_applicant.php?id=<?php echo $row['applicant_ID']; ?>" onclick="return confirm('Are you sure you want to delete this applicant?');" class="text-red-500 hover:underline ml-2">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>