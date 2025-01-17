<?php
session_start();
require_once "Event.php";
require_once "Ticket.php";

// Ensure the user is logged in and the EventID is passed
if (!isset($_SESSION['managerid'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['eventID'])) {
    die('Event ID is required.');
}

$eventID = $_GET['eventID'];
$eventObj = new FundraiseCreation([]); // Fetch event details using FundraiseCreation class
$event = $eventObj->getEventById($eventID); // Assuming this method exists

// If the event does not exist, show an error
if (!$event) {
    die("Event not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the ticket price from the form
    $ticketPrice = $_POST['ticketPrice'];

    try {
        // Instantiate the Ticket object (you can use either the legacy Ticket or Adapter)
        $ticket = new Ticket($eventObj); // Create an instance of the Ticket class
        $ticket->addTicket($eventID, $ticketPrice); // Add the ticket
        
        echo "<p>Ticket added successfully!</p>";
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="number"] {
            padding: 8px;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Use object notation (->) instead of array notation ([]) -->
        <h1>Add Ticket for Event: <?= htmlspecialchars($event->EventName) ?></h1>

        <form method="POST">
            <label for="ticketPrice">Ticket Price</label>
            <input type="number" name="ticketPrice" id="ticketPrice" step="0.01" required>

            <input type="submit" value="Add Ticket">
        </form>
    </div>
</body>
</html>
