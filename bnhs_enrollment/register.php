<?php
include 'enrollment.php'; // This should contain your $conn = new mysqli(...);

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = $_POST['last_name'];
    $firstname = $_POST['given_name'];
    $middlename = $_POST['middle_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];

    if ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind statement
        $stmt = $conn->prepare("INSERT INTO students (last_name, given_name, middle_name, username, email, password, gender, birthdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $lastname, $firstname, $middlename, $username, $email, $hashed, $gender, $birthdate);

        if ($stmt->execute()) {
            $success = "Registration successful!";
        } else {
            $error = "Something went wrong. Please try again.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function togglePassword(id) {
      const input = document.getElementById(id);
      input.type = input.type === "password" ? "text" : "password";
    }
  </script>
  <style>
    body {
      background-image: url('bnhs.jpg'); /* Replace with your image */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    .bg-overlay {
      background-color: rgba(255, 255, 255, 0.9); /* semi-transparent */
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen">
  <div class="bg-overlay p-8 rounded-xl shadow-xl w-full max-w-3xl">
    <h2 class="text-3xl font-bold mb-6 text-center text-blue-800">Student Registration</h2>

    <?php if (!empty($success)) echo "<p class='text-green-600 text-center mb-4'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='text-red-600 text-center mb-4'>$error</p>"; ?>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      
      <!-- Name fields in one row -->
      <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium">Last Name</label>
          <input type="text" name="last_name" required class="w-full p-2 border rounded-lg">
        </div>
        <div>
          <label class="block text-sm font-medium">Given Name</label>
          <input type="text" name="given_name" required class="w-full p-2 border rounded-lg">
        </div>
        <div>
          <label class="block text-sm font-medium">Middle Name</label>
          <input type="text" name="middle_name" required class="w-full p-2 border rounded-lg">
        </div>
      </div>

      <!-- Other fields -->
      <div>
        <label class="block text-sm font-medium">Username</label>
        <input type="text" name="username" required class="w-full p-2 border rounded-lg">
      </div>
      <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" required class="w-full p-2 border rounded-lg">
      </div>
      <div>
        <label class="block text-sm font-medium">Gender</label>
        <select name="gender" required class="w-full p-2 border rounded-lg">
          <option value="">Select gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Birthdate</label>
        <input type="date" name="birthdate" required class="w-full p-2 border rounded-lg">
      </div>

      <div>
        <label class="block text-sm font-medium">Password</label>
        <div class="flex">
          <input type="password" id="password" name="password" required class="w-full p-2 border rounded-l-lg">
          <button type="button" onclick="togglePassword('password')" class="px-3 bg-gray-200 rounded-r-lg">👁️</button>
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium">Confirm Password</label>
        <div class="flex">
          <input type="password" id="confirm_password" name="confirm_password" required class="w-full p-2 border rounded-l-lg">
          <button type="button" onclick="togglePassword('confirm_password')" class="px-3 bg-gray-200 rounded-r-lg">👁️</button>
        </div>
      </div>
      <div class="md:col-span-2">
        <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded-lg hover:bg-blue-800">Register</button>
      </div>
    </form>

    <p class="text-sm text-center mt-6">Already have an account?
      <a href="login.php" class="text-blue-700 hover:underline">Login here</a>
    </p>
  </div>
</body>
</html>
