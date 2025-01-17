<?php
session_start(); // Start the session

require_once "beneficiary.php"; // Include Beneficiary class

// Simulating a logged-in user (in practice, this should come from your login system)
$beneficiaryId = 1; // Replace with session value in actual implementation

// Initialize the Beneficiary class
$beneficiary = new Beneficiary();
$beneficiary->BeneficaryID = $beneficiaryId;

// Fetch donations
$donations = $beneficiary->get_donations_by_beneficiary();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_rate'])) {
        $rate = $_POST['rate'];
        $beneficiary->update_rate($rate);
    }

    if (isset($_POST['update_needs'])) {
        $needs = $_POST['needs'];
        $beneficiary->update_needs($needs);
    }
}

// Include the view
include "beneficiary_dashboard.php";
?>
