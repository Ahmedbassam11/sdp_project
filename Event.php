<?php
ob_start();


require_once "UserModel.php";
require_once "proxy.php";   
ob_clean();


interface EventType {
    public function eventCreation(Event $event, $userID);
}



class Fundraiser implements EventType {
    public function eventCreation(Event $event, $userID) {
        $event->eventtype = "Fundraiser";

        $notification = new notification_system();
        $users = User::getAllUsers();
        
        foreach ($users as $user) {
        
            $notification->addObserver($user);
        }

        $notification->setevent($event->EventName);
        $event->insertEvent($userID); // Pass userID to insertEvent
        return "Fundraiser event created!";
    }
}


class Workshop implements EventType {
    public function eventCreation(Event $event,$userID) {
        $event->eventtype = "Workshop";

        $notification = new notification_system();
        $users = User::getAllUsers();
        foreach ($users as $user) {
            $notification->addObserver($user);
        }
         
        $notification->setevent($event->EventName);
        
        
        $event->insertEvent($userID);

        return "Workshop event created!";
    }
}


class Outreach implements EventType {
    public function eventCreation($event,$userID) {
        $event->eventtype = "Outreach";

        $notification = new notification_system();
        $users = User::getAllUsers();
        foreach ($users as $user) {
            $notification->addObserver($user);
        }
         
        $notification->setevent($event->EventName);
        
        
        $event->insertEvent($userID);

        return "Outreach event created!";
    }
}





abstract class Event implements Subject
{
    public $EventID;
    public $EventName;
    public $EventDate;
    public $Location;
    public $Description;
    public $ManagerID;
    public $eventtype;
    public $message;
    private $connection;
    private $observers = [];

    abstract protected function createEvent(): EventType;


    public function planEvent($userID) {
        $eventType = $this->createEvent();
        return $eventType->eventCreation($this,$userID);  // Pass current Event object
    }



    // Create a new event
    public function insertEvent($userID)
    {
        $proxy = new DBProxy($userID);
        if (isset($this->EventName, $this->EventDate, $this->Location, $this->Description, $this->ManagerID, $this->eventtype)) {
            $query = "INSERT INTO event (EventName, EventDate, Location, Description, ManagerID, eventtype) 
                      VALUES ('$this->EventName', '$this->EventDate', '$this->Location', '$this->Description', '$this->ManagerID', '$this->eventtype')";
    

        }
        $proxy->executeQuery($query);
    }
    public function __toString() {
        $str = '<pre>';
        foreach ($this as $key => $value) {
            $str .= "$key: $value<br/>";
        }
        return $str . '</pre>';
    }

   


    // Read event by ID



    public function getEventById($eventId)
    {
        $db = DbConnection::getInstance();
        $stmt=$db->prepare("SELECT * FROM event WHERE EventID = ?");


        $stmt->bind_param("i", $eventId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->EventID = $row["EventID"];
            $this->EventName = $row["EventName"];
            $this->EventDate = $row["EventDate"];
            $this->Location = $row["Location"];
           
            $this->Description=$row["Description"];
            $this->ManagerID=$row["ManagerID"];
            $this->eventtype=$row["eventtype"];
           
        

        $stmt->close();
        return $this;
    }
}

    // Read all events
    public function getAllEvents()
    {
        $db = DbConnection::getInstance();
        $stmt=$db->prepare("SELECT * FROM event") ;
        $stmt->execute();

        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception("Query failed: " . $this->connection->error);
        }

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }

    // Update an existing event
    public function updateEvent($eventId, $eventName, $eventDate, $location, $description, $managerID)
    {
        $sql = "UPDATE event SET EventName = ?, EventDate = ?, Location = ?, Description = ?, ManagerID = ? WHERE EventID = ?";
        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            throw new Exception("SQL prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param("ssssii", $eventName, $eventDate, $location, $description, $managerID, $eventId);

        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }

    // Delete an event
    public function deleteEvent($eventId)
    {
        $sql = "DELETE FROM event WHERE EventID = ?";
        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            throw new Exception("SQL prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param("i", $eventId);

        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }

    public function addObserver(NotificationObserver $observer){
        $this->observers[] = $observer;
        
    }

    
    public function removeObserver(NotificationObserver $observer){
        $index = array_search($observer, $this->observers, true);
        if ($index !== false) {
            unset($this->observers[$index]);
        }
    }

     
    public function notifyObservers()
{
    foreach ($this->observers as $observer) {
            
        $observer->update($this->message);
    }
}
public function loadObservers(): array
{
    $db = DbConnection::getInstance();
    $stmt = $db->prepare("SELECT * FROM user WHERE eventid = ?");

    if (!$stmt) {
        throw new Exception("SQL prepare failed: " . $db->error);
    }

    $stmt->bind_param("i", $this->EventID);

    if (!$stmt->execute()) {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $observers = []; // List of User objects

    while ($row = $result->fetch_assoc()) {
        // Create a new User object for each observer and add it to the list
        $user = new User($row, false);
        $observers[] = $user;
    }

    $stmt->close();

    // Update the observers list of the Event instance
    $this->observers = $observers;

    return $observers;
}


    public function setdate($EventDate){
         $this->EventDate=$EventDate;
         $this->loadObservers();
         $this->message ="Event: "+ $this->EventName+"Date has changed to "+$this->EventDate;
         $this->notifyObservers(); 
    }
      

    
    public function setname($EventName){
        $this->EventName=$EventName;
        $this->loadObservers();
        $this->message =$this->EventName;
        $this->notifyObservers();
    }

    public function setdescription($Description){
        $this->Description=$Description;
         $this->loadObservers();
        $this->message ="Event: "+"Description has changed to "+$this->Description;
        $this->notifyObservers();

    }
    public function setlocation($Location){

        $this->Location=$Location;
        $this->loadObservers();
        $this->message ="Event: "+"Location has changed to "+$this->Location;
        $this->notifyObservers();
    }

    function getEventsByManagerId($managerId) {
        $db = DbConnection::getInstance();
        $stmt = $db->prepare("SELECT * FROM event WHERE ManagerID = ?");
    
        if (!$stmt) {
            throw new Exception("SQL prepare failed: " . $db->error);
        }
    
        $stmt->bind_param("i", $managerId);
    
        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }
    
        $result = $stmt->get_result();
        $events = [];
    
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    
        $stmt->close();
        return $events;
    }

}



 

class FundraiseCreation extends Event {
    public function __construct($properties = []) {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
    }
    protected function createEvent(): EventType {
        return new Fundraiser();
    }
}

class WorkshopCreation extends Event {
    public function __construct($properties = []) {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
    }
    protected function createEvent(): EventType {
        return new Workshop();
    }
}

class OutreachCreation extends Event {
    public function __construct($properties = []) {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }
    }
    protected function createEvent(): EventType {
        return new Outreach();
    } 
}


// $fundraiserEvent = new FundraiseCreation([
//     'EventName' => 'lyla belo',
//     'EventDate' => '2004-02-16',
//     'Location' => 'Cityz ]zj',
//     'Description' => ' the xcommunity.',
//     'ManagerID' => 7
// ]);

// $userID = 1; // Example user ID of the current user
// echo $fundraiserEvent->planEvent($userID);


?>