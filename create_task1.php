<?php

session_start();
require_once "Volunteer.php";
require_once "task.php";  // Include the task model

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the volunteer ID sent from the form
    $volunteer_id = $_POST['volunteer_id'];

    // Fetch the volunteer from the database or object
    $volunteer = (new Volunteer([],false))->get_by_id($volunteer_id); // You may need to implement this method in your Volunteer class
    $managerid = $_SESSION['managerid'];
    
    if ($volunteer) {
        // After fetching the volunteer, you can proceed to create a task
        
        // Example: Gather task details from a form
        if (isset($_POST['task_name']) && isset($_POST['task_description'])) {
            $task_name = $_POST['task_name'];
            $task_description = $_POST['task_description'];

            // Now, create a new task instance
            $task_properties = [
                'TaskName' => $task_name,
                'TaskDescription' => $task_description,
                'Managerob' => (new manager([], false))->get_by_id($managerid), // Assuming the Manager object is already set in Volunteer
            ];

            // Create the task and insert it into the database
            $new_task = new task($task_properties, true);  // Pass `true` to insert the task

            $volunteer->updateTaskID($new_task->TaskID);
            $volunteer->assignTask($new_task);
            
            // Redirect to the manager dashboard
            header("Location: manager_dashboard.php");
            exit();
        }
    } else {
        echo "Volunteer not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
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
            max-width: 800px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 5px;
        }
        textarea {
            resize: vertical;
            height: 150px;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .error-message {
            color: red;
            font-size: 16px;
            margin-top: 10px;
            text-align: center;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 100%;
                margin: 15px;
            }
        }
    </style>
</head>
<body>
    <h1>Create a Task</h1>
    <div class="container">
        <!-- Form to create a task -->
        <form action="create_task1.php" method="POST">
            <input type="hidden" name="volunteer_id" value="<?= $volunteer_id ?>">

            <div class="form-group">
                <label for="task_name">Task Name:</label>
                <input type="text" id="task_name" name="task_name" required>
            </div>

            <div class="form-group">
                <label for="task_description">Task Description:</label>
                <textarea id="task_description" name="task_description" required></textarea>
            </div>

            <button type="submit">Create Task</button>
        </form>
        
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= $error_message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
