<?php
// Start the session
session_start();

// Check if the user is logged in and has the Manager role
if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
    // Access the user ID based on their role
    if ($_SESSION['role'] == 'Manager' && isset($_SESSION['managerid'])) {
        $managerid = $_SESSION['managerid'];
        // Pass the manager ID to the view
        include('manager_dashboard.php');
    } elseif ($_SESSION['role'] == 'Beneficiary' && isset($_SESSION['beneficiaryid'])) {
        // Handle Beneficiary role logic here if needed
        echo "Access denied: Beneficiaries do not have access to the Manager dashboard.";
    } elseif ($_SESSION['role'] == 'Volunteer' && isset($_SESSION['volunteerid'])) {
        // Handle Volunteer role logic here if needed
        echo "Access denied: Volunteers do not have access to the Manager dashboard.";
    } else {
        echo "Role-specific ID not found.";
    }
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>
