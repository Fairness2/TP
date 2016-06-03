<?php
header("Content-Type: text/html; charset=utf-8");

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;

try {

    // Регистрируем автозагрузчик
    $loader = new Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/'
    ))->register();

    // Создаем DI
    $di = new FactoryDefault();

     // Настраиваем сервис для работы с БД
    $di->set('mongo', function () {
        $mongo = new MongoClient();
        return $mongo->selectDB("tptptp");
    }, true);
    
    $di->set('collectionManager', function(){
        return new Phalcon\Mvc\Collection\Manager();
    }, true);

    // Настраиваем компонент View
    $di->set('view', function () {
        $view = new View();
        $view->setViewsDir('../app/views/');
        return $view;
    });

    // Настраиваем базовый URI так, чтобы все генерируемые URI содержали директорию "tutorial"
    $di->set('url', function () {
        $url = new UrlProvider();
        $url->setBaseUri('');
        return $url;
    });

    
    // Обрабатываем запрос
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
     echo "Exception: ", $e->getMessage();
}

?>