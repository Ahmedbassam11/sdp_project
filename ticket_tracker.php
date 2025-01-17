<?php

session_start();
require_once 'ticketiartor.php';
// Ensure the user is logged in
if (!isset($_SESSION['managerid'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

// Retrieve event name from the URL query parameter
$eventName = isset($_GET['eventName']) ? $_GET['eventName'] : '';

if (empty($eventName)) {
    die('Event name is required.');
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'Charity');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch all tickets from the database
$sql = "SELECT * FROM tickets"; // Modify if necessary to get tickets by event
$result = $db->query($sql);
$tickets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}

// Close the database connection
$db->close();

// Use the EventNameIterator to filter tickets by event name
$eventNameIterator = new EventNameIterator($tickets, $eventName);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Tracker - <?= htmlspecialchars($eventName) ?></title>
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #007BFF;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tickets for <?= htmlspecialchars($eventName) ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>EventName</th>
                    <th>EventDate</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($eventNameIterator->hasNext()) : ?>
                    <?php $ticket = $eventNameIterator->next(); ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['TicketID']) ?></td>
                        <td><?= htmlspecialchars($ticket['EventName']) ?></td>
                        <td><?= htmlspecialchars($ticket['EventDate']) ?></td>
                        <td><?= htmlspecialchars($ticket['TicketPrice']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php if ($eventNameIterator->hasNext() === false) : ?>
           
        <?php endif; ?>
    </div>
</body>
</html>
