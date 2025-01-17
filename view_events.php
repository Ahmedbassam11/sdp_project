<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events</title>
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
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .event-list {
            margin-top: 20px;
            text-align: left;
        }
        .event-item {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .event-item h3 {
            margin: 0;
            font-size: 1.2em;
            color: #333;
        }
        .event-item p {
            margin: 5px 0;
        }
        .event-item button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .event-item button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View All Events</h1>

        <!-- Check if the variables are set -->
        <?php if (isset($volunteerId)): ?>
            <p>Your Volunteer ID: <?php echo htmlspecialchars($volunteerId); ?></p>
        <?php else: ?>
            <p>Volunteer ID not available.</p>
        <?php endif; ?>

        <?php if (isset($userId)): ?>
            <p>Your User ID: <?php echo htmlspecialchars($userId); ?></p>
        <?php else: ?>
            <p>User ID not available.</p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <div class="event-list">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <div class="event-item">
                            <h3><?php echo htmlspecialchars($event['EventName']); ?></h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($event['EventDate']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['Location']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($event['Description']); ?></p>
                            <button onclick="registerEvent(<?php echo htmlspecialchars($event['EventID']); ?>)">Register</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No events available.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function registerEvent(eventId) {
            const formData = new FormData();
            formData.append('EventID', eventId);

            fetch('register_event.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Display the success or error message
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
