<?php

use Phalcon\Mvc\Model;

class Owner extends Model
{
    public $id;

    public $name;
     
    public $del;
    
    // Название таблицы
    public function getSource()
    {
        return "owner";
    }
    
    public function initialize()
    {
        // Owner.id (один) <-> (много) Store.owner_id
        $this->hasMany("id", "Store", "owner_id");
        
        // Owner.id (один) <-> (много) Car.owner_id
        $this->hasMany("id", "Car", "owner_id");
    }
}
