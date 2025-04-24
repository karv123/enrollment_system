<?php
session_start();
$conn = new mysqli("localhost", "root", "", "enrollment");

// Check login
if (!isset($_SESSION['user_id'])) 

// Get user ID
$user_id = $_SESSION['user_id'];

// Fetch enrollment status
$status = "Not Enrolled";
$result = $conn->query("SELECT status FROM enroll WHERE user_id = $user_id");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $status = ucfirst($row['status']);
}

// Status Badge Color
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'approved': return 'green';
        case 'pending': return 'orange';
        case 'rejected': return 'red';
        default: return 'gray';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enrollment Status</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background-color: #f9f9f9;
    }

    .status-card {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      border-radius: 8px;
      text-align: center;
    }

    .status-badge {
      display: inline-block;
      padding: 10px 20px;
      border-radius: 25px;
      font-weight: bold;
      color: white;
      background-color: <?= getStatusColor($status); ?>;
    }

    .status-info {
      margin-top: 15px;
      font-size: 18px;
    }
  </style>
</head>
<body>

<div class="status-card">
  <h2>Enrollment Status</h2>
  <div class="status-badge"><?= $status ?></div>
  <p class="status-info">
    <?php
    switch (strtolower($status)) {
        case 'approved': echo "Congratulations! Your enrollment has been approved."; break;
        case 'pending': echo "Your application is currently under review."; break;
        case 'rejected': echo "Your enrollment has been rejected. Please contact admin."; break;
        default: echo "No enrollment found. Please apply first.";
    }
    ?>
  </p>
</div>

</body>
</html>
