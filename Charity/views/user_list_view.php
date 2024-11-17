<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
</head>
<body>
    <h1>User List</h1>
    <table border="1">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user->Name); ?></td>
                    <td><a href="index.php?action=view_details&UserID=<?php echo $user->UserID; ?>">View Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Link to explicitly list users -->
    <p><a href="index.php?action=list_users">Back to User List</a></p>
</body>
</html>
