<?php
session_start();
require_once "Event.php";
require_once "Ticket.php";

if (!isset($_SESSION['managerid'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['eventID'])) {
    die('Event ID is required.');
}

$eventID = $_GET['eventID'];
$eventObj = new FundraiseCreation([]); // Initialize event object
$event = $eventObj->getEventById($eventID); // Fetch event details

if (!$event) {
    die("Event not found.");
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketPrice = $_POST['ticketPrice'];

    try {
        $ticket = new Ticket($eventObj);
        $ticket->addTicket($eventID, $ticketPrice);
        $message = "<p class='success'>Ticket added successfully!</p>";
    } catch (Exception $e) {
        $message = "<p class='error'>Error: " . $e->getMessage() . "</p>";
    }
}

include "add_ticket.php";
