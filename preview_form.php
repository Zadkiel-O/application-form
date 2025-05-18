<?php
session_start();

// Function to validate file size
function validateFileSize($file) {
    $maxSize = 1048576; // 1MB in bytes
    return $file['size'] <= $maxSize;
}

// Validate uploaded files
$fileErrors = [];
$fileFields = ['photo', 'student_signature', 'guardian_signature'];

foreach ($fileFields as $field) {
    if (!empty($_FILES[$field]['name'])) {
        if (!validateFileSize($_FILES[$field])) {
            $fileErrors[] = ucfirst(str_replace('_', ' ', $field)) . " file size exceeds 1MB limit";
        }
    }
}

// If there are file errors, redirect back with error message
if (!empty($fileErrors)) {
    $_SESSION['upload_errors'] = $fileErrors;
    header("Location: admission_form.php");
    exit();
}

// Store the form data in the session
$_SESSION['form_data'] = $_POST;

// Handle file uploads
function handleFileUpload($file) {
    if (!empty($file['name'])) {
        $target_dir = "uploads/";
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return $new_filename;
        }
    }
    return null;
}

// Handle uploaded files
if (!empty($_FILES)) {
    foreach (['photo', 'student_signature', 'guardian_signature'] as $fileField) {
        if (!empty($_FILES[$fileField]['name'])) {
            $filename = handleFileUpload($_FILES[$fileField]);
            if ($filename) {
                $_SESSION['form_data'][$fileField] = $filename;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white m-0 p-0 flex justify-center items-center">
    <div class="w-2/3 bg-container rounded-lg shadow-md border-3 border-black mx-5 mt-10 mb-5 p-8">
        <div class="bg-header text-center text-2xl text-white font-bold py-4 border-b-2 border-black rounded-t-lg mb-6">
            Application Preview
        </div>

        <?php if (isset($_SESSION['form_data'])): ?>
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-black">                <tbody>
                    <?php foreach ($_SESSION['form_data'] as $key => $value): ?>
                    <?php if (!in_array($key, ['photo', 'student_signature', 'guardian_signature'])): ?>
                    <tr class="border-b border-black">
                        <td class="p-2 font-bold text-black border-r border-black w-1/4"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></td>
                        <td class="p-2 text-black"><?php echo htmlspecialchars($value); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    
                    <!-- Display uploaded files -->
                    <?php if (!empty($_SESSION['form_data']['photo'])): ?>
                    <tr class="border-b border-black">
                        <td class="p-2 font-bold text-black border-r border-black w-1/4">Profile Photo</td>
                        <td class="p-2 text-black">
                            <img src="uploads/<?php echo htmlspecialchars($_SESSION['form_data']['photo']); ?>" alt="Applicant Photo" class="max-w-48 max-h-48">
                        </td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php if (!empty($_SESSION['form_data']['student_signature'])): ?>
                    <tr class="border-b border-black">
                        <td class="p-2 font-bold text-black border-r border-black w-1/4">Student Signature</td>
                        <td class="p-2 text-black">
                            <img src="uploads/<?php echo htmlspecialchars($_SESSION['form_data']['student_signature']); ?>" alt="Student Signature" class="max-w-48 max-h-48">
                        </td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php if (!empty($_SESSION['form_data']['guardian_signature'])): ?>
                    <tr class="border-b border-black">
                        <td class="p-2 font-bold text-black border-r border-black w-1/4">Guardian Signature</td>
                        <td class="p-2 text-black">
                            <img src="uploads/<?php echo htmlspecialchars($_SESSION['form_data']['guardian_signature']); ?>" alt="Guardian Signature" class="max-w-48 max-h-48">
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>        <div class="flex justify-center mt-8">
            <form action="process_form.php" method="POST" enctype="multipart/form-data" class="mr-4">
                <?php foreach ($_SESSION['form_data'] as $key => $value): ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
                <?php endforeach; ?>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Confirm & Submit</button>
            </form>
            <form action="admission_form.php" method="POST" enctype="multipart/form-data">
                <?php foreach ($_SESSION['form_data'] as $key => $value): ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
                <?php endforeach; ?>
                <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">Edit</button>
            </form>
        </div>
        <?php else: ?>
        <p class="text-red-500">No data to preview.</p>
        <?php endif; ?>

    </div>
</body>

</html>