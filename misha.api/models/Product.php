<?php

use Phalcon\Mvc\Model;

class Product extends Model
{
    public $id;

    public $name;
    
    public $weight;
    
    public $type_id;
     
    public $del;
    
    // Название таблицы
    public function getSource()
    {
        return "product";
    }
    
    public function initialize()
    {
        // Product.type_id (много) <-> (один) ProductType.id
        $this->belongsTo("type_id", "ProductType", "id");
        
        // Product.id (один) <-> (много) Shipment.product_id
        $this->hasMany("id", "Shipment", "product_id");
    }
    
    public function mostWeight()
    {   
        $query = $this->modelsManager->createQuery("SELECT * FROM Product WHERE Product.weight IN (SELECT Max(Product.weight) FROM Product WHERE del = 0)");
        
        $cars  = $query->execute();
        
        return $cars;
    }
    
    public function leastWeight()
    {
        $query = $this->modelsManager->createQuery("SELECT * FROM Product WHERE Product.weight IN (SELECT Min(Product.weight) FROM Product WHERE del = 0)");
        
        $cars  = $query->execute();
        
        return $cars;
    }

}
