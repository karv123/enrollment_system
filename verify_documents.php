<?php
session_start();
require 'enrollment.php'; // Adjust if your DB connection file is named differently

// Redirect if not logged in
if (!isset($_SESSION['admin_name'])) 

// Fetch students with uploaded documents
$query = "SELECT * FROM enroll WHERE birth_certificate IS NOT NULL AND report_card IS NOT NULL";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Documents</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-100 p-6">
    <h2 class="text-2xl font-bold mb-4">Verify Uploaded Documents</h2>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-blue-800 text-white">
                <th class="py-2 px-4 text-left">Name</th>
                <th class="py-2 px-4 text-left">Birth Certificate</th>
                <th class="py-2 px-4 text-left">Report Card</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="py-2 px-4"><?= $row['firstname'] . ' ' . $row['last_name'] ?></td>
                    <td class="py-2 px-4">
                        <a href="uploads/<?= $row['birth_certificate'] ?>" target="_blank" class="text-blue-600 underline">View</a>
                    </td>
                    <td class="py-2 px-4">
                        <a href="uploads/<?= $row['report_card'] ?>" target="_blank" class="text-blue-600 underline">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
