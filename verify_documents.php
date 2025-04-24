<?php
session_start();
require 'enrollment.php';

if (!isset($_SESSION['admin_name'])) {
    header('Location: login.php');
    exit();
}

$filter = isset($_GET['type']) ? $_GET['type'] : '';

$sql = "SELECT d.id AS doc_id, d.student_id, d.file_path, d.file_type, e.firstname, e.middle_name, e.last_name
        FROM documents d
        JOIN enroll e ON d.student_id = e.user_id";

if ($filter && in_array($filter, ['report_card', 'birth_certificate'])) {
    $sql .= " WHERE d.file_type = '$filter'";
}

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Documents</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<a href="admin_dashboard.php" class="fixed top-4 right-4 text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-full shadow-lg z-50 text-sm">
    ✕
</a>

<body class="flex bg-gray-100">

<!-- Sidebar -->
<div class="w-1/5 bg-white shadow h-screen p-6">
    <h2 class="text-xl font-bold mb-4">Document Types</h2>
    <ul class="space-y-2">
        <li><a href="?type=report_card" class="text-blue-700 hover:underline">📄 Report Card</a></li>
        <li><a href="?type=birth_certificate" class="text-blue-700 hover:underline">📁 Birth Certificate</a></li>

</div>

<!-- Main Content -->
<div class="w-4/5 p-6">
    <h2 class="text-2xl font-bold mb-6">
        <?= $filter ? ucwords(str_replace('_', ' ', $filter)) . " Documents" : "All Documents" ?>
    </h2>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-blue-800 text-white">
                <th class="py-2 px-4 text-left">Student Name</th>
                <th class="py-2 px-4 text-left">File Type</th>
                <th class="py-2 px-4 text-left">Document</th>
                <th class="py-2 px-4 text-left">Status</th>
                <th class="py-2 px-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4"><?= htmlspecialchars($row['firstname'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']) ?></td>
                    <td class="py-2 px-4 capitalize"><?= str_replace('_', ' ', $row['file_type']) ?></td>
                    <td class="py-2 px-4">
                        <a href="uploads/<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="text-blue-600 underline">View</a>
                    </td>
                    <td class="py-2 px-4" id="status-<?= $row['doc_id'] ?>">
    <?= ucfirst($row['status'] ?? 'Pending') ?>
</td>
<td class="py-2 px-4">
    <div class="flex gap-2">
        <button onclick="updateStatus(<?= $row['doc_id'] ?>, 'verified')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Verify</button>
        <button onclick="updateStatus(<?= $row['doc_id'] ?>, 'rejected')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Reject</button>
    </div>
</td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script>
function updateStatus(docId, action) {
    fetch('process_verification.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ doc_id: docId, action: action })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`status-${docId}`).textContent = action.charAt(0).toUpperCase() + action.slice(1);
            showToast(data.message, true);
        } else {
            showToast(data.message, false);
        }
    })
    .catch(err => {
        showToast('Request failed. Try again.', false);
    });
}

function showToast(message, success = true) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-3 rounded shadow-lg z-50 transition-opacity duration-300 ${
        success ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}
</script>

</body>
<?php if (isset($_SESSION['toast'])): ?>
    <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded shadow-lg z-50 transition-opacity duration-300">
        <?= $_SESSION['toast'] ?>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 3000);
    </script>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>

</html>
