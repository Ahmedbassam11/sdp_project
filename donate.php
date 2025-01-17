<?php
require_once "beneficiary.php";
require_once "doner.php";

class donate {
    public $DonateID;
    public $Doner;
    public $Beneficiary;
    public $DonateDate;
    public $DonateTime;
    public $TotalAmount;

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

        $sql = "INSERT INTO donate (DonateID, DonerID, BeneficiaryID, DonateDate, DonateTime, TotalAmount) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param(
            "iiissi",
            $this->DonateID,
            $this->Doner->DonerID, // Assuming User object has UserID
            $this->Beneficiary->BeneficaryID, // Assuming User object has UserID
            $this->DonateDate,
            $this->DonateTime,
            $this->TotalAmount
        );

        if ($stmt->execute()) {
            $this->DonateID = $con->insert_id;  
            echo "Donate entry added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }

    public function get_by_id($DonateID) {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "SELECT * FROM donate WHERE DonateID = $DonateID";
        $result = $con->query($sql);
        if ($row = $result->fetch_assoc()) {
            $this->DonateID = $row["DonateID"];
            $this->Doner = (new doner([],false))->get_by_id($row["DonerID"]); // Assuming User constructor accepts UserID
            $this->Beneficiary = (new Beneficiary([],false))->get_by_id($row["BeneficiaryID"]);
            $this->DonateDate = $row["DonateDate"];
            $this->DonateTime = $row["DonateTime"];
            $this->TotalAmount = $row["TotalAmount"];

            return new donate($row);
        }

        $con->close();
    }

    public function update() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "UPDATE donate SET DonerID = ?, BeneficiaryID = ?, DonateDate = ?, DonateTime = ?, TotalAmount = ? WHERE DonateID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param(
            "iissii",
            $this->Doner->UserID,
            $this->Beneficiary->UserID,
            $this->DonateDate,
            $this->DonateTime,
            $this->TotalAmount,
            $this->DonateID
        );

        if ($stmt->execute()) {
            echo "Donate entry updated successfully!";
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

        $sql = "DELETE FROM donate WHERE DonateID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $this->DonateID);

        if ($stmt->execute()) {
            echo "Donate entry deleted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }
}




// echo "Fetching User from the database...\n";


// $beneficiarymodel= new Beneficiary([],false);
// $beneficiary = $beneficiarymodel->get_by_id(101);
// $donermodel = new doner([],false);
// $doner = $donermodel->get_by_id(3);



// $donate = new donate([
//     'Doner' => $doner,
//     'Beneficiary' => $beneficiary,
//     'DonateDate' => '2025-01-15',
//     'DonateTime' => '12:30:00',
//     'TotalAmount' => 500.0
// ]);

// $donate->add();


?>