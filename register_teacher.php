<?php
session_start();
require 'enrollment.php'; // Adjust to your actual DB connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_name'])) 

// Initialize variables
$teacher_name = $email = $subject = $password = "";
$name_err = $email_err = $subject_err = $password_err = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate teacher's name
    if (empty(trim($_POST["teacher_name"]))) {
        $name_err = "Please enter the teacher's name.";
    } else {
        $teacher_name = trim($_POST["teacher_name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter the teacher's email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate subject
    if (empty(trim($_POST["subject"]))) {
        $subject_err = "Please select a subject.";
    } else {
        $subject = trim($_POST["subject"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must be at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Insert into database if no errors
    if (empty($name_err) && empty($email_err) && empty($subject_err) && empty($password_err)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute SQL insert statement
        $stmt = $conn->prepare("INSERT INTO teachers (teacher_name, email, subject, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $teacher_name, $email, $subject, $hashed_password);
        if ($stmt->execute()) {
            echo "Teacher registered successfully!";
        } else {
            echo "Error: Could not register teacher. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Sidebar -->
    <div class="w-64 bg-blue-900 text-white flex flex-col p-5">
        <h2 class="text-2xl font-bold mb-8 text-center">Admin Panel</h2>
        <nav class="flex flex-col space-y-4">
            <a href="verify_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Verify Enrollments</a>
            <a href="verify_documents.php" class="hover:bg-blue-700 px-4 py-2 rounded">Verify Documents</a>
            <a href="manage_students.php" class="hover:bg-blue-700 px-4 py-2 rounded">Manage Students</a>
            <a href="update_status.php" class="hover:bg-blue-700 px-4 py-2 rounded">Update Enrollment Status</a>
            <a href="reports.php" class="hover:bg-blue-700 px-4 py-2 rounded">Reports</a>
            <a href="subjects.php" class="hover:bg-blue-700 px-4 py-2 rounded">Subjects</a>
            <a href="register_teacher.php" class="hover:bg-blue-700 px-4 py-2 rounded">Register Teachers</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-10">
        <h1 class="text-3xl font-bold mb-6">Register New Teacher</h1>

        <!-- Form for Registering Teacher -->
        <form action="register_teacher.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="teacher_name" class="block text-lg font-medium text-gray-700">Teacher's Name</label>
                <input type="text" id="teacher_name" name="teacher_name" class="w-full px-4 py-2 border rounded-lg" value="<?= htmlspecialchars($teacher_name) ?>">
                <span class="text-red-500"><?= $name_err ?></span>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-lg font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg" value="<?= htmlspecialchars($email) ?>">
                <span class="text-red-500"><?= $email_err ?></span>
            </div>

            <div class="mb-4">
                <label for="subject" class="block text-lg font-medium text-gray-700">Subject</label>
                <select name="subject" id="subject" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Select Subject</option>
                    <option value="Math" <?= $subject == 'Math' ? 'selected' : '' ?>>Math</option>
                    <option value="English" <?= $subject == 'English' ? 'selected' : '' ?>>English</option>
                    <option value="Science" <?= $subject == 'Science' ? 'selected' : '' ?>>Science</option>
                    <option value="History" <?= $subject == 'History' ? 'selected' : '' ?>>History</option>
                </select>
                <span class="text-red-500"><?= $subject_err ?></span>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg">
                <span class="text-red-500"><?= $password_err ?></span>
            </div>

            <div class="mb-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Register Teacher</button>
            </div>
        </form>

    </div>

</body>
</html>
