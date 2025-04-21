<?php
session_start();
if (!isset($_SESSION['student_name'])) 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            padding: 30px;
        }
        .logout {
            float: right;
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <a class="logout" href="logout.php">Logout</a>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?>!</h2>
    </div>

    <div class="container">
        <p>This is your student dashboard. Here you can:</p>
        <ul>
            <li>View and update your enrollment form</li>
            <li>Check announcements</li>
            <li>Manage your profile</li>
        </ul>
    </div>
</body>
</html>
