<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;


class GalaxyController extends Controller
{
    public function indexAction()
    {
        $paginator = new PaginatorArray(
            array(
                "data"  => Galaxy::find(
                        array(
                                array(
                                "dele" => 0
                            ),
                            "sort"  => array(
                            "name" => 1
                            )
                        )
                    ),
                "limit" => 15,
                "page"  => 1
            )
        );        
        if ($paginator->getPaginate()->total_pages != 0) 
        {
            $validation_page = new Validation();
            $validation_page->add('page', new Between(array(
               'minimum' => 1,
               'maximum' => $paginator->getPaginate()->total_pages,
               'message' => '1'
            )));

            $page = 1;        
            $messages = $validation_page->validate($_GET);
            if (!count($messages))
            {
                $page = $this->request->get("page");
            }

            $paginator->setCurrentPage($page);

            $this->view->galaxies = $paginator->getPaginate();
        }
    }

    public function TypeAction()
    {
        $types = TypeOfGalaxy::find();
        $this->view->types = $types;
    }

    public function DeleteAction()
    {
        if ($this->request->isPost() == true) {

           $galaxies = Galaxy::find(
                array(
                        array(
                            "name" => $name = $this->request->getPost("name")
                        )                        
                    )
            );

            foreach ($galaxies as $galaxy) {
                $galaxy->Deleted(1);
                $success = $galaxy->save();
            }
            $response = new \Phalcon\Http\Response();
            $response->redirect("index");
            $response->send();
        }
        else
        {
            // Получение экземпляра Response
            $response = new \Phalcon\Http\Response();

            // Установка кода статуса
            $response->setStatusCode(404, "Not Found");

            // Установка содержимого ответа
            $response->setContent("<h3>404</h3><p>Сожалеем, но страница не существует</p>");

            // Отправка ответа клиенту
            $response->send();

        }

        $this->view->disable();
    }

    public function UpdAction()
    {
        if ($this->request->isPost() == true) {

            $galaxy = Galaxy::findById($this->request->getPost("id"));
            $clusters= Cluster::find(
                array(
                        array(
                            "dele" => 0
                        )                        
                    ) 
                );
            $this->view->clusters = $clusters; 

            $types = TypeOfGalaxy::find();  
            $this->view->types = $types;  

            $this->view->galaxy = $galaxy;         

        }
        else
        {
            // Получение экземпляра Response
            $response = new \Phalcon\Http\Response();

            // Установка кода статуса
            $response->setStatusCode(404, "Not Found");

            // Установка содержимого ответа
            $response->setContent("<h3>404</h3><p>Сожалеем, но страница не существует</p>");

            // Отправка ответа клиенту
            $response->send();

        }
    }

