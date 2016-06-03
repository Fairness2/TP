<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;


class StarController extends Controller
{
    public function indexAction()
    {
        $paginator = new PaginatorArray(
            array(
                "data"  => Star::find(
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

            $this->view->stars = $paginator->getPaginate();
        }
    }

    public function TypeAction()
    {
        $types = TypeOfStar::find();
        $this->view->types = $types;
    }

    public function DeleteAction()
    {
        if ($this->request->isPost() == true) {

            $stars = Star::find(
                array(
                        array(
                            "name" => $name = $this->request->getPost("name")
                        )                        
                    )
            );

            foreach ($stars as $star) {
                $star->Deleted(1);
                $success = $star->save();
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

            $star = Star::findById($this->request->getPost("id"));
            $this->view->star = $star; 

            $solar_systems = SolarSystem::find(
                array(
                        array(
                            "dele" => 0
                        )                        
                    ) 
                );
            $this->view->solar_systems = $solar_systems; 

            $types = TypeOfStar::find();  
            $this->view->types = $types;         

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
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br />'
            )));

            $validation->add('weight', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Такая большая звезда быть не может<br />',
                'messageMinimum' => 'Такая маленькая звезда быть не может<br />'
            )));
            $validation->add('weight', new PresenceOf(array(
               'message' => 'Вы ввели пустой вес<br />'
            )));
            $validation->add('weight', new RegexValidator(array(
               'pattern' => '/[0-9]{1,9}/',
               'message' => 'Введите вес правильно<br />'
            )));

            $validation->add('id', new PresenceOf(array(
               'message' => 'Ой<br />'
            )));
            $validation->add('id', new RegexValidator(array(
                'pattern' => '/[a-z0-9]{1,50}/u',
                'message' => 'Id не правилен<br>'
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
                $star = Star::find(
                    array(
                        array(
                            "name" => $name = $this->request->getPost("name"),
                            "dele" => 0
                        )                        
                    )
                );
                if (count($star) == 1) 
                {
                    $solar_system_id = SolarSystem::findFirnst(
                        array(
                            array(
                                "name" => $this->request->getPost("solar_system"),
                                "dele" => 0
                            )                        
                        ) 
                    );

                    $type_id = TypeOfStar::findFirst(
                        array(
                            array(
                                "name" => $this->request->getPost("type")
                            )                        
                        )
                    );

                    $star = Star::findById($this->request->getPost("id"));
                    try 
                    {

                        $star->name = $this->request->getPost("name");
                        $star->weight = $this->request->getPost("weight");
                        $star->age = $this->request->getPost("age");
                        $star->type = $type_id->_id;
                        $star->solar_system = $solar_system_id->_id;
                        $success = $star->save();
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
        $solar_systems = SolarSystem::find(
            array(
                array(
                    "dele" => 0
                )                        
            ) 
        );
        $this->view->solar_systems = $solar_systems; 
        $types = TypeOfStar::find();  
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
                $star = Star::find(
                    array(
                        array(
                            "name" => $name = $this->request->getPost("name"),
                            "dele" => 0
                        )                        
                    )
                );
                if (count($star) == 0) 
                {
                    $solar_system_id = SolarSystem::findFirnst(
                        array(
                            array(
                                "name" => $this->request->getPost("solar_system"),
                                "dele" => 0
                            )                        
                        ) 
                    );

                    $type_id = TypeOfStar::findFirst(
                        array(
                            array(
                                "name" => $this->request->getPost("type")
                            )                        
                        )
                    );

                    try 
                    {
                        $star = new Star();
                        $star->name = $this->request->getPost("name");
                        $star->weight = $this->request->getPost("weight");
                        $star->age = $this->request->getPost("age");
                        $star->type = $type_id->_id;
                        $star->solar_system = $solar_system_id->_id;
                        $success = $star->save();
                        if ($success) {
                            echo "Звезда добавлена";
                        }
                        else
                            echo "Звезда не добавлена";
                    } 
                    catch (InvalidArgumentException $e) {
                        echo "Что-то пошло не так, пожалуйста проверьте корректность ввода";
                    }
                }
                else echo "Звёзда с таким названием уже есть";
            
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

        foreach (Star::find() as $star) 
        {
            array_push($data, array(
                $star->_id,
                $star->dele,
                $star->name,
                $star->weight,
                $star->age,
                $star->type_id,
                $star->solar_system_id
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