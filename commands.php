<?php

require_once "donations.php";
require_once "donate.php";
require_once "donationdetails.php";


interface Command {
    public function execute();
}

// Concrete command to add a donation
class AddDonationCommand implements Command {
    private $donation;
    private $donate;
    private $donationDetails;

    public function __construct($donation,$donate,$donationDetails) {
        $this->donation = $donation;
        $this->donate = $donate;
        $this->donationDetails = $donationDetails;
    }

    public function execute() {
        $this->donation->add();
        $this->donate->add();
        $this->donationDetails->Donation=$this->donation;
        $this->donationDetails->Donate=$this->donate;
        $this->donationDetails->add();
        

    }
}

// Concrete command to delete a donation
class DeleteDonationCommand implements Command {
    private $donation;
    private $donate;
    private $donationDetails;

    public function __construct($donation,$donate,$donationDetails) {
        $this->donation = $donation;
        $this->donate = $donate;
        $this->donationDetails = $donationDetails;
    }

    public function execute() {
        $this->donation->delete();
        $this->donate->delete();
        $this->donationDetails->delete();
    }
}

// Concrete command to edit a donation
class EditDonationCommand implements Command {
    private $donation;
    private $donate;
    private $donationdetails;

    public function __construct($donation,$donate,$donationdetails) {
        $this->donation = $donation;
        $this->donate = $donate;
        $this->donationdetails = $donationdetails;
    }

    public function execute() {
        $this->donation->update();
        $this->donate->update();
        $this->donationdetails->update();
    }
}



class CommandInvoker {
    private $commands = [];

    public function addCommand(Command $command) {
        $this->commands[] = $command;
    }

    public function executeCommands() {
        foreach ($this->commands as $command) {
            $command->execute();
        }
        // Clear the commands after execution
        $this->commands = [];
    }
}








?>