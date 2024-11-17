<?php

require("UserModel.php");

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

 

}


?>