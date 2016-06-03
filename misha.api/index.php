<?php

header('Content-Type: application/json; charset=utf-8');

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;

// Используем Loader() для автозагрузки нашей модели
$loader = new Loader();

$loader->registerDirs(
    array(
        __DIR__ . '/models/'
    )
)->register();

$di = new FactoryDefault();

// Настраиваем сервис базы данных
$di->set('db', function () {
    return new PdoMysql(
        array(
            "host"     => "localhost",
            "username" => "root",
            "password" => "",
            "dbname"   => "mishaapi",
            'charset'  => 'utf8'
        )
    );
});

// Создаем и привязываем DI к приложению
$app = new Micro($di);

// Тут определяются маршруты

// Получение всех
$app->get('/{talbe:[a-z]+}/{page:[0-9]+}', function ($table, $page) use ($app) {

    $options = array(
            "del = 0",
            "limit"      => 10,
            "offset"     => ($page - 1) * 10,
        );
    
    if ($table == "car") {
        
        $objects = Car::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'       => $obj->id,
                'dealer'   => $obj->dealer->name,
                'driver'   => $obj->driver->name,
                'owner'    => $obj->owner->name,
                'model'    => $obj->model,
                'capacity' => $obj->capacity
            );
        }
    } elseif ($table == "dealer") {
        
        $objects = Dealer::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'   => $obj->id,
                'name' => $obj->name
            );
        }
    } elseif ($table == "driver") {
        
        $objects = Driver::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'         => $obj->id,
                'name'       => $obj->name,
                'experience' => $obj->experience, 
                'salary'     => $obj->salary
            );
        }
    } elseif ($table == "organization") {
        
        $objects = Organization::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'      => $obj->id,
                'name'    => $obj->name,
                'address' => $obj->address, 
            );
        }
    } elseif ($table == "owner") {
        
        $objects = Owner::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'   => $obj->id,
                'name' => $obj->name
            );
        }
    } elseif ($table == "product") {
        
        $objects = Product::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'     => $obj->id,
                'name'   => $obj->name,
                'weight' => $obj->weight,
                'type'   => $obj->producttype->name
            );
        }
    } elseif ($table == "producttype") {
        
        $objects = ProductType::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'     => $obj->id,
                'name'   => $obj->name
            );
        }
    } elseif ($table == "shipment") {
        
        $objects = Shipment::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'                    => $obj->id,
                'product'               => $obj->product->name,
                'transportation_number' => $obj->transportation->id,
                'amount'                => $obj->amount
            );
        }
    } elseif ($table == "store") {
        
        $objects = Store::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'     => $obj->id,
                'name'   => $obj->name,
                'owner'  => $obj->owner->name
            );
        }
    } elseif ($table == "transport") {
        
        $objects = Transportation::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'           => $obj->id,
                'car'          => $obj->car->model,
                'organization' => $obj->organization->name,
                'store'        => $obj->store->name,
                'date'         => $obj->date
            );
        }
    }
    
    if ($objects == false) {
        echo json_encode(
            array(
                'status' => 'Не_найдено',
                'data'   => $data
            ), 
            JSON_UNESCAPED_UNICODE
        );
    } else {
        echo json_encode(
            array(
                'status' => 'Найдено',
                'data'   => $data
            ), 
            JSON_UNESCAPED_UNICODE
        );
    }
});

