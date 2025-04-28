<?php
session_start();
require_once 'config.php';
require_once 'db_connect.php';

$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

$sql = "SELECT course_name FROM " . TABLE_COURSE_LIST . " ORDER BY course_name";
$result = $conn->query($sql);
if (!$result) {
    die("Error fetching courses: " . $conn->error);
}
$courses = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
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
</head>
<body class="bg-white m-0 p-0 flex">
    <div class="w-32 bg-sidebar flex justify-center items-start pt-5 shadow-md fixed top-0 left-0 h-screen z-10">
        <div class="logo">
            <img src="assets/logo.png" alt="Logo" class="w-20 h-auto">
        </div>
    </div>

    <div class="absolute top-5 right-5 flex z-10">
        <button class="bg-transparent border-none cursor-pointer p-2 rounded-full transition-all hover:bg-black/10" title="Contact">
            <img src="assets\phone.png" alt="Contact" class="w-10 h-10">
        </button>
        <button class="bg-transparent border-none cursor-pointer p-2 rounded-full transition-all hover:bg-black/10" title="Notification">
            <img src="application_form/src/bell.png" alt="Notification" class="w-10 h-10">
        </button>
        <button class="bg-transparent border-none cursor-pointer p-2 rounded-full transition-all hover:bg-black/10" title="User">
            <img src="user.png" alt="User" class="w-10 h-10">
        </button>
    </div>

    <div class="flex-1 w-[calc(100%-140px)] bg-container rounded-lg shadow-md border-3 border-black mx-5 ml-36 mt-24 mb-5">
        <div class="bg-header text-center text-2xl text-white font-bold py-4 border-b-2 border-black rounded-t-lg mb-12">
            TOM YANG
            <br>
            COLLEGE ADMISSION APPLICATION FORM
        </div>

        <?php if (isset($_SESSION['success']) && $_SESSION['success']): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mx-5" role="alert">
                <p><strong>Success!</strong> Your application has been submitted successfully.</p>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-5" role="alert">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form id="admission_form" action="process_form.php" method="POST" enctype="multipart/form-data" class="px-5">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="block">
                <div class="bg-section text-white font-bold p-2 uppercase">A. NAME OF APPLICANT (AS IT APPEARS ON THE BIRTH CERTIFICATE)</div>
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">FIRST NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">LAST NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">MIDDLE NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">EXTENSION NAME</label>
                            <input type="text" name="first_name" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="text" name="last_name" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="text" name="middle_name" class="p-1.5 border border-black w-full box-border">
                            <select name="extension_name" class="p-1.5 border border-black w-full box-border">
                                <option value="N/A" selected>N/A</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                        <div class="bg-section h-5"></div>

                        <div class="grid grid-cols-5">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">DATE OF BIRTH</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">COUNTRY OF BIRTH</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">AGE</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">SEX</label>
                            <input type="date" name="birthday" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="text" name="country_of_birth" aria class="p-1.5 border border-black w-full box-border col-span-2" aria-aria="true">
                            <input type="number" name="age" min="0" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <select name="sex" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-5">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">BLOOD TYPE</label>
                            <select name="blood_type" class="p-1.5 border border-black w-full box-border">
                                <option value="">Select Blood Type</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="bg-section h-5"></div>

                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CIVIL STATUS</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">RELIGIOUS AFFILIATION</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CITIZENSHIP</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">NO. OF SIBLINGS</label>
                            <select name="civil_status" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                                <option value="">Select</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                            <input type="text" name="religious_affiliation" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="citizenship" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="number" name="number_of_siblings" min="0" class="p-1.5 border border-black w-full box-border">
                        </div>
                    </div>
                    <div class="w-48 h-48 border-2 border-dashed border-black text-center flex flex-col justify-center items-center text-black font-bold bg-white mt-1 mx-3 flex-shrink-0 relative">
                        <div>Passport Size<br>1.8 inch x 1.4 inch</div>
                        <label for="upload-photo" class="bg-section text-white p-2 rounded cursor-pointer text-sm mt-2.5 hover:bg-gray-800">Choose File</label>
                        <input type="file" id="upload-photo" name="picture" accept=".jpg,.jpeg,.png,.gif" class="hidden">
                    </div>
                </div>

                <div class="bg-section text-white font-bold p-2 uppercase mt-4">B. APPLICANT'S ADDRESS AND CONTACT INFORMATION</div>
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ADDRESS</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">BARANGAY</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CITY/MUNICIPALITY</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">DISTRICT</label>
                            <input type="text" name="address" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="text" name="barangay" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="city" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="text" name="district" class="p-1.5 border border-black w-full box-border">
                        </div>
                        <div class="grid grid-cols-5">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">REGION</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ZIP CODE</label>
                            <select name="region" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                                <option value="">Select Region</option>
                                <option value="NCR">NCR</option>
                                <option value="Region I">Region I</option>
                                <option value="Region II">Region II</option>
                                <option value="Region III">Region III</option>
                                <option value="CAR">CAR</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" name="zip_code" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                        </div>
                        <div class="grid grid-cols-2">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE EMAIL ADDRESS</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE CONTACT NUMBER</label>
                            <input type="email" name="email_address" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="tel" name="contact_number" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
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
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">FIRST NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">MIDDLE NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">LAST NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">EXTENSION NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">AGE</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">SEX</label>
                            <input type="text" name="guardian_first_name" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="text" name="guardian_middle_name" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="guardian_last_name" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <select name="guardian_extension_name" class="p-1.5 border border-black w-full box-border">
                                <option value="N/A" selected>N/A</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                            <input type="number" name="guardian_age" min="18" class="p-1.5 border border-black w-full box-border">
                            <select name="guardian_sex" class="p-1.5 border border-black w-full box-border">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">RELATIONSHIP TO APPLICANT</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CONTACT NUMBER</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">EMAIL ADDRESS</label>
                            <select name="guardian_relationship" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                                <option value="">Select</option>
                                <option value="Parent">Parent</option>
                                <option value="Aunt">Aunt</option>
                                <option value="Uncle">Uncle</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="tel" name="guardian_contact_number" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="email" name="guardian_email_address" class="p-1.5 border border-black w-full box-border col-span-2">
                        </div>
                    </div>
                </div>

                <div class="bg-section text-white font-bold p-2 uppercase mt-4">C. APPLICANT'S EDUCATIONAL INFORMATION</div>
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ENROLLMENT HISTORY</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">COMPLETE NAME OF SCHOOL</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">ACADEMIC YEAR</label>
                        </div>
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 12</label>
                            <input type="text" name="grade12_school_name" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="text" name="grade12_academic_year" aria class="p-1.5 border border-black w-full box-border" placeholder="YYYY-YYYY" aria-aria="true">
                        </div>
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 12 GWA</label>
                            <input type="number" name="grade12_GWA" min="0" max="100" step="0.01" aria class="p-1.5 border border-black w-full box-border col-span-2" placeholder="General Weighted Average" aria-aria="true">
                        </div>
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 11</label>
                            <input type="text" name="grade11_school_name" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <input type="text" name="grade11_academic_year" aria class="p-1.5 border border-black w-full box-border" placeholder="YYYY-YYYY" aria-aria="true">
                        </div>
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 11 GWA</label>
                            <input type="number" name="grade11_GWA" min="0" max="100" step="0.01" aria class="p-1.5 border border-black w-full box-border col-span-2" placeholder="General Weighted Average" aria-aria="true">
                        </div>
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 10</label>
                            <input type="text" name="grade10_school_name" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="grade10_academic_year" class="p-1.5 border border-black w-full box-border" placeholder="YYYY-YYYY">
                        </div>
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 9</label>
                            <input type="text" name="grade9_school_name" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="grade9_academic_year" class="p-1.5 border border-black w-full box-border" placeholder="YYYY-YYYY">
                        </div>
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 8</label>
                            <input type="text" name="grade8_school_name" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="grade8_academic_year" class="p-1.5 border border-black w-full box-border" placeholder="YYYY-YYYY">
                        </div>
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 7</label>
                            <input type="text" name="grade7_school_name" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="grade7_academic_year" class="p-1.5 border border-black w-full box-border" placeholder="YYYY-YYYY">
                        </div>
                    </div>
                </div>

                <div class="bg-section h-5 mt-4"></div>
                <div class="bg-sidebar h-5"></div>
                <div class="bg-section h-5 flex justify-center items-center">
                    <div class="text-white font-bold text-center text-sm uppercase">CHOOSE YOUR PREFERRED COURSES</div>
                </div>
                <div class="grid grid-cols-2 mt-2">
                    <div class="p-2">
                        <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">FIRST CHOICE</label>
                        <select name="first_course" aria class="p-1.5 border border-black w-full box-border" aria-aria="true">
                            <option value="">Select First Choice</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['course_name']); ?>">
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="p-2">
                        <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">SECOND CHOICE</label>
                        <select name="second_course" class="p-1.5 border border-black w-full box-border">
                            <option value="">Select Second Choice</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['course_name']); ?>">
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="bg-section text-white font-bold p-2 uppercase mt-4">COURSE OFFERED</div>
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">COLLEGE OF TECHNOLOGY</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">COLLEGE OF BUSINESS ADMINISTRATION</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">COLLEGE OF EDUCATION</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Information Technology (IT)</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Accountancy</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Elementary Education (ELED)</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Engineering</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Financial Management</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Secondary Education (SEED)</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Computer Science (CS)</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Marketing Management</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">Special Education (SPED)</label>
                        </div>
                    </div>
                </div>

                <div class="bg-section text-white font-bold p-2 uppercase mt-4">SUPPORTING DOCUMENTS</div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">TRANSCRIPT OF RECORDS</label>
                        <input type="file" name="document_transcript" accept=".pdf,.jpg,.jpeg,.png" class="p-1.5 border border-black w-full box-border">
                    </div>
                    <div>
                        <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">BIRTH CERTIFICATE</label>
                        <input type="file" name="document_birth" accept=".pdf,.jpg,.jpeg,.png" class="p-1.5 border border-black w-full box-border">
                    </div>
                    <div>
                        <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">VALID ID</label>
                        <input type="file" name="document_id" accept=".pdf,.jpg,.jpeg,.png" class="p-1.5 border border-black w-full box-border">
                    </div>
                </div>

                <input type="hidden" name="application_status" value="Pending">
                <input type="hidden" name="submission_date" value="<?php echo date('Y-m-d H:i:s'); ?>">

                <div class="flex justify-end mt-5 mb-2.5 mx-2.5">
                    <button type="button" onclick="cancelForm()" class="bg-cancel text-white py-2.5 px-5 border-none cursor-pointer text-base ml-2.5 mb-2.5 mr-2.5 rounded-full hover:opacity-90">Cancel</button>
                    <button type="button" onclick="showPopup()" class="bg-proceed text-white py-2.5 px-5 border-none cursor-pointer text-base ml-2.5 mb-2.5 mr-2.5 rounded-full hover:opacity-90">Proceed</button>
                </div>
            </div>

            <div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
                    <h2 class="text-lg font-bold mb-4">Confirmation</h2>
                    <p class="mb-4">Are you sure you want to submit?</p>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-5 rounded hover:opacity-90">Submit</button>
                    <button type="button" onclick="closePopup()" class="bg-gray-500 text-white py-2 px-5 rounded hover:opacity-90 ml-2">Cancel</button>
                    <button type="button" onclick="previewForm()" class="bg-yellow-500 text-white py-2 px-5 rounded hover:opacity-90 mt-2">Preview</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function cancelForm() {
            if (confirm("Are you sure you want to cancel? All data will be lost.")) {
                window.location.href = "index.php";
            }
        }

        function showPopup() {
            document.getElementById("popup").classList.remove("hidden");
        }

        function closePopup() {
            document.getElementById("popup").classList.add("hidden");
        }

        function previewForm() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            let previewWindow = window.open("", "Preview", "width=800,height=600");
            previewWindow.document.write("<html><head><title>Form Preview</title><link rel='stylesheet' href='https://cdn.tailwindcss.com'></head><body class='bg-white p-5'>");
            previewWindow.document.write("<h1 class='text-2xl font-bold mb-4'>Form Preview</h1>");
            formData.forEach((value, key) => {
                if (key !== 'picture' && key !== 'document_transcript' && key !== 'document_birth' && key !== 'document_id') {
                    previewWindow.document.write(`<p><strong>${key.replace(/_/g, ' ').toUpperCase()}:</strong> ${value}</p>`);
                } else if (value.name) {
                    previewWindow.document.write(`<p><strong>${key.replace(/_/g, ' ').toUpperCase()}:</strong> ${value.name}</p>`);
                }
            });
            previewWindow.document.write("</body></html>");
            previewWindow.document.close();
        }

        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email_address"]').value;
            const contact = document.querySelector('input[name="contact_number"]').value;
            const guardianEmail = document.querySelector('input[name="guardian_email_address"]').value;
            const guardianContact = document.querySelector('input[name="guardian_contact_number"]').value;
            const firstCourse = document.querySelector('select[name="first_course"]').value;
            const secondCourse = document.querySelector('select[name="second_course"]').value;
            const files = [
                document.querySelector('input[name="picture"]'),
                document.querySelector('input[name="document_transcript"]'),
                document.querySelector('input[name="document_birth"]'),
                document.querySelector('input[name="document_id"]')
            ];

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                document.querySelector('input[name="email_address"]').focus();
                closePopup();
                return;
            }

            if (!/^\+?\d{10,15}$/.test(contact)) {
                e.preventDefault();
                alert('Please enter a valid contact number (10-15 digits).');
                document.querySelector('input[name="contact_number"]').focus();
                closePopup();
                return;
            }

            if (guardianEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(guardianEmail)) {
                e.preventDefault();
                alert('Please enter a valid guardian email address.');
                document.querySelector('input[name="guardian_email_address"]').focus();
                closePopup();
                return;
            }

            if (guardianContact && !/^\+?\d{10,15}$/.test(guardianContact)) {
                e.preventDefault();
                alert('Please enter a valid guardian contact number (10-15 digits).');
                document.querySelector('input[name="guardian_contact_number"]').focus();
                closePopup();
                return;
            }

            if (firstCourse && secondCourse && firstCourse === secondCourse) {
                e.preventDefault();
                alert('First and second course choices must be different.');
                document.querySelector('select[name="second_course"]').focus();
                closePopup();
                return;
            }

            files.forEach(fileInput => {
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const maxSize = 2 * 1024 * 1024; 
                    if (file.size > maxSize) {
                        e.preventDefault();
                        alert(`File "${file.name}" exceeds 2MB limit.`);
                        fileInput.focus();
                        closePopup();
                        return;
                    }
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
                    if (!allowedTypes.includes(file.type)) {
                        e.preventDefault();
                        alert(`File "${file.name}" has an invalid type. Only JPG, PNG, GIF, and PDF are allowed.`);
                        fileInput.focus();
                        closePopup();
                        return;
                    }
                }
            });
        });
    </script>
</body>
</html>