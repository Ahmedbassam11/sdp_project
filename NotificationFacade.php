<?php

class NotificationFacade
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function sendNotification($message)
    {
        $this->sendSMS($message);
        $this->sendEmail($message);
        $this->sendWhatsApp($message);
    }

    private function sendSMS($message)
    {
        $db = DbConnection::getInstance();
        $stmt = $db->prepare("INSERT INTO notification (UserID, Message) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("SQL prepare failed: " . $db->error);
        }
        $stmt->bind_param("is", $this->user->UserID, $message);
        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }
        $stmt->close();
        // echo "Sending SMS to {$this->user->PhoneNumber}: $message\n<br/>";
    }

    private function sendEmail($message)
    {
        // echo "Sending Email to {$this->user->Email}: $message\n<br/>";
    }

    private function sendWhatsApp($message)
    {
        // echo "Sending WhatsApp message to {$this->user->PhoneNumber}: $message\n";
    }
}
