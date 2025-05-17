<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admission Application Form</title>
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
<body class="bg-white m-0 p-0 flex">
    <div class="w-32 bg-sidebar flex justify-center items-start pt-5 shadow-md fixed top-0 left-0 h-screen z-10">
        <div class="logo">
            <img src="logo.png" alt="Logo" class="w-20 h-auto">
        </div>
    </div>

    <div class="absolute top-5 right-5 flex z-10">
        <button type="button" id="fillSampleData" style="margin: 20px; padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Fill Sample Data
        </button>
        <button class="bg-transparent border-none cursor-pointer p-2 rounded-full transition-all hover:bg-black/10" title="Contact">
            <img src="phone.png" alt="Contact" class="w-10 h-10">
        </button>
        <button class="bg-transparent border-none cursor-pointer p-2 rounded-full transition-all hover:bg-black/10" title="Notification">
            <img src="bell.png" alt="Notification" class="w-10 h-10">
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
        
        <form action="preview_form.php" method="POST" enctype="multipart/form-data">
            <div class="block">
                <div class="bg-section text-white font-bold p-2 uppercase">A. NAME OF APPLICANT (AS IT APPEARS ON THE BIRTH CERTIFICATE)</div>
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">FIRST NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">LAST NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">MIDDLE NAME</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">EXTENSION NAME</label>
                <input type="text" name="first_name" maxlength="50" required class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="last_name" maxlength="50" required class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="middle_name" maxlength="50" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="extension_name" maxlength="10" class="p-1.5 border border-black w-full box-border">
                        </div>
                        <div class="bg-section h-5"></div>

                        <div class="grid grid-cols-5">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">DATE OF BIRTH</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">PLACE OF BIRTH</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">AGE</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">SEX</label>
                            <input type="date" name="date_of_birth"  class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="place_of_birth"  class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="number" name="age" min="0"  class="p-1.5 border border-black w-full box-border">
                            <select name="sex"  class="p-1.5 border border-black w-full box-border">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-5">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">BLOOD TYPE</label>
                            <input type="text" name="blood_type" class="p-1.5 border border-black w-full box-border">
                        </div>
                        <div class="bg-section h-5"></div>

                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CIVIL STATUS</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">RELIGIOUS AFFILIATION</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CITIZENSHIP</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">NO. OF SIBLINGS</label>
                            <select name="civil_status"  class="p-1.5 border border-black w-full box-border">
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                            <input type="text" name="religious_affiliation"  class="p-1.5 border border-black w-full box-border">
                            <select name="citizenship"  class="p-1.5 border border-black w-full box-border">
                                <option value="Filipino">Filipino</option>
                            </select>
                            <input type="number" name="no_of_siblings" min="0"  class="p-1.5 border border-black w-full box-border">
                        </div>
                    </div>
                    <div class="w-48 h-48 border-2 border-dashed border-black text-center flex flex-col justify-center items-center text-black font-bold bg-white mt-1 mx-3 flex-shrink-0 relative">
                        <div>Passport Size<br>1.8 inch x 1.4 inch</div>
                        <label for="upload-photo" class="bg-section text-white p-2 rounded cursor-pointer text-sm mt-2.5 hover:bg-gray-800">Choose File</label>
                        <input type="file" id="upload-photo" name="photo" accept="image/*" class="hidden">
                    </div>
                </div>

                <div class="bg-section text-white font-bold p-2 uppercase mt-4">B. APPLICANT'S ADDRESS AND CONTACT INFORMATION</div>
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="grid grid-cols-5">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">HOUSE/ROOM/BLDG/BLOCK/PHASE/STREET/SUBD</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">BARANGAY</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">CITY/MUNICIPALITY</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">DISTRICT</label>
                            <input type="text" name="house"  class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="text" name="barangay"  class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="city"  class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="district"  class="p-1.5 border border-black w-full box-border">
                        </div>
                        <div class="grid grid-cols-5">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ZIP CODE</label>
                            <input type="number" name="zip_code" min="0"  class="p-1.5 border border-black w-full box-border">
                        </div>

                        <div class="grid grid-cols-3">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE PERSONAL NUMBER</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE PERSONAL EMAIL ADDRESS</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">LANDLINE NUMBER</label>
                            <input type="number" name="personal_number" min="0"  class="p-1.5 border border-black w-full box-border">
                            <input type="email" name="personal_email"  class="p-1.5 border border-black w-full box-border">
                            <input type="number" name="landline_number" min="0" class="p-1.5 border border-black w-full box-border">
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
                        </div>

                        <div class="grid grid-cols-6">
                            <input type="text" name="guardian_first_name"  class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="guardian_middle_name" class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="guardian_last_name"  class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="guardian_extension_name" class="p-1.5 border border-black w-full box-border">
                            <input type="number" name="guardian_age" min="0"  class="p-1.5 border border-black w-full box-border">
                            <select name="guardian_sex"  class="p-1.5 border border-black w-full box-border">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">RELATIONSHIP TO APPLICANT</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">CURRENT ADDRESS</label>
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE CONTACT NUMBER</label>
                        </div>

                        <div class="grid grid-cols-4">
                            <input type="text" name="guardian_relationship"  class="p-1.5 border border-black w-full box-border">
                            <input type="text" name="guardian_address"  class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="number" name="guardian_contact_number" min="0"  class="p-1.5 border border-black w-full box-border">
                        </div>
                        
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ACTIVE EMAIL ADDRESS</label>
                            <input type="email" name="guardian_email"  class="p-1.5 border border-black w-full box-border">
                        </div>
                    </div>
                </div>

                <div class="bg-section text-white font-bold p-2 uppercase mt-4">C. APPLICANT'S EDUCATIONAL INFORMATION</div>
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">ENROLLMENT HISTORY</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center col-span-2">COMPLETE NAME OF SCHOOL</label>
                            <label class="p-1 font-bold text-sm text-black bg-white border border-black flex items-center">PERIOD OF ATTENDANCE</label>
                        </div>
                        
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 12</label>
                            <input type="text" name="grade12_school" class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="text" name="grade12_period" class="p-1.5 border border-black w-full box-border">
                        </div>
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 12 GWA</label>
                            <input type="number" name="grade12_gwa" step="0.01" min="75" max="100" class="p-1.5 border border-black w-full box-border" required>
                        </div>
                        
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 11</label>
                            <input type="text" name="grade11_school" class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="text" name="grade11_period" class="p-1.5 border border-black w-full box-border">
                        </div>
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 11 GWA</label>
                            <input type="number" name="grade11_gwa" step="0.01" min="75" max="100" class="p-1.5 border border-black w-full box-border" required>
                        </div>
                        
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 10</label>
                            <input type="text" name="grade10_school"  class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="text" name="grade10_period"  class="p-1.5 border border-black w-full box-border">
                        </div>
                        
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 9</label>
                            <input type="text" name="grade9_school"  class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="text" name="grade9_period"  class="p-1.5 border border-black w-full box-border">
                        </div>
                        
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 8</label>
                            <input type="text" name="grade8_school"  class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="text" name="grade8_period"  class="p-1.5 border border-black w-full box-border">
                        </div>
                        
                        <div class="grid grid-cols-4">
                            <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center">GRADE 7</label>
                            <input type="text" name="grade7_school"  class="p-1.5 border border-black w-full box-border col-span-2">
                            <input type="text" name="grade7_period"  class="p-1.5 border border-black w-full box-border">
                        </div>
                    </div>
                </div>
                
                <div class="bg-section h-5 mt-4"></div>
                <div class="bg-sidebar h-5"></div>
                <div class="bg-section h-5 flex justify-center items-center">
                    <div class="text-white font-bold text-center text-sm uppercase">CHOOSE THE SELECTED COLLEGE AND COURSES</div>
                </div>
                
                <div class="grid grid-cols-11 mt-2">
                    <label class="p-1 font-bold text-sm text-black bg-sidebar border border-black flex items-center col-span-2">COLLEGE & COURSE</label>
                    <div class="col-span-9 flex">
                        <select name="college_offered"  class="p-1.5 border border-black w-full box-border">
                            <option value="">Select Course Offered</option>
                            <option value="College of Technology">College of Technology</option>
                            <option value="College of Business Administration">College of Business Administration</option>
                            <option value="College of Education">College of Education</option>
                        </select>
                        <select name="course_offered"  class="p-1.5 border border-black w-full box-border">
                            <option value="">Select Course Offered</option>
                            <option value="Information Technology (IT)">Information Technology (IT)</option>
                            <option value="Accountancy">Accountancy</option>
                            <option value="Elementary Education (ELED)">Elementary Education (ELED)</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Financial Management">Financial Management</option>
                            <option value="Secondary Education (SEED)">Secondary Education (SEED)</option>
                            <option value="Computer Science (CS)">Computer Science (CS)</option>
                            <option value="Marketing Management">Marketing Management</option>
                            <option value="Special Education (SPED)">Special Education (SPED)</option>
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

                <div class="bg-section text-white font-bold p-2 uppercase mt-4">DATA PRIVACY AND CONSENT DECLARATION</div>
                <div class="flex justify-between items-start">
                    <div class="flex-grow p-4">
                        <div class="bg-white p-4 border border-black rounded">
                            <h3 class="font-bold mb-2">Consent Notice</h3>
                            <p class="text-sm mb-4">
                                I hereby give my consent to TOM YANG COLLEGE to collect, record, organize, update, use, consolidate, and/or process my personal data and my child's/ward's personal data. I understand that my personal information and my child's/ward's personal information is being collected, accessed, used, processed, and stored for the following purposes:
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
                                <label class="block mb-2 font-bold">Student's Full Name</label>
                                <input type="text" name="student_consent_name" required class="p-1.5 border border-black w-full box-border">
                                <div class="border-2 border-dashed border-black h-32 mt-2 relative">
                                    <input type="file" name="student_signature" accept="image/jpeg,image/png" class="absolute inset-0 opacity-0 cursor-pointer" required>
                                    <div class="flex items-center justify-center h-full">
                                        <span class="text-gray-500">Click to upload signature (JPG/PNG)</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 font-bold">Guardian/Parent's Full Name</label>
                                <input type="text" name="guardian_consent_name" required class="p-1.5 border border-black w-full box-border">
                                <div class="border-2 border-dashed border-black h-32 mt-2 relative">
                                    <input type="file" name="guardian_signature" accept="image/jpeg,image/png" class="absolute inset-0 opacity-0 cursor-pointer" required>
                                    <div class="flex items-center justify-center h-full">
                                        <span class="text-gray-500">Click to upload signature (JPG/PNG)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-5 mb-2.5 mx-2.5">
                    <button type="button" onclick="cancelForm()" class="bg-red-500 text-white py-2.5 px-5 border-none cursor-pointer text-base ml-2.5 mb-2.5 mr-2.5 rounded-full hover:opacity-90">Cancel</button>
                    <button type="button" onclick="showPopup()" class="bg-green-500 text-white py-2.5 px-5 border-none cursor-pointer text-base ml-2.5 mb-2.5 mr-2.5 rounded-full hover:opacity-90">Proceed</button> 
               </div>
            </div>
            <div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
            <h2 class="text-lg font-bold mb-4">Confirmation</h2>
                    <p class="mb-4">Are you sure you want to proceed?</p>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-5 rounded hover:opacity-90">OK</button>
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

      document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = [
        'first_name', 'last_name', 'date_of_birth', 'place_of_birth', 
        'age', 'sex', 'civil_status', 'citizenship',
        'house', 'barangay', 'city', 'personal_number', 'personal_email',
        'guardian_first_name', 'guardian_last_name', 'guardian_age', 
        'guardian_sex', 'guardian_relationship', 'guardian_address', 
        'guardian_contact_number', 'guardian_email', 'college_offered', 'course_offered',
        'grade12_gwa', 'grade11_gwa', 'student_consent_name', 'guardian_consent_name',
        'student_signature', 'guardian_signature'
    ];
    
    let isValid = true;
    let firstInvalidField = null;
    let errorMessages = [];

    // Maximum character lengths
    const maxLengths = {
        first_name: 50,
        last_name: 50,
        middle_name: 50,
        extension_name: 10,
        place_of_birth: 100,
        religious_affiliation: 50,
        house: 100,
        barangay: 50,
        city: 50,
        district: 50,
        personal_email: 100,
        guardian_first_name: 50,
        guardian_last_name: 50,
        guardian_middle_name: 50,
        guardian_extension_name: 10,
        guardian_relationship: 50,
        guardian_address: 100,
        guardian_email: 100
    };

    // Check required fields and max lengths
    requiredFields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (!element.value.trim()) {
            isValid = false;
            element.classList.add('border-red-500');
            if (!firstInvalidField) firstInvalidField = element;
            errorMessages.push(`${field.replace(/_/g, ' ').toUpperCase()} is required`);
        } else {
            element.classList.remove('border-red-500');
            
            // Check max length if specified
            if (maxLengths[field] && element.value.length > maxLengths[field]) {
                isValid = false;
                element.classList.add('border-red-500');
                if (!firstInvalidField) firstInvalidField = element;
                errorMessages.push(`${field.replace(/_/g, ' ').toUpperCase()} must not exceed ${maxLengths[field]} characters`);
            }
        }
    });

    // Email validation function
    function validateEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }

    // Phone number validation function
    function validatePhoneNumber(phone) {
        // Philippine phone number format: +63 or 0 followed by 9 digits
        const phoneRegex = /^(\+63|0)[0-9]{10}$/;
        return phoneRegex.test(phone);
    }

    // Validate email addresses
    const emailFields = ['personal_email', 'guardian_email'];
    emailFields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element.value.trim() && !validateEmail(element.value.trim())) {
            isValid = false;
            element.classList.add('border-red-500');
            if (!firstInvalidField) firstInvalidField = element;
            errorMessages.push(`Please enter a valid email address for ${field.replace(/_/g, ' ').toUpperCase()}`);
        }
    });

    // Validate phone numbers
    const phoneFields = ['personal_number', 'guardian_contact_number', 'landline_number'];
    phoneFields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element.value.trim() && !validatePhoneNumber(element.value.trim())) {
            isValid = false;
            element.classList.add('border-red-500');
            if (!firstInvalidField) firstInvalidField = element;
            errorMessages.push(`Please enter a valid phone number for ${field.replace(/_/g, ' ').toUpperCase()} (format: 09XXXXXXXXX or +639XXXXXXXXX)`);
        }
    });

    // Age validation
    const ageField = document.querySelector('[name="age"]');
    if (ageField.value < 16 || ageField.value > 70) {
        isValid = false;
        ageField.classList.add('border-red-500');
        if (!firstInvalidField) firstInvalidField = ageField;
        errorMessages.push('Age must be between 16 and 70');
    }

    // Guardian age validation
    const guardianAgeField = document.querySelector('[name="guardian_age"]');
    if (guardianAgeField.value < 21 || guardianAgeField.value > 100) {
        isValid = false;
        guardianAgeField.classList.add('border-red-500');
        if (!firstInvalidField) firstInvalidField = guardianAgeField;
        errorMessages.push('Guardian age must be between 21 and 100');
    }

    if (!isValid) {
        e.preventDefault();
        closePopup();
        alert(errorMessages.join('\n'));
        firstInvalidField.focus();
    }
});
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sampleData = {
            first_name: "John",
            last_name: "Doe",
            middle_name: "A.",
            extension_name: "Jr.",
            date_of_birth: "2000-01-01",
            place_of_birth: "Manila",
            age: 24,
            sex: "Male",
            blood_type: "O",
            civil_status: "Single",
            religious_affiliation: "Catholic",
            citizenship: "Filipino",
            no_of_siblings: 3,
            house: "1234",
            barangay: "Barangay 123",
            city: "Quezon City",
            district: "4th District",
            zip_code: "1100",
            personal_number: "09171234567",
            personal_email: "john.doe@example.com",
            landline_number: "82456789",
            guardian_first_name: "Jane",
            guardian_middle_name: "B.",
            guardian_last_name: "Doe",
            guardian_extension_name: "",
            guardian_age: 45,
            guardian_sex: "Female",
            guardian_relationship: "Mother",
            guardian_address: "456 Guardian St.",
            guardian_contact_number: "09221234567",
            guardian_email: "jane.doe@example.com",
            grade12_school: "High School XYZ",
            grade12_period: "2018-2019",
            grade12_gwa: 90.5,
            grade11_school: "High School XYZ",
            grade11_period: "2017-2018",
            grade11_gwa: 89.0,
            grade10_school: "High School XYZ",
            grade10_period: "2016-2017",
            grade9_school: "High School XYZ",
            grade9_period: "2015-2016",
            grade8_school: "High School XYZ",
            grade8_period: "2014-2015",
            grade7_school: "High School XYZ",
            grade7_period: "2013-2014",
            college_offered: "College of Technology",
            course_offered: "Information Technology (IT) ",
        };

        document.getElementById('fillSampleData').addEventListener('click', function () {
            for (const [field, value] of Object.entries(sampleData)) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.value = value;
                }
            }

            // Optional: If you want to simulate radio button selection
            if (sampleData.sex) {
                const sexInput = document.querySelector(`input[name="sex"][value="${sampleData.sex}"]`);
                if (sexInput) {
                    sexInput.checked = true;
                }
            }

            if (sampleData.guardian_sex) {
                const guardianSexInput = document.querySelector(`input[name="guardian_sex"][value="${sampleData.guardian_sex}"]`);
                if (guardianSexInput) {
                    guardianSexInput.checked = true;
                }
            }
        });
    });
    </script>
</body>
</html>

