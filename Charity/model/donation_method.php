<?php
interface  donation_method
{
    public function DonationStrategy();

}


class online implements donation_method {
    public function DonationStrategy(){
        echo 'successful online donation';
    } 
} 
    class inkind implements donation_method {
        public function DonationStrategy(){
            echo "successful inkind donation";
        }  
    }
        class check implements donation_method {
            public function DonationStrategy(){
                echo "successful check donation";
            }  
}
?>