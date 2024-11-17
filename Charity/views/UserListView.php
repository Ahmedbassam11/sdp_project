<?php
class UserListView
{
    private $users;

    // Constructor accepts user data
    public function __construct($users)
    {
        $this->users = $users;
    }

    // Override __toString method to output HTML content
    public function __toString()
    {
        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User List</title>
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
                table {
                    width: 100%;
                    max-width: 800px;
                    margin: 0 auto;
                    border-collapse: collapse;
                    background-color: #fff;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                th, td {
                    padding: 12px 15px;
                    text-align: left;
                    border-bottom: 1px solid #e0e0e0;
                }
                th {
                    background-color: #007bff;
                    color: white;
                    font-weight: bold;
                }
                tr:hover {
                    background-color: #f1f1f1;
                }
                a {
                    text-decoration: none;
                    padding: 8px 12px;
                    margin: 0 4px;
                    border-radius: 4px;
                    color: white;
                    font-size: 0.9em;
                    transition: background-color 0.3s ease;
                }
                a.view { background-color: #28a745; }
                a.view:hover { background-color: #218838; }
                a.edit { background-color: #ffc107; color: #333; }
                a.edit:hover { background-color: #e0a800; }
                a.delete { background-color: #dc3545; }
                a.delete:hover { background-color: #c82333; }
            </style>
        </head>
        <body>
            <h1>User List</h1>
            <table>
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        // Loop through the users and add rows for each user
        foreach ($this->users as $user) {
            $html .= '<tr>
                <td>' . htmlspecialchars($user->Name) . '</td>
                <td>
                    <a href="usercontroller.php?UserID=' . $user->UserID . '&action=view_details" class="view">View Details</a>
                    <a href="usercontroller.php?UserID=' . $user->UserID . '&action=edit" class="edit">Edit</a>
                    <a href="usercontroller.php?UserID=' . $user->UserID . '&action=delete" class="delete" onclick="return confirm(\'Are you sure you want to delete this user?\');">Delete</a>
                </td>
            </tr>';
        }

        $html .= '</tbody>
            </table>
        </body>
        </html>';

        return $html;
    }
}
?>
