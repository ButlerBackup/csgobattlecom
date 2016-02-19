<?php
/**
 * Function error404
 * @param null $message
 */
function error404($message = null)
{
    echo '<center><h1>NOT FOUND</h1></center>';
    echo '<center>'.$message.'</center>';
    exit;
}

/**
 * Function include
 * @param $path
 */
function incFile($path)
{
    include_once(_SYSDIR_.$path);
}

/**
 * Function redirect
 * @param $url
 */
function redirect($url)
{
    header('Location: '.$url);
    exit;
}

/**
 * Function print_data (debug function)
 * @param $data
 */
function print_data($data)
{
    echo '<hr/><pre>';
    print_r($data);
    echo '</pre><hr/>';
}

/**
 * Function filter
 * @param $data
 * @param bool|string $mode
 * @return array|int|mixed|string
 */
function filter($data, $mode = true)
{
    if ($mode === false) {
        return $data;
    } else {
        if (!is_array($data)) {
            if ($mode == 'string' OR $mode === true) {
                $data = reFilter($data);
                $data = str_replace("\0", "", trim($data));
                $data = htmlentities($data, ENT_QUOTES, "UTF-8");
                if (Mysqli_DB::$_db)
                    $data = Mysqli_DB::$_db->escape_string($data);
                //$data = htmlspecialchars($data);
                //$data = addslashes($data);
                return $data;
            } else
                return intval($data);
        } else {
            foreach ($data as $key => $value)
                $data[$key] = filter($value, $mode);

            return $data;
        }
    }
}

/**
 * @param $data
 * @return string
 */
function reFilter($data)
{
    $data = html_entity_decode($data, ENT_QUOTES);
    //$data = addslashes($data);

    return $data;
}

/**
 * @param $data
 * @param $mySearch
 * @return mixed
 */
function myReplace($data, $mySearch = false)
{
    if ($mySearch === false) {
        $mySearch['&#039;'] = "\'";
        //$mySearch['"'] = '&quot;';
    }

    foreach ($mySearch as $key => $value)
    {
        $search[] = $key;
        $replace[] = $value;
    }

    $data = str_replace($search, $replace, $data);

    return $data;
}

/**
 * Function post
 * @param $name
 * @param bool|string $mode
 * @return array|bool|mixed|string
 */
function post($name, $mode = true)
{
    if (isset($_POST[$name]))
        return filter($_POST[$name], $mode);
    else
        return false;
}

/**
 * Function isPost
 * @return bool
 */
function isPost()
{
    if (count($_POST) > 0)
        return true;

    return false;
}

/**
 * Function allPost
 * @param bool|string $mode
 * @return array|int|mixed|string
 */
function allPost($mode = true)
{
    return filter($_POST, $mode);
}

/**
 * Function get
 * @param $name
 * @param bool|string $mode
 * @return array|bool|int|mixed|string
 */
function get($name, $mode = true)
{
    if (isset($_GET[$name]))
        return filter($_GET[$name], $mode);
    else
        return false;
}

/**
 * Function getSession
 * @param $name
 * @param bool|string $mode
 * @return array|bool|int|mixed|string
 */
function getSession($name, $mode = true)
{
    if (isset($_SESSION[$name]))
        return filter($_SESSION[$name], $mode);
    else
        return false;
}

/**
 * Function setSession
 * @param $name
 * @param $value
 * @param bool|string $mode
 * @return array|int|mixed|string
 */
function setSession($name, $value, $mode = true)
{
    return $_SESSION[$name] = filter($value, $mode);
}

/**
 * Function unsetSession
 * @param $name
 * @return bool
 */
function unsetSession($name)
{
    $_SESSION[$name] = false;
    unset($_SESSION[$name]);
    return true;
}

/**
 * Function getCookie
 * @param $name
 * @param $value
 * @param $time
 * @param bool|string $mode
 * @return bool
 */
function setMyCookie($name, $value, $time, $mode = true)
{
    return setCookie($name, filter($value, $mode), $time, '/');
}

/**
 * Function getCookie
 * @param $name
 * @param bool|string $mode
 * @return array|bool|int|mixed|string
 */
function getCookie($name, $mode = true)
{
    if (isset($_COOKIE[$name]))
        return filter($_COOKIE[$name], $mode);
    else
        return false;
}

/**
 * Function unsetCookie
 * @param $name
 * @return mixed
 */
function unsetCookie($name)
{
    SetCookie($name, '', time() - 3600, '/');
    SetCookie($name);
    unset($_COOKIE[$name]);
    return $name;
}

/**
 * Function checkEmail
 * @param $email
 * @return bool
 */
function checkEmail($email)
{
    if (!preg_match("~^([a-z0-9_\.\-]{1,})@([a-z0-9\.\-]{1,20})\.([a-z\.]{2,6})+$~i", trim($email)))
        return false;
    else
        return true;
}

/**
 * Function checkURL
 * @param string $url
 * @return bool
 */
function checkURL($url)
{
    if (preg_match("~^(https?:\/\/)?([0-9a-z][0-9a-z\-]+\.[0-9a-z]+)+$~i", trim($url)))
        return true;
    else
        return false;
}

/**
 * Function checkLenght
 * @param $text
 * @param int $min
 * @param bool $max
 * @return bool
 */
function checkLenght($text, $min = 0, $max = FALSE)
{
    $lenght = mb_strlen($text);

    if ($lenght >= $min) {
        if ($max != false AND $lenght > $max)
            return false;

        return true;
    } else {
        return false;
    }
}

