<?php
session_start();
require 'enrollment.php'; // Adjust to your actual DB connection file

if (!isset($_SESSION['admin_name'])) 
// Fetch all student data for the report
$query = "SELECT grade_level, section, fullname, email, status FROM enroll";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enrollment Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 20px;
    }
    h1 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #007BFF;
      color: white;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
    }
  </style>
  <script>
    function printPage() {
      window.print();
    }
  </script>
</head>
<body>

  <div class="container">
    <h1>Enrollment Report</h1>
    <button onclick="printPage()">🖨️ Print this page</button>
    
    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Grade Level</th>
            <th>Section</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
              <td><?php echo htmlspecialchars($row['section']); ?></td>
              <td><?php echo htmlspecialchars($row['fullname']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No data available for the report.</p>
    <?php endif; ?>
  </div>

</body>
</html>
