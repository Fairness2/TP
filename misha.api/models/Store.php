<?php

use Phalcon\Mvc\Model;

class Store extends Model
{
    public $id;

    public $name;
    
    public $owner_id;
     
    public $del;
    
    // Название таблицы
    public function getSource()
    {
        return "store";
    }
    
    public function initialize()
    {
        // Store.owner_id (много) <-> (один) Owner.id
        $this->belongsTo("owner_id", "Owner", "id");
        
        // Store.id (один) <-> (много) Transportation.store_id
        $this->hasMany("id", "Transportation", "store_id");
    }

}
