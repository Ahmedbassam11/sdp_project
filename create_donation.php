<?php
session_start();
require_once "beneficiary.php";

// Check if the user is logged in and has the 'Doner' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Doner') {
    header("Location: login.php"); // Redirect to login if not authorized
    exit();
}

$beneficiary = new Beneficiary();
$beneficiaries = $beneficiary->getAll(); // Fetch all beneficiaries
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Donation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Select a Beneficiary</h1>
        <table>
            <thead>
                <tr>
                    <th>Beneficiary ID</th>
                    <th>Eligibility Status</th>
                    <th>Family Size</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($beneficiaries)): ?>
                    <tr>
                        <td colspan="4">No beneficiaries found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($beneficiaries as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['BeneficaryID']); ?></td>
                            <td><?= htmlspecialchars($b['EligibiltyStatus']); ?></td>
                            <td><?= htmlspecialchars($b['FamilySize']); ?></td>
                            <td>
                                <form action="donation_form.php" method="GET" style="margin: 0;">
                                    <input type="hidden" name="BeneficaryID" value="<?= htmlspecialchars($b['BeneficaryID']); ?>">
                                    <button type="submit" class="btn">Select</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
