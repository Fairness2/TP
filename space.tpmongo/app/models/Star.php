<?php

use Phalcon\Mvc\Collection;

class Star extends Collection
{
    public $_id;
    public $name;
    public $dele;
    public $weight;
    public $age;
    public $solar_system_id;
    public $type_id;

    /*public function initialize()
    {
        $this->belongsTo("solar_system_id", "SolarSystem", "id");
        $this->belongsTo("type_id", "TypeOfStar", "id");
        
        // Пропуск только при вставке
        $this->skipAttributesOnCreate(
            array(
                'dele',
                'id'
            )
        );

        // Пропуск только при обновлении
        $this->skipAttributesOnUpdate(
            array(
                'id'
            )
        );
    }*/

    public function getSource()
    {
        return "star";
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setName($name)
    {
        $name = htmlspecialchars(strip_tags(stripslashes(trim($name))));
        // Имя слишком короткое?
        if (strlen($name) < 1) {
            throw new \InvalidArgumentException('Имя слишком короткое');
        }
        elseif (strlen($name) > 100) {
        	throw new \InvalidArgumentException('Имя слишком длинное');
        }
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDele()
    {
        return $this->dele;
    }

    public function Deleted($i)
    {
        $this->dele = $this->dele + 1;
    }

    public function setWeight($weight)
    {
        $weight = htmlspecialchars(strip_tags(stripslashes(trim($weight))));
        if ($weight < 0) {
            throw new \InvalidArgumentException('Масса не может быть отрицательной');
        }
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setType($type)
    {
        $this->type_id = $type;
    }

    public function getType()
    {
        return $this->type_id;
    }

    public function setSolar_System($solar_system)
    {
        $this->solar_system_id = $solar_system;
    }

    public function getSolar_System_id()
    {
        return $this->solar_system_id;
    }

    public function setAge($age)
    {
        $age = htmlspecialchars(strip_tags(stripslashes(trim($age))));
        if ($age < 0) {
            throw new \InvalidArgumentException('Возраст не может быть отрицательным');
        }
        $this->age = $age;
    }

    public function getAge()
    {
        return $this->age;
    }
}


?>