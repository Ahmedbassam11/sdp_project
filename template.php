<?php

abstract class ReceiptTemplate {
    // Template method
    public function generateReceipt($donationDetails): string {
        $receipt = "Receipt\n";
        $receipt .= "-------------------------\n";
        $receipt .= $this->generateHeader($donationDetails);
        $receipt .= $this->generateBody($donationDetails);
        $receipt .= $this->generateFooter($donationDetails);
        return $receipt;
    }

    // Common header for all receipts
    protected function generateHeader($donationDetails): string {
        return "Donation ID: {$donationDetails->Donation->DonationID}\n" .
               "Donation Name: {$donationDetails->Donation->DonationName}\n";
    }

    // Abstract method for the body, to be implemented by subclasses
    abstract protected function generateBody($donationDetails): string;

    // Common footer for all receipts
    protected function generateFooter($donationDetails): string {
        return "-------------------------\n" .
               "Thank you for your contribution!";
    }
}


class OnlineReceipt extends ReceiptTemplate {
    protected function generateBody($donationDetails): string {
        return "Donation Type: Online\n" .
               "Amount: {$donationDetails->Donation->DonationAmout}\n" .
               "Quantity: {$donationDetails->ProductQty}\n";
    }
}


class InKindReceipt extends ReceiptTemplate {
    protected function generateBody($donationDetails): string {
        return "Donation Type: In-Kind\n" .
               "Description: {$donationDetails->Donation->DonationDescription}\n" .
               "Quantity: {$donationDetails->ProductQty}\n";
    }
}


class CheckReceipt extends ReceiptTemplate {
    protected function generateBody($donationDetails): string {
        return "Donation Type: Check\n" .
               "Amount: {$donationDetails->Donation->DonationAmout}\n" .
               "Quantity: {$donationDetails->ProductQty}\n";
    }
}

?>
