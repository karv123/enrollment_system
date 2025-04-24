<?php
session_start();
$conn = new mysqli("localhost", "root", "", "enrollment");

// Check if admin is logged in, redirect if not
if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php"); // Redirect to login page if admin is not logged in
    exit();
}

// Fetch pending enrollments
$result = $conn->query("SELECT * FROM enroll WHERE enrollment_status = 'pending'");

// Check if the query is successful
if (!$result) {
    die("Error fetching data: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify Enrollments</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<a href="admin_dashboard.php" class="fixed top-4 right-4 text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-full shadow-lg z-50 text-sm">
    ✕
</a>

<body class="bg-gray-100 p-10">
  <h1 class="text-3xl font-bold mb-6">Pending Enrollments</h1>

  <table class="w-full table-auto bg-white shadow-md rounded">
    <thead>
      <tr class="bg-blue-900 text-white">
      <th class="p-3">Student ID</th>
      <th class="p-3">Student Name</th>
        <th class="p-3">Email</th>
        <th class="p-3">Enrollment Type</th>
        <th class="p-3">Grade Level</th>
        <th class="p-3">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr class="text-center border-t">
      <td class="p-3"><?php echo $row['user_id']; ?></td>
        <td class="p-3"><?php echo $row['firstname'] . ' ' . $row['middle_name']. ' '.  $row['last_name']; ?></td>
        <td class="p-3"><?php echo $row['email']; ?></td>
        <td class="p-3"><?php echo $row['enrollment_type']; ?></td>
        <td class="p-3"><?php echo $row['grade_level']; ?></td>
        <td class="p-3">
          <form method="POST" action="update_status.php" class="flex justify-center gap-2">
            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
            <select name="status" class="border rounded px-2 py-1">
              <option value="approved">Approve</option>
              <option value="rejected">Reject</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Update</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
