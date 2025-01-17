<?php
// Include necessary files
require_once "UserModel.php"; // Assuming the User model is in this file

// Start session to store user info after login
session_start();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create an instance of the User class and call the flogin function
    $user = new User([]);
    $loggedInUser = $user->flogin($email, $password);

    if ($loggedInUser) {
        // Successfully logged in, get the user role
        $userRole = User::getUserRoleByEmail($email);
 
        $_SESSION['UserID']= $loggedInUser->UserID;
        if ($userRole) {
            $_SESSION['user'] = $loggedInUser; // Store user data in session
            $_SESSION['role'] = $userRole['role']; // Store user role in session

            // Navigate based on the role
            switch ($userRole['role']) {
                case 'Manager':
                    $_SESSION['donerid'] = $userRole['details']['DonerID'];
                    header("Location: manager_dashboard_controller.php"); // Redirect to Manager Dashboard
                    break;
                case 'Beneficiary':
                    $_SESSION['donerid'] = $userRole['details']['DonerID'];
                    header("Location: beneficiary_dashboard_contoller.php"); // Redirect to Beneficiary Dashboard
                    break;
                case 'Volunteer':
                    $_SESSION['donerid'] = $userRole['details']['DonerID'];
                    header("Location: volunteer_dashboard_controller.php"); // Redirect to Volunteer Dashboard
                    break;
                    case 'Doner':
                        $_SESSION['donerid'] = $userRole['details']['DonerID'];
                        header("Location: doner_dashboard_controller.php"); // Redirect to Volunteer Dashboard
                        break;
                default:
                    header("Location: home.php"); // Default redirect if no role found
                    break;
            }
            exit();
        } else {
            echo "No role assigned to this user.";
        }
    } else {
        echo "Invalid email or password.";
    }
}
?>
    