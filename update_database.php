<?php
$conn = require_once 'db_connect.php';

// Array of ALTER TABLE statements
$alterTableQueries = [
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS profile_photo VARCHAR(255)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS student_signature VARCHAR(255)",
    "ALTER TABLE applicants ADD COLUMN IF NOT EXISTS guardian_signature VARCHAR(255)"
];

$success = true;
$messages = [];

foreach ($alterTableQueries as $query) {
    if ($conn->query($query) === TRUE) {
        $messages[] = "Successfully executed: " . $query;
    } else {
        $success = false;
        $messages[] = "Error executing query: " . $conn->error;
    }
}

// Return results
echo "<h2>Database Update Results:</h2>";
foreach ($messages as $message) {
    echo $message . "<br>";
}
echo "<br>";
echo $success ? "Database update completed successfully!" : "Some errors occurred during the update.";

$conn->close();
?>
