<?php
// Start session to retrieve user info
session_start();

// Check if the user is logged in and has the role of Volunteer or Doner
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Volunteer', 'Doner'])) {
    header("Location: login.php"); // Redirect to login if not authorized
    exit();
}

// Initialize variables
$volunteerId = null;
$donerId = null;
$userId = $_SESSION['UserID'] ?? null; // Assuming UserID is stored in the session

// Retrieve role-specific information
if ($_SESSION['role'] === 'Volunteer') {
    $volunteerId = $_SESSION['volunteerid'] ?? null; // Fetch Volunteer ID
} elseif ($_SESSION['role'] === 'Doner') {
    $donerId = $_SESSION['donerid'] ?? null; // Fetch Doner ID
}

// Include the Event class to access the events
require_once 'Event.php';

// Fetch all events
try {
    $events = (new FundraiseCreation([]))->getAllEvents();
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Pass the variables to the view
include('view_events.php');
?>
