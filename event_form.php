<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #333;
        }

        form label {
            font-size: 1rem;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        form input,
        form textarea,
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        form textarea {
            resize: none;
            height: 100px;
        }

        form select {
            cursor: pointer;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 1.5rem;
            }

            button {
                font-size: 0.9rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create an Event</h1>
        <form action="EventController.php" method="POST">
            <label for="eventName">Event Name</label>
            <input type="text" id="eventName" name="eventName" placeholder="Enter event name" required>

            <label for="eventDate">Event Date</label>
            <input type="date" id="eventDate" name="eventDate" required>

            <label for="location">Location</label>
            <input type="text" id="location" name="location" placeholder="Enter location" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Provide a brief description" required></textarea>

            <label for="managerId">Manager ID</label>
            <input type="number" id="managerId" name="managerId" placeholder="Enter manager ID" required>

            <label for="eventType">Event Type</label>
            <select id="eventType" name="eventType" required>
                <option value="Fundraiser">Fundraiser</option>
                <option value="Workshop">Workshop</option>
                <option value="Outreach">Outreach</option>
            </select>

            <button type="submit">Create Event</button>
        </form>
    </div>
</body>
</html>
