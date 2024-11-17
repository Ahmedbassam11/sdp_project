<?php
interface NotificationObserver {
     
    public function update( $message, $type);
}


interface Subject {
    
    public function addObserver(NotificationObserver $observer);

    
    public function removeObserver(NotificationObserver $observer);

     
    public function notifyObservers($message, $type);
}




class notification_system implements Subject {
    private $observers = [];


   
    public function addObserver(NotificationObserver $observer) {
        $this->observers[] = $observer;
    }


   
    public function removeObserver(NotificationObserver $observer) {
        $index = array_search($observer, $this->observers, true);
        if ($index !== false) {
            unset($this->observers[$index]);
        }
    }

    
    public function notifyObservers($message, $type) {
        foreach ($this->observers as $observer) {
            
            $observer->update( $message, $type);
        }
    }
    public function createNotification($message, $type) {
        $this->notifyObservers($message, $type);
    }
}






?>