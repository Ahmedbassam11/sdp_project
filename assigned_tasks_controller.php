<?php
// Start session and ensure the user is logged in
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Volunteer') {
    header("Location: login.php"); // Redirect to login if unauthorized
    exit();
}

// Include the Task class
require_once("task.php");

// Retrieve the Volunteer ID from the session
$volunteerId = $_SESSION['volunteerid'];

// Database connection
$db = new mysqli('localhost', 'root', '', 'Charity');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Function to get Task IDs for the volunteer
function getAssignedTaskIDs($db, $volunteerId) {
    $taskIDs = [];
    $sql = "SELECT TaskID FROM Volunteer WHERE VolunteerID = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $volunteerId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $taskIDs[] = $row['TaskID'];
    }
    $stmt->close();
    return $taskIDs;
}

// Fetch assigned Task IDs
$taskIDs = getAssignedTaskIDs($db, $volunteerId);

// Fetch task details
$tasks = [];
foreach ($taskIDs as $taskID) {
    $taskObj = (new task([], false))->get_by_id($taskID);
    if ($taskObj) {
        $tasks[] = $taskObj;
    }
}

// Close the database connection
$db->close();

// Pass the variables to the view
include('assigned_tasks.php');
?>
