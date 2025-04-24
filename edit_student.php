<?php
session_start();
require 'enrollment.php';

if (!isset($_SESSION['admin_name'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    die("Student ID is required.");
}

$id = intval($_GET['id']);

// Fetch current student data
$query = $conn->prepare("SELECT * FROM enroll WHERE user_id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows !== 1) {
    die("Student not found.");
}

$student = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $grade_level = $_POST['grade_level'];

    $update = $conn->prepare("UPDATE enroll SET firstname=?, middle_name=?, last_name=?, email=?, grade_level=?  WHERE user_id=?");
    $update->bind_param("sssssi", $firstname, $middle_name, $last_name, $email, $grade_level, $id);

    if ($update->execute()) {
        $_SESSION['toast'] = "Student updated successfully!";
        header("Location: manage_students.php");
        exit();
    } else {
        $error = "Update failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <a href="manage_students.php" class="fixed top-4 right-4 text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-full shadow z-50 text-sm">✕</a>

    <h2 class="text-2xl font-bold mb-6">Edit Student</h2>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded shadow w-full max-w-lg">
        <div class="mb-4">
            <label class="block font-semibold">First Name</label>
            <input type="text" name="firstname" value="<?= htmlspecialchars($student['firstname']) ?>" required class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Middle Name</label>
            <input type="text" name="middle_name" value="<?= htmlspecialchars($student['middle_name']) ?>" class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Last Name</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block font-semibold">Grade Level</label>
            <input type="text" name="grade_level" value="<?= htmlspecialchars($student['grade_level']) ?>" class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
        
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Student</button>
    </form>

</body>
</html>
