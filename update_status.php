<?php
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("localhost", "root", "", "enrollment");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_id']) && !empty($_POST['enrollment_status'])) {
    $user_id = intval($_POST['user_id']);
    $new_status = $_POST['enrollment_status'];

    // Update enrollment status
    $stmt = $conn->prepare("UPDATE enroll SET enrollment_status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_status, $user_id);
    if ($stmt->execute()) {

        // Safely fetch student's email
        $stmt_email = $conn->prepare("SELECT email FROM enroll WHERE user_id = ?");
        $stmt_email->bind_param("i", $user_id);
        $stmt_email->execute();
        $result = $stmt_email->get_result();

        if ($result->num_rows > 0) {
            $email = $result->fetch_assoc()['email'];

            // Send notification email
            $mail = new PHPMailer(true);
            try {
                // SMTP Settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'karen.vallecer@bisu.edu.ph';      // Your Gmail address
                $mail->Password   = 'zsfn oxiy eukp onmt';           // App password
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('kharenjaculba@gmail.com', 'School Registrar');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "Enrollment Status Update";

                // Use if match() is not supported on PHP < 8
                switch ($new_status) {
                    case 'approved':
                        $statusMessage = "Congratulations! Your enrollment has been approved.";
                        break;
                    case 'rejected':
                        $statusMessage = "Unfortunately, your enrollment has been rejected. Please check your account or contact the registrar.";
                        break;
                    case 'pending':
                        $statusMessage = "Your application is still being reviewed. We'll notify you once a decision is made.";
                        break;
                    default:
                        $statusMessage = "Your status has been updated.";
                        break;
                }

                $mail->Body = "
                    <h2>Enrollment Status: " . ucfirst($new_status) . "</h2>
                    <p>$statusMessage</p>
                    <p>Visit your student portal to check details.</p>
                ";

                $mail->send();
                echo "Status updated and email sent!";
            } catch (Exception $e) {
                echo "Status updated but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Student email not found for the given user ID.";
        }
    } else {
        echo "Failed to update status.";
    }
} else {
    echo "Error: Please fill out all required fields.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Enrollment Status</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
        }
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<a href="admin_dashboard.php" class="fixed top-4 right-4 text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-full shadow-lg z-50 text-sm">
        ✕
    </a>

<div class="form-container">
    <h2>Update Enrollment Status</h2>
    <form method="POST" action="">
        <label for="user_id">User ID</label>
        <input type="number" name="user_id" id="user_id" required>

        <label for="enrollment_status">New Status</label>
        <select name="enrollment_status" id="enrollment_status" required>
            <option value="">-- Select Status --</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>

        <button type="submit">Update Status & Notify</button>
    </form>
</div>

</body>
</html>

