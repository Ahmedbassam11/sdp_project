<?php
// Start session to retrieve user info
session_start();

// Check if the user is logged in and determine their role
if (!isset($_SESSION['role']) || (!in_array($_SESSION['role'], ['Volunteer', 'Doner']))) {
    header("Location: login.php"); // Redirect to login if not authorized
    exit();
}

// Check the role and retrieve relevant information
$role = $_SESSION['role'];
if ($role === 'Volunteer') {
    $volunteerId = $_SESSION['volunteerid'] ?? null; // Fetch Volunteer ID
    $userId = $_SESSION['UserID'] ?? null; // Fetch User ID

    // Include the Event class to access the events
    require_once 'Event.php';

    // Fetch all events
    try {
        $events = (new FundraiseCreation([]))->getAllEvents();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

    // Pass the variables to the view
    include('view_events.php');
} elseif ($role === 'Doner') {
    $donerID = $_SESSION['donerid'] ?? null; // Fetch Doner ID
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Doner Dashboard</title>
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
            .dashboard-container {
                background: #fff;
                padding: 20px 40px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                max-width: 500px;
                width: 100%;
                text-align: center;
            }
            h1 {
                color: #333;
            }
            button {
                display: block;
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                background: #007BFF;
                color: #fff;
                font-weight: bold;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }
            button:hover {
                background: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="dashboard-container">
            <h1>Welcome, Doner!</h1>
            <p>Your Doner ID: <?= htmlspecialchars($donerID); ?></p>
            
            <!-- Buttons -->
            <button onclick="window.location.href='view_events_controller.php'">Register Event</button>
            <button onclick="window.location.href='create_donation.php'">Create Donation</button>
            <button onclick="window.location.href='view_notifications_controller.php'">Notifications</button>
        </div>
    </body>
    </html>
    <?php
}
?>
