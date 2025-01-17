<?php
require_once "Volunteer.php";

// Fetch all volunteers
$volunteers = Volunteer::getAllVolunteers();

// Filter volunteers with AvailableState
$availableVolunteers = array_filter($volunteers, function ($volunteer) {
    return get_class($volunteer->State) === "State\\AvailableState";
});

// Include the view for displaying the available volunteers
include('assign_task.php');
?>
