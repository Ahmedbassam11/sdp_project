<?php
// Start session to retrieve user info
session_start();

// Check if the user is logged in and has the role of Volunteer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Volunteer') {
    header("Location: login.php"); // Redirect to login if not authorized
    exit();
}

// Retrieve volunteer-specific information from the session
$volunteerId = $_SESSION['volunteerid'];

// Include the view to display the content
include('volunteer_dashboard.php');
?>
