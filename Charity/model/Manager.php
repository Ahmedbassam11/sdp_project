<?php

require ("UserModel.php");

class manager{

    public $ManagerID;
    public $YearsOfExperience;
    public $DepartmentName;
    public $UserOb;

    public function __construct($properties)
    {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
    }

     public function get_by_id($ManagerID){
        $con = mysqli_connect("localhost","root","","Charity");
        if(!$con){
            die('Could not connect: '.mysqli_connect_error());

        }
        $sql = "SELECT * FROM manger WHERE ManagerID = $ManagerID";
        $mangerData = $con->query($sql);
        if($row = mysqli_fetch_array($mangerData)){
            $this->ManagerID=$row["ManagerID"];
            $this->YearsOfExperience=$row["YearsOfExperience"];
            $this->DepartmentName=$row["DepartmentName"];
            
            $this->UserOb=(new User([],false))->get_by_id($row["UserID"]) ;

            return new manager($row);
        }
    }
       
    public function __toString()
    {
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
    
        
        $sql = "INSERT INTO manger (ManagerID, YearsOfExperience, DepartmentName, UserID) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
    
        
        $userID = $this->UserOb->UserID;
        $stmt->bind_param("iisi", $this->ManagerID, $this->YearsOfExperience, $this->DepartmentName, $userID); 
    
        if ($stmt->execute()) {
            echo "Manager added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        $con->close();
    }
    
    public function update() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        
        $sql = "UPDATE manger SET YearsOfExperience = ?, DepartmentName = ?, UserID = ? WHERE ManagerID = ?";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }

        
        $userID = $this->UserOb->UserID; 
        $stmt->bind_param("isii", $this->YearsOfExperience, $this->DepartmentName, $userID, $this->ManagerID);

        
        if ($stmt->execute()) {
            echo "Manager updated successfully!";
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

        
        $sql = "DELETE FROM manger WHERE ManagerID = ?";
        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $con->error);
        }

       
        $stmt->bind_param("i", $this->ManagerID);

        
        if ($stmt->execute()) {
            echo "Manager deleted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        
        $stmt->close();
        $con->close();
    }

}


?>