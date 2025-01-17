<?php
ob_start();
require_once("Event.php");

ob_clean();


interface TicketInterface
{
    public function createTicket($EventID, $TicketPrice);
    public function deleteTicket($TicketID);
    public function updateTicket($TicketID, $newPrice);
}


class Ticket
{
    public $TicketID;
    public $EventID;
    public $EventName;
    public $EventDate;
    public $TicketPrice;
    
    private Event $eventOb;

    public function __construct(Event $eventOb)
    {
        $this->eventOb = $eventOb;
    }


    
    // Add a new ticket to an event
    public function addTicket($EventID, $TicketPrice)
    {
        // Get event details using EventID 
        $db = DbConnection::getInstance();



        $event = $this->eventOb->getEventById($EventID); // Assuming getEventDetails() method exists

        if (!$event) {
            throw new Exception("Event not found.");
        }

        // Insert ticket into the database
        $stmt = $db->prepare("INSERT INTO tickets (EventID, EventName, EventDate, TicketPrice) 
                     VALUES (?, ?, ?, ?)");
       $stmt->bind_param("issd", $EventID,  $event->EventName, $event->EventDate, $TicketPrice);


       
        return $stmt->execute();
    }

    // Remove a ticket from an event by TicketID
    public function removeTicket($TicketID)
    {
        $db = DbConnection::getInstance();

        $stmt = $db->prepare( "DELETE FROM tickets WHERE TicketID = :TicketID");
       
        $stmt->bind_param("i", $TicketID);
        return $stmt->execute();
    }

    // Edit ticket details for an event
    public function editTicket($TicketID, $newPrice)
    {
        $db = DbConnection::getInstance();
        $stmt = $db->prepare("UPDATE tickets SET TicketPrice = ? WHERE TicketID = ?");

        
        $stmt->bind_Param("di",$newPrice, $TicketID);
       
        return $stmt->execute();
    }
}
class TicketAdapter implements TicketInterface
{
    private Ticket $legacyTicket;

    public function __construct(Ticket $legacyTicket)
    {
        $this->legacyTicket = $legacyTicket;
    }

    // Adapts the addTicket method
    public function createTicket($EventID, $TicketPrice)
    {
        return $this->legacyTicket->addTicket($EventID, $TicketPrice);
    }

    // Adapts the removeTicket method
    public function deleteTicket($TicketID)
    {
        return $this->legacyTicket->removeTicket($TicketID);
    }

    // Adapts the editTicket method
    public function updateTicket($TicketID, $newPrice)
    {
        return $this->legacyTicket->editTicket($TicketID, $newPrice);
    }
}

?>
<?php

//$eventobj = new Event();
//$ticket = new Ticket($eventobj);
//$event=$eventobj->getEventById(2);
//$ID=$event['EventID'];
//$ticket->editTicket(4,50.6);




// $eventobj = new Event(); // Assuming Event class is properly defined
// $legacyTicket = new Ticket($eventobj); // Create an instance of the legacy Ticket class

// // Use the Adapter to interact with the Ticket system
// $ticketAdapter = new TicketAdapter($legacyTicket);

// // Example usage of the adapter:
// try {
//     // Add a new ticket to event 2 with price 100.0
//     $ticketAdapter->createTicket(2, 100.0);
    
//     // Edit ticket with ID 4 and change price
//     $ticketAdapter->updateTicket(4, 50.6);

//     // Remove ticket with ID 4
    

//     echo "Ticket operations completed successfully.";

// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }


?>