// Поиск владельцев с $name в названии
/*
$app->get('/{table:[a-z]+}/search/name/{name}/{page:[0-9]+}', function ($table, $name, $page) use ($app) {
    
    $options = array(
            "del = 0",
            "conditions" => "name LIKE '{$name}'",
            "limit"      => 10,
            "offset"     => ($page - 1) * 10,
        );

    if ($table == "car") {
        
        $objects = Car::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'       => $obj->id,
                'dealer'   => $obj->dealer->name,
                'driver'   => $obj->driver->name,
                'owner'    => $obj->owner->name,
                'model'    => $obj->model,
                'capacity' => $obj->capacity
            );
        }
    } elseif ($table == "dealer") {
        
        $objects = Dealer::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'   => $obj->id,
                'name' => $obj->name
            );
        }
    } elseif ($table == "driver") {
        
        $objects = Driver::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'         => $obj->id,
                'name'       => $obj->name,
                'experience' => $obj->experience, 
                'salary'     => $obj->salary
            );
        }
    } elseif ($table == "organization") {
        
        $objects = Organization::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'      => $obj->id,
                'name'    => $obj->name,
                'address' => $obj->address, 
            );
        }
    } elseif ($table == "owner") {
        
        $objects = Owner::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'   => $obj->id,
                'name' => $obj->name
            );
        }
    } elseif ($table == "product") {
        
        $objects = Product::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'     => $obj->id,
                'name'   => $obj->name,
                'weight' => $obj->weight,
                'type'   => $obj->producttype->name
            );
        }
    } elseif ($table == "producttype") {
        
        $objects = ProductType::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'     => $obj->id,
                'name'   => $obj->name
            );
        }
    } elseif ($table == "shipment") {
        
        $objects = Shipment::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'                    => $obj->id,
                'product'               => $obj->product->name,
                'transportation_number' => $obj->transportation->id,
                'amount'                => $obj->amount
            );
        }
    } elseif ($table == "store") {
        
        $objects = Store::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'     => $obj->id,
                'name'   => $obj->name,
                'owner'  => $obj->owner->name
            );
        }
    } elseif ($table == "transport") {
        
        $objects = Transportation::find($options);
        
        foreach ($objects as $obj) {
        $data[] = array(
                'id'           => $obj->id,
                'car'          => $obj->car->model,
                'organization' => $obj->organization->name,
                'store'        => $obj->store->name,
                'date'         => $obj->date
            );
        }
    }

    if ($objects == false) {
        echo json_encode(
            array(
                'status' => 'Не_найдено',
                'data'   => $data
            ), 
            JSON_UNESCAPED_UNICODE
        );
    } else {
        echo json_encode(
            array(
                'parameters' => array('table' => $table, 'name' => $name, 'page' => $page),
                'status' => 'Найдено',
                'data'   => $data
            ), 
            JSON_UNESCAPED_UNICODE
        );
    }
});
*/

// Получение по первичному ключу
$app->get('/{table:[a-z]+}/search/id/{id:[0-9]+}', function ($table, $id) use ($app) {

    $options = array(
            "id = {$id}",
            "del = 0",
        );
    
    if ($table == "car") {
        
        $obj = Car::findFirst($options);
        
        $data = array(
                'id'       => $obj->id,
                'dealer'   => $obj->dealer->name,
                'driver'   => $obj->driver->name,
                'owner'    => $obj->owner->name,
                'model'    => $obj->model,
                'capacity' => $obj->capacity
            );
    } elseif ($table == "dealer") {
        
        $obj = Dealer::findFirst($options);
        
        $data = array(
                'id'   => $obj->id,
                'name' => $obj->name
            );
    } elseif ($table == "driver") {
        
        $obj = Driver::findFirst($options);
        
        $data = array(
                'id'         => $obj->id,
                'name'       => $obj->name,
                'experience' => $obj->experience, 
                'salary'     => $obj->salary
            );
    } elseif ($table == "organization") {
        
        $obj = Organization::findFirst($options);
        
        $data = array(
                'id'      => $obj->id,
                'name'    => $obj->name,
                'address' => $obj->address, 
            );
    } elseif ($table == "owner") {
        
        $obj = Owner::findFirst($options);
        
        $data = array(
                'id'   => $obj->id,
                'name' => $obj->name
            );
    } elseif ($table == "product") {
        
        $obj = Product::findFirst($options);
        
        $data = array(
                'id'     => $obj->id,
                'name'   => $obj->name,
                'weight' => $obj->weight,
                'type'   => $obj->producttype->name
            );
    } elseif ($table == "producttype") {
        
        $obj = ProductType::findFirst($options);
        
        $data = array(
                'id'     => $obj->id,
                'name'   => $obj->name
            );
    } elseif ($table == "shipment") {
        
        $obj = Shipment::findFirst($options);
        
        $data = array(
                'id'                    => $obj->id,
                'product'               => $obj->product->name,
                'transportation_number' => $obj->transportation->id,
                'amount'                => $obj->amount
            );
    } elseif ($table == "store") {
        
        $obj = Store::findFirst($options);
        
        $data = array(
                'id'     => $obj->id,
                'name'   => $obj->name,
                'owner'  => $obj->owner->name
            );
    } elseif ($table == "transport") {
        
        $obj = Transportation::findFirst($options);
        
        $data = array(
                'id'           => $obj->id,
                'car'          => $obj->car->model,
                'organization' => $obj->organization->name,
                'store'        => $obj->store->name,
                'date'         => $obj->date
            );
    }
    
    if ($obj == false) {
        echo json_encode(
            array(
                'status' => 'Не_найдено',
                'data'   => $data
            ), 
            JSON_UNESCAPED_UNICODE
        );
    } else {
        echo json_encode(
            array(
                'status' => 'Найдено',
                'data'   => $data
            ), 
            JSON_UNESCAPED_UNICODE
        );
    }
});

