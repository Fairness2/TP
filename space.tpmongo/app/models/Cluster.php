<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Collection;

class Cluster extends Collection
{
    public $_id;
    public $name;
    public $dele;
    public $size;

    /*public function initialize()
    {
        $this->hasMany("id", "Galaxy", "cluster_id");
        
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
        return "cluster";
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

    public function Deleted($i)
    {
        $this->dele = $this->dele + 1;
    }

     public function getDele()
    {
        return $this->dele;
    }

    public function setSize($size)
    {
        $size = htmlspecialchars(strip_tags(stripslashes(trim($size))));
        if ($size < 0) {
            throw new \InvalidArgumentException('Размер не может быть отрицательным');
        }
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }



}


?>