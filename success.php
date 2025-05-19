<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white flex justify-center items-center h-screen">
    <div class="bg-green-100 p-8 rounded-lg shadow-md max-w-md w-full">
        <h1 class="text-2xl font-bold text-green-800 mb-4">Application Submitted Successfully!</h1>
        <p class="text-gray-700 mb-6">Thank you for submitting your application to Tom Yang College. Your application has been received and is being processed.</p>
        <div class="flex justify-center">
            <a href="index.php" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Return to Home</a>
        </div>
    </div>

    <script>
        // Clear sessionStorage to ensure a fresh application next time
        if (typeof sessionStorage !== 'undefined') {
            sessionStorage.clear();
        }
    </script>
</body>
</html>
<?php
// Clear the session flag after using it
unset($_SESSION['clear_storage']);
?>