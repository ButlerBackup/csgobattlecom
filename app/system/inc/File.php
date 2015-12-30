<?php
/**
 * Class Image
 */
class File
{
    /**
     * @var array
     */
    static public $allowedFileFormats = array(
        '3gp'   => true,
        '7z'    => true,
        'amr'   => true,
        'apk'   => true,
        'avi'   => true,
        'bat'   => true,
        'bmp'   => true,
        'css'   => true,
        'djvu'  => true,
        'doc'   => true,
        'docx'  => true,
        'exe'   => true,
        'flv'   => true,
        'gif'   => true,
        'html'  => true,
        'ini'   => true,
        'ipa'   => true,
        'jar'   => true,
        'jpeg'  => true,
        'jpg'   => true,
        'js'    => true,
        'midi'  => true,
        'mp3'   => true,
        'mp4'   => true,
        'pdf'   => true,
        'php'   => true,
        'png'   => true,
        'pps'   => true,
        'ppt'   => true,
        'pptx'  => true,
        'psd'   => true,
        'rar'   => true,
        'sxc'   => true,
        'tar'   => true,
        'txt'   => true,
        'wav'   => true,
        'webm'  => true,
        'wma'   => true,
        'xls'   => true,
        'xlsx'  => true,
        'xml'   => true,
        'zip'   => true
    );

    /**
     * @var array
     */
    static public $allowedImageFormats = array(
        'gif'   => true,
        'png'   => true,
        'jpg'   => true,
        'jpeg'   => true
    );

    /**
     * @var int
     */
    static public $allowedFileSize = 209715200; //200M

    /**
     * @var
     */
    static public $error;

    /**
     * Function LoadFile
     * @param $file
     * @param $path
     * @param $name
     * @return mixed
     */
    static public function LoadFile($file, $path, $name, &$error = array(), &$data = array())
    {
        $format = mb_strtolower($file['name']);
        $format = mb_substr($format, mb_strrpos($format, '.')+1);

        if (is_uploaded_file($file['tmp_name'])) {
            if ($file['size'] <= self::$allowedFileSize) {
                if (self::$allowedFileFormats[$format] === true) {
                    if (!file_exists($path) || !is_dir($path)) {
                        @mkdir($path, 0777);
                        @chmod($path, 0777);
                    }

                    if (copy($file['tmp_name'], $path.'/'.$name.'.'.$format)) {
                        $data['fileName'] = $file['name'];
                        $data['name'] = $name;
                        $data['format'] = $format;
                        $data['size'] = $file['size'];
                        $data['path'] = $path;
                        $data['url'] = $path.'/'.$name.'.'.$format;
                        return true;
                    } else {
                        self::$error = "Error while moving file to site storage.";
                        return false;
                    }
                } else {
                    self::$error = "WRONG_FORMAT";
                    return false;
                }
            } else {
                self::$error = "WRONG_SIZE";
                return false;
            }
        } else {
            self::$error = $file['error'];
            return false;
        }
    }

    static public function imageSize($path, $format = 'png')
    {
        $data = array();

        if ($format == 'jpg')
            $imageCreateFrom = 'ImageCreateFromJpeg';
        else
            $imageCreateFrom = 'ImageCreateFrom'.$format;

        // Create resource image
        $img = $imageCreateFrom($path);

        $data['height'] = imagesy($img);
        $data['width'] = imagesx($img);

        return $data;
    }


