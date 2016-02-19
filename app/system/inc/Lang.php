<?php

/**
 * LANG
 */
class Lang
{
    static public $array = array();
    static public $language = null;

    static public function setLanguage($lang = LANGUAGE)
    {
        if (!$lang)
            $lang = LANGUAGE;

        self::$language = $lang;

        $mainLang = @parse_ini_file(_SYSDIR_ . 'lang/' . $lang . '.ini');
        if (!$mainLang)
            $mainLang = array();

        $moduleLang = @parse_ini_file(_SYSDIR_ . 'modules/' . CONTROLLER . '/lang/' . $lang . '.ini');
        if (!$moduleLang)
            $moduleLang = array();

        self::$array = @array_merge($mainLang, $moduleLang);

        //self::$array = parse_ini_file(_SYSDIR_.'lang/'.$lang.'.ini');
        //self::$array = array_merge(self::$array, parse_ini_file(_SYSDIR_.'modules/'.CONTROLLER.'/lang/'.$lang.'.ini'));
    }

    static public function translate($key)
    {
        if (array_key_exists($key, self::$array))
            return self::$array[$key];
        else
            return $key;
    }
}
/* End of file */