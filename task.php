<?php
ob_start();
require_once("Manager.php");
ob_end_clean();
class task 
{
    public $TaskID;
    public $Managerob;
    public $TaskName;
    public $TaskDescription;


    public function __construct($properties, $insert = false)
    {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
        if ($insert) {
            $this->insertTask();
        }
    }

    public function insertTask() {
        
       
        $db = mysqli_connect("localhost", "root", "", "Charity");
        if (!$db) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        
        $sql = "INSERT INTO task ( TaskName,TaskDescription, ManagerID) VALUES ( ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $Managerid = $this->Managerob->ManagerID; 
        $stmt->bind_param("ssi",  $this->TaskName,$this->TaskDescription, $Managerid);
        
        if ($stmt->execute()) {
            // Set the TaskID after insertion
            $this->TaskID = $db->insert_id;  // Get the last inserted TaskID
            echo "Task added successfully! TaskID: " . $this->TaskID;
        } else {
            echo "Error: " . $stmt->error;
        }
        

        
        $stmt->close();
        $db->close();
    }
   
    public function get_by_id($TaskID): ?task
{
    if (is_null($TaskID) || empty($TaskID)) {
        // If TaskID is invalid or empty, return null instead of running the query
        return null;
    }

    $db = new mysqli('localhost', 'root', '', 'Charity');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $sql = "SELECT * FROM task WHERE TaskID = $TaskID";
    $result = $db->query($sql);

    if ($row = $result->fetch_assoc()) {
        $this->TaskID = $row["TaskID"];
        $this->TaskName = $row["TaskName"];
        $this->TaskDescription = $row["TaskDescription"];
        $this->Managerob = (new manager([], false))->get_by_id($row["ManagerID"]);
        return new task($row, false);
    } else {
        return null;
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
}


?>
