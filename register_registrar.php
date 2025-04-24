<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "enrollment");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registrar_name = $_POST['registrar_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert registrar data into the database
    $stmt = $conn->prepare("INSERT INTO registrars (registrar_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $registrar_name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registrar successfully registered.";
    } else {
        echo "Error registering registrar: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Registrar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex h-screen">

<!-- Sidebar -->
<div class="w-64 bg-blue-900 text-white flex flex-col p-5">
    <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
    <nav class="flex flex-col space-y-4">
        <a href="verify_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Verify Enrollments</a>
        <a href="verify_documents.php" class="hover:bg-blue-700 px-4 py-2 rounded">Verify Documents</a>
        <a href="manage_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Manage Students</a>
        <a href="update_status.php" class="hover:bg-blue-700 px-4 py-2 rounded">Update Enrollment Status</a>
        <a href="reports.php" class="hover:bg-blue-700 px-4 py-2 rounded">Reports</a>
        <a href="add_subjects.php" class="hover:bg-blue-700 px-4 py-2 rounded">Add Subjects</a>
        <a href="register_teacher.php" class="hover:bg-blue-700 px-4 py-2 rounded">Register Teachers</a>
        <a href="register_registrar.php" class="hover:bg-blue-700 px-4 py-2 rounded">Register Registrar</a>
    </nav>
</div>

<!-- Main Content -->
<div class="flex-1 p-10">
    <h1 class="text-3xl font-bold mb-6">Register Registrar</h1>

    <form action="register_registrar.php" method="POST">
        <div class="mb-4">
            <label for="registrar_name" class="block text-gray-700">Registrar Name</label>
            <input type="text" name="registrar_name" id="registrar_name" class="w-full px-4 py-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">Register Registrar</button>
    </form>
</div>

</body>
</html>
