<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>
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
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .button-group a {
            display: block;
            padding: 10px;
            background: #007BFF;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background 0.3s;
        }
        .button-group a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Volunteer Dashboard</h1>
        <div class="button-group">
            <a href="view_events_controller.php">View Events</a>
            <a href="assigned_tasks_controller.php">View Assigned Tasks</a>
            <a href="view_notifications_controller.php">View Notifications</a>
        </div>
    </div>
</body>
</html>
