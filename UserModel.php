<?php
ob_start();
require_once "notification_system.php";
require_once "DB.php";
require_once "NotificationFacade.php";
ob_clean();

class User implements NotificationObserver
{
    public $UserID;
    public $Name;
    public $Address;
    public $PhoneNumber;
    public $Email;
    public $Password;

    public function __construct($properties, $insert = false, Subject $x = null)
    {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }

        if ($insert) {
            $this->insertUser();
        }
    }

    public function flogin($email, $password): ?User
    {
        $db = DbConnection::getInstance();
        $stmt = $db->prepare("SELECT * FROM User WHERE Email = ? AND Password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new User($row, false);
        } else {
            return null;
        }
    }

    public function updateUser(): bool
    {
        $db = DbConnection::getInstance();
        $stmt = $db->prepare("UPDATE User SET Name = ?, Address = ?, PhoneNumber = ?, Email = ?, Password = ? WHERE UserID = ?");
        $stmt->bind_param("sssssi", $this->Name, $this->Address, $this->PhoneNumber, $this->Email, $this->Password, $this->UserID);
        $success = $stmt->execute();

        if (!$success) {
            echo "Error updating user: " . $stmt->error;
        }

        $stmt->close();
        $db->close();
        return $success;
    }

    public function deleteUser($UserID): bool
    {
        $db = DbConnection::getInstance();
        $stmt = $db->prepare("DELETE FROM User WHERE UserID = ?");
        $stmt->bind_param("i", $UserID);
        $success = $stmt->execute();

        if (!$success) {
            echo "Error deleting user: " . $stmt->error;
        }

        $stmt->close();
        $db->close();
        return $success;
    }

    private function insertUser(): bool
    {
        $db = DbConnection::getInstance();
        $stmt = $db->prepare("INSERT INTO User (Name, Address, PhoneNumber, Email, Password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $this->Name, $this->Address, $this->PhoneNumber, $this->Email, $this->Password);
    
        if ($stmt->execute()) {
            // Retrieve the auto-generated UserID
            $this->UserID = $db->insert_id;
            echo "User inserted successfully with UserID: " . $this->UserID;
            return true;
        } else {
            echo "Error inserting user: " . $stmt->error;
            return false;
        }
    
        $stmt->close();
        $db->close();
    }
    

    public static function getAllUsers(): array
    {
        $db = DbConnection::getInstance();
        $sql = "SELECT UserID, Name,Address,PhoneNumber,Email,Password,eventid FROM User";
        $result = $db->query($sql);
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = new User($row, false);
        }

        return $users;
    }

    public function update($message)
    {

        $notificationFacade = new NotificationFacade($this);
        $notificationFacade->sendNotification($message);
    }

    public function sendSMS($message)
    {
          $db = DbConnection::getInstance();
           // Save the notification to the database
           $stmt = $db->prepare("INSERT INTO notification (UserID, Message) VALUES (?, ?)");

           if (!$stmt) {
               throw new Exception("SQL prepare failed: " . $db->error);
           }
           
   
           $stmt->bind_param("is", $this->UserID, $message);
   
           if (!$stmt->execute()) {
               throw new Exception("Execution failed: " . $stmt->error);
           }
   
           $stmt->close();
        // echo "Sending SMS to {$this->PhoneNumber}: $message\n<br/>";
    }

    public function sendEmail($message)
    
    {
        $db = DbConnection::getInstance();
        // Save the notification to the database
        $stmt = $db->prepare("INSERT INTO notification (UserID, Message) VALUES (?, ?)");

        if (!$stmt) {
            throw new Exception("SQL prepare failed: " . $db->error);
        }
        

        $stmt->bind_param("is", $this->UserID, $message);

        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }

        $stmt->close();
        // echo "Sending Email to {$this->Email}: $message\n<br/>";
    }

    public function sendWhatsApp($message)
    {
       $db = DbConnection::getInstance();
           // Save the notification to the database
           $stmt = $db->prepare("INSERT INTO notification (UserID, Message) VALUES (?, ?)");

           if (!$stmt) {
               throw new Exception("SQL prepare failed: " . $db->error);
           }
           
   
           $stmt->bind_param("is", $this->UserID, $message);
   
           if (!$stmt->execute()) {
               throw new Exception("Execution failed: " . $stmt->error);
           }
   
           $stmt->close();  // echo "Sending WhatsApp message to {$this->PhoneNumber}: $message\n";
    }

    public function get_by_id($UserID): ?User
    {
        $db = DbConnection::getInstance();
        $sql = "SELECT * FROM User WHERE UserID = $UserID";
        $result = $db->query($sql);

        if ($row = $result->fetch_assoc()) {
            return new User($row,false);
        } else {
            return null;
        }
    }

    public function registerevent(Event $event ){
        $event->addObserver($this);

        $db = DbConnection::getInstance();
        $stmt = $db->prepare("UPDATE user SET eventid = ? WHERE UserID = ?");

        if (!$stmt) {
            throw new Exception("SQL prepare failed: " . $db->error);
        }

        $stmt->bind_param("ii", $event->EventID, $this->UserID);

        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }

        $stmt->close();

        echo "User {$this->UserID} registered for the event.\n<br/>";
        
    }

    public function __toString()
    {
        $str = '<pre>';
        foreach ($this as $key => $value) {
            $str .= "$key: $value<br/>";
        }
        return $str . '</pre>';
    }



    public static function getUserRoleByEmail($email): ?array
{ 
    $db = DbConnection::getInstance();

    // Query to check if the email exists in the User table
    $stmt = $db->prepare("SELECT UserID FROM User WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $userID = $row['UserID'];

        // Check if the user is a Manager
        $stmt = $db->prepare("SELECT * FROM manger WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ['role' => 'Manager', 'details' => $result->fetch_assoc()];
        }

        // Check if the user is a Beneficiary
        $stmt = $db->prepare("SELECT * FROM Beneficiary WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ['role' => 'Beneficiary', 'details' => $result->fetch_assoc()];
        }

        // Check if the user is a Volunteer
        $stmt = $db->prepare("SELECT * FROM Volunteer WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ['role' => 'Volunteer', 'details' => $result->fetch_assoc()];
        }
        $stmt = $db->prepare("SELECT * FROM doner WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ['role' => 'Doner', 'details' => $result->fetch_assoc()];
        }

        // If no role is found
        return ['role' => 'None', 'details' => null];
    }

    // If email does not exist in the User table
    return null;
}

}

// $user = new User(['UserID' => 1, 'PhoneNumber' => '123456789', 'Email' => 'example@example.com']);
// $user->update("This is a test message");

?>
