<?php
session_start();

// Store the form data in the session
$_SESSION['form_data'] = $_POST;
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
            <table class="table-auto w-full border-collapse border border-black">
                <tbody>
                    <?php foreach ($_SESSION['form_data'] as $key => $value): ?>
                    <?php if ($key != 'photo'): // Skip displaying the photo path ?>
                    <tr class="border-b border-black">
                        <td class="p-2 font-bold text-black border-r border-black w-1/4"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></td>
                        <td class="p-2 text-black"><?php echo htmlspecialchars($value); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (!empty($_FILES['photo']['name'])): ?>
                    <tr class="border-b border-black">
                        <td class="p-2 font-bold text-black border-r border-black w-1/4">Photo</td>
                        <td class="p-2 text-black">
                            <img src="uploads/<?php echo htmlspecialchars($_FILES['photo']['name']); ?>" alt="Applicant Photo" class="max-w-48 max-h-48">
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex justify-center mt-8">
            <form action="process_form.php" method="POST" class="mr-4">
                <?php foreach ($_SESSION['form_data'] as $key => $value): ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
                <?php endforeach; ?>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Confirm & Submit</button>
            </form>
            <form action="admission_form.php" method="POST">
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