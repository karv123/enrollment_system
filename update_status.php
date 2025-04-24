<?php
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$conn = new mysqli("localhost", "root", "", "enrollment");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $new_status = $_POST['status'];

    // Update enrollment status
    $stmt = $conn->prepare("UPDATE enroll SET status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_status, $user_id);
    $stmt->execute();

    // Get student's email
    $email_result = $conn->query("SELECT email FROM enroll WHERE user_id = $user_id");
    $email = $email_result->fetch_assoc()['email'];

    // Send notification email
    $mail = new PHPMailer(true);
    try {
        // SMTP Settings (Example using Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kharenjaculba@gmail.com';      // Replace with your email
        $mail->Password   = 'erac kvzk qdrl iryv';         // Use app password if 2FA is enabled
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email content
        $mail->setFrom('kharenjaculba@gmail.com', 'School Registrar');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Enrollment Status Update";

        $statusMessage = match($new_status) {
            'approved' => "Congratulations! Your enrollment has been approved.",
            'rejected' => "Unfortunately, your enrollment has been rejected. Please check your account or contact the registrar.",
            'pending'  => "Your application is still being reviewed. We'll notify you once a decision is made.",
            default    => "Your status has been updated.",
        };

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
}
?>

<!-- Example form -->
<form method="POST" action="update_status.php">
    <label>User ID:</label>
    <input type="number" name="user_id" required><br>

    <label>New Status:</label>
    <select name="status" required>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
    </select><br><br>

    <button type="submit">Update Status & Notify</button>
</form>
