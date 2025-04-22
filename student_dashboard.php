<?php
session_start();
if (!isset($_SESSION['student_name'])) 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sidebar Menu</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f2f5;
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      color: #ecf0f1;
      padding-top: 20px;
      display: flex;
      flex-direction: column;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .menu-section {
      border-top: 1px solid #34495e;
    }

    .menu-title {
      padding: 15px 20px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: background-color 0.3s;
    }

    .menu-title:hover {
      background-color: #34495e;
    }

    .menu-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      background-color: #34495e;
    }

    .menu-content ul {
      list-style: none;
      padding-left: 20px;
      margin: 0;
    }

    .menu-content li {
      padding: 10px 0;
      border-bottom: 1px solid #3d566e;
    }

    .arrow {
      transition: transform 0.3s ease;
    }

    .menu-section.active .menu-content {
      max-height: 300px;
    }

    .menu-section.active .arrow {
      transform: rotate(90deg);
    }

    .main-content {
      flex: 1;
      padding: 40px;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Dashboard</h2>

    <div class="menu-section" onclick="toggleMenu(this)">
      <div class="menu-title">
        <span>Enrollment</span>
        <span class="arrow">▶</span>
      </div>
      <div class="menu-content">
        <ul>
          <li>Apply for Enrollment</li>
          <li>Modify Enrollment</li>
          <li>Add/Withdraw Subjects</li>
        </ul>
      </div>
    </div>

    <div class="menu-section" onclick="toggleMenu(this)">
      <div class="menu-title">
        <span>Grades</span>
        <span class="arrow">▶</span>
      </div>
      <div class="menu-content">
        <ul>
          <li>Report of Grades</li>
        </ul>
      </div>
    </div>

    <div class="menu-section" onclick="toggleMenu(this)">
      <div class="menu-title">
        <span>Account</span>
        <span class="arrow">▶</span>
      </div>
      <div class="menu-content">
        <ul>
          <li>My Account</li>
          <li>Log out</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-content">
    <h1>Welcome to the Student Portal</h1>
    <p>Select an option from the sidebar to get started.</p>
  </div>

  <script>
    function toggleMenu(element) {
      element.classList.toggle("active");
    }
  </script>

</body>
</html>
