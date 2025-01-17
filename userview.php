<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
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
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        h2 {
            color: #333;
            text-align: center;
            font-size: 1.8em;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 1.1em;
            margin: 10px 0 5px;
            color: #555;
        }
        input[type="text"],
        input[type="tel"],
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
        input[type="tel"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <form action="usercontroller.php" method="post">
            <label for="Name">Name:</label>
            <input type="text" id="Name" name="Name" required>

            <label for="Address">Address:</label>
            <input type="text" id="Address" name="Address" required>

            <label for="PhoneNumber">Phone Number:</label>
            <input type="tel" id="PhoneNumber" name="PhoneNumber" required>

            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required>

            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" required>

            <input type="submit" name="action" value="Sign Up">
        </form>
    </div>
</body>
</html>