// Добавление нового
$app->post('/{table:[a-z]+}', function ($table) use ($app) {
    
    if ($table == "car") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Car (
                dealer_id, 
                driver_id, 
                owner_id, 
                model, 
                capacity
            ) VALUES (
                :dealer_id:, 
                :driver_id:, 
                :owner_id:, 
                :model:, 
                :capacity:
            )", 
            array(
                'dealer_id' => $app->request->getJsonRawBody()->dealer_id,
                'driver_id' => $app->request->getJsonRawBody()->driver_id,
                'owner_id'  => $app->request->getJsonRawBody()->owner_id,
                'model'     => $app->request->getJsonRawBody()->model,
                'capacity'  => $app->request->getJsonRawBody()->capacity
        ));
    } elseif ($table == "dealer") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Dealer (name) VALUES (:name:)", 
            array(
                'name' => $app->request->getJsonRawBody()->name
        ));
    } elseif ($table == "driver") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Driver (
                name, 
                experience, 
                salary
            ) VALUES (
                :name:, 
                :experience:, 
                :salary:
            )", 
            array(
                'name'       => $app->request->getJsonRawBody()->name,
                'experience' => $app->request->getJsonRawBody()->experience,
                'salary'     => $app->request->getJsonRawBody()->salary
        ));
    } elseif ($table == "organization") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Organization (
                name,
                address
            ) VALUES (
                :name:,
                :address:
            )", 
            array(
                'name'    => $app->request->getJsonRawBody()->name,
                'address' => $app->request->getJsonRawBody()->address,
        ));
    } elseif ($table == "owner") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Owner (name) VALUES (:name:)", 
            array(
                'name' => $app->request->getJsonRawBody()->name
        ));
    } elseif ($table == "product") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Product (
                name, 
                weight, 
                type_id
            ) VALUES (
                :name:, 
                :weight:, 
                :type_id:
            )", 
            array(
                'name'    => $app->request->getJsonRawBody()->name,
                'weight'  => $app->request->getJsonRawBody()->weight,
                'type_id' => $app->request->getJsonRawBody()->type_id
        ));
    } elseif ($table == "producttype") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO ProductType (name) VALUES (:name:)", 
            array(
                'name' => $app->request->getJsonRawBody()->name
        ));
    } elseif ($table == "shipment") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Shipment (
                product_id, 
                transportation_id, 
                amount
            ) VALUES (
                :product_id:, 
                :transportation_id:, 
                :amount:
            )", 
            array(
                'product_id'        => $app->request->getJsonRawBody()->product_id,
                'transportation_id' => $app->request->getJsonRawBody()->transportation_id,
                'amount'            => $app->request->getJsonRawBody()->amount
        ));
    } elseif ($table == "store") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Store (
                name, 
                owner_id 
            ) VALUES (
                :name:, 
                :owner_id:
            )", 
            array(
                'name'      => $app->request->getJsonRawBody()->name,
                'owner_id'  => $app->request->getJsonRawBody()->owner_id
        ));
    } elseif ($table == "transport") {
        
        $status = $app->modelsManager->executeQuery(
            "INSERT INTO Transportation (
                car_id, 
                organization_id, 
                store_id,
                date
            ) VALUES (
                :car_id:, 
                :organization_id:, 
                :store_id:,
                :date:
            )", 
            array(
                'car_id'          => $app->request->getJsonRawBody()->car_id,
                'organization_id' => $app->request->getJsonRawBody()->organization_id,
                'store_id'        => $app->request->getJsonRawBody()->store_id,
                'date'            => $app->request->getJsonRawBody()->date
        ));
    }

    // Формируем ответ
    $response = new Response();

    // Проверяем, что вставка произведена успешно
    if ($status->success() == true) {

        // Меняем HTTP статус
        $response->setStatusCode(201, "Created");

        $response->setJsonContent(
            array(
                'status' => 'CREATED'
            )
        );

    } else {

        // Меняем HTTP статус
        $response->setStatusCode(409, "Conflict");

        // Отправляем сообщение об ошибке клиенту
        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(
            array(
                'status'   => 'ERROR',
                'messages' => $errors
            )
        );
    }

    $response->setContentType('application/json', 'UTF-8');
    return $response;
    
});


