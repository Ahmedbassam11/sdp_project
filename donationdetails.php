<?php
require_once "donate.php";
require_once "donations.php";

class DonationDetails {
    public $DonationDetailsID;
    public $Donation;
    public $Donate;
    public $Price;
    public $ProductQty;

    public function __construct($properties) {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
    }

    public function add() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "INSERT INTO donationsdetails (DonationDetailsID, DonationID, DonateID, Price, ProductQty) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param(
            "iiiid",
            $this->DonationDetailsID,
            $this->Donation->DonationID, // Assuming Donations object has DonationID
            $this->Donate->DonateID, // Assuming Donate object has DonateID
            $this->Price,
            $this->ProductQty
        );

        if ($stmt->execute()) {
            echo "DonationDetails entry added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }

    public function get_by_id($DonationDetailsID) {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "SELECT * FROM donationsdetails WHERE DonationDetailsID = $DonationDetailsID";
        $result = $con->query($sql);
        if ($row = $result->fetch_assoc()) {
            $this->DonationDetailsID = $row["DonationDetailsID"];
            $this->Donation = (new Donations([]))->get_by_id($row["DonationID"]);
            $this->Donate = (new Donate([]))->get_by_id($row["DonateID"]);
            $this->Price = $row["Price"];
            $this->ProductQty = $row["ProductQty"];

            return $this;
        }

        $con->close();
    }

    public function update() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "UPDATE donationsdetails SET DonationID = ?, DonateID = ?, Price = ?, ProductQty = ? WHERE DonationDetailsID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param(
            "iiiid",
            $this->Donation->DonationID,
            $this->Donate->DonateID,
            $this->Price,
            $this->ProductQty,
            $this->DonationDetailsID
        );

        if ($stmt->execute()) {
            echo "DonationDetails entry updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }

    public function delete() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "DELETE FROM donationdetails WHERE DonationDetailsID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $this->DonationDetailsID);

        if ($stmt->execute()) {
            echo "DonationDetails entry deleted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }

    public function get_totalprice($donation) {
        // Output before applying decorators
        echo "before taxes of unit: " . "{$donation->calcPrice()}\n" ;
    
        // Apply the OnlineDecorator and VATDecorator
        $donationWithDecorators = new VATDecorator(new OnlineDecorator($donation));
    
        // Calculate price after decorators
        $unit_price = $donationWithDecorators->calcPrice();
        
        // Output after applying decorators
        echo "after taxes of unit: " . "{$unit_price}\n" ;
    
        // Calculate the total price based on the quantity
        $totalprice = $unit_price * $this->ProductQty;
        return $totalprice;
    }
    

    public function generateSummaryReceipt(array $donationDetailsArray, ReceiptTemplate $receiptTemplate): string {
        $totalAmount = 0.0;
        $summary = "Summary Receipt\n\n";
        $summary .= "-----------------------------------------\n";
    
        foreach ($donationDetailsArray as $index => $donationDetails) {
            // Get the donation object for this donation detail
            $donation = $donationDetails->Donation;
    
            
            // Get the total price for this item by passing the donation object
            $itemTotal = $donationDetails->get_totalprice($donation);
    
            // Add to the grand total
            $totalAmount += $itemTotal;
    
            // Build the summary receipt for this item
            $summary .= "Item #" . ($index + 1) . ":\n";
            $summary .= $receiptTemplate->generateReceipt($donationDetails); // Generate receipt for this donation detail
            $summary .= "Item Total: $" . number_format($itemTotal, 2) . "\n";
            $summary .= "-----------------------------------------\n";
        }
    
        // Output the grand total at the end
        $summary .= "\nGrand Total: $" . number_format($totalAmount, 2) . "\n";
        $summary .= "-----------------------------------------\n";
        return $summary;
    }
    
    public function __toString() {
        $str = '<pre>';
        foreach ($this as $key => $value) {
            // Check if the value is an object
            if (is_object($value)) {
                if (method_exists($value, '__toString')) {
                    $str .= "$key: " . $value->__toString() . "<br/>";
                } else {
                    $str .= "$key: Object of class " . get_class($value) . "<br/>";
                }
            } elseif ($value === null) {
                $str .= "$key: Not Set<br/>";
            } else {
                $str .= "$key: $value<br/>";
            }
        }
        return $str . '</pre>';
    }
    
    
    

}
  //echo (new donationdetails([]))->get_by_id(2);
// echo "Fetching User from the database...\n";


// $beneficiarymodel= new Beneficiary([],false);
// $beneficiary = $beneficiarymodel->get_by_id(101);
// $donermodel = new doner([],false);
// $doner = $donermodel->get_by_id(3);
// $donation = (new donations([],false))->get_by_id(1);
// echo $donation;



// $donate = (new donate([],false))->get_by_id(1);

// $donationDetails = new donationdetails([
    
//     'Donation' => $donation,
//     'Donate' => $donate,
//     'Price' => 250.0,
//     'ProductQty' => 2
// ]);

// $donationDetails->add();
?>