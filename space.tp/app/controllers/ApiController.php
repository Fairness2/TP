<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use Phalcon\Mvc\Micro;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ApiController extends Controller
{
    public function typeblackholeAction() //типы
    {
        $data = array();
        foreach (TypeOfBlackHole::find() as $type) {
            $data[] = array(
                'id'   => $type->id,
                'name' => $type->name,
                'description' => $type->description,
            );
        }
        //echo json_encode($data, JSON_UNESCAPED_UNICODE);
        $response = new Response();
        $response->setJsonContent(
            array(
                'data'   => $data
                )
            , JSON_UNESCAPED_UNICODE
        );
        return $response;
        $this->view->disable();
    }

    public function typegalaxyAction()
    {
        $data = array();
        foreach (TypeOfGalaxy::find() as $type) {
            $data[] = array(
                'id'   => $type->id,
                'name' => $type->name,
                'description' => $type->description,
            );
        }
        $response = new Response();
        $response->setJsonContent(
            array(
                'data'   => $data
                )
            , JSON_UNESCAPED_UNICODE
        );
        return $response;
        $this->view->disable();
    }

    public function typeplanetAction()
    {
        $data = array();
        foreach (TypeOfPlanet::find() as $type) {
            $data[] = array(
                'id'   => $type->id,
                'name' => $type->name,
                'description' => $type->description,
            );
        }
        $response = new Response();
        $response->setJsonContent(
            array(
                'data'   => $data
                )
            , JSON_UNESCAPED_UNICODE
        );
        return $response;
        $this->view->disable();
    }

    public function typestarAction()
    {
        $data = array();
        foreach (TypeOfStar::find() as $type) {
            $data[] = array(
                'id'   => $type->id,
                'name' => $type->name,
                'description' => $type->description,
            );
        }
        $response = new Response();
        $response->setJsonContent(
            array(
                'data'   => $data
                )
            , JSON_UNESCAPED_UNICODE
        );
        return $response;
        $this->view->disable();
    }

    public function blackholeAction() //постраничный вывод
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        
        $paginator = new Paginator(
            array(
                "data"  => BlackHole::find(
                        array(
                            "dele = :dele:",
                            "bind" => array(
                                "dele" => "0")
                        )
                    ),
                "limit" => 15,
                "page"  => 1
            )
        );
        $validation = new Validation();
        $validation->add('page', new Between(array(
           'minimum' => 1,
           'maximum' => $paginator->getPaginate()->total_pages,
           'message' => '1'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $paginator->setCurrentPage($this->request->getQuery("page"));
            $page = $paginator->getPaginate();
            if ($page->total_items)
            {

                $data = array();
                foreach ($page->items as $blackhole) {
                    $data[] = array(
                        'id'   => $blackhole->id,
                        'name' => $blackhole->name,
                        'weight' => $blackhole->weight,
                        'type' => $blackhole->TypeOfBlackHole->name,
                        'age' => $blackhole->age,
                        'galaxy' => $blackhole->Galaxy->name
                    );
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'count_pages' => $paginator->getPaginate()->total_pages,
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
            else
            {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                    )
                , JSON_UNESCAPED_UNICODE
            );
        }
        $this->view->disable();
        return $response;
    }

    public function clusterAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        
        $paginator = new Paginator(
            array(
                "data"  => Cluster::find(
                        array(
                            "dele = :dele:",
                            "bind" => array(
                                "dele" => "0")
                        )
                    ),
                "limit" => 15,
                "page"  => 1
            )
        );
        $validation = new Validation();
        $validation->add('page', new Between(array(
           'minimum' => 1,
           'maximum' => $paginator->getPaginate()->total_pages,
           'message' => '1'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $paginator->setCurrentPage($this->request->getQuery("page"));
            $page = $paginator->getPaginate();
            if ($page->total_items)
            {

                $data = array();
                foreach ($page->items as $cluster) {
                    $data[] = array(
                        'id'   => $cluster->id,
                        'name' => $cluster->name,
                        'size' => $cluster->size,
                        
                    );
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'count_pages' => $paginator->getPaginate()->total_pages,
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
            else
            {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                    )
                , JSON_UNESCAPED_UNICODE
            );
        }
        $this->view->disable();
        return $response;
    }

    public function galaxyAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        
        $paginator = new Paginator(
            array(
                "data"  => Galaxy::find(
                        array(
                            "dele = :dele:",
                            "bind" => array(
                                "dele" => "0")
                        )
                    ),
                "limit" => 15,
                "page"  => 1
            )
        );
        $validation = new Validation();
        $validation->add('page', new Between(array(
           'minimum' => 1,
           'maximum' => $paginator->getPaginate()->total_pages,
           'message' => '1'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $paginator->setCurrentPage($this->request->getQuery("page"));
            $page = $paginator->getPaginate();
            if ($page->total_items)
            {

                $data = array();
                foreach ($page->items as $galaxy) {
                    $data[] = array(
                        'id'   => $galaxy->id,
                        'name' => $galaxy->name,
                        'size' => $galaxy->size,
                        'type' => $galaxy->TypeOfGalaxy->name,
                        'cluster' => $galaxy->Cluster->name
                    );
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'count_pages' => $paginator->getPaginate()->total_pages,
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
            else
            {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                    )
                , JSON_UNESCAPED_UNICODE
            );
        }
        $this->view->disable();
        return $response;
    }

    public function planetAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        
        $paginator = new Paginator(
            array(
                "data"  => Planet::find(
                        array(
                            "dele = :dele:",
                            "bind" => array(
                                "dele" => "0")
                        )
                    ),
                "limit" => 15,
                "page"  => 1
            )
        );
        $validation = new Validation();
        $validation->add('page', new Between(array(
           'minimum' => 1,
           'maximum' => $paginator->getPaginate()->total_pages,
           'message' => '1'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $paginator->setCurrentPage($this->request->getQuery("page"));
            $page = $paginator->getPaginate();
            if ($page->total_items)
            {

                $data = array();
                foreach ($page->items as $planet) {
                    $data[] = array(
                        'id'   => $planet->id,
                        'name' => $planet->name,
                        'weight' => $planet->weight,
                        'type' => $planet->TypeOfPlanet->name,
                        'solar_system' => $planet->SolarSystem->name
                    );
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'count_pages' => $paginator->getPaginate()->total_pages,
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
            else
            {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                    )
                , JSON_UNESCAPED_UNICODE
            );
        }
        $this->view->disable();
        return $response;
    }

    public function solarsystemAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        
        $paginator = new Paginator(
            array(
                "data"  => SolarSystem::find(
                        array(
                            "dele = :dele:",
                            "bind" => array(
                                "dele" => "0")
                        )
                    ),
                "limit" => 15,
                "page"  => 1
            )
        );
        $validation = new Validation();
        $validation->add('page', new Between(array(
           'minimum' => 1,
           'maximum' => $paginator->getPaginate()->total_pages,
           'message' => '1'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $paginator->setCurrentPage($this->request->getQuery("page"));
            $page = $paginator->getPaginate();
            if ($page->total_items)
            {

                $data = array();
                foreach ($page->items as $solar_system) {
                    $data[] = array(
                        'id'   => $solar_system->id,
                        'name' => $solar_system->name,
                        'galaxy' => $solar_system->Galaxy->name
                    );
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'count_pages' => $paginator->getPaginate()->total_pages,
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
            else
            {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                    )
                , JSON_UNESCAPED_UNICODE
            );
        }
        $this->view->disable();
        return $response;
    }

    public function StarAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        
        $paginator = new Paginator(
            array(
                "data"  => Star::find(
                        array(
                            "dele = :dele:",
                            "bind" => array(
                                "dele" => "0")
                        )
                    ),
                "limit" => 15,
                "page"  => 1
            )
        );
        $validation = new Validation();
        $validation->add('page', new Between(array(
           'minimum' => 1,
           'maximum' => $paginator->getPaginate()->total_pages,
           'message' => '1'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $paginator->setCurrentPage($this->request->getQuery("page"));
            $page = $paginator->getPaginate();
            if ($page->total_items)
            {

                $data = array();
                foreach ($page->items as $star) {
                    $data[] = array(
                        'id'   => $star->id,
                        'name' => $star->name,
                        'weight' => $star->weight,
                        'age' => $star->age,
                        'type' => $star->TypeOfStar->name,
                        'solar_system' => $star->SolarSystem->name
                    );
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'count_pages' => $paginator->getPaginate()->total_pages,
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
            else
            {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                    )
                , JSON_UNESCAPED_UNICODE
            );
        }
        $this->view->disable();
        return $response;
    }

    public function nameblackholeAction() //поиск по имени
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $black_hole = BlackHole::find(
                array(
                    "name = :name: AND dele = :dele:",
                    "bind" => array(
                        "name" => $this->request->getQuery("name"),
                        "dele" => 0)
                            )
            );            

            // Формируем ответ
            $response = new Response();

            if (count($black_hole) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $data = array();
                foreach ($black_hole as $blackhole) {
                    $data[] = array(
                        'id'   => $blackhole->id,
                        'name' => $blackhole->name,
                        'weight' => $blackhole->weight,
                        'type' => $blackhole->TypeOfBlackHole->name,
                        'age' => $blackhole->age,
                        'galaxy' => $blackhole->Galaxy->name
                    );
                }
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }            
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function nameclusterAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $clusters = Cluster::find(
                array(
                    "name = :name: AND dele = :dele:",
                    "bind" => array(
                        "name" => $this->request->getQuery("name"),
                        "dele" => 0)
                        )
            );            

            // Формируем ответ
            $response = new Response();

            if (count($clusters) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $data = array();
                foreach ($clusters as $cluster) {
                    $data[] = array(
                        'id'   => $cluster->id,
                        'name' => $cluster->name,
                        'size' => $cluster->size,
                        
                    );
                }
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }            
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function namegalaxyAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $database = Galaxy::find(
                array(
                    "name = :name: AND dele = :dele:",
                    "bind" => array(
                        "name" => $this->request->getQuery("name"),
                        "dele" => 0)
                            )
            );            

            // Формируем ответ
            $response = new Response();

            if (count($database) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $data = array();
                foreach ($database as $galaxy) {
                    $data[] = array(
                        'id'   => $galaxy->id,
                        'name' => $galaxy->name,
                        'size' => $galaxy->size,
                        'type' => $galaxy->TypeOfGalaxy->name,
                        'cluster' => $galaxy->Cluster->name
                    );
                }
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }            
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function nameplanetAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $database = Planet::find(
                array(
                    "name = :name: AND dele = :dele:",
                    "bind" => array(
                        "name" => $this->request->getQuery("name"),
                        "dele" => 0)
                            )
            );            

            // Формируем ответ
            $response = new Response();

            if (count($database) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $data = array();
                foreach ($database as $planet) {
                    $data[] = array(
                        'id'   => $planet->id,
                        'name' => $planet->name,
                        'weight' => $planet->weight,
                        'type' => $planet->TypeOfPlanet->name,
                        'solar_system' => $planet->SolarSystem->name
                    );
                }
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }            
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function namesolarsystemAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $database = SolarSystem::find(
                array(
                    "name = :name: AND dele = :dele:",
                    "bind" => array(
                        "name" => $this->request->getQuery("name"),
                        "dele" => 0)
                            )
            );            

            // Формируем ответ
            $response = new Response();

            if (count($database) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $data = array();
                foreach ($database as $solar_system) {
                    $data[] = array(
                        'id'   => $solar_system->id,
                        'name' => $solar_system->name,
                        'galaxy' => $solar_system->Galaxy->name
                    );
                }
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }            
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function namestarAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $database = Star::find(
                array(
                    "name = :name: AND dele = :dele:",
                    "bind" => array(
                        "name" => $this->request->getQuery("name"),
                        "dele" => 0)
                            )
            );            

            // Формируем ответ
            $response = new Response();

            if (count($database) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $data = array();
                foreach ($database as $star) {
                    $data[] = array(
                        'id'   => $star->id,
                        'name' => $star->name,
                        'weight' => $star->weight,
                        'age' => $star->age,
                        'type' => $star->TypeOfStar->name,
                        'solar_system' => $star->SolarSystem->name
                    );
                }
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => $data
                        )
                    , JSON_UNESCAPED_UNICODE
                );
            }            
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function idblackholeAction() //поиск по id
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $black_hole = BlackHole::findFirstById($this->request->getQuery("id"));

               // Формируем ответ
            $response = new Response();
            if (count($black_hole) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => array(
                            'id'   => $black_hole->id,
                            'name' => $black_hole->name,
                            'weight' => $black_hole->weight,
                            'type' => $black_hole->TypeOfBlackHole->name,
                            'age' => $black_hole->age,
                            'galaxy' => $black_hole->Galaxy->name
                        )
                    ), JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function idclusterAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $cluster = Cluster::findFirstById($this->request->getQuery("id"));

               // Формируем ответ
            $response = new Response();
            if (count($cluster) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => array(
                            'id'   => $cluster->id,
                            'name' => $cluster->name,
                            'size' => $cluster->size,
                        )
                    ), JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function idgalaxyAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $galaxy = Galaxy::findFirstById($this->request->getQuery("id"));

               // Формируем ответ
            $response = new Response();
            if (count($galaxy) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => array(
                            'id'   => $galaxy->id,
                            'name' => $galaxy->name,
                            'size' => $galaxy->size,
                            'type' => $galaxy->TypeOfGalaxy->name,
                            'cluster' => $galaxy->Cluster->name
                        )
                    ), JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function idplanetAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $planet = Planet::findFirstById($this->request->getQuery("id"));

               // Формируем ответ
            $response = new Response();
            if (count($planet) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => array(
                            'id'   => $planet->id,
                            'name' => $planet->name,
                            'weight' => $planet->weight,
                            'type' => $planet->TypeOfPlanet->name,
                            'solar_system' => $planet->SolarSystem->name
                        )
                    ), JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function idsolarsystemAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $solar_system = SolarSystem::findFirstById($this->request->getQuery("id"));

               // Формируем ответ
            $response = new Response();
            if (count($solar_system) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => array(
                            'id'   => $solar_system->id,
                            'name' => $solar_system->name,
                            'galaxy' => $solar_system->Galaxy->name
                        )
                    ), JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function idstarAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {
            $star = Star::findFirstById($this->request->getQuery("id"));

               // Формируем ответ
            $response = new Response();
            if (count($star) == 0) {
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                $response->setJsonContent(
                    array(
                        'status' => 'FOUND',
                        'data'   => array(
                            'id'   => $star->id,
                            'name' => $star->name,
                            'weight' => $star->weight,
                            'age' => $star->age,
                            'type' => $star->TypeOfStar->name,
                            'solar_system' => $star->SolarSystem->name
                        )
                    ), JSON_UNESCAPED_UNICODE
                );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }


    public function addblackholeAction()//добавление
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));

            $validation->add('weight', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая чёрная дыра быть не может<br />',
                'messageMinimum' => 'Такая маленькая чёрная дыра быть не может<br />'
            )));
            $validation->add('weight', new PresenceOf(array(
               'message' => 'Вы ввели пустой вес<br />'
            )));
            $validation->add('weight', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

            $validation->add('age', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большой возраст быть не может<br />',
                'messageMinimum' => 'Такая маленький возраст быть не может<br />'
            )));
            $validation->add('age', new PresenceOf(array(
               'message' => 'Вы ввели пустой возраст<br />'
            )));
            $validation->add('age', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите возраст правильно<br />'
            )));            
            
            $validation->add('galaxy', new PresenceOf(array(
               'message' => 'Вы ввели пустую галактику<br />'
            )));
            $validation->add('type', new PresenceOf(array(
               'message' => 'Вы ввели пустой тип<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $black_holes = BlackHole::findFirstByName($this->request->getPost("name"));

                if ($black_holes == false) 
                {
                    $galaxy_id = Galaxy::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("galaxy"))
                        )
                    );

                   $type_id = TypeOfBlackHole::findFirst(
                        array(
                            "name = :name:",
                            "bind" => array(
                                "name" => $this->request->getPost("type"))
                        )
                    );
                    if ($type_id == true && $galaxy_id == true)
                    {
                        try 
                        {
                            $black_hole = new BlackHole();
                            $black_hole->name = $this->request->getPost("name");
                            $black_hole->weight = $this->request->getPost("weight");
                            $black_hole->age = $this->request->getPost("age");
                            $black_hole->type = $type_id->id; // передавай имена, а не id
                            $black_hole->galaxy = $galaxy_id->id;
                            $success = $black_hole->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'ADD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-ADD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    } 

    public function addclusterAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br>',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
            )));
            $validation->add('size', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Так много галактик быть не может<br>',
                'messageMinimum' => 'Так мало галактик быть не может<br>'
            )));
            $validation->add('size', new PresenceOf(array(
               'message' => 'Вы ввели пустое кол-во галактик'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br>'
            )));
            $validation->add('size', new RegexValidator(array(
               'pattern' => '/[0-9]{1,3}/',
               'message' => 'Введите кол-во правильно<br>'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $clusters = Cluster::findFirstByName($this->request->getPost("name"));

                if ($clusters == false) 
                {
                    try 
                    {
                        $cluster = new Cluster();
                        $cluster->name = $this->request->getPost("name");
                        $cluster->size = $this->request->getPost("size");
                        $success = $cluster->save();
                        if ($success) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'ADD'
                                )
                            );                        
                        }
                        else
                        {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'NOT-ADD'
                                )
                            );
                        }
                    } 
                    catch (InvalidArgumentException $e) {
                        $response = new Response();
                        $response->setJsonContent(
                            array(
                                'status' => 'EXCEPTION'
                            )
                        );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    } 

    public function addgalaxyAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('size', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая галактика быть не может<br />',
                'messageMinimum' => 'Такая маленькая галактика быть не может<br />'
            )));
            $validation->add('size', new PresenceOf(array(
               'message' => 'Вы ввели пустой размер<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));
            $validation->add('size', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите размер правильно<br />'
            )));
            $validation->add('cluster', new PresenceOf(array(
               'message' => 'Вы ввели пустое скопление<br />'
            )));
            $validation->add('type', new PresenceOf(array(
               'message' => 'Вы ввели пустой тип<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $galaxis = Galaxy::findFirstByName($this->request->getPost("name"));

                if ($galaxis == false) 
                {
                    $cluster_id = Cluster::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("cluster"))
                        )
                    );

                   $type_id = TypeOfGalaxy::findFirst(
                        array(
                            "name = :name:",
                            "bind" => array(
                                "name" => $this->request->getPost("type"))
                        )
                    );
                    if ($type_id == true && $cluster_id == true)
                    {
                        try 
                        {
                            $galaxy = new Galaxy();
                            $galaxy->name = $this->request->getPost("name");
                            $galaxy->size = $this->request->getPost("size");
                            $galaxy->type = $type_id->id; // передавай имена, а не id
                            $galaxy->cluster = $cluster_id->id;
                            $success = $galaxy->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'ADD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-ADD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    }

    public function addPlanetAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));

            $validation->add('weight', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая чёрная дыра быть не может<br />',
                'messageMinimum' => 'Такая маленькая чёрная дыра быть не может<br />'
            )));
            $validation->add('weight', new PresenceOf(array(
               'message' => 'Вы ввели пустой вес<br />'
            )));
            $validation->add('weight', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));            
            
            $validation->add('solar_system', new PresenceOf(array(
               'message' => 'Вы ввели пустую солнечную систему<br />'
            )));
            $validation->add('type', new PresenceOf(array(
               'message' => 'Вы ввели пустой тип<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $planets = Planet::findFirstByName($this->request->getPost("name"));

                if ($planets == false) 
                {
                    $solar_system_id = SolarSystem::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("solar_system"))
                        )
                    );

                   $type_id = TypeOfPlanet::findFirst(
                        array(
                            "name = :name:",
                            "bind" => array(
                                "name" => $this->request->getPost("type"))
                        )
                    );
                    if ($type_id == true && $solar_system_id == true)
                    {
                        try 
                        {
                            $planet = new Planet();
                            $planet->name = $this->request->getPost("name");
                            $planet->weight = $this->request->getPost("weight");
                            $planet->type = $type_id->id; // передавай имена, а не id
                            $planet->solar_system = $solar_system_id->id;
                            $success = $planet->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'ADD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-ADD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    } 

    public function addSolarSystemAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));
            
            $validation->add('galaxy', new PresenceOf(array(
               'message' => 'Вы ввели пустую галактику<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $solar_systems = SolarSystem::findFirstByName($this->request->getPost("name"));

                if ($solar_systems == false) 
                {
                    $galaxy_id = Galaxy::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("galaxy"))
                        )
                    );

                    if ($galaxy_id == true)
                    {
                        try 
                        {
                            $solar_system = new SolarSystem();
                            $solar_system->name = $this->request->getPost("name");
                            $solar_system->galaxy = $galaxy_id->id;
                            $success = $solar_system->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'ADD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-ADD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    } 

    public function addStarAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));

            $validation->add('weight', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая чёрная дыра быть не может<br />',
                'messageMinimum' => 'Такая маленькая чёрная дыра быть не может<br />'
            )));
            $validation->add('weight', new PresenceOf(array(
               'message' => 'Вы ввели пустой вес<br />'
            )));
            $validation->add('weight', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

            $validation->add('age', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большой возраст быть не может<br />',
                'messageMinimum' => 'Такая маленький возраст быть не может<br />'
            )));
            $validation->add('age', new PresenceOf(array(
               'message' => 'Вы ввели пустой возраст<br />'
            )));
            $validation->add('age', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите возраст правильно<br />'
            )));            
            
            $validation->add('solar_system', new PresenceOf(array(
               'message' => 'Вы ввели пустую солнечную систему<br />'
            )));
            $validation->add('type', new PresenceOf(array(
               'message' => 'Вы ввели пустой тип<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $stars = Star::findFirstByName($this->request->getPost("name"));

                if ($stars == false) 
                {
                    $solar_system_id = SolarSystem::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("solar_system"))
                        )
                    );

                   $type_id = TypeOfPlanet::findFirst(
                        array(
                            "name = :name:",
                            "bind" => array(
                                "name" => $this->request->getPost("type"))
                        )
                    );
                    if ($type_id == true && $solar_system_id == true)
                    {
                        try 
                        {
                            $star = new Star();
                            $star->name = $this->request->getPost("name");
                            $star->weight = $this->request->getPost("weight");
                            $star->age = $this->request->getPost("age");
                            $star->type = $type_id->id; // передавай имена, а не id
                            $star->solar_system = $solar_system_id->id;
                            $success = $star->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'ADD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-ADD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    }  

    public function delblackholeAction() //удаление
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            global $name;
            $name = $this->request->getQuery("name");
            $conditions = "name = :name:";

            $parameters = array(
            "name" => $name);

            $black_holes = BlackHole::find(
                array(
                    $conditions,
                    "bind" => $parameters
                )
            );



            // Формируем ответ
            $response = new Response();

            if ($black_holes == false) {
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                foreach ($black_holes as $black_hole) {
                    $black_hole->dele = 1;
                    $success = $black_hole->save();
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'DELETE'
                    )
                ); 
            } 
                      
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function delclusterAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $objects = Cluster::find(
                array(
                    "name = :name:",
                    "bind" => array(
                         "name" => $this->request->getQuery("name"))
                        )
            );



            // Формируем ответ
            $response = new Response();

            if ($objects == false) {
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                foreach ($objects as $object) {
                    $object->dele = 1;
                    $success = $object->save();
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'DELETE'
                    )
                ); 
            } 
                      
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function delgalaxyAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $objects = Galaxy::find(
                array(
                    "name = :name:",
                    "bind" => array(
                         "name" => $this->request->getQuery("name"))
                        )
            );



            // Формируем ответ
            $response = new Response();

            if ($objects == false) {
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                foreach ($objects as $object) {
                    $object->dele = 1;
                    $success = $object->save();
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'DELETE'
                    )
                ); 
            } 
                      
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function delplanetAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $objects = Planet::find(
                array(
                    "name = :name:",
                    "bind" => array(
                         "name" => $this->request->getQuery("name"))
                        )
            );



            // Формируем ответ
            $response = new Response();

            if ($objects == false) {
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                foreach ($objects as $object) {
                    $object->dele = 1;
                    $success = $object->save();
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'DELETE'
                    )
                ); 
            } 
                      
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function delsolarsystemAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $objects = SolarSystem::find(
                array(
                    "name = :name:",
                    "bind" => array(
                         "name" => $this->request->getQuery("name"))
                        )
            );



            // Формируем ответ
            $response = new Response();

            if ($objects == false) {
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                foreach ($objects as $object) {
                    $object->dele = 1;
                    $success = $object->save();
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'DELETE'
                    )
                ); 
            } 
                      
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function delstarAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $validation = new Validation();
        $validation->add('name', new PresenceOf(array(
           'message' => 'Вы ввели пустое название<br>'
        )));
        $validation->add('name', new StringLength(array(
            'max' => 100,
            'min' => 1,
            'messageMaximum' => 'Вы ввели слишком большое название<br>',
            'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
        )));
        $validation->add('name', new RegexValidator(array(
           'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
           'message' => 'Введите название правильно<br>'
        )));
        $messages = $validation->validate($_GET);
        if (!count($messages)) 
        {

            $objects = Star::find(
                array(
                    "name = :name:",
                    "bind" => array(
                         "name" => $this->request->getQuery("name"))
                        )
            );



            // Формируем ответ
            $response = new Response();

            if ($objects == false) {
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'NOT-FOUND'
                    )
                );
            } else {
                foreach ($objects as $object) {
                    $object->dele = 1;
                    $success = $object->save();
                }
                $response = new Response();
                $response->setJsonContent(
                    array(
                        'status' => 'DELETE'
                    )
                ); 
            } 
                      
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
        }
        return $response;
        $this->view->disable();
    }

    public function updblackholeAction() //изменение
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));

            $validation->add('weight', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая чёрная дыра быть не может<br />',
                'messageMinimum' => 'Такая маленькая чёрная дыра быть не может<br />'
            )));
            $validation->add('weight', new PresenceOf(array(
               'message' => 'Вы ввели пустой вес<br />'
            )));
            $validation->add('weight', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

            $validation->add('age', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большой возраст быть не может<br />',
                'messageMinimum' => 'Такая маленький возраст быть не может<br />'
            )));
            $validation->add('age', new PresenceOf(array(
               'message' => 'Вы ввели пустой возраст<br />'
            )));
            $validation->add('age', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите возраст правильно<br />'
            )));            
            
            $validation->add('galaxy', new PresenceOf(array(
               'message' => 'Вы ввели пустую галактику<br />'
            )));
            $validation->add('type', new PresenceOf(array(
               'message' => 'Вы ввели пустой тип<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $black_holes = BlackHole::find(
                    array(
                        "name = :name: AND id != :id:",
                        "bind" => array(
                            "name" => $this->request->getPost("name"),
                            "id" => $this->request->getPost("id")
                        )
                    )
                );

                if (count($black_holes) == 0) 
                {
                    $galaxy_id = Galaxy::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("galaxy"))
                        )
                    );

                   $type_id = TypeOfBlackHole::findFirst(
                        array(
                            "name = :name:",
                            "bind" => array(
                                "name" => $this->request->getPost("type"))
                        )
                    );
                    if ($type_id == true && $galaxy_id == true)
                    {
                        try 
                        {
                            $black_hole = BlackHole::findFirstById($this->request->getPost("id"));
                            $black_hole->name = $this->request->getPost("name");
                            $black_hole->weight = $this->request->getPost("weight");
                            $black_hole->age = $this->request->getPost("age");
                            $black_hole->type = $type_id->id; // передавай имена, а не id
                            $black_hole->galaxy = $galaxy_id->id;
                            $success = $black_hole->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'UPD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-UPD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    }

    public function updclusterAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br>',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
            )));
            $validation->add('size', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Так много галактик быть не может<br>',
                'messageMinimum' => 'Так мало галактик быть не может<br>'
            )));
            $validation->add('size', new PresenceOf(array(
               'message' => 'Вы ввели пустое кол-во галактик'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br>'
            )));
            $validation->add('size', new RegexValidator(array(
               'pattern' => '/[0-9]{1,3}/',
               'message' => 'Введите кол-во правильно<br>'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $clusters = Cluster::find(
                    array(
                        "name = :name: AND id != :id:",
                        "bind" => array(
                            "name" => $this->request->getPost("name"),
                            "id" => $this->request->getPost("id")
                        )
                    )
                );

                if ($clusters == false) 
                {
                    try 
                    {
                        $cluster = Cluster::findFirstById($this->request->getPost("id"));
                        $cluster->name = $this->request->getPost("name");
                        $cluster->size = $this->request->getPost("size");
                        $success = $cluster->save();
                        if ($success) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'UPD'
                                )
                            );                        
                        }
                        else
                        {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'NOT-UPD'
                                )
                            );
                        }
                    } 
                    catch (InvalidArgumentException $e) {
                        $response = new Response();
                        $response->setJsonContent(
                            array(
                                'status' => 'EXCEPTION'
                            )
                        );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    } 

    public function updgalaxyAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('size', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая галактика быть не может<br />',
                'messageMinimum' => 'Такая маленькая галактика быть не может<br />'
            )));
            $validation->add('size', new PresenceOf(array(
               'message' => 'Вы ввели пустой размер<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));
            $validation->add('size', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите размер правильно<br />'
            )));
            $validation->add('cluster', new PresenceOf(array(
               'message' => 'Вы ввели пустое скопление<br />'
            )));
            $validation->add('type', new PresenceOf(array(
               'message' => 'Вы ввели пустой тип<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $galaxis = Galaxy::find(
                    array(
                        "name = :name: AND id != :id:",
                        "bind" => array(
                            "name" => $this->request->getPost("name"),
                            "id" => $this->request->getPost("id")
                        )
                    )
                );

                if ($galaxis == false) 
                {
                    $cluster_id = Cluster::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("cluster"))
                        )
                    );

                   $type_id = TypeOfGalaxy::findFirst(
                        array(
                            "name = :name:",
                            "bind" => array(
                                "name" => $this->request->getPost("type"))
                        )
                    );
                    if ($type_id == true && $cluster_id == true)
                    {
                        try 
                        {
                            $galaxy = Galaxy::findFirstById($this->request->getPost("id"));
                            $galaxy->name = $this->request->getPost("name");
                            $galaxy->size = $this->request->getPost("size");
                            $galaxy->type = $type_id->id; // передавай имена, а не id
                            $galaxy->cluster = $cluster_id->id;
                            $success = $galaxy->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'UPD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-UPD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    }

    public function updPlanetAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));

            $validation->add('weight', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая чёрная дыра быть не может<br />',
                'messageMinimum' => 'Такая маленькая чёрная дыра быть не может<br />'
            )));
            $validation->add('weight', new PresenceOf(array(
               'message' => 'Вы ввели пустой вес<br />'
            )));
            $validation->add('weight', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));            
            
            $validation->add('solar_system', new PresenceOf(array(
               'message' => 'Вы ввели пустую солнечную систему<br />'
            )));
            $validation->add('type', new PresenceOf(array(
               'message' => 'Вы ввели пустой тип<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $planets = Planet::find(
                    array(
                        "name = :name: AND id != :id:",
                        "bind" => array(
                            "name" => $this->request->getPost("name"),
                            "id" => $this->request->getPost("id")
                        )
                    )
                );

                if ($planets == false) 
                {
                    $solar_system_id = SolarSystem::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("solar_system"))
                        )
                    );

                   $type_id = TypeOfPlanet::findFirst(
                        array(
                            "name = :name:",
                            "bind" => array(
                                "name" => $this->request->getPost("type"))
                        )
                    );
                    if ($type_id == true && $solar_system_id == true)
                    {
                        try 
                        {
                            $planet = Planet::findFirstById($this->request->getPost("id"));
                            $planet->name = $this->request->getPost("name");
                            $planet->weight = $this->request->getPost("weight");
                            $planet->type = $type_id->id; // передавай имена, а не id
                            $planet->solar_system = $solar_system_id->id;
                            $success = $planet->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'UPD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-UPD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    } 

    public function updSolarSystemAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));
            
            $validation->add('galaxy', new PresenceOf(array(
               'message' => 'Вы ввели пустую галактику<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $solar_systems = SolarSystem::find(
                    array(
                        "name = :name: AND id != :id:",
                        "bind" => array(
                            "name" => $this->request->getPost("name"),
                            "id" => $this->request->getPost("id")
                        )
                    )
                );

                if ($solar_systems == false) 
                {
                    $galaxy_id = Galaxy::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("galaxy"))
                        )
                    );

                    if ($galaxy_id == true)
                    {
                        try 
                        {
                            $solar_system = SolarSystem::findFirstById($this->request->getPost("id"));
                            $solar_system->name = $this->request->getPost("name");
                            $solar_system->galaxy = $galaxy_id->id;
                            $success = $solar_system->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'UPD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-UPD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    } 

    public function updStarAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) 
        {
            $validation = new Validation();
            $validation->add('name', new PresenceOf(array(
               'message' => 'Вы ввели пустое название<br>'
            )));
            $validation->add('name', new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => 'Вы ввели слишком большое название<br />',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br />'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));

            $validation->add('weight', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая чёрная дыра быть не может<br />',
                'messageMinimum' => 'Такая маленькая чёрная дыра быть не может<br />'
            )));
            $validation->add('weight', new PresenceOf(array(
               'message' => 'Вы ввели пустой вес<br />'
            )));
            $validation->add('weight', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

            $validation->add('age', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большой возраст быть не может<br />',
                'messageMinimum' => 'Такая маленький возраст быть не может<br />'
            )));
            $validation->add('age', new PresenceOf(array(
               'message' => 'Вы ввели пустой возраст<br />'
            )));
            $validation->add('age', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите возраст правильно<br />'
            )));            
            
            $validation->add('solar_system', new PresenceOf(array(
               'message' => 'Вы ввели пустую солнечную систему<br />'
            )));
            $validation->add('type', new PresenceOf(array(
               'message' => 'Вы ввели пустой тип<br />'
            )));
            $validation->add('id', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $stars = Star::find(
                    array(
                        "name = :name: AND id != :id:",
                        "bind" => array(
                            "name" => $this->request->getPost("name"),
                            "id" => $this->request->getPost("id")
                        )
                    )
                );

                if ($stars == false) 
                {
                    $solar_system_id = SolarSystem::findFirst(
                        array(
                            "name = :name: AND dele = 0",
                            "bind" => array(
                                "name" => $this->request->getPost("solar_system"))
                        )
                    );

                   $type_id = TypeOfPlanet::findFirst(
                        array(
                            "name = :name:",
                            "bind" => array(
                                "name" => $this->request->getPost("type"))
                        )
                    );
                    if ($type_id == true && $solar_system_id == true)
                    {
                        try 
                        {
                            $star = Star::findFirstById($this->request->getPost("id"));
                            $star->name = $this->request->getPost("name");
                            $star->weight = $this->request->getPost("weight");
                            $star->age = $this->request->getPost("age");
                            $star->type = $type_id->id; // передавай имена, а не id
                            $star->solar_system = $solar_system_id->id;
                            $success = $star->save();
                            if ($success) {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'UPD'
                                    )
                                );                        
                            }
                            else
                            {
                                $response = new Response();
                                $response->setJsonContent(
                                    array(
                                        'status' => 'NOT-UPD'
                                    )
                                );
                            }
                        } 
                        catch (InvalidArgumentException $e) {
                            $response = new Response();
                            $response->setJsonContent(
                                array(
                                    'status' => 'EXCEPTION'
                                )
                            );
                        }
                    }
                    else
                    {
                        $response = new Response();
                        $response->setJsonContent(
                        array(
                            'status' => 'UNCORRECT'
                        )
                    );
                    }
                }
                else 
                {
                    $response = new Response();
                    $response->setJsonContent(
                        array(
                            'status' => 'NAME-CLOSED'
                        )
                    );
                }
            
            }
            else
            {
                $response = new Response();
                $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                )
            );
            }
        }
        else
        {
            $response = new Response();
            $response->setJsonContent(
                array(
                    'status' => 'NOT-POST'
                )
            );
        }

        return $response;
        $this->view->disable();
    }  

}


?>