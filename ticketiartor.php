<?php

interface IteratorInterface
{
    public function hasNext(): bool;
    public function next();
}

class EventNameIterator implements IteratorInterface


{
    private array $tickets;
    private string $eventName;
    private int $position = 0;

    public function __construct(array $tickets, string $eventName)
    {
        $this->tickets = $tickets;
        $this->eventName = $eventName;
    }

    public function hasNext(): bool
    {
        while ($this->position < count($this->tickets)) {
            if ($this->tickets[$this->position]['EventName'] === $this->eventName) {
                return true;
            }
            $this->position++;
        }
        return false;
    }

    public function next()
    {
        if ($this->hasNext()) {
            return $this->tickets[$this->position++];
        }
        return null;
    }
}




// Iterator to filter tickets by EventID



class EventIDIterator implements IteratorInterface
{
    private array $tickets;
    private int $eventID;
    private int $position = 0;

    public function __construct(array $tickets, int $eventID)
    {
        $this->tickets = $tickets;
        $this->eventID = $eventID;
    }

    public function hasNext(): bool
    {
        while ($this->position < count($this->tickets)) {
            if ($this->tickets[$this->position]['EventID'] === $this->eventID) {
                return true;
            }
            $this->position++;
        }
        return false;
    }

    public function next()
    {
        if ($this->hasNext()) {
            return $this->tickets[$this->position++];
        }
        return null;
    }
}

// Iterator to filter tickets by EventDate
class EventDateIterator implements IteratorInterface
{
    private array $tickets;
    private string $eventDate;
    private int $position = 0;

    public function __construct(array $tickets, string $eventDate)
    {
        $this->tickets = $tickets;
        $this->eventDate = $eventDate;
    }

    public function hasNext(): bool
    {
        while ($this->position < count($this->tickets)) {
            if ($this->tickets[$this->position]['EventDate'] === $this->eventDate) {
                return true;
            }
            $this->position++;
        }
        return false;
    }

    public function next()
    {
        if ($this->hasNext()) {
            return $this->tickets[$this->position++];
        }
        return null;
    }
}



?>