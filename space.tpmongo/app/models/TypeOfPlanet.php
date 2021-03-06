<?php
use Phalcon\Mvc\Collection;

class TypeOfPlanet extends Collection
{
    public $_id;
    public $name;
    public $description;

    /*public function initialize()
    {
        $this->hasMany("id", "Planet", "type_id");
    }*/

    public function getSource()
    {
        return "type_of_planet";
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }
}


?>