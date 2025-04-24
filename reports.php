<?php
$conn = new mysqli("localhost", "root", "", "enrollment");

// Fetch count of students per grade level
$grade_query = $conn->query("SELECT grade_level, COUNT(*) AS total FROM enroll GROUP BY grade_level");

// Fetch detailed class list (example: Grade 7 - Section A)
$class_query = $conn->query("SELECT grade_level, section, firstname, last_name FROM enroll ORDER BY grade_level, section");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Enrollment Reports</title>
  <style>
    table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f0f0f0; }
    .btn { margin: 10px; padding: 8px 12px; background: #4CAF50; color: white; border: none; cursor: pointer; }
  </style>
</head>
<body>
<div style="margin-bottom: 20px;">
  <button onclick="window.print()">🖨️ Print this page</button>
</div>

  <h2>Student Enrollment Summary</h2>
  <table>
    <tr><th>Grade Level</th><th>Total Students</th></tr>
    <?php while ($row = $grade_query->fetch_assoc()) { ?>
      <tr><td><?= $row['grade_level'] ?></td><td><?= $row['total'] ?></td></tr>
    <?php } ?>
  </table>

  <h2>Class List Report</h2>
  <table>
    <tr><th>Grade</th><th>Section</th><th>First Name</th><th>Last Name</th></tr>
    <?php while ($row = $class_query->fetch_assoc()) { ?>
      <tr>
        <td><?= $row['grade_level'] ?></td>
        <td><?= $row['section'] ?></td>
        <td><?= $row['firstname'] ?></td>
        <td><?= $row['last_name'] ?></td>
      </tr>
    <?php } ?>
  </table>

  <!-- Export Buttons -->
  <button class="btn" onclick="window.print()">🖨️ Print Report</button>
  <button class="btn" onclick="window.location.href='export_excel.php'">📊 Export to Excel</button>
  <button class="btn" onclick="window.location.href='export_pdf.php'">📄 Export to PDF</button>

</body>
</html>
