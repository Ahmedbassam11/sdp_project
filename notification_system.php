<?php
interface NotificationObserver {
     
    public function update($message);
}



interface Subject {
    
    public function addObserver(NotificationObserver $observer);

    
    public function removeObserver(NotificationObserver $observer);

     
    public function notifyObservers();
}




class notification_system implements Subject {
    private $observers = [];
    public $eventname;
    public $message;



   
    public function addObserver(NotificationObserver $observer) {
        $this->observers[] = $observer;
    }


   
    public function removeObserver(NotificationObserver $observer) {
        $index = array_search($observer, $this->observers, true);
        if ($index !== false) {
            unset($this->observers[$index]);
        }
    }

    
    public function notifyObservers() {
        
        

    foreach ($this->observers as $observer) {
        // Notify the user (observer)
        $observer->update($this->message);

     
    }
    }
    public function setevent($eventname) {
        
        $this->eventname=$eventname;
        $this->message= $eventname;
        $this->notifyObservers();
            
        
        

    }

}



?>