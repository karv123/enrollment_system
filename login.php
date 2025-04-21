<?php
session_start();
include 'enrollment.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['email'];  // this will be either email or username
    $password = $_POST['password'];

    // Check if input is an email or username and query admin table
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $input, $input); // Binding both email and username for comparison
    $stmt->execute();
    $admin_result = $stmt->get_result();
    $admin = $admin_result->fetch_assoc();

    // Check if admin exists and compare plaintext passwords
    if ($admin && $password === $admin['password']) { // Direct comparison for plaintext password
        $_SESSION['admin_name'] = $admin['username']; // Use admin column name
        header("Location: admin_dashboard.php");
        exit();
    }

    // If not admin, check student table
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $input, $input); // Binding both email and username for comparison
    $stmt->execute();
    $student_result = $stmt->get_result();
    $student = $student_result->fetch_assoc();

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_name'] = $student['firstname']; // Use your preferred column
        header("Location: dashboard.php");
        exit();
    }

    // If none match
    $error = "Invalid email/username or password.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bantolinao NHS Enrollment</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-msg {
            background-color: #ffdddd;
            color: #d8000c;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #d8000c;
            border-radius: 5px;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .error-msg.show {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="download.png" class="logo" alt="BNHS Logo">
            <h2><span class="highlight">Bantolinao NHS</span><br>Enrollment Login</h2>

            <form method="post" action="login.php">
                <?php if (!empty($error)) : ?>
                    <div class="error-msg show" id="errorMsg"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="input-group">
                    <img src="Users-Name-icon.png" alt="User Icon">
                    <input type="text" name="email" placeholder="Username or Email" required>
                </div>

                <div class="input-group">
                    <img src="Lock-icon.png" alt="Password Icon">
                    <input type="password" name="password" placeholder="Password" id="password" required>
                    <span class="toggle" onclick="togglePassword()">Show</span>
                </div>

                <div class="forgot">
                    <a href="#">Forgot Password?</a>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>

            <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById("password");
            pw.type = pw.type === "password" ? "text" : "password";
        }

        // Only fade in if error exists
        window.addEventListener("DOMContentLoaded", () => {
            const errorMsg = document.getElementById("errorMsg");
            if (errorMsg) {
                errorMsg.classList.add("show");
                setTimeout(() => {
                    errorMsg.classList.remove("show");
                }, 4000); // Hide after 4 seconds
            }
        });
    </script>
</body>
</html>
