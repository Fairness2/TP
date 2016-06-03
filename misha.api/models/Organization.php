<?php

use Phalcon\Mvc\Model;

class Organization extends Model
{
    public $id;

    public $name;
    
    public $address;
     
    public $del;
    
    // Название таблицы
    public function getSource()
    {
        return "organization";
    }
    
    public function initialize()
    {
        // Organization.id (один) <-> (много) Transportation.organization_id
        $this->hasMany("id", "Transportation", "organization_id");
    }

}
