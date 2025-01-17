<?php
session_start();

// Check if the user is logged in and has the Manager role
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Manager' || !isset($_SESSION['managerid'])) {
    header("Location: login.php");
    exit();
}

$managerId = $_SESSION['managerid'];

require_once "Event.php"; // Ensure the Event classes are included

// Initialize variables for success and error messages
$error = $success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $eventName = $_POST['event_name'] ?? '';
    $eventDate = $_POST['event_date'] ?? '';
    $location = $_POST['location'] ?? '';
    $description = $_POST['description'] ?? '';
    $eventType = $_POST['event_type'] ?? '';

    // Validate input (simple validation here; extend as needed)
    if (empty($eventName) || empty($eventDate) || empty($location) || empty($description) || empty($eventType)) {
        $error = "All fields are required.";
    } else {
        try {
            // Create the appropriate event object
            switch ($eventType) {
                case 'Fundraiser':
                    $event = new FundraiseCreation([
                        'EventName' => $eventName,
                        'EventDate' => $eventDate,
                        'Location' => $location,
                        'Description' => $description,
                        'ManagerID' => $managerId,
                    ]);
                    break;
                case 'Workshop':
                    $event = new WorkshopCreation([
                        'EventName' => $eventName,
                        'EventDate' => $eventDate,
                        'Location' => $location,
                        'Description' => $description,
                        'ManagerID' => $managerId,
                    ]);
                    break;
                case 'Outreach':
                    $event = new OutreachCreation([
                        'EventName' => $eventName,
                        'EventDate' => $eventDate,
                        'Location' => $location,
                        'Description' => $description,
                        'ManagerID' => $managerId,
                    ]);
                    break;
                default:
                    throw new Exception("Invalid event type.");
            }

            // Retrieve UserID from the session
            $loggedInUser = ($_SESSION['UserID']); // Assuming the UserID is directly available
         
            $result = $event->planEvent($loggedInUser); // Pass the UserID to planEvent
            $success = "Event created successfully: $result";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Include the view for displaying the event creation form
include('create_task.php');
?>
