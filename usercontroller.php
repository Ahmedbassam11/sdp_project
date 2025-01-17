<?php
require_once "UserModel.php"; 
require_once "notification_system.php"; 
require_once "DB.php"; 
require_once "UserListView.php";
require_once "user_details.php";
require_once "UserModel.php"; 
require_once "notification_system.php"; 
require_once "DB.php"; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] === 'Login') {
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    
    $user = (new User([], false))->flogin($email, $password);
    
    if ($user) {
   
        session_start();
        $_SESSION['UserID'] = $user->UserID;
        $_SESSION['Name'] = $user->Name;
        $_SESSION['Email'] = $user->Email;
        
        
        header("Location: usercontroller.php?action=list_users");
        exit;
    } else {
      
        session_start();
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: loginview.php");
        exit;
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] === 'Update') {
    $user = new User([
        'UserID' => $_POST['UserID'],
        'Name' => $_POST['Name'],
        'Address' => $_POST['Address'],
        'PhoneNumber' => $_POST['PhoneNumber'],
        'Email' => $_POST['Email'],
        'Password' => $_POST['Password']
    ], false);

    if ($user->updateUser()) {
        echo "User updated successfully.";
    } else {
        echo "Error updating user.";
    }

    header("Location: usercontroller.php?action=list_users");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['UserID'])) {
    $UserID = (int)$_GET['UserID'];
    $user = (new User([], false))->get_by_id($UserID);

    if ($user) {
        include 'update_user_view.php';
    } else {
        echo "User not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] === 'Sign Up') {
    $properties = [
        'Name' => $_POST['Name'],
        'Address' => $_POST['Address'],
        'PhoneNumber' => $_POST['PhoneNumber'],
        'Email' => $_POST['Email'],
        'Password' => $_POST['Password'],
    ];

    $newUser = new User($properties, true);


    header("Location: usercontroller.php?action=list_users"); 
    exit(); 
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['UserID'])) {
    $UserID = (int)$_GET['UserID'];
    
    $user = new User([], false);
    $deleted = $user->deleteUser($UserID);

    if ($deleted) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user.";
    }

    
    header("Location: usercontroller.php?action=list_users");
    exit;
}


if (isset($_GET['UserID'])) {
    $UserID = (int)$_GET['UserID'];

   
    $user = (new User([], false))->get_by_id($UserID);

    
    $view = new UserDetailsView($user);

    
    $view->display();

}


        
        
        $users = User::getAllUsers();

        
        $view = new UserListView($users);

        echo $view;

if (isset($_GET['action']) && $_GET['action'] === 'view_details' && isset($_GET['UserID'])) {

    $newUser = new User([], false);
    $newUser->get_by_id(($_GET['UserID']));

    
} elseif (isset($_GET['action']) && $_GET['action'] === 'list_users') {
    $Users = (new User([], false))->getAllUsers();
   
    
  
}

?>