// Обновление по первичному ключу
$app->put('/{table:[a-z]+}/{id:[0-9]+}', function ($table, $id) use ($app) {

    if ($table == "car") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Car SET 
                dealer_id = :dealer_id:, 
                driver_id = :driver_id:, 
                owner_id = :owner_id:, 
                model = :model:, 
                capacity = :capacity:
            WHERE id = :id: and del = 0",
            array(
                'id'        => $id,
                'dealer_id' => $app->request->getJsonRawBody()->dealer_id,
                'driver_id' => $app->request->getJsonRawBody()->driver_id,
                'owner_id'  => $app->request->getJsonRawBody()->owner_id,
                'model'     => $app->request->getJsonRawBody()->model,
                'capacity'  => $app->request->getJsonRawBody()->capacity
        ));
    } elseif ($table == "dealer") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Dealer SET name = :name: WHERE id = :id: and del = 0", 
            array(
                'id'   => $id,
                'name' => $app->request->getJsonRawBody()->name
        ));
    } elseif ($table == "driver") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Driver SET
                name = :name:, 
                experience = :experience:, 
                salary = :salary: 
            WHERE id = :id: and del = 0",
            array(
                'id'         => $id,
                'name'       => $app->request->getJsonRawBody()->name,
                'experience' => $app->request->getJsonRawBody()->experience,
                'salary'     => $app->request->getJsonRawBody()->salary
        ));
    } elseif ($table == "organization") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Organization SET
                name = :name:,
                address = :address:
            WHERE id = :id: and del = 0", 
            array(
                'id'      => $id,
                'name'    => $app->request->getJsonRawBody()->name,
                'address' => $app->request->getJsonRawBody()->address,
        ));
    } elseif ($table == "owner") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Owner SET name = :name: WHERE id = :id: and del = 0", 
            array(
                'id'   => $id,
                'name' => $app->request->getJsonRawBody()->name
        ));
    } elseif ($table == "product") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Product SET
                name = :name:, 
                weight = :weight:, 
                type_id = :type_id:
            WHERE id = :id: and del = 0",
            array(
                'id'      => $id,
                'name'    => $app->request->getJsonRawBody()->name,
                'weight'  => $app->request->getJsonRawBody()->weight,
                'type_id' => $app->request->getJsonRawBody()->type_id
        ));
    } elseif ($table == "producttype") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE ProductType SET name = :name: WHERE id = :id: and del = 0", 
            array(
                'id'   => $id,
                'name' => $app->request->getJsonRawBody()->name
        ));
    } elseif ($table == "shipment") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Shipment SET
                product_id = :product_id:, 
                transportation_id = :transportation_id:, 
                amount = :amount:
            WHERE id = :id: and del = 0",
            array(
                'id'                => $id,
                'product_id'        => $app->request->getJsonRawBody()->product_id,
                'transportation_id' => $app->request->getJsonRawBody()->transportation_id,
                'amount'            => $app->request->getJsonRawBody()->amount
        ));
    } elseif ($table == "store") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Store SET
                name = :name:, 
                owner_id = :owner_id: 
            WHERE id = :id: and del = 0",
            array(
                'id'        => $id,
                'name'      => $app->request->getJsonRawBody()->name,
                'owner_id'  => $app->request->getJsonRawBody()->owner_id
        ));
    } elseif ($table == "transport") {
        
        $status = $app->modelsManager->executeQuery(
            "UPDATE Transportation SET
                car_id = :car_id:, 
                organization_id = :organization_id:, 
                store_id = :store_id:,
                date = :date:
            WHERE id = :id: and del = 0", 
            array(
                'id'              => $id,
                'car_id'          => $app->request->getJsonRawBody()->car_id,
                'organization_id' => $app->request->getJsonRawBody()->organization_id,
                'store_id'        => $app->request->getJsonRawBody()->store_id,
                'date'            => $app->request->getJsonRawBody()->date
        ));
    }

    // Формируем ответ
    $response = new Response();

    // Проверяем, что обновление произведено успешно
    if ($status->success() == true) {
        $response->setJsonContent(
            array(
                'status' => 'UPDATED'
            )
        );
    } else {

        // Меняем HTTP статус
        $response->setStatusCode(409, "Conflict");

        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(
            array(
                'status'   => 'ERROR',
                'messages' => $errors
            )
        );
    }

    $response->setContentType('application/json', 'UTF-8');
    return $response;
});


