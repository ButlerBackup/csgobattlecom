<?php

/**
 * CALL
 */
class Call
{
    /**
     * Function form
     * @param $name
     * @param bool $path
     * @return object
     */
    static public function form($name, $path = false)
    {
        $name = mb_ucfirst(mb_strtolower($name));
        $className = $name . 'Form';

        if ($path === false)
            $path = 'modules/' . CONTROLLER . '/system/form/' . $name . '.php';

        incFile($path);

        return new $className;
    }
}
/* End of file */