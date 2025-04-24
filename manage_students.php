<?php
session_start();
require 'enrollment.php'; // Adjust if your DB connection file is different

// Redirect if not logged in
if (!isset($_SESSION['admin_name']))

$result = $conn->query("SELECT * FROM enroll");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-100 p-6">
    <h2 class="text-2xl font-bold mb-6">Manage Students</h2>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-blue-800 text-white">
                <th class="py-2 px-4">ID</th>
                <th class="py-2 px-4">Full Name</th>
                <th class="py-2 px-4">Email</th>
                <th class="py-2 px-4">Grade Level</th>
                <th class="py-2 px-4">Status</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="py-2 px-4"><?= $row['user_id'] ?></td>
                    <td class="py-2 px-4"><?= $row['firstname'] . ' ' . $row['last_name'] ?></td>
                    <td class="py-2 px-4"><?= $row['email'] ?></td>
                    <td class="py-2 px-4"><?= $row['grade_level'] ?? 'N/A' ?></td>
                    <td class="py-2 px-4"><?= ucfirst($row['status']) ?></td>
                    <td class="py-2 px-4">
                        <a href="edit_student.php?id=<?= $row['user_id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        |
                        <a href="delete_student.php?id=<?= $row['user_id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
