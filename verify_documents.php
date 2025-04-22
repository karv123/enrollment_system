<?php
session_start();
if (!isset($_SESSION['admin_name'])) 

$conn = new mysqli("localhost", "root", "", "enrollment");

// Handle document verification form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doc_id'], $_POST['action'])) {
    $doc_id = $_POST['doc_id'];
    $verified = ($_POST['action'] === 'verify') ? 1 : 0;
    $stmt = $conn->prepare("UPDATE documents SET is_verified = ? WHERE id = ?");
    $stmt->bind_param("ii", $verified, $doc_id);
    $stmt->execute();
}

// Fetch documents with student data
$sql = "
SELECT d.id, d.student_id, d.file_type, d.file_path, d.is_verified, s.full_name
FROM documents d
JOIN students s ON d.student_id = s.user_id
ORDER BY d.upload_date DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify Documents</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex h-screen">

  <!-- Sidebar -->
  <div class="w-64 bg-blue-900 text-white flex flex-col p-5">
    <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
    <nav class="flex flex-col space-y-4">
      <a href="admin_dashboard.php" class="hover:bg-blue-700 px-4 py-2 rounded">Dashboard</a>
      <a href="verify_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Verify Enrollments</a>
      <a href="verify_documents.php" class="bg-blue-700 px-4 py-2 rounded">Verify Documents</a>
      <a href="manage_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Manage Students</a>
      <a href="add_subjects.php" class="hover:bg-blue-700 px-4 py-2 rounded">Add Subjects</a>
      <a href="register_teacher.php" class="hover:bg-blue-700 px-4 py-2 rounded">Register Teachers</a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-10 overflow-y-auto">
    <h1 class="text-3xl font-bold mb-6">Verify Student Documents</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="min-w-full table-auto border-collapse">
        <thead class="bg-blue-100 text-left">
          <tr>
            <th class="px-6 py-3">Student Name</th>
            <th class="px-6 py-3">Document Type</th>
            <th class="px-6 py-3">View</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="border-b">
            <td class="px-6 py-4"><?= htmlspecialchars($row['full_name']) ?></td>
            <td class="px-6 py-4 capitalize"><?= $row['file_type'] ?></td>
            <td class="px-6 py-4">
              <a href="<?= $row['file_path'] ?>" target="_blank" class="text-blue-600 underline">View File</a>
            </td>
            <td class="px-6 py-4">
              <?php if ($row['is_verified']): ?>
                <span class="text-green-600 font-semibold">Verified</span>
              <?php else: ?>
                <span class="text-red-600 font-semibold">Not Verified</span>
              <?php endif; ?>
            </td>
            <td class="px-6 py-4">
              <form method="POST" class="flex space-x-2">
                <input type="hidden" name="doc_id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="verify" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Verify</button>
                <button type="submit" name="action" value="unverify" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Unverify</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
