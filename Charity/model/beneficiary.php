<?php

require "Volunteer.php";

class Beneficiary {

    public $BeneficaryID;
    public $EligibiltyStatus;
    public $FamilySize;
    public $Volunteerob;
    public $Userob;

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
            $this->Volunteerob = (new Volunteer([], false))->get_by_id($row["VolunteerID"]);
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

        $sql = "INSERT INTO beneficiary (BeneficaryID, EligibiltyStatus, FamilySize, VolunteerID, UserID) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }

        $volunteerID = $this->Volunteerob->VolunteerID ?? null;
        $userID = $this->Userob->UserID ?? null;

        if (!$volunteerID || !$userID) {
            die("Error: VolunteerID or UserID is not set.");
        }

        $stmt->bind_param("isiii", $this->BeneficaryID, $this->EligibiltyStatus, $this->FamilySize, $volunteerID, $userID);

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

    public function edit() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "UPDATE beneficiary SET EligibiltyStatus = ?, FamilySize = ?, VolunteerID = ?, UserID = ? WHERE BeneficaryID = ?";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }

        $volunteerID = $this->Volunteerob->VolunteerID ?? null;
        $userID = $this->Userob->UserID ?? null;

        if (!$volunteerID || !$userID) {
            die("Error: VolunteerID or UserID is not set.");
        }

        $stmt->bind_param("siiii", $this->EligibiltyStatus, $this->FamilySize, $volunteerID, $userID, $this->BeneficaryID);

        
        if ($stmt->execute()) {
            echo "Beneficiary updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }
}


?>
