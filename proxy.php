<?php

interface IDBInterface {
    public function executeQuery(string $query);
}
?>

<?php
class ConcreteImpl implements IDBInterface {
    public function executeQuery(string $query) {
        $db = DbConnection::getInstance();
        $stmt = $db->prepare($query);
        $stmt->execute();

    }
    // public function insertEvent($eventName, $eventDate, $location, $description, $managerID, $eventType) {
    //     $query = "INSERT INTO event (EventName, EventDate, Location, Description, ManagerID, eventtype) 
    //               VALUES ('$eventName', '$eventDate', '$location', '$description', $managerID, '$eventType')";
        
    //     $this->executeQuery($query);
    // }

}
?>
<?php

class DBProxy implements IDBInterface {
    private $dbImpl;
    private $isManager;

    public function __construct($userId) {
        $this->dbImpl = new ConcreteImpl();
        $this->isManager = $this->checkIfManager($userId);

    }

    private function checkIfManager($userId) {
        // Simulate a database lookup for managers
        $connection = new mysqli("localhost", "root", "", "charity");

        if ($connection->connect_error) {
            die("Database connection failed: " . $connection->connect_error);
        }

        $query = "SELECT * FROM manger WHERE UserID = '$userId'";
        $result = $connection->query($query);

        $connection->close();
        return $result && $result->num_rows > 0;
    }

    public function executeQuery(string $query) {
        if ($this->isManager) {
            $this->dbImpl->executeQuery($query);
        } else {

            // if (stripos($query, "delete") !== false) {
            //     echo "Query contains 'delete'. This is prohibited for non-managers.\n";
            // } else {
            //     $this->dbImpl->executeQuery($query);
            // }
        }
    }
}
?>

