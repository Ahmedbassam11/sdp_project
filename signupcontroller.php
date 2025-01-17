<?php
require_once "Auth-stategies.php"; // Include your strategy implementation files
//require_once "Manager.php";
// require_once "Beneficiary.php";
// require_once "Volunteer.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve common user data
    $userData = [
        'Name' => $_POST['username'],
        'Address' => $_POST['address'],
        'PhoneNumber' => $_POST['phoneNumber'],
        'Email' => $_POST['email'],
        'Password' => $_POST['password']
    ];

    // Retrieve the role
    $role = $_POST['role'];
    $roleData = [];
    echo $role;

    // Populate role-specific data
    if ($role === 'manager') {
        $roleData = [
            'YearsOfExperience' => $_POST['yearsOfExperience'],
            'DepartmentName' => $_POST['departmentName']
        ];
        $signupStrategy = new SignupAsManager();
    } elseif ($role === 'volunteer') {
        $roleData = [
            'Availability' => $_POST['availability'],
            'Skills' => $_POST['skills'],
            'Hours' => $_POST['hours']
        ];
        $signupStrategy = new SignupAsVolunteer();
    } elseif ($role === 'beneficiary') {
        $roleData = [
            'EligibiltyStatus' => $_POST['eligibilityStatus'],
            'FamilySize' => $_POST['familySize']
        ];
        $signupStrategy = new SignupAsBeneficary();
    } 
    elseif ($role === 'donor') {
        // Donor-specific data, credit is optional
        $roleData = [
            'Credit' => isset($_POST['credit']) ? $_POST['credit'] : null
        ];
        $signupStrategy = new SignupAsDoner();
    } else {
        die("Invalid role selected.");
    }

    // Create context and execute signup
    $context = new ContextSignup($signupStrategy);
    

    $success = $context->signup($userData, $roleData);

    // Handle success or failure
    if ($success) {
        echo "Signup successful!";
    } else {
        echo "Signup failed.";
    }
}
?>
