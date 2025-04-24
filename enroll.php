<?php
$conn = new mysqli("localhost", "root", "", "enrollment");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $allowed_enrollment_types = ['new', 'transferee'];
  $enrollment_type = isset($_POST['enrollment_type']) ? $_POST['enrollment_type'] : '';

  if (!in_array($enrollment_type, $allowed_enrollment_types)) {
      echo "Error: Only new students and transferees are allowed to enroll on this page.";
      exit;
  }
    function calculateAge($birthdate) {
        $today = new DateTime();
        $birth = new DateTime($birthdate);
        return $birth->diff($today)->y;
    }

    function assignGradeLevel($age) {
        if ($age >= 12 && $age <= 13) return 7;
        elseif ($age == 14) return 8;
        elseif ($age == 15) return 9;
        elseif ($age == 16) return 10;
        else return null; // manual review needed
    }

    function assignSection($conn, $grade_level, $strand_id = null) {
        if ($strand_id) {
            $stmt = $conn->prepare("
                SELECT id FROM sections 
                WHERE grade_level = ? AND strand_id = ? 
                AND (SELECT COUNT(*) FROM section_students WHERE section_id = sections.id) < max_capacity 
                LIMIT 1
            ");
            $stmt->bind_param("ii", $grade_level, $strand_id);
        } else {
            $stmt = $conn->prepare("
                SELECT id FROM sections 
                WHERE grade_level = ? AND strand_id IS NULL
                AND (SELECT COUNT(*) FROM section_students WHERE section_id = sections.id) < max_capacity 
                LIMIT 1
            ");
            $stmt->bind_param("i", $grade_level);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['id'] ?? null;
    }

    // Get form data
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $enrollment_type = isset($_POST['enrollment_type']) ? $_POST['enrollment_type'] : '';
    $student_type = isset($_POST['student_type']) ? $_POST['student_type'] : '';
    $strand_id = ($student_type === 'senior' && isset($_POST['strand_id'])) ? $_POST['strand_id'] : null;

    // Auto-generate user ID (you can replace with actual logic)
    $user_id = rand(1000, 9999);

    // Determine grade level
    if ($student_type === 'junior') {
        $age = calculateAge($birthdate);
        $grade_level = assignGradeLevel($age);
    } else {
        $grade_level = isset($_POST['grade_level']) ? $_POST['grade_level'] : null;
    }

    // Insert student into `enroll` table
    if ($grade_level !== null) {
        $stmt = $conn->prepare("INSERT INTO enroll 
            (user_id, firstname, middle_name, last_name, email, birthdate, address, enrollment_type, grade_level, student_type, strand_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssis", $user_id, $firstname, $middle_name, $last_name, $email, $birthdate, $address, $enrollment_type, $grade_level, $student_type, $strand_id);
        $stmt->execute();

        // Assign section
        $section_id = assignSection($conn, $grade_level, $strand_id);
        if ($section_id) {
            $stmt = $conn->prepare("INSERT INTO section_students (student_id, section_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $section_id);
            $stmt->execute();
        }

        // Upload documents (optional)
        function uploadDoc($fileInput, $type, $student_id, $conn) {
            $target_dir = "uploads/";
            $file_name = basename($_FILES[$fileInput]["name"]);
            $target_file = $target_dir . time() . "_" . $file_name;

            if (move_uploaded_file($_FILES[$fileInput]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO documents (student_id, file_type, file_path) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $student_id, $type, $target_file);
                $stmt->execute();
            }
        }

        uploadDoc("birth_certificate", "birth_certificate", $user_id, $conn);
        uploadDoc("report_card", "report_card", $user_id, $conn);

        echo "Enrollment submitted successfully!";
    } else {
        echo "Error: Grade level is missing or invalid.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Enrollment</title>
  <script>
    function toggleStudentTypeFields() {
      var studentType = document.querySelector('select[name="student_type"]').value;
      var seniorHighFields = document.getElementById('senior-high-fields');
      var juniorHighFields = document.getElementById('junior-high-fields');

      if (studentType === 'senior') {
        seniorHighFields.style.display = 'block';
        juniorHighFields.style.display = 'none';
      } else if (studentType === 'junior') {
        juniorHighFields.style.display = 'block';
        seniorHighFields.style.display = 'none';
      } else {
        seniorHighFields.style.display = 'none';
        juniorHighFields.style.display = 'none';
      }
    }

    window.onload = function() {
      toggleStudentTypeFields();
      document.querySelector('select[name="student_type"]').addEventListener('change', toggleStudentTypeFields);
    };
  </script>
</head>
<body>
  <h2>Online Enrollment Form</h2>
  <form action="enroll.php" method="POST" enctype="multipart/form-data">
    <label>First Name:</label><br>
    <input type="text" name="firstname" required><br>

    <label>Middle Name:</label><br>
    <input type="text" name="middle_name" required><br>

    <label>Last Name:</label><br>
    <input type="text" name="last_name" required><br>

    <label>Birthdate:</label><br>
    <input type="date" name="birthdate" required><br>

    <label>Address:</label><br>
    <input type="text" name="address" required><br>

    <label>Contact Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Enrollment Type:</label><br>
    <select name="enrollment_type">
      <option value="new">New</option>
      <option value="returning">Transferee</option>
    </select><br><br>

    <label>Student Type:</label><br>
    <select name="student_type">
      <option value="junior">Junior High School</option>
      <option value="senior">Senior High School</option>
    </select><br><br>

    <!-- Junior High fields -->
    <div id="junior-high-fields" style="display: none;">
      <label>Grade Level (Junior High School Only):</label><br>
      <select name="grade_level">
        <option value="7">Grade 7</option>
        <option value="8">Grade 8</option>
        <option value="9">Grade 9</option>
        <option value="10">Grade 10</option>
      </select><br><br>
    </div>

    <!-- Senior High fields -->
    <div id="senior-high-fields" style="display: none;">
      <label>Grade Level (Senior High School Only):</label><br>
      <select name="grade_level">
        <option value="11">Grade 11</option>
        <option value="12">Grade 12</option>
      </select><br><br>

      <label>Strand (Senior High School Only):</label><br>
      <select name="strand_id">
        <option value="1">ELEC</option>
        <option value="2">HUMSS</option>
        <option value="3">TVL</option>
      </select><br><br>
    </div>

    <label>Birth Certificate:</label><br>
    <input type="file" name="birth_certificate" required><br>

    <label>Report Card:</label><br>
    <input type="file" name="report_card" required><br><br>

    <input type="submit" value="Submit Enrollment">
  </form>
</body>
</html>