    /**
     * Function LoadImg
     * @param $file
     * @param array $data
     * @return array
     */
    static public function LoadImg($file, $data = array())
    {
        /*
        "+" - (нужно указывать)
        "!" - (необязательно указывать)
        "-" - (не нужно указывать)

        $file - файл(+) / $_FILES['name']
        $data['path'] - путь загрузки(+) / 'app/public/'

        $data['new_name'] - новое имя(!) / 'name' else random md5 hash
        $data['new_format'] - новый формат загружаймого файла(! по умолчанию jpg) / 'png'
        $data['resize'] - resize картинки(! по умолчанию 0) / 0 - no resize(сжать), 1 - обрезать не изменяя размеров, 2 - обрезать симетрически уменьшив
        $data['allowed_formats'] - разрешаемые форматы(!) / array('jpg' => true, 'gif' => false)
        $data['mkdir'] - создание пути(!) / true, false
        $data['min_size'] - min размер(!)
        $data['max_size'] - max размер(!)
        $data['new_width'] - новая ширина(!)
        $data['new_height'] - новая высота(!)
        $data['min_width'] - min ширина(!)
        $data['min_height'] - min высота(!)
        $data['max_width'] - max ширина(!)
        $data['max_height'] - max высота(!)
        $data['ratio'] - коэффициент(!)

        $data['format'] - формат загружаймого файла(-)
        $data['tmp_name'] - хранение tmp(-)
        $data['size'] - размер файла(-)
        $data['type'] - тип файла(-)
        $data['name'] - имя файла(-)
        $data['width'] - ширина картинки(-)
        $data['height'] - высота картинки(-)
        $data['error'] - код ошибки(-)
        */

        $data['error'] = 0;
        $data['format'] = mb_strtolower(mb_substr($file['name'], mb_strrpos($file['name'], '.')+1));
        $data['tmp_name'] = $file['tmp_name'];
        $data['size'] = $file['size'];
        $data['type'] = $file['type'];
        $data['name'] = $file['name'];
        $data['path'] = _SYSDIR_.trim($data['path'], '/').'/';

        if (!$data['new_name'])
            $data['new_name'] = randomHash();
        if (!$data['new_format'])
            $data['new_format'] = 'png';
        if (!$data['resize'])
            $data['resize'] = 0;
        if (!$data['allowed_formats'])
            $data['allowed_formats'] = self::$allowedImageFormats;
        if (!$data['mkdir'])
            $data['mkdir'] = true;
        if ($data['mkdir'] === true)
            remkdir($data['path']);

        if ($data['allowed_formats'][$data['format']] !== true)
            $data['error'] = 10;

        if ($data['min_size'] && intval($data['min_size']) < $data['size'])
            $data['error'] = 20;

        if ($data['max_size'] && intval($data['max_size']) > $data['size'])
            $data['error'] = 30;


        if ($data['format'] == 'jpg')
            $imageCreateFrom = 'ImageCreateFromJpeg';
        else
            $imageCreateFrom = 'ImageCreateFrom'.$data['format'];

        if ($data['new_format'] == 'jpg')
            $imagePrint = 'imageJpeg';
        else
            $imagePrint = 'image'.$data['new_format'];

        // Create resource image
        $image = $imageCreateFrom($data['tmp_name']);

        $data['width'] = imagesx($image);
        $data['height'] = imagesy($image);

        // Min/Max resizing
        $minWidth = 0;
        $minHeight = 0;
        $maxWidth = 0;
        $maxHeight = 0;

        if ($data['min_width']){
            if ($data['min_width'] <= $data['width'])
                $minWidth = $data['width'];
            else
                $data['error'] = 40;
        }

        if ($data['min_height']) {
            if ($data['min_height'] <= $data['height'])
                $minHeight = $data['height'];
            else
                $data['error'] = 50;
        }

        if ($data['max_width']) {
            if ($data['max_width'] > $data['width'])
                $maxWidth = $data['width'];
            else
                $maxWidth = $data['max_width'];
        }

        if ($data['max_height']) {
            if ($data['max_height'] > $data['height'])
                $maxHeight = $data['height'];
            else
                $maxHeight = $data['max_height'];
        }

        // Приоритеты
        if (!$data['new_width']) {
            if ($maxWidth)
                $data['new_width'] = $maxWidth;
            else
                $data['new_width'] = $data['width'];
        }

        if (!$data['new_height']) {
            if ($maxHeight)
                $data['new_height'] = $maxHeight;
            else
                $data['new_height'] = $data['height'];
        }

        // Resizing
        if ($data['new_width'] == 0 && $data['new_height'] == 0) {
            $data['new_width'] = $data['width'];
            $data['new_height'] = $data['height'];
        } else if ($data['new_width'] != 0 && $data['new_height'] == 0) {
            $hw = round($data['height'] / $data['width'], 6);
            $data['new_height'] = round($hw * $data['new_width'], 0);
        } else if ($data['new_width'] == 0 && $data['new_height'] != 0) {
            $hw = round($data['width'] / $data['height'], 6);
            $data['new_width'] = round($hw * $data['new_height'], 0);
        } else if ($data['new_width'] != 0 && $data['new_height'] != 0) {

        }

        if ($data['resize'] == 1) {
            $data['height'] = $data['new_height'];
            $data['width'] = $data['new_width'];
        }

        if ($data['resize'] == 2) {
            if ($data['new_width'] > $data['new_height']) {
                $hw = round($data['new_height'] / $data['new_width'], 6);
                $data['height'] = round($hw * $data['width'], 0);
            } elseif ($data['new_width'] < $data['new_height']) {
                $hw = round($data['new_width'] / $data['new_height'], 6);
                $data['width'] = round($hw * $data['height'], 0);
            } else {
                if ($data['width'] > $data['height']) {
                    $data['width'] = $data['height'];
                } else {
                    $data['height'] = $data['width'];
                }
            }
        }

        if ($data['error'] != 0)
            return $data;

        $screen = imageCreateTrueColor($data['new_width'], $data['new_height']);

        if ($data['format'] == 'png') {
            imagealphablending($screen, false); // Disable pairing colors
            imagesavealpha($screen, true); // Including the preservation of the alpha channel
        }

        imageCopyResampled($screen, $image, 0, 0, 0, 0, $data['new_width'], $data['new_height'], $data['width'], $data['height']);
        $imagePrint($screen, $data['path'].$data['new_name'].'.'.$data['new_format']);
        imageDestroy($image);
        return $data;
    }

