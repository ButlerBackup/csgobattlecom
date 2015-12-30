<?php
class ModuleView extends View
{
    public function leftMenu()
    {
        $path = _SYSDIR_.'modules/page/views/sub/index.php';
        if (file_exists($path))
            include_once($path);

        /*
        if (Request::getParam('user')->id) {
            $path = _SYSDIR_.'modules/page/views/sub/index.php';
            if (file_exists($path))
                include_once($path);
        } else {
            $path = _SYSDIR_.'modules/page/views/sub/guest.php';
            if (file_exists($path))
                include_once($path);
        }
        */
    }

    public function rightMenu()
    {
        $path = _SYSDIR_.'modules/'.CONTROLLER.'/views/sub/'.ACTION.'.php';
        if (file_exists($path)) {
            include_once($path);
        } else {
            $path = _SYSDIR_.'modules/'.CONTROLLER.'/views/sub/index.php';
            if (file_exists($path))
                include_once($path);
        }
    }
}

/* End of file */