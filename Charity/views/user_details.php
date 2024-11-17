<?php
class UserDetailsView
{
    private $user;

    // Constructor accepts user data
    public function __construct($user)
    {
        $this->user = $user;
    }

    // Output the HTML to display the user details
    public function display()
    {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Details</title>
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
                .container {
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    max-width: 500px;
                    width: 100%;
                    text-align: center;
                }
                h1 {
                    color: #333;
                    font-size: 1.8em;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 1.1em;
                    color: #555;
                    margin: 10px 0;
                }
                strong {
                    color: #007bff;
                }
                .not-found {
                    font-size: 1.2em;
                    color: #dc3545;
                }
            </style>
        </head>
        <body>
            <div class="container">';

        if ($this->user !== null) {
            echo "<h1>User Details</h1>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($this->user->Name) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($this->user->Address) . "</p>";
            echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($this->user->PhoneNumber) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($this->user->Email) . "</p>";
        } else {
            echo "<p class='not-found'>User not found.</p>";
        }

        echo '</div>
        </body>
        </html>';
    }
}
?>
