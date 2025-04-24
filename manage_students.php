<?php
session_start();
require 'enrollment.php'; // Adjust if your DB connection file is different

// Redirect if not logged in
if (!isset($_SESSION['admin_name'])) {
    header('Location: login.php');
    exit();
}

// Now run the query
$result = $conn->query("SELECT * FROM enroll");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <!-- Close Button -->
    <a href="admin_dashboard.php" class="fixed top-4 right-4 text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-full shadow-lg z-50 text-sm">
        ✕
    </a>

    <h2 class="text-2xl font-bold mb-6">Manage Students</h2>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-blue-800 text-white">
                <th class="py-2 px-4">ID</th>
                <th class="py-2 px-4">Full Name</th>
                <th class="py-2 px-4">Email</th>
                <th class="py-2 px-4">Grade Level</th>
                
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4"><?= htmlspecialchars($row['user_id']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($row['firstname'] . ' ' . $row['middle_name']. ' ' . $row['last_name']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($row['grade_level'] ?? 'N/A') ?></td>
                    
                    <td class="py-2 px-4">
                        <a href="edit_student.php?id=<?= $row['user_id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        |
                        <a href="#" onclick="confirmDelete(<?= $row['user_id'] ?>)" class="text-red-600 hover:underline">Delete</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
        <!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded shadow-lg text-center max-w-sm w-full">
        <p class="text-lg font-semibold mb-4">Are you sure you want to delete this student?</p>
        <div class="flex justify-center space-x-4">
            <button onclick="proceedDelete()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Yes, Delete</button>
            <button onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
        </div>
    </div>
</div>

<script>
    let deleteId = null;

    function confirmDelete(id) {
        deleteId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeModal() {
        deleteId = null;
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function proceedDelete() {
        if (deleteId !== null) {
            window.location.href = 'delete_student.php?id=' + deleteId;
        }
    }
</script>

</body>
</html>
