<!DOCTYPE html>
<html lang="en" class="font-[Roboto] h-full flex flex-1 overflow-auto box-border">

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

        <!-- Original index.php content -->
        <div class="container mx-auto px-4 py-8">
            <div class="bg-container rounded-lg shadow-md p-8 max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-section mb-6 text-center">Welcome to the Admissions Portal</h2>
                
                <p class="mb-6 text-gray-700">Thank you for your interest in Tom Yang College. We are dedicated to providing quality education and fostering academic excellence.</p>
                
                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <h3 class="text-xl font-bold text-section mb-4">Apply for Admission</h3>
                    <p class="mb-4">Ready to begin your academic journey with us? Click the button below to fill out our admission application form.</p>
                                        <div class="text-center">                        <a href="admission_form.php?reset=1" onclick="sessionStorage.clear();" class="bg-green-500 text-white py-2 px-6 rounded-full hover:bg-green-600 inline-block">Start Application</a>                    </div>
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