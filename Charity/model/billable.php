<?php
abstract class TaxDecorator implements Billable
{

    protected Billable $donation;
    abstract public function calcPrice(): float;
    public function __construct(Billable $donation) {
        $this->donation = $donation;
    }

}
class VATDecorator extends TaxDecorator {
    


    public function calcPrice(): float {
        $basePrice = $this->donation->calcPrice();
        $vat = $basePrice * 0.15;  
        return $basePrice + $vat;
    }
}

class OnlineDecorator extends TaxDecorator {
    

  
    public function calcPrice(): float {
        $basePrice = $this->donation->calcPrice();
        $onlineFee = $basePrice * 0.05; 
        return $basePrice + $onlineFee;
    }
}
?>