/**
 * Function printTime
 * @param $time
 * @param null $format
 * @return string
 */
function printTime($time, $format = null)
{
    if (is_null($format))
        $format = "H:i:s / m.d.Y";

    return date($format, $time);
}

/**
 * Function randomHash
 * @return string
 */
function randomHash()
{
    return md5(time().'_'.rand(1000000, 9999999));
}

if (!function_exists('url')) {
    /**
     * Function createURL
     * @return string
     */
    function url()
    {
        $url = '';

        if (func_num_args() > 0)
            $url = implode('/', func_get_args());

        $url = ltrim($url, '/');

        return _DIR_.$url;
    }
}

if (!function_exists('mb_ucfirst')) {
    /**
     * Function mb_ucfirst
     * @param $string
     * @return string
     */
    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1, mb_strlen($string));
    }
}

/**
 * Function printError
 * @param $error
 * @param bool $prefix
 * @param string $separate
 * @return string
 */
function printError($error, $prefix = false, $separate = 'p')
{
    $data = '';
    if ($error) {
        if (is_array($error)) {
            foreach ($error as $value)
            {
                $data .= '<'.$separate.'>';
                $data .= Lang::translate($prefix.$value);
                $data .= '</'.$separate.'>';
            }
        } else {
            $data .= '<'.$separate.'>';
            $data .= Lang::translate($prefix.$error);
            $data .= '</'.$separate.'>';
        }
    }

    return $data;
}

/**
 * Function setNotice
 * @param $value
 * @param string $name
 * @param bool|string $mode
 */
function setNotice($value, $name = ACTION, $mode = true)
{
    setSession(mb_strtoupper($name.'_'), $value, $mode);
}

/**
 * Function getNotice
 * @param string $name
 * @param bool $prefix
 * @param bool|string $mode
 * @param string $separate
 * @return string
 */
function getNotice($name = ACTION, $prefix = false, $mode = true, $separate = 'p')
{
    $name = mb_strtoupper($name.'_');
    $prefix = mb_strtoupper($prefix);
    $array = getSession($name, $mode);
    $data = '';

    if (!$array)
        return false;

    if (is_array($array)) {
        foreach ($array as $value)
        {
            $data .= '<'.$separate.'>';
            $data .= Lang::translate($prefix.$value);
            $data .= '</'.$separate.'>';
        }
    } else {
        $data .= '<'.$separate.'>';
        $data .= Lang::translate($prefix.$array);
        $data .= '</'.$separate.'>';
    }
    unsetSession($name);

    return $data;
}

/**
 * Function callbackParser
 * @param $buffer
 */
function callbackParser($buffer)
{
    $arr1 = array();
    $arr2 = array();
    preg_match_all("~{([0-9A-Z_]{1,}):([0-9A-Za-z_//#]+)}~", $buffer, $m);


    foreach ($m[1] as $key => $value)
    {
        if ($value == 'L') {
            $arr1[] = '{'.$value.':'.$m[2][$key].'}';
            $arr2[] = Lang::translate($m[2][$key]);
        } elseif ($value == 'URL') {
            $arr1[] = '{'.$value.':'.$m[2][$key].'}';
            $arr2[] = url($m[2][$key]);
        }
    }

    $ar = str_replace($arr1, $arr2, $buffer);
    Request::setParam('buffer', $ar);
    Request::setParam('buf', $m);
}

/**
 * Function recursive mkdir
 * @param $path
 * @param string $chmod
 * @return bool
 */
function remkdir($path, $chmod = '0777') {
    $array = explode('/', trim($path, '/'));
    $s = _SYSDIR_;

    foreach ($array as $value) {
        $s .= $value.'/';
        if (!is_dir($s)) {
            @mkdir($s, 0777);
            @chmod($s, 0777);
        }
    }

    return true;
}

/**
 * Function get_contents
 * @param $url
 * @param bool $cookie
 * @return mixed|string|type
 */
function get_contents($url, $cookie = false) {
    if (!$cookie)
        $cookie = tempnam("/tmp", "CURLCOOKIE");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 15);
    curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36');
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    }
    curl_setopt($ch, CURLOPT_HEADER, false);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    if ($info['http_code'] == 301 || $info['http_code'] == 302) {
        $url = $info['redirect_url'];
        $url_parsed = parse_url($url);
        return (isset($url_parsed))? get_contents($url, $cookie) : '';
    }

    return $output;
}

/**
 * Function getYouTube
 * @param $id
 * @param string $part
 * @return mixed
 */
function getYouTube($id, $part = 'snippet,contentDetails,statistics,status')
{
    $url = 'https://www.googleapis.com/youtube/v3/videos?id='.$id.'&key='.YOUTUBE_DEVELOPER_KEY.'&part='.$part;
    $result = get_contents($url);
    return json_decode($result);
}

/**
 * Function checkYouTubeURL
 * @param $url
 * @return bool
 */
function checkYouTubeURL($url)
{
    if (stripos($url, 'youtube.com') !== false) {
        preg_match('#v=([^\&]+)#is', $url, $m);
        $videoID = $m[1];
    } elseif (stripos($url, 'youtu.be') !== false) {
        preg_match('#/([^?\/]{11})#is', $url, $m);
        $videoID = $m[1];
    } else
        $videoID = false;

    if ($videoID)
        return $videoID;
    else
        return false;
}

/* End of file */