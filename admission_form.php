<?php
session_start();
// Function to get form value from session if it exists
function getFormValue($field) {
    return isset($_SESSION['form_data'][$field]) ? htmlspecialchars($_SESSION['form_data'][$field]) : '';
}

// Reset session if reset parameter is set (from index.php)
if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    // Clear session data including all uploads
    $imageFields = ['profile_photo', 'student_signature', 'guardian_signature'];
    foreach ($imageFields as $field) {
        if (!empty($_SESSION['form_data'][$field])) {
            $file = __DIR__ . '/uploads/' . $_SESSION['form_data'][$field];
            if (file_exists($file)) {
                unlink($file);
            }
            unset($_SESSION['form_data'][$field]);
        }
    }
    unset($_SESSION['form_data']);
    // No redirect needed as we're already on the correct page
}

// Keep session data on page refresh unless coming from preview or cancellation
if (!isset($_SERVER['HTTP_REFERER']) || (strpos($_SERVER['HTTP_REFERER'], 'preview_form.php') === false && !isset($_GET['cancel']) && !isset($_GET['reset']))) {
    // Don't unset session data on regular page refresh
}

if (isset($_GET['cancel']) && $_GET['cancel'] === '1') {
    $imageFields = ['profile_photo', 'student_signature', 'guardian_signature'];
    foreach ($imageFields as $field) {
        if (!empty($_SESSION['form_data'][$field])) {
            $file = __DIR__ . '/uploads/' . $_SESSION['form_data'][$field];
            if (file_exists($file)) {
                unlink($file);
            }
            unset($_SESSION['form_data'][$field]);
        }
    }
    unset($_SESSION['form_data']);
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="font-[Roboto] h-full flex flex-1 overflow-auto box-border">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admission Application Form</title>
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
    
    #popup {
        transition: opacity 0.3s ease;
    }
    #popup.hidden {
        opacity: 0;
        pointer-events: none;
    }
    #popup:not(.hidden) {
        opacity: 1;
        pointer-events: auto;
    }
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

        <!-- Original admission_form.php content -->
        <div class="w-32 bg-sidebar flex justify-center items-start pt-5 fixed top-0 left-0 h-screen z-10 sm:hidden">
            <div class="logo">
                <img src="TomYang-Logo.png" alt="Logo" class="w-20 h-auto">
            </div>
        </div>

        <div class="absolute top-5 right-5 flex z-10">
            
        </div>

        <div class="flex-1 w-full rounded-lg shadow-md border-3 border-black mx-auto">
            <?php
            // Display file upload errors if any
            if (isset($_SESSION['upload_errors'])) {
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">';
                foreach ($_SESSION['upload_errors'] as $error) {
                    echo '<p class="block sm:inline">' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
                unset($_SESSION['upload_errors']);
            }
            ?>
            
            <div class="bg-header text-center text-2xl text-white font-bold py-4 border-b-2 border-black rounded-t-lg mb-12">
                TOM YANG
                <br>
                COLLEGE ADMISSION APPLICATION FORM
            </div>
            
            <form id="application-form" action="preview_form.php" method="POST" enctype="multipart/form-data">
                <div class="block">
                    <div class="bg-section text-white font-bold p-2 uppercase">A. NAME OF APPLICANT (AS IT APPEARS ON THE BIRTH CERTIFICATE)</div>
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">FIRST NAME*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">LAST NAME*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">MIDDLE NAME</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">EXTENSION NAME</label>
                                <input type="text" name="first_name" maxlength="50" required class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('first_name'); ?>">
                                <input type="text" name="last_name" maxlength="50" required class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('last_name'); ?>">
                                <input type="text" name="middle_name" maxlength="50" class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('middle_name'); ?>">
                                <input type="text" name="extension_name" maxlength="10" class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('extension_name'); ?>">
                            </div>
                            <div class="bg-section h-5"></div>

                            <div class="grid grid-cols-5">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">DATE OF BIRTH*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">PLACE OF BIRTH*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">AGE*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">SEX*</label>
                                <input type="date" name="date_of_birth"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('date_of_birth'); ?>">
                                <input type="text" name="place_of_birth"  class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('place_of_birth'); ?>">
                                <input type="number" name="age" min="0"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('age'); ?>">
                                <select name="sex"  class="p-1.5 border border-black w-full box-border">
                                    <option value="Male" <?php if(getFormValue('sex') == 'Male') echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if(getFormValue('sex') == 'Female') echo 'selected'; ?>>Female</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-5">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">BLOOD TYPE</label>
                                <input type="text" name="blood_type" class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('blood_type'); ?>">
                            </div>
                            <div class="bg-section h-5"></div>

                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CIVIL STATUS*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">RELIGIOUS AFFILIATION</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CITIZENSHIP*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">NO. OF SIBLINGS*</label>
                                <select name="civil_status"  class="p-1.5 border border-black w-full box-border">
                                    <option value="Single" <?php if(getFormValue('civil_status') == 'Single') echo 'selected'; ?>>Single</option>
                                    <option value="Married" <?php if(getFormValue('civil_status') == 'Married') echo 'selected'; ?>>Married</option>
                                    <option value="Widowed" <?php if(getFormValue('civil_status') == 'Widowed') echo 'selected'; ?>>Widowed</option>
                                </select>
                                <input type="text" name="religious_affiliation"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('religious_affiliation'); ?>">
                                <select name="citizenship"  class="p-1.5 border border-black w-full box-border">
                                    <option value="Filipino" <?php if(getFormValue('citizenship') == 'Filipino') echo 'selected'; ?>>Filipino</option>
                                </select>
                                <input type="number" name="no_of_siblings" min="0"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('no_of_siblings'); ?>">
                            </div>
                        </div>
                                                <div class="w-48 h-48 border-2 border-dashed border-black text-center flex flex-col justify-center items-center text-black font-bold bg-white mt-1 mx-3 flex-shrink-0 relative">
                            <?php if (!empty($_SESSION['form_data']['profile_photo'])): ?>
                                <img id="photo-preview" src="uploads/<?php echo htmlspecialchars($_SESSION['form_data']['profile_photo']); ?>" class="max-w-full max-h-full object-contain absolute inset-0">
                                <div id="photo-placeholder" class="hidden">
                                    <div>Passport Size<br>1.8 inch x 1.4 inch</div>
                                    <label for="upload-photo" class="bg-section text-white p-2 rounded cursor-pointer text-sm mt-2.5 hover:bg-gray-800">Choose File</label>
                                    <input type="file" id="upload-photo" name="profile_photo" accept="image/*" class="hidden" onchange="handleImageUpload(this, 'photo-preview', 'photo-placeholder', 'photo-success', 'delete-photo')">
                                </div>
                                <button type="button" id="delete-photo" onclick="deleteUpload('upload-photo', 'photo-preview', 'photo-placeholder', 'photo-success', 'delete-photo'); document.getElementById('delete_profile_photo').value='1';" class="absolute top-2 right-2 bg-red-500 rounded-full p-1 hover:bg-red-600" style="z-index:999;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            <?php else: ?>
                                <img id="photo-preview" class="hidden max-w-full max-h-full object-contain absolute inset-0">
                                <div id="photo-placeholder">
                                    <div>Passport Size<br>1.8 inch x 1.4 inch</div>
                                    <label for="upload-photo" class="bg-section text-white p-2 rounded cursor-pointer text-sm mt-2.5 hover:bg-gray-800">Choose File</label>
                                </div>
                                <button type="button" id="delete-photo" onclick="deleteUpload('upload-photo', 'photo-preview', 'photo-placeholder', 'photo-success', 'delete-photo'); document.getElementById('delete_profile_photo').value='1';" class="hidden absolute top-2 right-2 bg-red-500 rounded-full p-1 hover:bg-red-600" style="z-index:999;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                            <input type="file" id="upload-photo" name="profile_photo" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" style="z-index:5;" onchange="handleImageUpload(this, 'photo-preview', 'photo-placeholder', 'photo-success', 'delete-photo')" <?php if (empty($_SESSION['form_data']['profile_photo'])) echo 'required'; ?>>
                            <input type="hidden" id="delete_profile_photo" name="delete_profile_photo" value="0">
                        </div>
                    </div>

                    <div class="bg-section text-white font-bold p-2 uppercase mt-4">B. APPLICANT'S ADDRESS AND CONTACT INFORMATION</div>
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <div class="grid grid-cols-5">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">HOUSE/ROOM/BLDG/BLOCK/PHASE/STREET/SUBD*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">BARANGAY*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CITY/MUNICIPALITY*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">DISTRICT*</label>
                                <input type="text" name="house"  class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('house'); ?>">
                                <input type="text" name="barangay"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('barangay'); ?>">
                                <input type="text" name="city"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('city'); ?>">
                                <input type="text" name="district"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('district'); ?>">
                            </div>
                            <div class="grid grid-cols-5">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ZIP CODE*</label>
                                <input type="number" name="zip_code" min="0"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('zip_code'); ?>">
                            </div>

                            <div class="grid grid-cols-3">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE PERSONAL NUMBER*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE PERSONAL EMAIL ADDRESS*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">LANDLINE NUMBER</label>
                                <input type="number" name="personal_number" min="0"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('personal_number'); ?>">
                                <input type="email" name="personal_email"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('personal_email'); ?>">
                                <input type="text" name="landline_number" pattern="^(N/A|n/a|(\(\d{2}\)\s?\d{4}[\s-]?\d{4}))$" title="Please enter a valid landline number format like (02) 1234-5678 or N/A" class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('landline_number'); ?>">
                            </div>
                            <div class="bg-section h-5"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-start mt-4">
                        <div class="w-24 h-32 border border-black text-center flex justify-center items-center text-red-600 font-bold transform rotate-180 bg-sidebar flex-shrink-0 [writing-mode:vertical-rl]">
                            GUARDIAN INFORMATION
                        </div>
                        <div class="flex-grow">
                            <div class="grid grid-cols-6">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">FIRST NAME*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">MIDDLE NAME</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">LAST NAME*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">EXTENSION NAME</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">AGE*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">SEX*</label>
                            </div>

                            <div class="grid grid-cols-6">
                                <input type="text" name="guardian_first_name"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_first_name'); ?>">
                                <input type="text" name="guardian_middle_name" class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_middle_name'); ?>">
                                <input type="text" name="guardian_last_name"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_last_name'); ?>">
                                <input type="text" name="guardian_extension_name" class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_extension_name'); ?>">
                                <input type="number" name="guardian_age" min="0"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_age'); ?>">
                                <select name="guardian_sex"  class="p-1.5 border border-black w-full box-border">
                                    <option value="Male" <?php if(getFormValue('guardian_sex') == 'Male') echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if(getFormValue('guardian_sex') == 'Female') echo 'selected'; ?>>Female</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">RELATIONSHIP TO APPLICANT*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">CURRENT ADDRESS*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE CONTACT NUMBER*</label>
                            </div>

                            <div class="grid grid-cols-4">
                                <input type="text" name="guardian_relationship"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_relationship'); ?>">
                                <input type="text" name="guardian_address"  class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('guardian_address'); ?>">
                                <input type="number" name="guardian_contact_number" min="0"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_contact_number'); ?>">
                            </div>
                            
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE EMAIL ADDRESS*</label>
                                <input type="email" name="guardian_email"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_email'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="bg-section text-white font-bold p-2 uppercase mt-4">C. APPLICANT'S EDUCATIONAL INFORMATION</div>
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ENROLLMENT HISTORY </label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">COMPLETE NAME OF SCHOOL*</label>
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">PERIOD OF ATTENDANCE*</label>
                            </div>
                            
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 12*</label>
                                <input type="text" name="grade12_school" class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('grade12_school'); ?>">
                                <input type="text" name="grade12_period" class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('grade12_period'); ?>">
                            </div>
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 12 GWA*</label>
                                <input type="number" name="grade12_gwa" step="0.01" min="75" max="100" class="p-1.5 border border-black w-full box-border" required value="<?php echo getFormValue('grade12_gwa'); ?>">
                            </div>
                            
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 11*</label>
                                <input type="text" name="grade11_school" class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('grade11_school'); ?>">
                                <input type="text" name="grade11_period" class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('grade11_period'); ?>">
                            </div>
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 11 GWA*</label>
                                <input type="number" name="grade11_gwa" step="0.01" min="75" max="100" class="p-1.5 border border-black w-full box-border" required value="<?php echo getFormValue('grade11_gwa'); ?>">
                            </div>
                            
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 10*</label>
                                <input type="text" name="grade10_school"  class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('grade10_school'); ?>">
                                <input type="text" name="grade10_period"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('grade10_period'); ?>">
                            </div>
                            
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 9*</label>
                                <input type="text" name="grade9_school"  class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('grade9_school'); ?>">
                                <input type="text" name="grade9_period"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('grade9_period'); ?>">
                            </div>
                            
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 8*</label>
                                <input type="text" name="grade8_school"  class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('grade8_school'); ?>">
                                <input type="text" name="grade8_period"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('grade8_period'); ?>">
                            </div>
                            
                            <div class="grid grid-cols-4">
                                <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 7*</label>
                                <input type="text" name="grade7_school"  class="p-1.5 border border-black w-full box-border col-span-2" value="<?php echo getFormValue('grade7_school'); ?>">
                                <input type="text" name="grade7_period"  class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('grade7_period'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-section h-5 mt-4"></div>
                    <div class="bg-sidebar h-5"></div>
                    <div class="bg-section h-5 flex justify-center items-center">
                        <div class="text-white font-bold text-center text-sm uppercase">CHOOSE THE SELECTED COLLEGE AND COURSES</div>
                    </div>
                      <div class="grid grid-cols-11 mt-2">
                        <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">SELECT COURSES*</label>
                        <div class="col-span-9 grid grid-cols-2 gap-4">
                            <select name="course_1" id="course_1" class="p-1.5 border border-black w-full box-border" required>
                                <option value="">Select Course 1</option>
                                <optgroup label="Technology Courses">
                                    <option value="Information Technology (IT)" <?php if(getFormValue('course_1') == 'Information Technology (IT)') echo 'selected'; ?>>Information Technology (IT)</option>
                                    <option value="Computer Science" <?php if(getFormValue('course_1') == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                                    <option value="Computer Engineering" <?php if(getFormValue('course_1') == 'Computer Engineering') echo 'selected'; ?>>Computer Engineering</option>
                                    <option value="Information Systems" <?php if(getFormValue('course_1') == 'Information Systems') echo 'selected'; ?>>Information Systems</option>
                                </optgroup>
                                <optgroup label="Business Courses">
                                    <option value="Marketing Management" <?php if(getFormValue('course_1') == 'Marketing Management') echo 'selected'; ?>>Marketing Management</option>
                                    <option value="Business Administration" <?php if(getFormValue('course_1') == 'Business Administration') echo 'selected'; ?>>Business Administration</option>
                                    <option value="Accounting" <?php if(getFormValue('course_1') == 'Accounting') echo 'selected'; ?>>Accounting</option>
                                    <option value="Economics" <?php if(getFormValue('course_1') == 'Economics') echo 'selected'; ?>>Economics</option>
                                </optgroup>
                                <optgroup label="Education Courses">
                                    <option value="Special Education (SPED)" <?php if(getFormValue('course_1') == 'Special Education (SPED)') echo 'selected'; ?>>Special Education (SPED)</option>
                                    <option value="Elementary Education" <?php if(getFormValue('course_1') == 'Elementary Education') echo 'selected'; ?>>Elementary Education</option>
                                    <option value="Secondary Education" <?php if(getFormValue('course_1') == 'Secondary Education') echo 'selected'; ?>>Secondary Education</option>
                                    <option value="Early Childhood Education" <?php if(getFormValue('course_1') == 'Early Childhood Education') echo 'selected'; ?>>Early Childhood Education</option>
                                </optgroup>
                            </select>
                            <select name="course_2" id="course_2" class="p-1.5 border border-black w-full box-border" required>
                                <option value="">Select Course 2</option>
                                <optgroup label="Technology Courses">
                                    <option value="Information Technology (IT)" <?php if(getFormValue('course_2') == 'Information Technology (IT)') echo 'selected'; ?>>Information Technology (IT)</option>
                                    <option value="Computer Science" <?php if(getFormValue('course_2') == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                                    <option value="Computer Engineering" <?php if(getFormValue('course_2') == 'Computer Engineering') echo 'selected'; ?>>Computer Engineering</option>
                                    <option value="Information Systems" <?php if(getFormValue('course_2') == 'Information Systems') echo 'selected'; ?>>Information Systems</option>
                                </optgroup>
                                <optgroup label="Business Courses">
                                    <option value="Marketing Management" <?php if(getFormValue('course_2') == 'Marketing Management') echo 'selected'; ?>>Marketing Management</option>
                                    <option value="Business Administration" <?php if(getFormValue('course_2') == 'Business Administration') echo 'selected'; ?>>Business Administration</option>
                                    <option value="Accounting" <?php if(getFormValue('course_2') == 'Accounting') echo 'selected'; ?>>Accounting</option>
                                    <option value="Economics" <?php if(getFormValue('course_2') == 'Economics') echo 'selected'; ?>>Economics</option>
                                </optgroup>
                                <optgroup label="Education Courses">
                                    <option value="Special Education (SPED)" <?php if(getFormValue('course_2') == 'Special Education (SPED)') echo 'selected'; ?>>Special Education (SPED)</option>
                                    <option value="Elementary Education" <?php if(getFormValue('course_2') == 'Elementary Education') echo 'selected'; ?>>Elementary Education</option>
                                    <option value="Secondary Education" <?php if(getFormValue('course_2') == 'Secondary Education') echo 'selected'; ?>>Secondary Education</option>
                                    <option value="Early Childhood Education" <?php if(getFormValue('course_2') == 'Early Childhood Education') echo 'selected'; ?>>Early Childhood Education</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="bg-section text-white font-bold p-2 uppercase mt-4">AVAILABLE COURSES</div>
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="flex flex-col gap-2">
                                    <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center justify-center">Technology Courses</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Information Technology (IT)</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Computer Science</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Computer Engineering</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Information Systems</label>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center justify-center">Business Courses</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Marketing Management</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Business Administration</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Accounting</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Economics</label>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center justify-center">Education Courses</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Special Education (SPED)</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Elementary Education</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Secondary Education</label>
                                    <label class="p-1 text-sm text-black bg-white border border-black flex items-center justify-center">Early Childhood Education</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-section text-white font-bold p-2 uppercase mt-4">DATA PRIVACY AND CONSENT DECLARATION</div>
                    <div class="flex justify-between items-start">
                        <div class="flex-grow p-4">
                            <div class="bg-white p-4 border border-black rounded">
                                <h3 class="font-bold mb-2">Consent Notice</h3>
                                <p class="text-sm mb-4">
                                    I hereby give my consent to TOM YANG COLLEGE to collect, record, organize, update, use, consolidate, and/or process my personal data and my personal data. I understand that my personal information and my child's/ward's personal information is being collected, accessed, used, processed, and stored for the following purposes:
                                    <br><br>
                                    1. Processing of admission application and student registration
                                    <br>
                                    2. Academic-related purposes
                                    <br>
                                    3. Research and statistical purposes
                                    <br>
                                    4. Administrative purposes
                                    <br><br>
                                    I agree to the Terms and Conditions and consent to the collection and processing of my personal information in accordance with the Privacy Policy.
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block mb-2 font-bold">Student's Full PRINTED Name*</label>
                                    <input type="text" name="student_consent_name" required class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('student_consent_name'); ?>">
                                                                        <div class="border-2 border-dashed border-black h-32 mt-2 relative">
                                        <?php if (!empty($_SESSION['form_data']['student_signature'])): ?>
                                            <img id="student-signature-preview" src="uploads/<?php echo htmlspecialchars($_SESSION['form_data']['student_signature']); ?>" class="max-w-full max-h-full object-contain absolute inset-0">
                                            <div id="student-signature-placeholder" class="hidden flex items-center justify-center h-full">
                                                <label for="student-signature-input" class="bg-black cursor-pointer w-full h-full flex items-center justify-center m-0">
                                                    <span class="text-gray-500">Click to upload signature (JPG/PNG)*</span>
                                                    <input type="file" name="student_signature" accept="image/jpeg,image/png" class="bg-black absolute inset-0 opacity-0 z-10" id="student-signature-input" onchange="handleImageUpload(this, 'student-signature-preview', 'student-signature-placeholder', 'student-signature-success', 'delete-student-signature')" style=" pointer-events: auto;">
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <img id="student-signature-preview" class="hidden max-w-full max-h-full object-contain absolute inset-0">
                                            <div id="student-signature-placeholder" class="flex items-center justify-center h-full">
                                                <label for="student-signature-input" class="cursor-pointer w-full h-full flex items-center justify-center m-0">
                                                    <span class="text-gray-500">Click to upload signature (JPG/PNG)*</span>
                                                    <input type="file" name="student_signature" accept="image/jpeg,image/png" class="absolute inset-0 opacity-0 z-10" id="student-signature-input" onchange="handleImageUpload(this, 'student-signature-preview', 'student-signature-placeholder', 'student-signature-success', 'delete-student-signature')" <?php if (empty($_SESSION['form_data']['student_signature'])) echo 'required'; ?> style="pointer-events: auto;">
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                        <button type="button" id="delete-student-signature" onclick="deleteUpload('student-signature-input', 'student-signature-preview', 'student-signature-placeholder', 'student-signature-success', 'delete-student-signature'); document.getElementById('delete_student_signature').value='1';" class="<?php if (empty($_SESSION['form_data']['student_signature'])) echo 'hidden '; ?>absolute top-2 right-2 bg-red-500 rounded-full p-1 hover:bg-red-600 z-20" style="z-index:999;">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <input type="hidden" id="delete_student_signature" name="delete_student_signature" value="0">
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-2 font-bold">Guardian/Parent's Full PRINTED Name*</label>
                                    <input type="text" name="guardian_consent_name" required class="p-1.5 border border-black w-full box-border" value="<?php echo getFormValue('guardian_consent_name'); ?>">
                                        <div class="border-2 border-dashed border-black h-32 mt-2 relative">
                                        <?php if (!empty($_SESSION['form_data']['guardian_signature'])): ?>
                                            <img id="guardian-signature-preview" src="uploads/<?php echo htmlspecialchars($_SESSION['form_data']['guardian_signature']); ?>" class="max-w-full max-h-full object-contain absolute inset-0">
                                            <div id="guardian-signature-placeholder" class="hidden flex items-center justify-center h-full">
                                                <label for="guardian-signature-input" class="cursor-pointer w-full h-full flex items-center justify-center m-0">
                                                    <span class="text-gray-500">Click to upload signature (JPG/PNG)</span>
                                                    <input type="file" name="guardian_signature" accept="image/jpeg,image/png" class="absolute inset-0 opacity-0 z-10" id="guardian-signature-input" onchange="handleImageUpload(this, 'guardian-signature-preview', 'guardian-signature-placeholder', 'guardian-signature-success', 'delete-guardian-signature')" style="pointer-events: auto;">
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <img id="guardian-signature-preview" class="hidden max-w-full max-h-full object-contain absolute inset-0">
                                            <div id="guardian-signature-placeholder" class="flex items-center justify-center h-full">
                                                <label for="guardian-signature-input" class="cursor-pointer w-full h-full flex items-center justify-center m-0">
                                                    <span class="text-gray-500">Click to upload signature (JPG/PNG)</span>
                                                    <input type="file" name="guardian_signature" accept="image/jpeg,image/png" class="absolute inset-0 opacity-0 z-10" id="guardian-signature-input" onchange="handleImageUpload(this, 'guardian-signature-preview', 'guardian-signature-placeholder', 'guardian-signature-success', 'delete-guardian-signature')" <?php if (empty($_SESSION['form_data']['guardian_signature'])) echo 'required'; ?> style="pointer-events: auto;">
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                        <button type="button" id="delete-guardian-signature" onclick="deleteUpload('guardian-signature-input', 'guardian-signature-preview', 'guardian-signature-placeholder', 'guardian-signature-success', 'delete-guardian-signature'); document.getElementById('delete_guardian_signature').value='1';" class="<?php if (empty($_SESSION['form_data']['guardian_signature'])) echo 'hidden '; ?>absolute top-2 right-2 bg-red-500 rounded-full p-1 hover:bg-red-600 z-20" style="z-index:999;">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <input type="hidden" id="delete_guardian_signature" name="delete_guardian_signature" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-5 mb-2.5 mx-2.5">
                        <button type="button" id="fillSampleData" class="bg-green-500 text-white py-2.5 px-5 border-none cursor-pointer text-base ml-2.5 mb-2.5 mr-2.5 rounded-full hover:opacity-90">Fill Sample Data</button>
                        <button type="button" onclick="cancelAll()" class="bg-red-500 text-white py-2.5 px-5 border-none cursor-pointer text-base ml-2.5 mb-2.5 mr-2.5 rounded-full hover:opacity-90">Cancel</button>
                        <button type="button" onclick="validateAndShowPopup()" class="bg-green-500 text-white py-2.5 px-5 border-none cursor-pointer text-base ml-2.5 mb-2.5 mr-2.5 rounded-full hover:opacity-90">Proceed</button> 
                   </div>
                </div>
            </div>

            <div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
                    <h2 class="text-lg font-bold mb-4">Confirmation</h2>
                    <p class="mb-4">Are you sure you want to proceed?</p>
                    <div class="flex justify-center gap-4">
                        <button type="button" onclick="closePopup()" class="bg-red-500 text-white py-2 px-5 rounded hover:opacity-90">Cancel</button>
                        <button type="submit" form="application-form" class="bg-blue-500 text-white py-2 px-5 rounded hover:opacity-90">OK</button>
                    </div>
                </div>
            </div>
            </form>
        </div>

      </div>
    </main>

    <!-- Include the template footer -->
    <?php include "general-template/components/navigation/footer.php" ?>

  </section>

  <!-- Include the template JavaScript -->
  <script src="general-template/javascript/index.js"></script>

  <!-- Custom JavaScript to handle navigation and form functionality -->
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

    // Function to handle image uploads
    function handleImageUpload(input, previewId, placeholderId, successId, deleteButtonId) {
      const preview = document.getElementById(previewId);
      const placeholder = document.getElementById(placeholderId);
      const deleteButton = document.getElementById(deleteButtonId);
      
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('hidden');
          placeholder.classList.add('hidden');
          deleteButton.classList.remove('hidden');
          
          // Store preview in session to persist across page refreshes
          if (typeof sessionStorage !== 'undefined') {
            sessionStorage.setItem(previewId + '_data', e.target.result);
            sessionStorage.setItem(previewId + '_visible', 'true');
          }
        }
        
        reader.readAsDataURL(input.files[0]);
      }
    }

    // Function to delete uploaded image
    function deleteUpload(inputId, previewId, placeholderId, successId, deleteButtonId) {
      const input = document.getElementById(inputId);
      const preview = document.getElementById(previewId);
      const placeholder = document.getElementById(placeholderId);
      const deleteButton = document.getElementById(deleteButtonId);
      
      if (input) {
        input.value = '';
        
        // Make sure the input field is enabled and clickable
        if (input.style) {
          input.style.pointerEvents = 'auto';
          input.style.zIndex = '10';
        }
      }
      
      if (preview) {
        preview.src = '';
        preview.classList.add('hidden');
      }
      
      if (placeholder) {
        placeholder.classList.remove('hidden');
        
        // Make sure the placeholder is clickable
        const label = placeholder.querySelector('label');
        if (label && label.style) {
          label.style.pointerEvents = 'auto';
          label.style.cursor = 'pointer';
        }
      }
      
      if (deleteButton) {
        deleteButton.classList.add('hidden');
      }
      
      // Remove from session storage
      if (typeof sessionStorage !== 'undefined') {
        sessionStorage.removeItem(previewId + '_data');
        sessionStorage.removeItem(previewId + '_visible');
      }
      
      // Set the delete flag
      const flagElement = document.getElementById('delete_' + inputId.replace('-input', '').replace('upload-', ''));
      if (flagElement) {
        flagElement.value = '1';
      }
    }
    
    // Function to restore image previews from session storage
    function restoreImagePreviewsFromSession() {
      const imagePreviewIds = ['photo-preview', 'student-signature-preview', 'guardian-signature-preview'];
      
      imagePreviewIds.forEach(previewId => {
        const preview = document.getElementById(previewId);
        if (!preview) return;
        
        const placeholder = document.getElementById(previewId.replace('-preview', '-placeholder'));
        const deleteButton = document.getElementById('delete-' + previewId.replace('-preview', ''));
        
        if (typeof sessionStorage !== 'undefined' && sessionStorage.getItem(previewId + '_data')) {
          const isVisible = sessionStorage.getItem(previewId + '_visible') === 'true';
          
          if (isVisible && preview) {
            preview.src = sessionStorage.getItem(previewId + '_data');
            preview.classList.remove('hidden');
            
            if (placeholder) placeholder.classList.add('hidden');
            if (deleteButton) deleteButton.classList.remove('hidden');
          }
        }
      });
    }

    // Function to validate form and show popup
    function validateAndShowPopup() {
      // Check form validity first
      const form = document.getElementById('application-form');
      
      // Temporarily disable required attribute for file inputs if images already exist in session
      const photoInput = document.getElementById('upload-photo');
      const studentSigInput = document.getElementById('student-signature-input');
      const guardianSigInput = document.getElementById('guardian-signature-input');
      
      // Store original required state
      const photoRequired = photoInput.required;
      const studentSigRequired = studentSigInput ? studentSigInput.required : false;
      const guardianSigRequired = guardianSigInput ? guardianSigInput.required : false;
      
      // Check if images are already uploaded
      const photoPreview = document.getElementById('photo-preview');
      const studentSigPreview = document.getElementById('student-signature-preview');
      const guardianSigPreview = document.getElementById('guardian-signature-preview');
      
      if (photoPreview && !photoPreview.classList.contains('hidden')) {
        photoInput.required = false;
      }
      
      if (studentSigInput && studentSigPreview && !studentSigPreview.classList.contains('hidden')) {
        studentSigInput.required = false;
      }
      
      if (guardianSigInput && guardianSigPreview && !guardianSigPreview.classList.contains('hidden')) {
        guardianSigInput.required = false;
      }
      
      // Check form validity
      const isValid = form.checkValidity();
      
      // Restore original required state
      photoInput.required = photoRequired;
      if (studentSigInput) studentSigInput.required = studentSigRequired;
      if (guardianSigInput) guardianSigInput.required = guardianSigRequired;
      
      if (!isValid) {
        // Trigger browser's native validation error display
        form.reportValidity();
        return;
      }
      
      // If valid, show popup
      showPopup();
    }

    // Function to show popup
    function showPopup() {
      const popup = document.getElementById('popup');
      popup.classList.remove('hidden');
    }

    // Function to close popup
    function closePopup() {
      const popup = document.getElementById('popup');
      popup.classList.add('hidden');
    }

    // Function to fill sample data
    function fillSampleData() {
      const sampleData = {
        'first_name': 'John',
        'last_name': 'Doe',
        'middle_name': 'Smith',
        'extension_name': 'Jr',
        'date_of_birth': '2000-01-01',
        'place_of_birth': 'Manila',
        'age': '23',
        'sex': 'Male',
        'blood_type': 'O+',
        'civil_status': 'Single',
        'religious_affiliation': 'Catholic',
        'citizenship': 'Filipino',
        'no_of_siblings': '2',
        'house': '123 Main St',
        'barangay': 'Barangay 1',
        'city': 'Manila',
        'district': 'District 1',
        'zip_code': '1000',
        'personal_number': '09123456789',
        'personal_email': 'john.doe@email.com',
        'landline_number': '(02) 1234-5678',
        'guardian_first_name': 'Jane',
        'guardian_middle_name': 'Marie',
        'guardian_last_name': 'Doe',
        'guardian_extension_name': '',
        'guardian_age': '45',
        'guardian_sex': 'Female',
        'guardian_relationship': 'Mother',
        'guardian_address': '123 Main St, Manila',
        'guardian_contact_number': '09123456789',
        'guardian_email': 'jane.doe@email.com',
        // Educational background
        'grade12_school': 'Sample Senior High School',
        'grade12_period': '2017-2018',
        'grade12_gwa': '92.50',
        'grade11_school': 'Sample Senior High School',
        'grade11_period': '2016-2017',
        'grade11_gwa': '91.00',
        'grade10_school': 'Sample Junior High School',
        'grade10_period': '2015-2016',
        'grade9_school': 'Sample Junior High School',
        'grade9_period': '2014-2015',
        'grade8_school': 'Sample Junior High School',
        'grade8_period': '2013-2014',
        'grade7_school': 'Sample Junior High School',
        'grade7_period': '2012-2013',
        // Course selection
        'course_1': 'Information Technology (IT)',
        'course_2': 'Business Administration',
        // Consent section
        'student_consent_name': 'John Doe',
        'guardian_consent_name': 'Jane Doe',
        // GWA for required fields
        'grade12_gwa': '92.50',
        'grade11_gwa': '91.00',
      };
      for (const [field, value] of Object.entries(sampleData)) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
          if (input.tagName === 'SELECT') {
            input.value = value;
            input.dispatchEvent(new Event('change'));
          } else {
            input.value = value;
          }
        }
      }
    }

    document.getElementById('fillSampleData').addEventListener('click', function() {
      fillSampleData();
      sessionStorage.setItem('sampleDataFilled', '1');
    });

    // On page load, fill sample data if flag is set
    if (sessionStorage.getItem('sampleDataFilled') === '1') {
      fillSampleData();
    }
    
    // Restore image previews from session storage on page load
    document.addEventListener('DOMContentLoaded', function() {
      restoreImagePreviewsFromSession();
    });

    // Update cancelAll to clear the flag and reset images
    function cancelAll() {
      if (confirm('Are you sure you want to cancel? All entered data and uploaded images will be lost.')) {
        // Clear sample data flag and all image session data
        sessionStorage.clear();
        
        // Reset all text fields
        const fields = document.querySelectorAll('input[type="text"], input[type="email"], input[type="number"], input[type="date"], select');
        fields.forEach(field => {
          if (field.tagName === 'SELECT') {
            field.selectedIndex = 0;
          } else {
            field.value = '';
          }
        });
        
        // Reset images and placeholders
        const imagePreviews = [
          {
            preview: 'photo-preview',
            placeholder: 'photo-placeholder',
            deleteButton: 'delete-photo'
          },
          {
            preview: 'student-signature-preview',
            placeholder: 'student-signature-placeholder',
            deleteButton: 'delete-student-signature'
          },
          {
            preview: 'guardian-signature-preview',
            placeholder: 'guardian-signature-placeholder',
            deleteButton: 'delete-guardian-signature'
          }
        ];
        
        imagePreviews.forEach(item => {
          const preview = document.getElementById(item.preview);
          const placeholder = document.getElementById(item.placeholder);
          const deleteButton = document.getElementById(item.deleteButton);
          
          if (preview) {
            preview.src = '';
            preview.classList.add('hidden');
          }
          if (placeholder) placeholder.classList.remove('hidden');
          if (deleteButton) deleteButton.classList.add('hidden');
        });
        
        // Redirect to clear PHP session and uploaded images
        window.location.href = 'admission_form.php?cancel=1';
      }
    }
  </script>

</body>
</html>

