<?php
$conn = new mysqli("localhost", "root", "", "enrollment");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $allowed_enrollment_types = ['new', 'transferee'];
    $enrollment_type = $_POST['enrollment_type'] ?? '';
    if (!in_array($enrollment_type, $allowed_enrollment_types)) {
        echo "Error: Only new students and transferees are allowed to enroll.";
        exit;
    }

    $firstname = $_POST['firstname'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $student_type = $_POST['student_type'] ?? '';

    // Determine grade_level and strand_id based on student type
    $strand_id = ($student_type === 'senior' && isset($_POST['strand_id'])) ? $_POST['strand_id'] : null;
    $grade_level = null;
    if ($student_type === 'junior') {
        $grade_level = $_POST['junior_grade_level'] ?? null;
    } elseif ($student_type === 'senior') {
        $grade_level = $_POST['senior_grade_level'] ?? null;
    }

    $user_id = rand(1000, 9999); // Random ID for now

    if (
        !empty($firstname) &&
        
        !empty($last_name) &&
        !empty($birthdate) &&
        !empty($address) &&
        !empty($email) &&
        !empty($enrollment_type) &&
        !empty($student_type) &&
        !empty($grade_level) &&
        ($student_type === 'senior' ? !empty($strand_id) : true)
    ) {
     
        $stmt = $conn->prepare("INSERT INTO enroll 
            (user_id, firstname, middle_name, last_name, email, birthdate, address, enrollment_type, grade_level, strand_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssii", $user_id, $firstname, $middle_name, $last_name, $email, $birthdate, $address, $enrollment_type, $grade_level, $strand_id);

        $stmt->execute();

        function uploadDoc($fileInput, $type, $student_id, $conn) {
          $target_dir = "uploads/";
          if (!is_dir($target_dir)) {
              mkdir($target_dir, 0777, true);
          }
      
          $file_name = basename($_FILES[$fileInput]["name"]);
          $target_file = $target_dir . time() . "_" . $file_name;
      
          if (move_uploaded_file($_FILES[$fileInput]["tmp_name"], $target_file)) {
              // First, fetch student name from DB for document entry
              $student_query = $conn->prepare("SELECT firstname, middle_name, last_name FROM enroll WHERE user_id = ?");
              $student_query->bind_param("i", $student_id);
              $student_query->execute();
              $result = $student_query->get_result();
              $student = $result->fetch_assoc();
      
              $firstname = $student['firstname'];
              $middle_name = $student['middle_name'];
              $last_name = $student['last_name'];
              $upload_date = date("Y-m-d");
      
              $stmt = $conn->prepare("INSERT INTO documents (student_id, firstname, middle_name, last_name, file_type, file_path, upload_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
              $stmt->bind_param("issssss", $student_id, $firstname, $middle_name, $last_name, $type, $target_file, $upload_date);
              $stmt->execute();
          }
      }
    
        uploadDoc("birth_certificate", "birth_certificate", $user_id, $conn);
        uploadDoc("report_card", "report_card", $user_id, $conn);

        echo "Enrollment submitted successfully!";
    } else {
        echo "Error: Please fill out all required fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Online Enrollment</title>
  <script>
    function toggleStudentTypeFields() {
      var studentType = document.querySelector('select[name="student_type"]').value;
      document.getElementById('senior-high-fields').style.display = (studentType === 'senior') ? 'block' : 'none';
      document.getElementById('junior-high-fields').style.display = (studentType === 'junior') ? 'block' : 'none';
    }

    window.onload = function () {
      toggleStudentTypeFields();
      document.querySelector('select[name="student_type"]').addEventListener('change', toggleStudentTypeFields);
    }

    function showReview() {
      const form = document.querySelector('form');
      const data = new FormData(form);
      let studentType = data.get('student_type');
      let reviewHTML = '';

      for (let [key, value] of data.entries()) {
        if (key === 'birth_certificate' || key === 'report_card') continue;
        if (key === 'strand_id' && studentType === 'junior') continue;

        let label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        if (key === 'junior_grade_level' || key === 'senior_grade_level') label = 'Grade Level';
        reviewHTML += `<div><strong>${label}:</strong> ${value}</div>`;
      }

      document.getElementById('reviewContent').innerHTML = reviewHTML;
      document.getElementById('reviewModal').classList.remove('hidden');
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
  <h2 class="text-3xl font-bold mb-6 text-center">Online Enrollment Form</h2>
  <form action="enroll.php" method="POST" enctype="multipart/form-data" class="space-y-6">
    <input type="text" name="firstname" placeholder="First Name" required class="w-full p-2 border rounded">
    <input type="text" name="middle_name" placeholder="Middle Name" required class="w-full p-2 border rounded">
    <input type="text" name="last_name" placeholder="Last Name" required class="w-full p-2 border rounded">
    <input type="date" name="birthdate" required class="w-full p-2 border rounded">
    <input type="text" name="address" placeholder="Address" required class="w-full p-2 border rounded">
    <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded">

    <select name="enrollment_type" required class="w-full p-2 border rounded">
      <option value="">Select Enrollment Type</option>
      <option value="new">New</option>
      <option value="transferee">Transferee</option>
    </select>

    <select name="student_type" required class="w-full p-2 border rounded">
      <option value="">Select Student Type</option>
      <option value="junior">Junior High</option>
      <option value="senior">Senior High</option>
    </select>

    <!-- Junior High -->
    <div id="junior-high-fields" class="space-y-2 hidden">
      <label>Junior High Grade Level:</label>
      <select name="junior_grade_level" class="w-full p-2 border rounded">
        <option value="">Select Grade Level</option>
        <option value="7">Grade 7</option>
        <option value="8">Grade 8</option>
        <option value="9">Grade 9</option>
        <option value="10">Grade 10</option>
      </select>
    </div>

    <!-- Senior High -->
    <div id="senior-high-fields" class="space-y-2 hidden">
      <label>Senior High Grade Level:</label>
      <select name="senior_grade_level" class="w-full p-2 border rounded">
        <option value="">Select Grade Level</option>
        <option value="11">Grade 11</option>
        <option value="12">Grade 12</option>
      </select>

      <label>Strand:</label>
      <select name="strand_id" class="w-full p-2 border rounded">
        <option value="">Select Strand</option>
        <option value="1">EIM</option>
        <option value="2">HUMSS</option>
        <option value="3">TVL</option>
      </select>
    </div>

    <div>
      <label>Birth Certificate:</label>
      <input type="file" name="birth_certificate" required class="w-full p-2 border rounded">
    </div>

    <div>
      <label>Report Card:</label>
      <input type="file" name="report_card" required class="w-full p-2 border rounded">
    </div>

    <button type="button" onclick="showReview()" class="w-full bg-green-500 text-white p-3 rounded">Review & Submit</button>
  </form>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl w-full">
    <h3 class="text-xl font-bold mb-4">Please Review Your Information</h3>
    <div id="reviewContent" class="text-sm space-y-2"></div>
    <div class="flex justify-end mt-4 space-x-2">
      <button onclick="document.getElementById('reviewModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded">Edit</button>
      <button onclick="document.querySelector('form').submit()" class="px-4 py-2 bg-blue-500 text-white rounded">Confirm & Submit</button>
    </div>
  </div>
</div>

</body>
</html>
