<?php

use Phalcon\Mvc\Collection;

class TypeOfGalaxy extends Collection
{
    public $_id;
    public $name;
    public $description;

    /*public function initialize()
    {
        $this->hasMany("id", "Galaxy", "type_id");
    }*/

    public function getSource()
    {
        return "type_of_galaxy";
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