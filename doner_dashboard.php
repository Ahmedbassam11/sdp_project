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
        button, input[type="submit"] {
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
        button:hover, input[type="submit"]:hover {
            background: #0056b3;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, Doner!</h1>
        <p>Your Doner ID: <?= htmlspecialchars($donerID); ?></p>

        <!-- Buttons -->
        <button onclick="window.location.href='register_event.php'">Register Event</button>
        <button onclick="window.location.href='create_donation.php'">Create Donation</button>
        <button onclick="window.location.href='notifications.php'">Notifications</button>

        <!-- Event Registration Form -->
        <form action="register_event.php" method="POST">
            <h2>Register for an Event</h2>
            <label for="eventID">Enter Event ID:</label>
            <input type="text" name="EventID" id="eventID" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
