<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Update User</h1>
    <form action="usercontroller.php" method="POST">
        <input type="hidden" name="UserID" value="<?= htmlspecialchars($user->UserID) ?>">
        
        <label for="Name">Name:</label>
        <input type="text" name="Name" id="Name" value="<?= htmlspecialchars($user->Name) ?>">

        <label for="Address">Address:</label>
        <input type="text" name="Address" id="Address" value="<?= htmlspecialchars($user->Address) ?>">

        <label for="PhoneNumber">Phone Number:</label>
        <input type="text" name="PhoneNumber" id="PhoneNumber" value="<?= htmlspecialchars($user->PhoneNumber) ?>">

        <label for="Email">Email:</label>
        <input type="email" name="Email" id="Email" value="<?= htmlspecialchars($user->Email) ?>">

        <label for="Password">Password:</label>
        <input type="password" name="Password" id="Password" value="<?= htmlspecialchars($user->Password) ?>">

        <button type="submit" name="action" value="Update">Update</button>
    </form>
</body>
</html>
