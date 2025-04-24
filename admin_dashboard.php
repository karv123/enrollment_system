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
</nav>
  </div>

  <div class="w-64 bg-blue-900 text-white flex flex-col p-5">
  <h2 class="text-2xl font-bold mb-8 text-center">Admin Dashboard</h2>
  <nav class="flex flex-col space-y-4">

    <a href="verify_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">✔️ Verify Enrollments</a>
    <a href="verify_documents.php" class="hover:bg-blue-700 px-4 py-2 rounded">📄 Verify Documents</a>
    <a href="manage_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">👨‍🎓 Manage Students</a>
    <a href="update_status.php" class="hover:bg-blue-700 px-4 py-2 rounded">🔄 Update Enrollment Status</a>

    <!-- Reports Dropdown -->
    <div class="relative group">
      <button class="w-full text-left hover:bg-blue-700 px-4 py-2 rounded flex justify-between items-center">
      📊 Reports
        <svg class="w-4 h-4 ml-2 transform group-hover:rotate-180 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="absolute left-0 mt-1 w-full bg-blue-800 rounded shadow-lg hidden group-hover:block z-10">
        <a href="reports.php" class="block px-4 py-2 hover:bg-blue-700">📥 Export Excel</a>
        <a href="export_excel.php" class="block px-4 py-2 hover:bg-blue-700">🖨️ Print</a>
      </div>
    </div>

    <!-- Subjects Dropdown -->
    <div class="relative group">
      <button class="w-full text-left hover:bg-blue-700 px-4 py-2 rounded flex justify-between items-center">
      📚 Subjects
        <svg class="w-4 h-4 ml-2 transform group-hover:rotate-180 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="absolute left-0 mt-1 w-full bg-blue-800 rounded shadow-lg hidden group-hover:block z-10">
        <a href="add_subjects.php" class="block px-4 py-2 hover:bg-blue-700">➕ Add Subjects</a>
        <a href="manage_subjects.php" class="block px-4 py-2 hover:bg-blue-700">📂 Manage Subjects</a>
      </div>
    </div>

    <!-- Teachers Dropdown -->
    <div class="relative group">
      <button class="w-full text-left hover:bg-blue-700 px-4 py-2 rounded flex justify-between items-center">
      👥 Staff
        <svg class="w-4 h-4 ml-2 transform group-hover:rotate-180 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="absolute left-0 mt-1 w-full bg-blue-800 rounded shadow-lg hidden group-hover:block z-10">
        <a href="register_teacher.php" class="block px-4 py-2 hover:bg-blue-700">🧑‍🏫 Register Teacher</a>
        <a href="teachers_list.php" class="block px-4 py-2 hover:bg-blue-700"> 📜 Teacher List</a>
        <a href="registrar.php" class="block px-4 py-2 hover:bg-blue-700"> 📋Registrar</a>
      </div>
    </div>

  </nav>
</div>
<style>
  .nav-link {
    display: block;
    padding: 8px 16px;
    background-color: #1e3a8a;
    color: white;
    border-radius: 4px;
    transition: background-color 0.2s;
  }

  .nav-link:hover {
    background-color: #1d4ed8;
  }

  .nav-sublink {
    display: block;
    padding: 6px 16px;
    color: #cbd5e1;
    font-size: 14px;
    transition: color 0.2s;
  }

  .nav-sublink:hover {
    color: #ffffff;
  }
</style>
