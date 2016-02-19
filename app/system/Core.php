<?php if (!defined('_BASEPATH_')) exit(ERROR_NO_BASEPATH);



/**

 * CORE

 */

class Core

{

    private $router = array();



    public function __construct()

    {

        error_reporting(E_ALL & ~E_NOTICE);

        //error_reporting(E_ALL);



        // Include files

        $this->includeFiles();

        // Settings

        $this->setSettings();

        // Processing routes

        $this->getRoute();

        // Run the module

        $this->dispatch();

    }



    /**

     * Include files

     */

    private function includeFiles()

    {

        include_once(_SYSDIR_ . 'system/Config.php');

        include_once(_SYSDIR_ . 'system/inc/Common.php');

        include_once(_SYSDIR_ . 'system/Function.php');

        include_once(_SYSDIR_ . 'system/inc/Ajax.php');

        include_once(_SYSDIR_ . 'system/inc/Request.php');

        include_once(_SYSDIR_ . 'system/inc/Lang.php');

        include_once(_SYSDIR_ . 'system/inc/Call.php');

        include_once(_SYSDIR_ . 'system/inc/Form.php');

        include_once(_SYSDIR_ . 'system/inc/Imap.php');

        include_once(_SYSDIR_ . 'system/inc/Pagination.php');

        include_once(_SYSDIR_ . 'system/db/Mysqli.php');

        include_once(_SYSDIR_ . 'system/inc/Controller.php');

        include_once(_SYSDIR_ . 'system/inc/Model.php');

        include_once(_SYSDIR_ . 'system/inc/View.php');

        include_once(_SYSDIR_ . 'system/inc/File.php');

    }



    /**

     * Зпуск до выполнения модуля

     */

    private function preDispatch()

    {

        incFile('system/preDispatch.php');

    }



    /**

     * Зпуск после выполнения модуля

     */

    private function postDispatch()

    {

        incFile('system/postDispatch.php');

    }



    /**

     * Run the module

     */

    private function dispatch()

    {

        // Путь к модулю

        $path = _SYSDIR_ . 'modules/' . CONTROLLER . '/system/Controller.php';



        if (file_exists($path))

            include_once($path);

        else

            error404('ERROR 404');



        // Зпуск до выполнения модуля

        $this->preDispatch();



        $controllerName = ucfirst(CONTROLLER) . 'Controller';

        $actionName = ACTION . 'Action';



        $controller = new $controllerName;



        if (method_exists($controller, $actionName)) {

            // Обработка

            $controller->processing();

            // Вызываем метод

            $controller->$actionName();

            // Вызываем вывод страницы

            $controller->printOut();

            // Зпуск после выполнения модуля

            $this->postDispatch();

        } else {

            error404('ERROR 404');

        }

    }



    /**

     * Processing routes

     */

    private function getRoute()

    {

        $route = array();

        include_once(_SYSDIR_ . 'system/Routes.php');



        $router = null;

        $uri = mb_strtolower(trim(_URI_, '/'));



        $uriPos = mb_strpos($uri, '?');

        if ($uriPos !== false)

            $uri = mb_substr($uri, 0, $uriPos);



        // Проверка заданых путей

        foreach ($route as $value) {

            if (preg_match($value['pattern'], $uri, $matches)) {

                $router['options'] = array();

                $router['uri'] = array();

                $uri = trim(mb_substr($uri, mb_strlen($matches[0])), '/');

                unset($matches[0]);

                $router['controller'] = $value['controller'];

                $router['action'] = $value['action'];

                $router['options'] = array_values($matches);

                if (!empty($uri))

                    $router['uri'] = explode('/', $uri);

                break;

            }

        }



        // Если нет заданых путей(Routes) 

        if (!is_array($router)) {

            $uriArray = explode('/', $uri);

            $uriCount = count($uriArray);

            $router['options'] = array();

            $router['uri'] = array();



            if ($uriCount < 3) {

                if (!empty($uriArray[0]))

                    $router['controller'] = $uriArray[0];

                else

                    $router['controller'] = DEFAULT_CONTROLLER;



                if (!empty($uriArray[1]))

                    $router['action'] = $uriArray[1];

                else

                    $router['action'] = DEFAULT_ACTION;

            } else {

                $router['controller'] = $uriArray[0];

                $router['action'] = $uriArray[1];

                unset($uriArray[0]);

                unset($uriArray[1]);

                $router['uri'] = array_values($uriArray);

            }

        }



        // Delete array route

        unset($route);





        define('CONTROLLER', $router['controller']);

        define('ACTION', $router['action']);



        if (is_array($router['options']))

            Request::setUriOptions($router['options']);



        if (is_array($router['uri']))

            Request::setUri($router['uri']);



        $this->router = $router;

    }



    /**

     * Settings

     */

    private function setSettings()

    {

        // Session

        if (SESSION_SWITCH === true) {

            session_set_cookie_params(3600 * 24 * 2);

            session_start();

        }



        // Delete $_GET

        if (GET_SWITCH === false)

            unset($_GET);



        // Mb encoding

        if (CHARSET)

            mb_internal_encoding(CHARSET);

    }

}



/* End of file */