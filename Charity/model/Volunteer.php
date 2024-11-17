<?php

require "task.php";

class Volunteer {

    public $VolunteerID;
    public $Availability;
    public $Skills;
    public $Hours;
    public $TaskOb;
    public $UserOb;

    public function __construct($properties = []) {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
    }

    public function get_by_id($VolunteerID) {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "SELECT * FROM volunteer WHERE VolunteerID = $VolunteerID";
        $volunteerData = $con->query($sql);

        if ($row = mysqli_fetch_array($volunteerData)) {
            $this->VolunteerID = $row["VolunteerID"];
            $this->Availability = $row["Availability"];
            $this->Skills = $row["Skills"];
            $this->Hours = $row["Hours"];
            $this->TaskOb = (new Task([], false))->get_by_id($row["TaskID"]);
            $this->UserOb = (new User([], false))->get_by_id($row["UserID"]);

            return new Volunteer($row);
        }

        return null;
    }

    public function __toString() {
        $str = '<pre>';
        foreach ($this as $key => $value) {
            $str .= "$key: $value<br/>";
        }
        return $str . '</pre>';
    }

    public function addvolunteer() {
       
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "INSERT INTO volunteer (VolunteerID, Availability, Skills, Hours, TaskID, UserID) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        
        $TaskID = $this->TaskOb->TaskID ;
        $UserID = $this->UserOb->UserID ;
        $stmt->bind_param(
            "issiii",
            $this->VolunteerID,
            $this->Availability,
            $this->Skills,
            $this->Hours,
            $TaskID,
            $UserID
        );
    
        
        
    
        
        if ($stmt->execute()) {
            echo "Volunteer added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        
        $stmt->close();
        $con->close();
    }


    public function deletevolunteer() {
       
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "DELETE FROM volunteer WHERE VolunteerID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $this->VolunteerID);
        
        
        if ($stmt->execute()) {
            echo "Volunteer deleted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        $con->close();
    }

    public function editvolunteer() {
        
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "UPDATE volunteer SET Availability = ?, Skills = ?, Hours = ?, TaskID = ?, UserID = ? WHERE VolunteerID = ?";
        $stmt = $con->prepare($sql);
        
        
        $TaskID = $this->TaskOb->TaskID ;
        $UserID = $this->UserOb->UserID ;
        $stmt->bind_param(
            "ssiiii",
            $this->Availability,
            $this->Skills,
            $this->Hours,
            $TaskID,
            $UserID,
            $this->VolunteerID
        );
    
        
        if ($stmt->execute()) {
            echo "Volunteer updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

      
        $stmt->close();
        $con->close();
    }

    
}


?>
