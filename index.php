<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tom Yang College - Admissions</title>
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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white m-0 p-0">
    <div class="w-full bg-header py-4 shadow-md">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl font-bold text-white">TOM YANG COLLEGE</h1>
            <p class="text-white text-lg">Admissions Portal</p>
        </div>
    </div>
    
    <div class="container mx-auto px-4 py-8">
        <div class="bg-container rounded-lg shadow-md p-8 max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold text-section mb-6 text-center">Welcome to the Admissions Portal</h2>
            
            <p class="mb-6 text-gray-700">Thank you for your interest in Tom Yang College. We are dedicated to providing quality education and fostering academic excellence.</p>
            
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-xl font-bold text-section mb-4">Apply for Admission</h3>
                <p class="mb-4">Ready to begin your academic journey with us? Click the button below to fill out our admission application form.</p>
                <div class="text-center">
                    <a href="admission_form.php" class="bg-green-500 text-white py-2 px-6 rounded-full hover:bg-green-600 inline-block">Start Application</a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-section mb-3">Application Requirements</h3>
                    <ul class="list-disc pl-5 text-gray-700">
                        <li>Valid ID</li>
                        <li>Passport-sized photo</li>
                        <li>Academic records</li>
                        <li>Birth certificate</li>
                    </ul>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-section mb-3">Contact Information</h3>
                    <p class="text-gray-700">
                        Email: admissions@tomyangcollege.edu<br>
                        Phone: (123) 456-7890<br>
                        Address: 123 Education Lane, Knowledge City
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-section text-white py-4 mt-8">
        <div class="container mx-auto text-center">
            <p>© 2025 Tom Yang College. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>