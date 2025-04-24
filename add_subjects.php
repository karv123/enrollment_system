<?php
session_start();
require 'enrollment.php'; // Adjust to your actual DB connection file

if (!isset($_SESSION['admin_name'])) 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];

    // Insert new subject into the database
    $stmt = $conn->prepare("INSERT INTO subjects (subject_name, subject_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $subject_name, $subject_code);

    if ($stmt->execute()) {
        $message = "Subject added successfully!";
    } else {
        $message = "Failed to add subject.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Subject</title>
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
    <h1 class="text-3xl font-bold mb-6">Add Subject</h1>

    <?php if (isset($message)): ?>
      <div class="p-4 mb-4 text-white bg-green-500 rounded">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 shadow rounded">
        <label for="subject_name" class="block mb-2 font-semibold">Subject Name</label>
        <input type="text" name="subject_name" id="subject_name" class="w-full p-3 mb-4 border rounded" required>

        <label for="subject_code" class="block mb-2 font-semibold">Subject Code</label>
        <input type="text" name="subject_code" id="subject_code" class="w-full p-3 mb-4 border rounded" required>

        <button type="submit" class="w-full p-3 bg-blue-600 text-white rounded">Add Subject</button>
    </form>
  </div>

</body>
</html>
