<?php

/**
 * REQUEST
 *
 */
class Request
{
    static public $uriOptions = array();

    static public $uri = array();

    static public $role;

    static public $param = array();

    /**
     * @param $value
     */
    static public function setUriOptions($value)
    {
        self::$uriOptions = $value;
    }

    /**
     * @param bool $num
     * @return array
     */
    public static function getUriOptions($num = false)
    {
        if ($num === false)
            return self::$uriOptions;
        else
            return self::$uriOptions[$num];
    }

    /**
     * @param $value
     */
    static public function setUri($value)
    {
        self::$uri = $value;
    }

    /**
     * @param bool $num
     * @return array
     */
    public static function getUri($num = false)
    {
        if ($num === false)
            return self::$uri;
        else
            return self::$uri[$num];
    }

    /**
     * @param $value
     */
    static public function setRole($value)
    {
        self::$role = $value;
    }

    /**
     * @return mixed
     */
    static public function getRole()
    {
        return self::$role;
    }

    /**
     * @param $key
     * @param $value
     */
    static public function setParam($key, $value)
    {
        self::$param[$key] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    static public function getParam($name)
    {
        return self::$param[$name];
    }

    /**
     * @return array
     */
    static public function getAllParam()
    {
        return self::$param;
    }

}
/* End of file */