    public function UpdaterAction()
    {
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
            $validation->add('size', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая галактика быть не может<br />',
                'messageMinimum' => 'Такая маленькая галактика быть не может<br />'
            )));
            $validation->add('size', new PresenceOf(array(
               'message' => 'Вы ввели пустой размер<br />'
            )));
            $validation->add('id', new PresenceOf(array(
               'message' => 'Ой<br>'
            )));
            $validation->add('id', new RegexValidator(array(
                'pattern' => '/[a-z0-9]{1,50}/u',
                'message' => 'Id не правилен<br>'
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
                $galaxy = Galaxy::find(
                    array(
                        array(
                            "name" => $name = $this->request->getPost("name"),
                            "dele" => 0
                        )                        
                    )
                );

                if (count($galaxy) == 1) 
                {
                    $cluster_id = Cluster::findFirnst(
                        array(
                            array(
                                "name" => $this->request->getPost("cluster"),
                                "dele" => 0
                            )                        
                        ) 
                    );

                    $conditions = "name = :name:";

                    $parameters = array(
                    "name" => $type);

                    $type_id = TypeOfGalaxy::findFirst(
                        array(
                            array(
                                "name" => $this->request->getPost("type")
                            )                        
                        )
                    );

                    $galaxy = Galaxy::findById($this->request->getPost("id"));
                    try 
                    {

                        $galaxy->name = $this->request->getPost("name");
                        $galaxy->size = $this->request->getPost("size");
                        $galaxy->type_id = $type_id->_id;
                        $galaxy->cluster_id = $cluster_id->_id;
                        $success = $galaxy->save();
                        if ($success) {
                            echo "Данные упешно изменены";
                        }
                        else
                            echo "Данные не изменены";
                    } 
                    catch (InvalidArgumentException $e) {
                        echo "Что-то пошло не так, пожалуйста проверьте корректность ввода";
                    }
                }
                else echo "Галактика с таким названием уже есть";
            
            }
            else
                foreach ($messages as $message) {
                    echo $message;
                }
        }
        else
            echo "Опаньки, поста то нет";

        $this->view->disable();
    }

    public function InsertAction() 
    {
        $clusters= Cluster::find(
            array(
                array(
                    "dele" => 0
                )                        
            ) 
        );
        $this->view->clusters = $clusters; 

        $types = TypeOfGalaxy::find();  
        $this->view->types = $types;  
    }

    public function EnterAction()
    {
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
                $galaxy = Galaxy::find(
                    array(
                        array(
                            "name" => $name = $this->request->getPost("name"),
                            "dele" => 0
                        )                        
                    )
                );

                if (count($galaxy) == 0) 
                {
                    $cluster_id = Cluster::findFirst(
                        array(
                            array(
                                "name" => $this->request->getPost("cluster"),
                                "dele" => 0
                            )                        
                        ) 
                    );

                    $conditions = "name = :name:";

                    $parameters = array(
                    "name" => $type);

                    $type_id = TypeOfGalaxy::findFirst(
                        array(
                            array(
                                "name" => $this->request->getPost("type")
                            )                        
                        )
                    );

                    try 
                    {
                        $galaxy = new Galaxy();
                        $galaxy->name = $this->request->getPost("name");
                        $galaxy->size = $this->request->getPost("size");
                        $galaxy->dele = 0;
                        $galaxy->type_id = $type_id->_id;
                        $galaxy->cluster_id = $cluster_id->_id;
                        $success = $galaxy->save();
                        if ($success) {
                            echo "Галактика добавлена";
                        }
                        else
                            echo "Галактика не добавлена";
                    } 
                    catch (Exception $e) {
                        echo "Что-то пошло не так, пожалуйста проверьте корректность ввода";
                    }
                }
                else echo "Галактика с таким названием уже есть";
            
            }
            else
                foreach ($messages as $message) {
                    echo $message;
                }
        }
        else
            echo "Опаньки, поста то нет";

        $this->view->disable();
    }

    public function IsAction()
    {
        if ($this->request->hasQuery("id") == true) {

            $validation = new Validation();
            $validation->add('id', new PresenceOf(array(
               'message' => 'Ой<br>'
            )));
            $validation->add('id', new RegexValidator(array(
                'pattern' => '/[a-z0-9]{1,50}/u',
                'message' => 'Id не правилен<br>'
            )));
            $messages = $validation->validate($_GET);
            if (!count($messages)) 
            { 
                $galaxy = Galaxy::findById($this->request->getQuery("id"));
                $black_holes = BlackHole::find(
                    array(
                        array(
                            "dele" => 0,
                            "galaxy_id" => $this->request->getQuery("id")
                        ),
                        "sort"  => array(
                        "name" => 1
                        )
                    )
                );
                $solar_systems = SolarSystem::find(
                    array(
                        array(
                            "dele" => 0,
                            "galaxy_id" => $this->request->getQuery("id")
                        ),
                        "sort"  => array(
                        "name" => 1
                        )
                    )
                );
                $this->view->galaxy_name = $galaxy->name;
                $this->view->black_holes = $black_holes;
                $this->view->solar_systems = $solar_systems;  
            } 
            else
                foreach ($messages as $message) {
                    echo $message;
                }         

        }
        else
        {
            // Получение экземпляра Response
            $response = new \Phalcon\Http\Response();

            // Установка кода статуса
            $response->setStatusCode(404, "Not Found");

            // Установка содержимого ответа
            $response->setContent("<h3>404</h3><p>Сожалеем, но страница не существует</p>");

            // Отправка ответа клиенту
            $response->send();

        }
    }

    public function ExcelAction()
    {
        // Подключаем класс для работы с excel
        require_once('Exel.php');

        //$header = array(
        //    'Название'=>'string',
        //    'Вес в массах Солнца'=>'double',
        //    'Возраст в миллиардах лет'=>'double',
        //    'Тип'=>'string',
        //    'Домашняя галактика'=>'string',
        //    'Удалён'=>'int'
        //);
        $data = array();
        /*$data1 = array(
            array('2003','1','-50.5','2010-01-01 23:00:00','2012-12-31 23:00:00'),
            array('2003','=B2', '23.5','2010-01-01 00:00:00','2012-12-31 00:00:00'),
        );*/

        foreach (Galaxy::find() as $galaxy) 
        {
            array_push($data, array(
                $galaxy->_id,
                $galaxy->dele,
                $galaxy->name,
                $galaxy->size,
                $galaxy->type_id,
                $galaxy->cluster_id
            ));
        }

        // file name to output
        $temp_file = tempnam(sys_get_temp_dir(), 'phpexcel');

        $writer = new XLSXWriter();
        $writer->writeSheet($data,'sheet1'); // write your data into excel sheet
        $writer->writeToFile($temp_file); // Name the file you want to save as

        $response = new \Phalcon\Http\Response();

        // Redirect output to a client’s web browser (Excel2007)
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment;filename="' . date("Ymd_his") . ".xlsx" . '"');
        $response->setHeader('Cache-Control', 'max-age=0');

        // If you're serving to IE 9, then the following may be needed
        $response->setHeader('Cache-Control', 'max-age=1');

        //Set the content of the response
        $response->setContent(file_get_contents($temp_file));

        // delete temp file
        unlink($temp_file);

        //Return the response
        return $response;

        $this->view->disable();

    }
}


?>