<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_name']))
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex h-screen">

  <!-- Sidebar -->
  <div class="w-64 bg-blue-900 text-white flex flex-col p-5">
    <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
    <nav class="flex flex-col space-y-4">
      <a href="verify_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Verify Enrollments</a>
      <a href="manage_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Manage Students</a>
      <a href="add_subjects.php" class="hover:bg-blue-700 px-4 py-2 rounded">Add Subjects</a>
      <a href="register_teacher.php" class="hover:bg-blue-700 px-4 py-2 rounded">Register Teachers</a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-10">
    <h1 class="text-3xl font-bold mb-6">Welcome, Admin</h1>
    <p class="text-gray-700">Use the sidebar to manage enrollments, subjects, and teachers.</p>
    
    <!-- Optional: Dashboard cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
      <div class="bg-white shadow p-6 rounded-lg">
        <h3 class="text-lg font-semibold mb-2">Pending Enrollments</h3>
        <p>View and approve students waiting for verification.</p>
        <a href="verify_students.php" class="text-blue-600 hover:underline mt-2 inline-block">Go to Page →</a>
      </div>
      <div class="bg-white shadow p-6 rounded-lg">
        <h3 class="text-lg font-semibold mb-2">Subjects</h3>
        <p>Add or update subjects offered by the school.</p>
        <a href="add_subjects.php" class="text-blue-600 hover:underline mt-2 inline-block">Go to Page →</a>
      </div>
    </div>
  </div>

</body>
</html>
