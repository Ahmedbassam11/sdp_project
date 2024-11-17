<?php
ob_start();
require "notification_system.php";
require "DB.php";
ob_clean();

class User implements NotificationObserver
{
    public $UserID;
    public $Name;
    public $Address;
    public $PhoneNumber;
    public $Email;
    public $Password;
    public Subject $ref;

    public function __construct($properties, $insert = false, Subject $x = null)
    {
        foreach ($properties as $prop => $value) {
            $this->{$prop} = $value;
        }

        if ($x != null) {
            $ref = $x;
            $x->addObserver($this);
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
        $stmt = $db->prepare("INSERT INTO User (UserID, Name, Address, PhoneNumber, Email, Password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $this->UserID, $this->Name, $this->Address, $this->PhoneNumber, $this->Email, $this->Password);

        if ($stmt->execute()) {
            echo "User inserted successfully.";
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
        $sql = "SELECT UserID, Name FROM User";
        $result = $db->query($sql);
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = new User($row, false);
        }

        return $users;
    }

    public function update($message, $type)
    {
        switch ($type) {
            case 'SMS':
                $this->sendSMS($message);
                break;
            case 'Email':
                $this->sendEmail($message);
                break;
            case 'WhatsApp':
                $this->sendWhatsApp($message);
                break;
        }
    }

    private function sendSMS($message)
    {
        echo "Sending SMS to {$this->PhoneNumber}: $message\n<br/>";
    }

    private function sendEmail($message)
    {
        echo "Sending Email to {$this->Email}: $message\n<br/>";
    }

    private function sendWhatsApp($message)
    {
        echo "Sending WhatsApp message to {$this->PhoneNumber}: $message\n";
    }

    public function get_by_id($UserID): ?User
    {
        $db = DbConnection::getInstance();
        $sql = "SELECT * FROM User WHERE UserID = $UserID";
        $result = $db->query($sql);

        if ($row = $result->fetch_assoc()) {
            return new User($row, false);
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