// Удаление по первичному ключу
$app->delete('/{table:[a-z]+}/{id:[0-9]+}', function ($table, $id) use ($app) {
    
    if ($table == "car") {
        
        $status = $app->modelsManager->executeQuery("UPDATE Car SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "dealer") {
        
        $status = $app->modelsManager->executeQuery("UPDATE Dealer SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "driver") {
        
        $status = $app->modelsManager->executeQuery("UPDATE Driver SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "organization") {

        $status = $app->modelsManager->executeQuery("UPDATE Organization SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "owner") {
        
        $status = $app->modelsManager->executeQuery("UPDATE Owner SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "product") {
        
        $status = $app->modelsManager->executeQuery("UPDATE Product SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "producttype") {
        
        $status = $app->modelsManager->executeQuery("UPDATE ProductType SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "shipment") {
        
        $status = $app->modelsManager->executeQuery("UPDATE Shipment SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "store") {
        
        $status = $app->modelsManager->executeQuery("UPDATE Store SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    } elseif ($table == "transport") {
        
        $status = $app->modelsManager->executeQuery("UPDATE Transportation SET del = 1 WHERE id = :id: and del = 0", array(
            'id' => $id
        ));
    }

    // Формируем ответ
    $response = new Response();

    if ($status->success() == true) {
        $response->setJsonContent(
            array(
                'status' => 'DELETED'
            )
        );
    } else {

        // Меняем HTTP статус
        $response->setStatusCode(409, "Conflict");

        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(
            array(
                'status'   => 'ERROR',
                'messages' => $errors
            )
        );
    }

    $response->setContentType('application/json', 'UTF-8');
    return $response;
});

// Несуществующий маршрут
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'Неверный адрес!';
});

$app->handle();