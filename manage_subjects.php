<?php
session_start();
require 'enrollment.php'; // Adjust to your actual DB connection file

if (!isset($_SESSION['admin_name'])) 

// Delete subject if requested
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all subjects from the database
$result = $conn->query("SELECT * FROM subjects");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Subjects</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<a href="admin_dashboard.php" class="fixed top-4 right-4 text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-full shadow-lg z-50 text-sm">
        ✕
    </a>

<body class="bg-gray-100 font-sans">

  <!-- Sidebar -->
  <div class="w-64 bg-blue-900 text-white flex flex-col p-5">
    <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
    <nav class="flex flex-col space-y-4">
        <a href="verify_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Verify Enrollments</a>
        <a href="verify_documents.php" class="hover:bg-blue-700 px-4 py-2 rounded">Verify Documents</a>
        <a href="manage_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Manage Students</a>
        <a href="update_status.php" class="hover:bg-blue-700 px-4 py-2 rounded">Update Enrollment Status</a>
        <a href="reports.php" class="hover:bg-blue-700 px-4 py-2 rounded">Reports</a>
        <a href="subjects.php" class="hover:bg-blue-700 px-4 py-2 rounded">Subjects</a>
        <a href="register_teacher.php" class="hover:bg-blue-700 px-4 py-2 rounded">Register Teachers</a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-10">
    <h1 class="text-3xl font-bold mb-6">Manage Subjects</h1>

    <table class="min-w-full bg-white border shadow-md rounded">
      <thead>
        <tr>
          <th class="py-2 px-4 border-b text-left">Subject Name</th>
          <th class="py-2 px-4 border-b text-left">Subject Code</th>
          <th class="py-2 px-4 border-b text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['subject_name']) ?></td>
            <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['subject_code']) ?></td>
            <td class="py-2 px-4 border-b">
              <a href="edit_subject.php?id=<?= $row['id'] ?>" class="text-blue-500 hover:underline">Edit</a> |
              <a href="?delete_id=<?= $row['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this subject?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>

</body>
</html>
