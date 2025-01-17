<?php

require("Event.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $managerId = intval($_POST['managerId']);
    $eventType = $_POST['eventType'];

    try {
        // Choose the appropriate event class
        $event = null;

        switch ($eventType) {
            case 'Fundraiser':
                $event = new FundraiseCreation();
                break;
            case 'Workshop':
                $event = new WorkshopCreation();
                break;
            case 'Outreach':
                $event = new OutreachCreation();
                break;
            default:
                throw new Exception("Invalid event type.");
        }

        // Set event properties
        $event->EventName = $eventName;
        $event->EventDate = $eventDate;
        $event->Location = $location;
        $event->Description = $description;
        $event->ManagerID = $managerId;

        // Plan and save the event
        $message = $event->planEvent();
        echo "<h1>Success!</h1>";
        echo "<p>$message</p>";
    } catch (Exception $e) {
        // Handle errors
        echo "<h1>Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
    }
} else {
    echo "<h1>Invalid Request</h1>";
}
