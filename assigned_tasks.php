<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .task {
            background: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .task h2 {
            margin: 0 0 10px;
            color: #007BFF;
        }
        .task p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Assigned Tasks</h1>
    <?php if (empty($tasks)) : ?>
        <p>No tasks assigned to you.</p>
    <?php else : ?>
        <?php foreach ($tasks as $task) : ?>
            <div class="task">
                <h2><?php echo htmlspecialchars($task->TaskName); ?></h2>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($task->TaskDescription); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
