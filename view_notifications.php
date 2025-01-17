<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .notification {
            background: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .notification p {
            margin: 0;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Your Notifications</h1>
    <?php if (empty($notifications)) : ?>
        <p>No notifications available.</p>
    <?php else : ?>
        <?php foreach ($notifications as $message) : ?>
            <div class="notification">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
