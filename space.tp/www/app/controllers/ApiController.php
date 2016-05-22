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
    public function blackholeAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        $paginator = new Paginator(
            array(
                "data"  => BlackHole::find(),
                "limit" => 100,
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

                echo json_encode($data, JSON_UNESCAPED_UNICODE);
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
            $response->setJsonContent(
                array(
                    'status' => 'UNCORRECT'
                    )
                , JSON_UNESCAPED_UNICODE
            );
        }
        $this->view->disable();
    }

    public function nameblackholeAction()
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

            $conditions = "name = :name:";

            $parameters = array(
            "name" => $this->request->getQuery("name"));

            $black_hole = BlackHole::find(
                array(
                    $conditions,
                    "bind" => $parameters
                )
            );            

            // Формируем ответ
            $response = new Response();

            if ($black_hole == false) {
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

    public function idblackholeAction()
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
            if ($black_hole == false) {
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

    public function addblackholeAction()
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

            $validation->add('type', new Between(array(
               'minimum' => 1,
               'maximum' => 99999999,
               'message' => '1'
            )));

            $validation->add('galaxy', new Between(array(
               'minimum' => 1,
               'maximum' => 99999999,
               'message' => '1'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $black_holes = BlackHole::findFirstByName($this->request->getPost("name"));

                if ($black_holes == false) 
                {
                    try 
                    {
                        $black_hole = new BlackHole();
                        $black_hole->name = $this->request->getPost("name");
                        $black_hole->weight = $this->request->getPost("weight");
                        $black_hole->age = $this->request->getPost("age");
                        $black_hole->type = $this->request->getPost("type");
                        $black_hole->galaxy = $this->request->getPost("galaxy");
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

    public function delblackholeAction()
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

    public function updblackholeAction()
    {
        //ini_set('memory_limit', '2000M');
        //ini_set("max_execution_time", "2900");
        if ($this->request->isPost() == true) {

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

            $validation->add('type', new Between(array(
               'minimum' => 1,
               'maximum' => 99999999,
               'message' => '1'
            )));

            $validation->add('galaxy', new Between(array(
               'minimum' => 1,
               'maximum' => 99999999,
               'message' => '1'
            )));

            $validation->add('id', new PresenceOf(array(
               'message' => 'Ой<br />'
            )));
            $validation->add('id', new StringLength(array(
                'max' => 9,
                'min' => 1,
                'messageMaximum' => 'ОЙ<br />',
                'messageMinimum' => 'ОЙ<br />'
            )));


            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {    
                $conditions = "name = :name: AND id != :id:";

                $parameters = array(
                "name" => $this->request->getPost("name"),
                "id" => $this->request->getPost("id"));

                $black_holes = BlackHole::find(
                    array(
                        $conditions,
                        "bind" => $parameters
                    )
                );         
                
                if (count($black_holes) == 0) 
                {
                    $black_hole = BlackHole::findFirstById($this->request->getPost("id"));
                    try 
                    {
                        $black_hole->name = $this->request->getPost("name");
                        $black_hole->weight = $this->request->getPost("weight");
                        $black_hole->age = $this->request->getPost("age");
                        $black_hole->type = $this->request->getPost("type");
                        $black_hole->galaxy = $this->request->getPost("galaxy");
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