<?php
session_start();

// Function to validate file size
function validateFileSize($file) {
    $maxSize = 1048576; // 1MB in bytes
    return $file['size'] <= $maxSize;
}

// Validate uploaded files
$fileErrors = [];
$fileFields = ['profile_photo', 'student_signature', 'guardian_signature'];

// Handle delete flags for images
foreach ($fileFields as $field) {
    $deleteField = 'delete_' . $field;
    
    // Debug the delete flags
    error_log("Delete flag for $field: " . (isset($_POST[$deleteField]) ? $_POST[$deleteField] : 'not set'));
    
    if (isset($_POST[$deleteField]) && $_POST[$deleteField] == '1') {
        // If the delete flag is set, unset the field from session
        if (isset($_SESSION['form_data'][$field])) {
            $file = __DIR__ . '/uploads/' . $_SESSION['form_data'][$field];
            if (file_exists($file)) {
                unlink($file);
            }
            unset($_SESSION['form_data'][$field]);
            error_log("Deleted $field from session");
        }
    }
}

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

// Store the form data in the session (except file fields that will be handled separately)
$formData = $_POST;
// Remove delete flag fields
foreach ($fileFields as $field) {
    if (isset($formData['delete_' . $field])) {
        unset($formData['delete_' . $field]);
    }
}
$_SESSION['form_data'] = array_merge(isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [], $formData);

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
    foreach ($fileFields as $fileField) {
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
<html lang="en" class="font-[Roboto] h-full flex flex-1 overflow-auto box-border">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application Preview</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            sidebar: '#B0B0FF',
            container: '#B8D0B8',
            header: '#7b0c8c7a',
            section: '#000059',
            cancel: '#FF0004',
            proceed: '#00AB42'
          }
        }
      }
    }
  </script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
  </style>
</head>

<body class="font-[Roboto] h-full flex flex-1 overflow-auto box-border bg-gray-100">

  <!-- Include the template sidebar -->
  <?php include "general-template/components/navigation/sidebar.php" ?>

  <section class="flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

    <!-- Include the template header -->
    <?php include "general-template/components/navigation/header.php" ?>

    <main class="flex flex-col h-full overflow-auto">
      <div class="bg-white border border-solid border-black rounded-xl rounded-tr-none rounded-br-none m-3 px-6 py-5 overflow-auto">

        <!-- Original preview_form.php content -->
        <div class="bg-container rounded-lg shadow-md border-3 border-black mb-5 p-6">
            <div class="bg-header text-center text-2xl text-white font-bold py-4 border-b-2 border-black rounded-t-lg mb-6">
                Application Preview
            </div>

            <?php if (isset($_SESSION['form_data'])): ?>
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-black">
                    <tbody>
                        <?php foreach ($_SESSION['form_data'] as $key => $value): ?>
                        <?php if (!in_array($key, ['profile_photo', 'student_signature', 'guardian_signature'])): ?>
                        <tr class="border-b border-black">
                            <td class="p-2 font-bold text-black border-r border-black w-1/4"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></td>
                            <td class="p-2 text-black"><?php echo htmlspecialchars($value); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                          <!-- Display uploaded files -->
                        <?php if (!empty($_SESSION['form_data']['profile_photo'])): ?>
                        <tr class="border-b border-black">
                            <td class="p-2 font-bold text-black border-r border-black w-1/4">Profile Photo</td>
                            <td class="p-2 text-black">
                                <img src="uploads/<?php echo htmlspecialchars($_SESSION['form_data']['profile_photo']); ?>" alt="Applicant Photo" class="max-w-48 max-h-48">
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
            </div>
            
            <div class="flex justify-center mt-8">
                <form action="process_form.php" method="POST" class="mr-4">
                    <?php foreach ($_SESSION['form_data'] as $key => $value): ?>
                        <?php if (is_array($value)): ?>
                            <?php foreach ($value as $subKey => $subValue): ?>
                                <input type="hidden" name="<?php echo htmlspecialchars($key); ?>[<?php echo htmlspecialchars($subKey); ?>]" value="<?php echo htmlspecialchars($subValue); ?>">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Confirm & Submit</button>
                </form>
                
                <a href="admission_form.php" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">Edit</a>
            </div>
            <?php else: ?>
            <p class="text-red-500">No data to preview.</p>
            <?php endif; ?>
        </div>

      </div>
    </main>

    <!-- Include the template footer -->
    <?php include "general-template/components/navigation/footer.php" ?>

  </section>

</body>

<!-- Include the template JavaScript -->
<script src="general-template/javascript/index.js"></script>

<!-- Custom JavaScript to handle navigation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Update header title
  const headerTitle = document.querySelector('header h1');
  if (headerTitle) {
    headerTitle.textContent = 'Application Form';
  }
  
  // Add footer content
  const footerDiv = document.querySelector('div.bg-white.rounded-xl.m-3.px-6.py-3.mt-auto');
  if (footerDiv) {
    const footerContent = document.createElement('p');
    footerContent.className = 'text-center text-sm text-gray-600';
    footerContent.innerHTML = '© 2025 Tom Yang College. All rights reserved.';
    footerDiv.appendChild(footerContent);
  }
});
</script>

</html>