<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficiary Donations</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet"> <!-- Adding Google Fonts -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-top: 30px;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        td {
            background-color: #f9f9f9;
        }

        td:hover {
            background-color: #f1f1f1;
        }

        .form-container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
        }

        .form-container input, .form-container textarea {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .form-container input:focus, .form-container textarea:focus {
            border-color: #007BFF;
            outline: none;
        }

        .form-container button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .no-donations {
            text-align: center;
            font-size: 18px;
            color: #666;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color: #999;
        }

        /* Responsiveness for smaller screens */
        @media (max-width: 768px) {
            table {
                width: 100%;
                margin: 20px;
            }
            .form-container {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <h1>Beneficiary Donations</h1>

    <?php if (!empty($donations)): ?>
        <table>
            <thead>
                <tr>
                    <th>Donate ID</th>
                    <th>Donation Name</th>
                    <th>Donation Description</th>
                    <th>Donation Amount</th>
                    <th>Price</th>
                    <th>Product Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['DonateID']); ?></td>
                        <td><?php echo htmlspecialchars($donation['DonationName']); ?></td>
                        <td><?php echo htmlspecialchars($donation['DonationDescription']); ?></td>
                        <td><?php echo htmlspecialchars($donation['DonationAmount']); ?></td>
                        <td><?php echo htmlspecialchars($donation['Price']); ?></td>
                        <td><?php echo htmlspecialchars($donation['ProductQty']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-donations">No donations found for your account.</p>
    <?php endif; ?>

    <div class="form-container">
        <h3>Update Rate</h3>
        <form action="beneficiarycontroller.php" method="POST">
            <label for="rate">Rate: </label>
            <input type="number" id="rate" name="rate" required>
            <button type="submit" name="update_rate">Update Rate</button>
        </form>
    </div>

    <div class="form-container">
        <h3>Update Needs</h3>
        <form action="beneficiarycontroller.php" method="POST">
            <label for="needs">Needs: </label>
            <textarea id="needs" name="needs" required></textarea>
            <button type="submit" name="update_needs">Update Needs</button>
        </form>
    </div>

    <div class="footer">
        <p>&copy; 2025 Charity Organization | All rights reserved</p>
    </div>
</body>
</html>
