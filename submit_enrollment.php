<?php
$conn = new mysqli("localhost", "root", "", "enrollment");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Calculate age from birthdate
function calculateAge($birthdate) {
    $today = new DateTime();
    $birth = new DateTime($birthdate);
    return $birth->diff($today)->y;
}

// Assign grade level based on age
function assignGradeLevel($age) {
    if ($age >= 12 && $age <= 13) return 7;
    elseif ($age == 14) return 8;
    elseif ($age == 15) return 9;
    elseif ($age == 16) return 10;
    else return null; // manual review needed
}

// Safely get POST data
$firstname = $_POST['firstname'] ?? '';
$middle_name = $_POST['middle_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$address = $_POST['address'] ?? '';
$email = $_POST['email'] ?? '';
$enrollment_type = $_POST['enrollment_type'] ?? '';

// Basic validation
if (!$firstname || !$last_name || !$birthdate || !$email) {
    die("Missing required fields.");
}

$age = calculateAge($birthdate);
$grade_level = assignGradeLevel($age);

// Generate a random user_id (ideally auto-increment in production)
$user_id = rand(1000, 9999);

// Insert into 'enroll' table
$stmt = $conn->prepare("INSERT INTO enroll (user_id, firstname, middle_name, last_name, email, birthdate, address, enrollment_type, grade_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssi", $user_id, $firstname, $middle_name, $last_name, $email, $birthdate, $address, $enrollment_type, $grade_level);

if ($stmt->execute()) {
    // Document upload function
    function uploadDoc($fileInput, $type, $student_id, $conn) {
        if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) return;

        $target_dir = "uploads/";
        $file_name = basename($_FILES[$fileInput]["name"]);
        $target_file = $target_dir . time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $file_name);

        if (move_uploaded_file($_FILES[$fileInput]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO documents (student_id, file_type, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $student_id, $type, $target_file);
            $stmt->execute();
        }
    }

    // Upload required documents
    uploadDoc("birth_certificate", "birth_certificate", $user_id, $conn);
    uploadDoc("report_card", "report_card", $user_id, $conn);

    echo "Enrollment submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
