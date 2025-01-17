<?php
session_start();
require_once "donationdetails.php";
require_once "template.php";
require_once "UserModel.php";
require_once "donationfacade.php"; // Include the facade class

if (!isset($_SESSION['donationDetailsArray'])) {
    echo "No donation details available for the receipt.";
    exit();
}

// Deserialize the donation details array
$donationDetailsArray = unserialize($_SESSION['donationDetailsArray']);

// Use the facade to handle receipt generation and messaging
$facade = new DonationFacade();

// Determine the receipt type (default to OnlineReceipt)
$receiptType = isset($_GET['type']) ? $_GET['type'] : 'online';

// Generate receipt and optionally send message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = htmlspecialchars($_POST['payment_method']);
    $facade->handlePaymentAndMessage($donationDetailsArray, $receiptType, $paymentMethod);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
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
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007BFF;
            text-align: center;
            font-size: 2rem;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        pre {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        form {
            margin-top: 30px;
            text-align: center;
        }
        label {
            margin-right: 20px;
            font-size: 18px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .payment-options {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .payment-options input {
            margin-right: 10px;
        }
        .payment-options label {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Donation Receipt</h1>
        <pre><?php echo htmlspecialchars($facade->generateReceipt($donationDetailsArray, $receiptType)); ?></pre>

        <h2>Select Payment Method</h2>
        <form method="POST" action="receipt.php?type=<?php echo htmlspecialchars($receiptType); ?>">
            <div class="payment-options">
                <label>
                    <input type="radio" name="payment_method" value="Cash" required> Cash
                </label>
                <label>
                    <input type="radio" name="payment_method" value="Credit Card" required> Credit Card
                </label>
            </div>
            <br>
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
