<?php

use Phalcon\Mvc\Model;

class Cluster extends Model
{
    protected $id;
    protected $name;
    protected $dele;
    protected $size;

    public function initialize()
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
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDele($i)
    {
        $this->dele = $this->dele + 1;
    }

     public function getDele()
    {
        return $this->dele;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }



}


?>