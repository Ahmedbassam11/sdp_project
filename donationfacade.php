<?php
class DonationFacade {
    public function processDonations($donationRows, $doner, $Beneficiary) {
        $commandInvoker = new CommandInvoker();
        $donationDetailsArray = [];

        foreach ($donationRows as $row) {
            $donation = new donations([
                'DonationID' => null,
                'DonationName' => htmlspecialchars($row['donation_name']),
                'DonationDescription' => htmlspecialchars($row['donation_desc']),
                'DonationAmout' => (float)$row['donation_amount'],
            ]);

            $donate = new donate([
                'DonateID' => null,
                'Doner' => $doner,
                'Beneficiary' => $Beneficiary,
                'DonateDate' => htmlspecialchars($row['donate_date']),
                'DonateTime' => htmlspecialchars($row['donate_time']),
                'TotalAmount' => (float)$row['total_amount'],
            ]);

            foreach ($row['details'] as $detail) {
                $donationDetail = new DonationDetails([
                    'DonationDetailsID' => null,
                    'Donation' => $donation,
                    'Donate' => $donate,
                    'Price' => (float)$detail['price'],
                    'ProductQty' => (int)$detail['qty'],
                ]);

                $donationDetailsArray[] = $donationDetail;
                $addDonationCommand = new AddDonationCommand($donation, $donate, $donationDetail);
                $commandInvoker->addCommand($addDonationCommand);
            }
        }

        $commandInvoker->executeCommands();
        $_SESSION['donationDetailsArray'] = serialize($donationDetailsArray);
    }

    public function generateReceipt($donationDetailsArray, $receiptType) {
        $receiptTemplate = match (strtolower($receiptType)) {
            'inkind' => new InKindReceipt(),
            'check' => new CheckReceipt(),
            default => new OnlineReceipt(),
        };

        return (new DonationDetails([]))->generateSummaryReceipt($donationDetailsArray, $receiptTemplate);
    }

    public function handlePaymentAndMessage($donationDetailsArray, $receiptType, $paymentMethod) {
        $summary = $this->generateReceipt($donationDetailsArray, $receiptType);
        $userid = $_SESSION['UserID'];

        $message = match ($paymentMethod) {
            'Cash' => "Payment Method: Cash\nThank you for your generous donation!",
            'Credit Card' => "Payment Method: Credit Card\nYour payment has been successfully processed!",
            default => "Payment Method: Unknown\nPlease contact support for assistance.",
        };

        $user = (new User([], false))->get_by_id($userid);
        $user->sendSMS($message);

        // Return the message instead of echoing it
        return nl2br($message); // Use nl2br to format line breaks for HTML rendering
    }
}
?>
