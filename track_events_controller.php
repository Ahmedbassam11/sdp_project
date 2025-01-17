<?php
session_start();
require_once "Event.php";

// Check if the user is logged in and has the Manager role
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Manager' && isset($_SESSION['managerid'])) {
    // Fetch the manager ID from session
    $managerId = $_SESSION['managerid'];
    
    // Fetch events for the manager
    try {
        $events = (new FundraiseCreation([]))->getEventsByManagerId($managerId);
        // Pass events data to the view
        include('track_events.php');
    } catch (Exception $e) {
        echo "Error fetching events: " . $e->getMessage();
    }
} else {
    // Redirect to login page if not authorized
    header("Location: login.php");
    exit();
}
?>
