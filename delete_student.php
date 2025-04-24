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

// Delete related documents first
$doc_stmt = $conn->prepare("DELETE FROM documents WHERE student_id = ?");
$doc_stmt->bind_param("i", $id);
$doc_stmt->execute();

// Delete student
$stmt = $conn->prepare("DELETE FROM enroll WHERE user_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['toast'] = "Student deleted successfully!";
} else {
    $_SESSION['toast'] = "Failed to delete student: " . $stmt->error;
}

header("Location: manage_students.php");
exit();
?>
