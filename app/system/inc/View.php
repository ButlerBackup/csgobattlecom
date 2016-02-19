<?php

/**
 * VIEW
 */
class View
{
    public $viewParser = false;

    /**
     * Load layout
     * @param string $layout
     */
    public function Layout($layout = 'layout')
    {
        if (!$this->viewParser) {
            ob_start("callbackParser");

            include_once(_SYSDIR_ . 'layout/' . $layout . '.php');

            ob_end_flush();
            echo Request::getParam('buffer');
        } else {
            include_once(_SYSDIR_ . 'layout/' . $layout . '.php');
        }
    }

    /**
     * Main load viewer
     */
    public function Content()
    {
        include_once(_SYSDIR_ . 'modules/' . CONTROLLER . '/views/' . ACTION . '.php');
    }

    /**
     * Load view method
     * @param $method
     */
    public function Load($method)
    {
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            //echo '!' . $method . '!';
        }
    }
}
/* End of file */