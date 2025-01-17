<?php

require_once "Volunteer.php";

class Beneficiary {

    public $BeneficaryID;
    public $EligibiltyStatus;
    public $FamilySize;
    public $Volunteerob;
    public $Userob;
    public $Needs;
    public $Rate;

    public function __construct($properties = []) {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
    }

    public function get_by_id($BeneficaryID) {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "SELECT * FROM beneficiary WHERE BeneficaryID = ?";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }

        $stmt->bind_param("i", $BeneficaryID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->BeneficaryID = $row["BeneficaryID"];
            $this->EligibiltyStatus = $row["EligibiltyStatus"];
            $this->FamilySize = $row["FamilySize"];
            $this->Needs = $row["Needs"];
            $this->Rate = $row["Rate"];
            if ($row["VolunteerID"] === NULL) {
                $this->Volunteerob = null;
            } else {
                $this->Volunteerob = (new Volunteer([], false))->get_by_id($row["VolunteerID"]);
            }
            
            $this->Userob = (new User([], false))->get_by_id($row["UserID"]);

            $stmt->close();
            $con->close();
            return new Beneficiary($row);
        }

        $stmt->close();
        $con->close();
        return null;
    }


    public function __toString() {
        $str = '<pre>';
        foreach ($this as $key => $value) {
            $str .= "$key: $value<br/>";
        }
        return $str . '</pre>';
    }

    public function add() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }
    
        $checkSql = "SELECT BeneficaryID FROM beneficiary WHERE BeneficaryID = ?";
        $checkStmt = $con->prepare($checkSql);
    
        if (!$checkStmt) {
            die("Error preparing check statement: " . $con->error);
        }
    
        $checkStmt->bind_param("i", $this->BeneficaryID);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
    
        if ($checkResult->num_rows > 0) {
            echo "Error: Duplicate BeneficaryID.";
            $checkStmt->close();
            $con->close();
            return;
        }
    
        $checkStmt->close();
    
        $sql = "INSERT INTO beneficiary (BeneficaryID, EligibiltyStatus, FamilySize, VolunteerID, UserID, Needs, Rate) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
    
        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }
    
        $volunteerID = $this->Volunteerob->VolunteerID ?? null;
        $userID = $this->Userob->UserID;
    
        $stmt->bind_param("isiiisi", $this->BeneficaryID, $this->EligibiltyStatus, $this->FamilySize, $volunteerID, $userID, $this->Needs, $this->Rate);
    
        if ($stmt->execute()) {
            echo "Beneficiary added successfully!";
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

        $sql = "SELECT BeneficaryID FROM beneficiary WHERE BeneficaryID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $this->BeneficaryID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $deleteSql = "DELETE FROM beneficiary WHERE BeneficaryID = ?";
            $deleteStmt = $con->prepare($deleteSql);
            $deleteStmt->bind_param("i", $this->BeneficaryID);

            if ($deleteStmt->execute()) {
                echo "Beneficiary deleted successfully!";
            } else {
                echo "Error deleting beneficiary: " . $deleteStmt->error;
            }

            $deleteStmt->close();
        } else {
            echo "Error: Beneficiary not found.";
        }

        $stmt->close();
        $con->close();
    }
    public function getAll() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "SELECT * FROM beneficiary";
        $result = $con->query($sql);
        $beneficiaries = [];
        while ($row = $result->fetch_assoc()) {
            $beneficiaries[] = $row;
        }

        $con->close();
        return $beneficiaries;
    }
    public function edit() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }
    
        $sql = "UPDATE beneficiary SET EligibiltyStatus = ?, FamilySize = ?, VolunteerID = ?, UserID = ?, Needs = ?, Rate = ? WHERE BeneficaryID = ?";
        $stmt = $con->prepare($sql);
    
        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }
    
        $volunteerID = $this->Volunteerob->VolunteerID ?? null;
        $userID = $this->Userob->UserID ?? null;
    
        $stmt->bind_param("siiisii", $this->EligibiltyStatus, $this->FamilySize, $volunteerID, $userID, $this->Needs, $this->Rate, $this->BeneficaryID);
    
        if ($stmt->execute()) {
            echo "Beneficiary updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
        $con->close();
    }

    
    public function get_donations_by_beneficiary() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }
    
        $sql = "
            SELECT 
                d.DonateID,
                dn.DonationName,
                dn.DonationID,
                dn.DonationDescription,
                dn.DonationAmout,
                dd.Price,
                dd.ProductQty
            FROM 
                donate d
            JOIN 
                donationsdetails dd ON d.DonateID = dd.DonateID
            JOIN 
                donations dn ON dd.DonationID = dn.DonationID
            WHERE 
                d.BeneficiaryID = ?;
        ";
    
        $stmt = $con->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }
    
        $stmt->bind_param("i", $this->BeneficaryID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $donations = [];
        while ($row = $result->fetch_assoc()) {
            $donations[] = [
                "DonateID" => $row["DonateID"],
                "DonationName" => $row["DonationName"],
                "DonationID" => $row["DonationID"],
                "DonationDescription" => $row["DonationDescription"],
                "DonationAmount" => $row["DonationAmout"],
                "Price" => $row["Price"],
                "ProductQty" => $row["ProductQty"],
            ];
        }
    
        $stmt->close();
        $con->close();
    
        return $donations;
    }
    
    public function update_rate($rate) {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }
    
        $sql = "UPDATE beneficiary SET Rate = ? WHERE BeneficaryID = ?";
        $stmt = $con->prepare($sql);
    
        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }
    
        $stmt->bind_param("ii", $rate, $this->BeneficaryID);
    
        if ($stmt->execute()) {
        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
        $con->close();
    }
    
    public function update_needs($needs) {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }
    
        $sql = "UPDATE beneficiary SET Needs = ? WHERE BeneficaryID = ?";
        $stmt = $con->prepare($sql);
    
        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }
    
        $stmt->bind_param("si", $needs, $this->BeneficaryID);
    
        if ($stmt->execute()) {
        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
        $con->close();
    }
    
    
}


?>
