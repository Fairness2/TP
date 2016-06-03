<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;

class ClusterController extends Controller
{
    public function indexAction()
    {
        $paginator = new PaginatorArray(
            array(
                "data"  => Cluster::find(
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

            $this->view->clusters = $paginator->getPaginate();
        }
    }

    public function DeleteAction()
    {
        if ($this->request->isPost() == true) {

            $clusters = Cluster::find(
                array(
                        array(
                            "name" => $name = $this->request->getPost("name")
                        )                        
                    )
            );

            foreach ($clusters as $cluster) {
                $cluster->Deleted(1);
                $success = $cluster->save();
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

            $cluster = Cluster::findById($this->request->getPost("id"));

            $this->view->cluster = $cluster;            

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
                'messageMaximum' => 'Вы ввели слишком большое название<br>',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
            )));
            $validation->add('kol', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Так много галактик быть не может<br>',
                'messageMinimum' => 'Так мало галактик быть не может<br>'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br>'
            )));
            $validation->add('kol', new RegexValidator(array(
               'pattern' => '/[0-9]{1,3}/',
               'message' => 'Введите кол-во правильно<br>'
            )));
            $validation->add('kol', new PresenceOf(array(
               'message' => 'Вы ввели пустое кол-во галактик<br>'
            )));
            $validation->add('id', new PresenceOf(array(
               'message' => 'Ой<br>'
            )));
            $validation->add('id', new RegexValidator(array(
                'pattern' => '/[a-z0-9]{1,50}/u',
                'message' => 'Id не правилен<br>'
            )));

            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $cluster = Cluster::find(
                array(
                        array(
                            "name" => $name = $this->request->getPost("name"),
                            "dele" => 0
                        )                        
                    )
                )/*->filter(
                    function ($cluster) {
                        global $name;
                        global $id;
                        if ($cluster->dele == 0 && $cluster->name == $name && $cluster->id != $id ) {
                            return $cluster;
                        }
                    }
                )*/;

                if (count($cluster) == 1) 
                {
                    $cluster = Cluster::findById($this->request->getPost("id"));

                    try {
                        $cluster->name = $this->request->getPost("name");
                        $cluster->size = $this->request->getPost("kol");
                        $success = $cluster->save();
                        if ($success) {
                            echo "Данные упешно изменены<br>";
                        }
                        else
                            echo "Данные не изменены<br>";
                    } 
                    catch (InvalidArgumentException $e) {
                        echo "Что-то пошло не так, пожалуйста проверьте корректность ввода<br>";
                    }
                }
                else echo "Скопление с таким названием уже есть";
            }
            else
                foreach ($messages as $message) {
                    echo $message;
                }
        }
        else
            echo "Опаньки, поста то нет<br>";

        $this->view->disable();
    }

    public function InsertAction() {}

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
                'messageMaximum' => 'Вы ввели слишком большое название<br>',
                'messageMinimum' => 'Вы ввели слишком маленькое название<br>'
            )));
            $validation->add('kol', new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => 'Так много галактик быть не может<br>',
                'messageMinimum' => 'Так мало галактик быть не может<br>'
            )));
            $validation->add('kol', new PresenceOf(array(
               'message' => 'Вы ввели пустое кол-во галактик'
            )));
            $validation->add('name', new RegexValidator(array(
               'pattern' => '/[a-zA-Zа-яА-ЯЁё0-9]{1}[a-zA-Zа-яА-ЯЁё0-9\s]{0,99}/u',
               'message' => 'Введите название правильно<br>'
            )));
            $validation->add('kol', new RegexValidator(array(
               'pattern' => '/[0-9]{1,50}/u',
               'message' => 'Введите кол-во правильно<br>'
            )));

            $messages = $validation->validate($_POST);
            if (!count($messages)) 
            {                 
                $cluster = Cluster::find(
                    array(
                        array(
                            "name" => $name = $this->request->getPost("name"),
                            "dele" => 0
                        )                        
                    )
                )/*->filter(
                    function ($cluster) {
                        global $name;
                        if ($cluster->dele == 0 && $cluster->name == $name ) {
                            return $cluster;
                        }
                    }
                )*/;

                if ($cluster == false) 
                {
                    try {
                        $cluster = new Cluster();
                        $cluster->name = $this->request->getPost("name");
                        $cluster->size = $this->request->getPost("kol");
                        $cluster->dele = 0;
                        $success = $cluster->save();
                        if ($success) {
                            echo "Скопление успешно добавлено<br>";
                        }
                        else
                            echo "Скопление не добавленно<br>";
                    } 
                    catch (InvalidArgumentException $e) {
                        echo "Что-то пошло не так, пожалуйста проверьте корректность ввода<br>";
                    }
                }
                else echo "Скопление с таким названием уже есть";
            }
            else
                foreach ($messages as $message) {
                    echo $message;
                }
        }
        else
            echo "Опаньки, поста то нет<br>";

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
                $cluster = Cluster::findById($this->request->getQuery("id"));
                $galaxis = Galaxy::find(
                    array(
                        array(
                            "dele" => 0,
                            "cluster_id" => $this->request->getQuery("id")
                        ),
                        "sort"  => array(
                        "name" => 1
                        )
                    )
                );
                $this->view->cluster_name = $cluster->name;
                $this->view->galaxis = $galaxis;  
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

        foreach (Cluster::find() as $cluster) 
        {
            array_push($data, array(
                $cluster->id,
                $cluster->dele,
                $cluster->name,
                $cluster->size,
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