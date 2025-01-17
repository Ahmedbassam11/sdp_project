<?php

namespace State;

interface VolunteerState {
    public function assignTask($task);
    public function startTask();
    public function completeTask();
}

class AvailableState implements VolunteerState {
    private $volunteer;

    public function __construct($volunteer) {
        $this->volunteer = $volunteer;
    }

    public function assignTask($task) {
        $this->volunteer->TaskOb = $task;
    
        $this->volunteer->setState(new AssignedState($this->volunteer));
        echo "Task assigned to volunteer.";
    }

    public function startTask() {
        echo "Cannot start task. No task assigned yet.";
    }

    public function completeTask() {
        echo "Cannot complete task. No task assigned yet.";
    }
}

class AssignedState implements VolunteerState {
    private $volunteer;

    public function __construct($volunteer) {
        $this->volunteer = $volunteer;
    }

    public function assignTask($task) {
        echo "Cannot assign a new task. Task is already assigned.";
    }

    public function startTask() {
        $this->volunteer->setState(new InProgressState($this->volunteer));
        echo "Task started.";
    }

    public function completeTask() {
        echo "Cannot complete task. Task is not in progress.";
    }
}

class InProgressState implements VolunteerState {
    private $volunteer;

    public function __construct($volunteer) {
        $this->volunteer = $volunteer;
    }

    public function assignTask($task) {
        echo "Cannot assign a new task. Task is already in progress.";
    }

    public function startTask() {
        echo "Task is already in progress.";
    }

    public function completeTask() {
        $this->volunteer->setState(new CompletedState($this->volunteer));
        echo "Task completed.";
    }
}

class CompletedState implements VolunteerState {
    private $volunteer;

    public function __construct($volunteer) {
        $this->volunteer = $volunteer;
    }

    public function assignTask($task) {
        echo "Cannot assign a new task. Task is already completed.";
    }

    public function startTask() {
        echo "Cannot start task. Task is already completed.";
    }

    public function completeTask() {
        echo "Task is already completed.";
    }
}

?>

