<?php

/**
 * CONTROLLER
 */
class Controller
{
    protected $view;
    protected $layout = 'layout';

    public function __construct()
    {
    }

    public function processing()
    {
        $permission = array();
        $action = false;
        require_once(_SYSDIR_ . 'modules/' . CONTROLLER . '/system/Permission.php');

        if (array_key_exists(ACTION, $permission))
            $action = ACTION;
        elseif (array_key_exists('*', $permission))
            $action = '*';

        if ($action !== false) {
            if (array_key_exists(Request::getRole(), $permission[$action])) {
                if ($permission[$action][Request::getRole()]['allow'] === false) {
                    if (array_key_exists('redirect', $permission[$action][Request::getRole()]))
                        redirect($permission[$action][Request::getRole()]['redirect']);
                    elseif (array_key_exists('redirect', $permission[$action]['*']))
                        redirect($permission[$action]['*']['redirect']);
                }
            } elseif (array_key_exists('*', $permission[$action])) {
                if ($permission[$action]['*']['allow'] === false)
                    redirect($permission[$action]['*']['redirect']);
            }
        }

        require_once(_SYSDIR_ . 'modules/' . CONTROLLER . '/system/Model.php');


        $pathView = _SYSDIR_ . 'modules/' . CONTROLLER . '/system/View.php';

        if (file_exists($pathView)) {
            include_once($pathView);
            $this->view = new ModuleView;
        } else {
            $this->view = new View;
        }
    }

    // Call layout
    public function printOut()
    {
        $this->view->Layout($this->layout);
    }

    // Set layout
    protected function setLayout($layout = 'layout')
    {
        $this->layout = $layout;
    }
}
/* End of file */