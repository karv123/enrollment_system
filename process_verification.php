<?php
session_start();
require 'enrollment.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doc_id'], $_POST['action'])) {
    $doc_id = intval($_POST['doc_id']);
    $action = $_POST['action'];

    if (in_array($action, ['verified', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE documents SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $doc_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Document successfully $action."]);
        } else {
            echo json_encode(['success' => false, 'message' => "Failed to update document."]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => "Invalid action."]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Invalid request."]);
}
exit();
