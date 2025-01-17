<?php
ob_start();
require_once "state.php";
require_once "task.php";
require_once "UserModel.php";
require_once "itirator.php";
ob_clean();
class Volunteer {

    public $VolunteerID;
    public $Availability;
    public $Skills;
    public $Hours;
    public $TaskOb;
    public $UserOb;
    public $State;

    public function __construct($properties = []) {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
        $this->State = new \State\AvailableState($this); // Default state
    }

    public function setState(\State\VolunteerState $state) {
        $this->State = $state;
        $this->updateState();
    }

    public function assignTask($task) {
        $this->State->assignTask($task);
    }

    public function startTask() {
        $this->State->startTask();
    }

    public function completeTask() {
        $this->State->completeTask();
    }

    public function updateTaskID($newTaskID) {
        // Connect to the database
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }
    
        // Prepare the SQL query to update the TaskID
        echo $newTaskID;
        $sql = "UPDATE volunteer SET TaskID = ? WHERE VolunteerID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $newTaskID, $this->VolunteerID);
    
        // Execute the query
        if ($stmt->execute()) {
            echo "TaskID updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        // Close the connection
        $stmt->close();
        $con->close();
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
            $stateClass =  $row["state"];
            $this->State = new $stateClass($this);
            // if($stateClass=="State\CompletedState"){
            //     $this->State = new \State\CompletedState($this);

            // }
            // else if($stateClass=="State\AvailableState"){
            //     $this->State = new \State\AvailableState($this);

            // }
            // else if($stateClass=="State\InProgressState"){
            //     $this->State = new \State\InProgressState($this);

            // }
            // else if($stateClass=="State\AssignedState"){
            //     $this->State = new \State\AssignedState($this);

            // }

            

            return new Volunteer($row);
        }

        return null;
    }

    public function __toString() {
        $str = '<pre>';
        foreach ($this as $key => $value) {
            // Check if value is an object (like state)
            if (is_object($value)) {
                $str .= "$key: " . get_class($value) . "<br/>";
            } else {
                $str .= "$key: $value<br/>";
            }
        }
        return $str . '</pre>';
    }

    

    public function addvolunteer() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "INSERT INTO volunteer (VolunteerID, Availability, Skills, Hours, TaskID, UserID, State) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);

        $TaskID = $this->TaskOb->TaskID;
        $UserID = $this->UserOb->UserID;
        $stateClass = get_class($this->State);
        $stmt->bind_param(
            "issiiis",
            $this->VolunteerID,
            $this->Availability,
            $this->Skills,
            $this->Hours,
            $TaskID,
            $UserID,
            $stateClass
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

        $sql = "UPDATE volunteer SET Availability = ?, Skills = ?, Hours = ?, TaskID = ?, UserID = ?, State = ? WHERE VolunteerID = ?";
        $stmt = $con->prepare($sql);

        $TaskID = $this->TaskOb->TaskID;
        $UserID = $this->UserOb->UserID;
        $stateClass = get_class($this->State);
        $stmt->bind_param(
            "ssiiiis",
            $this->Availability,
            $this->Skills,
            $this->Hours,
            $TaskID,
            $UserID,
            $stateClass,
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


    public static function getAllVolunteers() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }
    
        $sql = "SELECT * FROM volunteer";
        $result = $con->query($sql);
    
        $volunteers = [];
        while ($row = mysqli_fetch_array($result)) {
            $volunteer = new Volunteer();
            $volunteer->VolunteerID = $row["VolunteerID"];
            $volunteer->Availability = $row["Availability"];
            $volunteer->Skills = $row["Skills"];
            $volunteer->Hours = $row["Hours"];
            $volunteer->TaskOb = (new Task([], false))->get_by_id($row["TaskID"]);
            $volunteer->UserOb = (new User([], false))->get_by_id($row["UserID"]);
            $volunteer->State = new $row["state"]($volunteer);
            $volunteers[] = $volunteer;
        }
    
        $con->close();
        return $volunteers;
    }
    





    public function updateState() {
        $con = mysqli_connect("localhost", "root", "", "Charity");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }

        $sql = "UPDATE volunteer SET State = ? WHERE VolunteerID = ?";
        $stmt = $con->prepare($sql);

        $stateClass = get_class($this->State);
        $stmt->bind_param(
            "si",
            $stateClass,
            $this->VolunteerID
        );

        if ($stmt->execute()) {
            echo "Volunteer state updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }
    
    function testVolunteerStatePattern() {
        // Step 1: Fetch User and Task from the database
        echo "Fetching User from the database...\n";
        $userModel = new User([],false);
        $user = $userModel->get_by_id(1); // Replace 1 with an existing UserID in the database
    
        echo "User fetched:\n";
        print_r($user);
    
        echo "\nFetching Task from the database...\n";
        $taskModel = new Task([],false);
        $task = $taskModel->get_by_id(15); // Replace 101 with an existing TaskID in the database
    
        echo "Task fetched:\n";
        print_r($task);
    
        // Step 2: Create a Volunteer object and associate the User and Task
        $volunteer = new Volunteer([
            "Availability" => "Weekends",
            "Skills" => "Event Planning, Fundraising",
            "Hours" => 10,
            "UserOb" => $user,
            "TaskOb" => null
        ]);

    
        echo "\nInitial Volunteer State:\n";
        echo $volunteer;
    
        // Step 3: Assign a Task (State changes from AvailableState to AssignedState)
        echo "\nAssigning a task to the volunteer...\n";
        $volunteer->assignTask($task);
    
        echo "Volunteer State after assigning task:\n";
        echo $volunteer;
    
        // Step 4: Start the Task (State changes from AssignedState to InProgressState)
        echo "\nStarting the task...\n";
        $volunteer->startTask();
    
        echo "Volunteer State after starting task:\n";
        echo $volunteer;
    
        // Step 5: Complete the Task (State changes from InProgressState to CompletedState)
        echo "\nCompleting the task...\n";
        $volunteer->completeTask();
    
        echo "Volunteer State after completing task:\n";
        echo $volunteer;
    
        // Step 6: Save the volunteer to the database
        echo "\nAdding volunteer to the database...\n";
        $volunteer->addvolunteer();
    
        // Step 7: Edit volunteer's availability and update it in the database
        echo "\nEditing volunteer details...\n";
        $volunteer->Availability = "Weekdays and Weekends";
        $volunteer->editvolunteer();
    
        // Step 8: Update volunteer state in the database
        echo "\nUpdating volunteer state...\n";
        $volunteer->updateState();
    }
    
    

    // Run the test
}






?>

