<?php
// Start session and ensure the user is logged in
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Volunteer', 'Doner'])) {
    header("Location: login.php"); // Redirect to login if unauthorized
    exit();
}

// Retrieve the User ID based on role
$userid=$_SESSION['UserID'];


// Database connection
$db = new mysqli('localhost', 'root', '', 'Charity');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Function to fetch notifications based on User ID
function getNotifications($db, $userid) {
    $notifications = [];
    $sql = "SELECT Message FROM notification WHERE UserId = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row['Message'];
    }
    $stmt->close();
    return $notifications;
}

// Fetch notifications for the user
$notifications = getNotifications($db, $userid);

// Close the database connection
$db->close();

// Pass the variables to the view
include('view_notifications.php');
?>
