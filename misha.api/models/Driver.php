<?php

use Phalcon\Mvc\Model;

class Driver extends Model
{
    public $id;

    public $name;
    
    public $experience;
    
    public $salary;
     
    public $del;
    
    // Название таблицы
    public function getSource()
    {
        return "driver";
    }
    
    public function initialize()
    {
        // Driver.id (один) <-> (много) Car.driver_id
        $this->hasMany("id", "Car", "driver_id");
    }
    
    public function mostExp()
    {   
        $query = $this->modelsManager->createQuery("SELECT * FROM Driver WHERE Driver.experience IN (SELECT Min(Driver.experience) FROM Driver WHERE del = 0)");
        
        $cars  = $query->execute();
        
        return $cars;
    }
    
    public function leastExp()
    {
        $query = $this->modelsManager->createQuery("SELECT * FROM Driver WHERE Driver.experience IN (SELECT Max(Driver.experience) FROM Driver WHERE del = 0)");
        
        $cars  = $query->execute();
        
        return $cars;
    }
    
    public function mostSalary()
    {   
        $query = $this->modelsManager->createQuery("SELECT * FROM Driver WHERE Driver.salary IN (SELECT Max(Driver.salary) FROM Driver WHERE del = 0)");
        
        $cars  = $query->execute();
        
        return $cars;
    }
    
    public function leastSalary()
    {
        $query = $this->modelsManager->createQuery("SELECT * FROM Driver WHERE Driver.salary IN (SELECT Min(Driver.salary) FROM Driver WHERE del = 0)");
        
        $cars  = $query->execute();
        
        return $cars;
    }

}
