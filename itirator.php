<?php

interface VolunteerIterator {
    public function hasNext();
    public function next();
}


class AvailableVolunteerIterator implements VolunteerIterator {
    private $volunteers;

    private $index = 0;

    public function __construct($volunteers) {
        $this->volunteers = array_filter($volunteers, function($volunteer) {
            return get_class($volunteer->State) === 'State\AvailableState';
        });
    }

    
    public function hasNext() {
        return $this->index < count($this->volunteers);
    }

    public function next() {
        if ($this->hasNext()) {
            return $this->volunteers[$this->index++];
        }
        return null;
    }
}

class AssignedVolunteerIterator implements VolunteerIterator {
    private $volunteers;
    private $index = 0;

    public function __construct($volunteers) {
        $this->volunteers = array_filter($volunteers, function($volunteer) {
            return get_class($volunteer->State) === 'State\AssignedState';
        });
    }

    public function hasNext() {
        return $this->index < count($this->volunteers);
    }

    public function next() {
        if ($this->hasNext()) {
            return $this->volunteers[$this->index++];
        }
        return null;
    }
}


class InProgressVolunteerIterator implements VolunteerIterator {
    private $volunteers;
    private $index = 0;

    public function __construct($volunteers) {
        $this->volunteers = array_filter($volunteers, function($volunteer) {
            return get_class($volunteer->State) === 'State\InProgressState';
        });
    }

    public function hasNext() {
        return $this->index < count($this->volunteers);
    }

    public function next() {
        if ($this->hasNext()) {
            return $this->volunteers[$this->index++];
        }
        return null;
    }
}



class CompletedVolunteerIterator implements VolunteerIterator {
    private $volunteers;
    private $index = 0;

    public function __construct($volunteers) {
        $this->volunteers = array_filter($volunteers, function($volunteer) {
            return get_class($volunteer->State) === 'State\CompletedState';
        });
    }

    public function hasNext() {
        return $this->index < count($this->volunteers);
    }

    public function next() {
        if ($this->hasNext()) {
            return $this->volunteers[$this->index++];
        }
        return null;
    }
}






?>

















