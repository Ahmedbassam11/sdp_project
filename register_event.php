<?php
session_start();
require_once 'UserModel.php';
require_once 'Event.php';

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['UserID'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['eventId'])) {
    echo json_encode(['success' => false, 'message' => 'Event ID not provided']);
    exit();
}

try {
    // Get the user and event
    $user = (new User([],false))->get_by_id($userId);
    $event = new Event(['EventID' => $data['eventId']]);

    // Register the user for the event
    $user->registerevent($event);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