    /**
     * Function LoadImage
     * @param array $file ex. $_FILES['name']
     * @param string $path ex. 'app/public/'
     * @param null $name ex. 'name'
     * @param string $format ex. 'jpg'
     * @param array $allowedFormats ex. array('jpg' => true, 'gif' => false)
     * @param int $size - max file size
     * @param int $resize ex. 0 - no resize(сжать), 1 - обрезать не изменяя размеров, 2 - обрезать симетрически уменьшив
     * @param int $minHeight
     * @param int $minWidth
     * @param int $maxHeight
     * @param int $maxWidth
     * @return mixed
     */
    static public function LoadImage($file, $path, $name = null, $format = 'jpg', $allowedFormats = array(), $size = 0, $resize = 0, $minHeight = 0, $minWidth = 0, $maxHeight = 0, $maxWidth = 0)
    {
        $data = array('error' => 0);
        $data['format'] = mb_strtolower(mb_substr($file['name'], mb_strrpos($file['name'], '.')+1));
        $data['new_format'] = $format;
        $data['path'] = _SYSDIR_.trim($path, '/').'/';
        $data['tmp_name'] = $file['tmp_name'];
        $data['size'] = $file['size'];
        $data['type'] = $file['type'];
        $data['name'] = $file['name'];

        // Recursive mkdir
        remkdir($path);

        if (!$name)
            $data['new_name'] = randomHash();
        else
            $data['new_name'] = $name;

        if (!is_array($allowedFormats) OR empty($allowedFormats))
            $allowedFormats = self::$allowedImageFormats;

        if ($allowedFormats[$data['format']] !== true) {
            $data['error'] = 1;
            $data['error_msg'] = 'Incorrect file format';
            return $data;
        }

        if (intval($size) > 0 && $data['size'] > $size) {
            $data['error'] = 2;
            $data['error_msg'] = 'File size is too large';
            return $data;
        }

        if ($data['format'] == 'jpg')
            $imageCreateFrom = 'ImageCreateFromJpeg';
        else
            $imageCreateFrom = 'ImageCreateFrom'.$data['format'];

        if ($data['new_format'] == 'jpg')
            $imagePrint = 'imageJpeg';
        else
            $imagePrint = 'image'.$data['new_format'];

        // Create resource image
        $img = $imageCreateFrom($file['tmp_name']);

        $data['height'] = imagesy($img);
        $data['width'] = imagesx($img);

        // Min resizing
        if ($minHeight == 0 && $minWidth == 0) {
            $data['new_height'] = $data['height'];
            $data['new_width'] = $data['width'];
        } else if ($minHeight != 0 && $minWidth == 0) {
            $data['new_height'] = $minHeight;
            $hw = round($data['width'] / $data['height'], 6);
            $data['new_width'] = round($hw * $minHeight,0);
        } else if ($minHeight == 0 && $minWidth != 0) {
            $data['new_width'] = $minWidth;
            $hw = round($data['height'] / $data['width'], 6);
            $data['new_height'] = round($hw * $minWidth, 0);
        } else if ($minHeight != 0 && $minWidth != 0) {
            $data['new_height'] = $minHeight;
            $data['new_width'] = $minWidth;
        }

        // Max resizing
        if ($maxHeight != 0 && $maxWidth == 0 && $maxHeight < $data['height']) {
            $data['new_height'] = $maxHeight;
            $hw = round($data['width'] / $data['height'], 6);
            $data['new_width'] = round($hw * $maxHeight,0);
        } else if ($maxHeight == 0 && $maxWidth != 0 && $maxWidth < $data['width']) {
            $data['new_width'] = $maxWidth;
            $hw = round($data['height'] / $data['width'], 6);
            $data['new_height'] = round($hw * $maxWidth, 0);
        } else if ($maxHeight != 0 && $maxWidth != 0 && ($maxHeight < $data['height'] OR $maxWidth < $data['width'])) {
            if ($data['height'] > $data['width']) {
                $data['new_height'] = $maxHeight;
                $hw = round($data['width'] / $data['height'], 6);
                $data['new_width'] = round($hw * $maxHeight,0);
            } elseif ($data['height'] < $data['width']) {
                $data['new_width'] = $maxWidth;
                $hw = round($data['height'] / $data['width'], 6);
                $data['new_height'] = round($hw * $maxWidth, 0);
            }
        }

        if ($resize == 1) {
            $data['height'] = $data['new_height'];
            $data['width'] = $data['new_width'];
        }

        if ($resize == 2) {
            if ($data['new_width'] > $data['new_height']) {
                $hw = round($data['new_height'] / $data['new_width'], 6);
                $data['height'] = round($hw * $data['width'], 0);
            } elseif ($data['new_width'] < $data['new_height']) {
                $hw = round($data['new_width'] / $data['new_height'], 6);
                $data['width'] = round($hw * $data['height'], 0);
            } else {
                if ($data['width'] > $data['height']) {
                    $data['width'] = $data['height'];
                } else {
                    $data['height'] = $data['width'];
                }
            }
        }

        $screen = imageCreateTrueColor($data['new_width'], $data['new_height']);

        if ($data['format'] == 'png') {
            imagealphablending($screen, false); // Disable pairing colors
            imagesavealpha($screen, true); // Including the preservation of the alpha channel
        }

        imageCopyResampled($screen, $img, 0, 0, 0, 0, $data['new_width'], $data['new_height'], $data['width'], $data['height']);
        $imagePrint($screen, $data['path'].$data['new_name'].'.'.$data['new_format']);
        imageDestroy($img);
        return $data;
    }

    /**
     * Function captcha
     * @param int $length
     * @param string $font
     */
    static public function captcha($length = 4, $font = 'verdana.ttf')
    {
        $string = '';
        for ($i = 1; $i <= $length; $i++)
            $string .= chr(rand(97, 122));

        $_SESSION['captcha'] = $string;

        $font = _SYSDIR_.'public/fonts/'.$font;

        $image = imagecreatetruecolor($length*22, 50);
        $color = imagecolorallocate($image, 200, 100, 90);
        $background = imagecolorallocate($image, 255, 255, 255);

        imagefilledrectangle($image,0,0,399,99,$background);
        imagettftext($image, 20, 5, 10, 45, $color, $font, $_SESSION['captcha']);

        header("Content-type: image/png");
        imagepng($image);
    }
}
/* End of file */