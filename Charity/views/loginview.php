<!-- login_view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            color: #333;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .forgot-password {
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .forgot-password a {
            color: #007BFF;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 1rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Login</h1>

    <!-- Display error message if available -->
    <?php
    session_start();
    if (isset($_SESSION['error'])) {
        echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']); // Clear the error message after displaying it
    }
    ?>

    <form method="POST" action="usercontroller.php">
        <input type="hidden" name="action" value="Login">
        
        <div class="form-group">
            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required placeholder="Enter your email">
        </div>

        <div class="form-group">
            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" required placeholder="Enter your password">
        </div>

        <button type="submit">Login</button>

        <div class="forgot-password">
            <a href="#">Forgot password?</a>
        </div>
    </form>
</div>

</body>
</html>
