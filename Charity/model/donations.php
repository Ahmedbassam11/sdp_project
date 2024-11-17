<?php
    require "donation_method.php";
    require "billable.php";
    interface Billable {
        public function calcPrice(): float;
    }
  
    class donations implements Billable{
        public $DonationName;	
        public $DonationID	;
        public $DonationDescription;	
        public $DonationAmout	;
        public donation_method $donation_type;


        public function __construct($properties)
        {
            foreach ($properties as $prop => $value) {
                $this->{$prop} = $value;
            }
            
        }

        public function set_donation_type( $donation_type)
        {
            $this->donation_type = $donation_type;
        }

        public function acknowledgeDonor(): string {
            return "Thank you for your generous donation of $" . $this->DonationAmout;
        }
        
        public function isHighValueDonation(int $threshold): bool {
            return $this->DonationAmout >= $threshold;
        }

        public function scheduleReminder(): string {
            $daysToAdd = rand(1, 30);
            
            $reminderDate = (new DateTime())->modify("+{$daysToAdd} days");
            
            $formattedDate = $reminderDate->format('Y-m-d');
            
            return "Reminder scheduled for {$formattedDate} to follow up on donation: {$this->DonationName}.";
        }
        public function add() {
            $con = mysqli_connect("localhost", "root", "", "Charity");
            if (!$con) {
                die('Could not connect: ' . mysqli_connect_error());
            }
    
            $sql = "INSERT INTO donations (DonationID, DonationName, DonationDescription ,DonationAmout ) VALUES (?, ?, ?,?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("issd", $this->DonationID, $this->DonationName, $this-> DonationDescription,$this->DonationAmout);
    
            if ($stmt->execute()) {
                echo "Donation added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
    
            $stmt->close();
            $con->close();
        }
       
         public function get_by_id($DonationID){
            $con = mysqli_connect("localhost","root","","Charity");
            if(!$con){
                die('Could not connect: '.mysqli_connect_error());
    
            }
            $sql = "SELECT * FROM donations WHERE DonationID = $DonationID";
            $donationsData = $con->query($sql);
            if($row = mysqli_fetch_array($donationsData)){
                $this->DonationID=$row["DonationID"];
                $this->DonationName=$row["DonationName"];
                $this->DonationDescription=$row["DonationDescription"];
                $this->DonationAmout=$row["DonationAmout"];
                
    
                return new donations($row);
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

        public function calcPrice(): float {

            return $this->DonationAmout;
        }
    
     
        


    }

  
?>