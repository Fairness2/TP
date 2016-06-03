<?php

use Phalcon\Mvc\Model;

class Shipment extends Model
{
    public $id;

    public $product_id;
    
    public $transportation_id;
    
    public $amount;
     
    public $del;
    
    // Название таблицы
    public function getSource()
    {
        return "shipment";
    }
    
        public function initialize()
    {
        // Shipment.product_id (много) <-> (один) Product.id
        $this->belongsTo("product_id", "Product", "id");
            
        // Shipment.transportation_id (много) <-> (один) Transportation.id
        $this->belongsTo("transportation_id", "Transportation", "id");
    }

}
