<?php
session_start();
include 'db_connect.php';

$sql = "SELECT applicant_ID as id, first_name, middle_name, last_name, extension_name, email_address as personal_email FROM applicants ORDER BY applicant_ID DESC";$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}
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
            <a href="admin_page.php" class="bg-purple-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-purple-700">Dashboard</a>
            <a href="logout.php" class="bg-red-500 p-2 mt-2 text-center font-bold w-full rounded text-white hover:bg-red-700">Logout</a>
        </div>

       
        <div class="bg-purple-500 rounded-lg w-4/5 max-w-6xl mx-auto p-5 mt-4">
            <h1 class="text-white text-2xl font-bold mb-4">Applicant Management</h1>
            
            
                
                <input type="text" id="searchInput" class="w-full p-2 mb-3 border border-gray-300 rounded" placeholder="Search applicants...">

                <table class="w-full border-2 border-black" id="applicantTable">
                    <thead class="bg-purple-700 text-white">
                        <tr>
                            <th class="border border-black p-2 text-center">ID</th>
                            <th class="border border-black p-2 text-center">Applicant Full Name</th>
                            <th class="border border-black p-2 text-center">E-Mail Address</th>
                            <th class="border border-black p-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="applicantTableBody">
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $fullName = $row["first_name"] . " " . 
                                        ($row["middle_name"] ? $row["middle_name"] . " " : "") . 
                                        $row["last_name"] . " " . 
                                        ($row["extension_name"] ? $row["extension_name"] : "");
                                echo "<tr class='bg-white'>";
                                echo "<td class='border border-black p-2 text-center'>" . $row["id"] . "</td>";
                                echo "<td class='border border-black p-2 text-center'>" . htmlspecialchars($fullName) . "</td>";
                                echo "<td class='border border-black p-2 text-center'>" . htmlspecialchars($row["personal_email"]) . "</td>";
                                echo "<td class='border border-black p-2 text-center'>
                                        <a href='view_applicant.php?id=" . $row["id"] . "' class='bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block'>View</a>
                                        <a href='edit_applicant.php?id=" . $row["id"] . "' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block ml-2'>Edit</a>
                                        <button class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 delete-btn' data-id='" . $row["id"] . "'>Delete</button>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr class='bg-white'><td colspan='4' class='border border-black p-2 text-center'>No applicants found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg max-w-md w-full">
            <h3 class="text-xl font-bold mb-4">Confirm Deletion</h3>
            <p>Are you sure you want to delete this applicant? This action cannot be undone.</p>
            <div class="flex justify-end mt-4">
                <button id="cancelDelete" class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded mr-2">
                    Cancel
                </button>
                <button id="confirmDelete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#applicantTableBody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            
            let deleteId = null;
            
            $(".delete-btn").click(function() {
                deleteId = $(this).data("id");
                $("#deleteModal").removeClass("hidden");
            });
            
            $("#cancelDelete").click(function() {
                $("#deleteModal").addClass("hidden");
                deleteId = null;
            });
            
            $("#confirmDelete").click(function() {
                if (deleteId) {
                    
                    $.ajax({
                        url: "delete_applicant.php",
                        type: "POST",
                        data: { id: deleteId },
                        dataType: "json", 
                        success: function(response) {
                            if (response.success) {
                                $(`button[data-id="${deleteId}"]`).closest("tr").remove();
                                alert("Applicant deleted successfully!");
                            } else {
                                alert("Error: " + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert("An error occurred during deletion.");
                        },
                        complete: function() {
                            $("#deleteModal").addClass("hidden");
                            deleteId = null;
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>