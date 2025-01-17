<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Events</title>
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
        .button {
            padding: 5px 10px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Track Events</h1>
        <?php if (empty($events)) : ?>
            <p>No events found for this manager.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) : ?>
                        <tr>
                            <td><a href="ticket_tracker.php?eventName=<?= urlencode($event['EventName']) ?>"><?= htmlspecialchars($event['EventName']) ?></a></td>
                            <td><?= htmlspecialchars($event['EventDate']) ?></td>
                            <td><?= htmlspecialchars($event['Location']) ?></td>
                            <td><?= htmlspecialchars($event['Description']) ?></td>
                            <td><?= htmlspecialchars($event['eventtype']) ?></td>
                            <td>
                                <!-- Add Ticket Button -->
                                <a href="add_ticket.php?eventID=<?= urlencode($event['EventID']) ?>" class="button">Add Ticket</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
