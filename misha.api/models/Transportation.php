<?php

use Phalcon\Mvc\Model;

class Transportation extends Model
{
    public $id;

    public $car_id;

    public $organization_id;

    public $store_id;

    public $date;
     
    public $del;
    
    // Название таблицы
    public function getSource()
    {
        return "transportation";
    }
    
    public function initialize()
    {
        // Transportation.car_id (много) <-> (один) Car.id
        $this->belongsTo("car_id", "Car", "id");
        
        // Transportation.organization_id (много) <-> (один) Organization.id
        $this->belongsTo("organization_id", "Organization", "id");
        
        // Transportation.store_id (много) <-> (один) Store.id
        $this->belongsTo("store_id", "Store", "id");
        
        // Transportation.id (один) <-> (много) Shipment.Transportation_id
        $this->hasMany("id", "Shipment", "Transportation_id");
    }
    
    public function lastDay()        
    {   
        $cd = date("d") - 1;
        if ($cd < 10) $cd = "0" . $cd;
        
        $query = $this->modelsManager->createQuery("SELECT * FROM Transportation WHERE date LIKE '%-{$cd}' AND del = 0");
        
        $transportations  = $query->execute();
        
        return $transportations;
    }
    
    public function lastWeek()        
    {   
        $cy = date("Y");
        $cm = date("m");
        
        $cwd = date("w");      // 5 + 1 = 6
        $ld = date("d") - $cwd;     // 30 - 6 = 24
        $fd = date("d") - $cwd - 6; // 18
        
        if ($ld < 10) $ld = "0" . $ld;
        if ($fd < 10) $fd = "0" . $fd;
        
        $query = $this->modelsManager->createQuery("SELECT * FROM Transportation WHERE date BETWEEN '{$cy}-{$cm}-{$fd}' AND '{$cy}-{$cm}-{$ld}' AND del = 0");
        
        $transportations  = $query->execute();
        
        return $transportations;
    }
    
    public function lastMonth()        
    {   
        $cm = date("m") - 1;
        if ($cm < 10) $cm = "0" . $cm;
        
        $query = $this->modelsManager->createQuery("SELECT * FROM Transportation WHERE date LIKE '%-{$cm}-%' AND del = 0 ");
        
        $transportations  = $query->execute();
        
        return $transportations;
    }
    
    public function lastYear()        
    {   
        $cy = date("Y") - 1;
        
        $query = $this->modelsManager->createQuery("SELECT * FROM Transportation WHERE date LIKE '{$cy}-%' AND del = 0");
        
        $transportations  = $query->execute();
        
        return $transportations;
    }

}
