<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Volunteers</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: #007BFF;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            max-width: 1200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table th, table td {
            padding: 12px 15px;
            text-align: left;
        }
        table th {
            background-color: #007BFF;
            color: #fff;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #e6f0ff;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .no-volunteers {
            text-align: center;
            font-size: 18px;
            color: #999;
        }

        @media screen and (max-width: 768px) {
            table th, table td {
                padding: 10px;
            }
            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Available Volunteers</h1>
    <div class="container">
        <?php if (count($availableVolunteers) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>VolunteerID</th>
                        <th>Availability</th>
                        <th>Skills</th>
                        <th>Hours</th>
                        <th>TaskID</th>
                        <th>UserID</th>
                        <th>State</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($availableVolunteers as $volunteer): ?>
                        <tr>
                            <td><?= $volunteer->VolunteerID ?></td>
                            <td><?= $volunteer->Availability ?></td>
                            <td><?= $volunteer->Skills ?></td>
                            <td><?= $volunteer->Hours ?></td>
                            <td><?= $volunteer->TaskOb ? $volunteer->TaskOb->TaskID : "None" ?></td>
                            <td><?= $volunteer->UserOb->UserID ?></td>
                            <td><?= get_class($volunteer->State) ?></td>
                            <td>
                                <form action="create_task1.php" method="POST">
                                    <input type="hidden" name="volunteer_id" value="<?= $volunteer->VolunteerID ?>">
                                    <button type="submit">Create Task</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-volunteers">No available volunteers found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
