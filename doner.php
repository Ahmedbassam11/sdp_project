<?php

require_once "commands.php";
require_once "template.php";
require_once "donationdetails.php";

class doner{

    public $DonerID;
    public $Credit;
    public $UserOb;

    public function __construct($properties)
    {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
    }
    public function add() {
        
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

       
        $sql = "INSERT INTO doner (DonerID, Credit, UserID) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $userID = $this->UserOb->UserID;
        $stmt->bind_param("isi", $this->DonerID, $this->Credit, $userID);

        if ($stmt->execute()) {
            echo "Donor added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

       
        $stmt->close();
        $con->close();
    }
   
     public function get_by_id($DonerID){
        $con = mysqli_connect("localhost","root","","Charity");
        if(!$con){
            die('Could not connect: '.mysqli_connect_error());

        }
        $sql = "SELECT * FROM doner WHERE DonerID = $DonerID";
        $DonerData = $con->query($sql);
        if($row = mysqli_fetch_array($DonerData)){
            $this->DonerID=$row["DonerID"];
            $this->Credit=$row["Credit"];
            $this->UserOb=(new User([],false))->get_by_id($row["UserID"]) ;

            return new Doner($row);
        }
    }


    public function update() {
        
       
        
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

       
        $sql = "UPDATE doner SET Credit = ?, UserID = ? WHERE DonerID = ?";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }

      
        $UserID = $this->UserOb->UserID ; 
        $stmt->bind_param("isi", $this->Credit, $UserID, $this->DonerID);

       
        if ($stmt->execute()) {
            echo "Donor updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

       
        $stmt->close();
        $con->close();
    }

    public function delete() {
        
        if ($this->DonerID === null) {
            echo "DonerID is null, cannot delete.";
            return;
        }

        
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "DELETE FROM doner WHERE DonerID = ?";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }

        $stmt->bind_param("i", $this->DonerID);

        if ($stmt->execute()) {
            echo "Donor deleted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }

    public function __toString()
    {
        $str = '<pre>';
        foreach ($this as $key => $value) {
            $str .= "$key: $value<br/>";
        }
        return $str . '</pre>';
    }

    public function executeCommand(Command $command) {
        $command->execute();
    }
 
    public function get_receipt($donationDetailsArray){
        $reciept= (new DonationDetails([]))->generateSummaryReceipt($donationDetailsArray, new OnlineReceipt());

        echo $reciept;

    }

}


// $donationdetail= (new DonationDetails([]))->get_by_id(2);


// $donationDetailsArray = [$donationdetail];

// $doner= (new doner([],false))->get_receipt($donationDetailsArray);






// echo "Fetching User from the database...\n";
// $userModel = new User([],false);
// $userModel2 = new User([],false);
// $user = $userModel->get_by_id(1);
// $user2 = $userModel2->get_by_id(34);

// $donermodel = new doner([],false);
// $doner = $donermodel->get_by_id(3);
// $beneficiarymodel= new Beneficiary([],false);
// $beneficiary = $beneficiarymodel->get_by_id(101);

// $donation = new donations([
    
//     'DonationName' => 'Food ',
//     'DonationID'=>6,
//     'DonationDescription' => 'Providing food supplies to families in need',
//     'DonationAmout' => 600.0,
    
// ]);

// $donate = new donate([
//     'DonateID' => 7,
//     'Doner' => $doner,
//     'Beneficiary' => $beneficiary,
//     'DonateDate' => '2025-01-15',
//     'DonateTime' => '12:30:00',
//     'TotalAmount' => 500.0
// ]);

// $donationDetails = new donationdetails([
    
//     'Donation' => $donation,
//     'Donate' => $donate,
//     'Price' => 250.0,
//     'ProductQty' => 2
// ]);

// // Initialize commands
// $addCommand = new AddDonationCommand($donation, $donate, $donationDetails);


// echo "added but not in database...\n";
// // Create CommandInvoker and add commands
// $invoker = new CommandInvoker();
// $invoker->addCommand($addCommand);

// echo "added in invoker but not in database...\n";

// // Execute commands
// $invoker->executeCommands();
// echo "added successfully in database...\n";

?>