<?php
session_start();
require_once "donations.php";
require_once "donate.php";
require_once "donationdetails.php";
require_once "commands.php";
require_once "donationfacade.php"; // Include the facade class

// Ensure the user is logged in and has the 'Doner' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Doner') {
    header("Location: login.php");
    exit();
}

// Check if Beneficiary ID is provided
if (!isset($_GET['BeneficaryID'])) {
    echo "No beneficiary selected.";
    exit();
}

$beneficiaryID = htmlspecialchars($_GET['BeneficaryID']);
$donerid = $_SESSION['donerid'];
$Beneficiary = (new Beneficiary([], false))->get_by_id($beneficiaryID);
$doner = (new doner([], false))->get_by_id($donerid);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Instantiate the facade
    $facade = new DonationFacade();

    // Collect donation rows from the form
    $donationRows = $_POST['rows'];

    // Call the facade method to handle all processes
    $facade->processDonations($donationRows, $doner, $Beneficiary);

    // Redirect to the receipt page
    header("Location: receipt.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Donation</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        .donation-row {
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .donation-row h2 {
            color: #333;
            font-size: 18px;
        }
        input[type="text"], input[type="number"], input[type="date"], input[type="time"], textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .details-container {
            margin-top: 20px;
        }
        .detail-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .detail-item input {
            width: calc(50% - 5px);
        }
        .form-actions {
            text-align: center;
            margin-top: 30px;
        }
        .form-actions button {
            width: 200px;
        }
        .form-actions button[type="submit"] {
            background-color: #28a745;
        }
        .form-actions button[type="submit"]:hover {
            background-color: #218838;
        }
        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
                padding: 15px;
            }
            .detail-item input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Donation</h1>
        <form method="POST" id="donation-form">
            <div id="donation-rows">
                <div class="donation-row">
                    <h2>Donation Details</h2>
                    <input type="text" name="rows[0][donation_name]" placeholder="Donation Name" required>
                    <textarea name="rows[0][donation_desc]" placeholder="Donation Description" required></textarea>
                    <input type="number" step="0.01" name="rows[0][donation_amount]" placeholder="Donation Amount" required>

                    <h2>Donate Information</h2>
                    <input type="date" name="rows[0][donate_date]" required>
                    <input type="time" name="rows[0][donate_time]" required>
                    <input type="number" step="0.01" name="rows[0][total_amount]" placeholder="Total Amount" required>

                    <h2>Donation Details</h2>
                    <div class="details-container">
                        <div class="detail-item">
                            <input type="number" step="0.01" name="rows[0][details][0][price]" placeholder="Price" required>
                            <input type="number" name="rows[0][details][0][qty]" placeholder="Quantity" required>
                        </div>
                    </div>
                    <button type="button" onclick="addDetail(this)">Add More Details</button>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" onclick="addRow()">Add Donation Row</button>
                <button type="submit">Submit All</button>
            </div>
        </form>
    </div>
    <script>
        // JavaScript to add a new donation row
        let rowIndex = 1; // Start from 1 to differentiate rows
        function addRow() {
            const donationRows = document.getElementById("donation-rows");
            const newRow = donationRows.querySelector(".donation-row").cloneNode(true);
            const inputs = newRow.querySelectorAll("input, textarea");

            // Update names to make them unique for the new row
            inputs.forEach(input => {
                const name = input.name;
                input.name = name.replace(/\[\d+\]/, `[${rowIndex}]`);
            });

            // Append the new row to the form
            donationRows.appendChild(newRow);
            rowIndex++; // Increment row index for next addition
        }

        // JavaScript to add more donation details inside a donation row
        function addDetail(button) {
            const detailContainer = button.previousElementSibling; // .details-container
            const newDetail = detailContainer.querySelector(".detail-item").cloneNode(true);

            // Update name attributes for the new detail
            const inputs = newDetail.querySelectorAll("input");
            inputs.forEach((input, index) => {
                const name = input.name;
                input.name = name.replace(/\[\d+\]/, `[${new Date().getTime()}]`);
            });

            // Append the new detail to the container
            detailContainer.appendChild(newDetail);
        }
    </script>
</body>
</html>
