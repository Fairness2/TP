<?php

use Phalcon\Mvc\Model;

class Dealer extends Model
{
    public $id;

    public $name;
    
    public $del;
    
    // Название таблицы
    public function getSource()
    {
        return "dealer";
    }
    
    public function initialize()
    {
        // Dealer.id (один) <-> (много) Car.dealer_id
        $this->hasMany("id", "Car", "dealer_id");
    }

}
