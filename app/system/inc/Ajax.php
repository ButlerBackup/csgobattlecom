<?php
/**
 * Function load ajax page
 * @param string $url ex. ('/page/select?act=shop&time=1250')
 * @param bool $permit ex. ('name') access recall function
 * @param bool $fields ex. ('#msg|.num|#name!|id:3') - One or more fields (! - don't clean field)
 * @param bool $func ex. ('ajaxScroll|ajaxScroll!') - load function func if return true (! - if return false)
 * @param bool $type ex. ('POST')
 * @return string
 */
function ajaxLoad($url, $permit = false, $fields = false, $func = false, $type = false)
{
    $value = 'ajaxLoad(\'' . $url . '\'';
    if ($permit !== false)
        $value .= ', \'' . $permit . '\'';
    if ($fields !== false)
        $value .= ', \'' . $fields . '\'';
    if ($func !== false)
        $value .= ', \'' . $func . '\'';
    if ($type !== false)
        $value .= ', \'' . $type . '\'';
    $value .= ');';

    return $value;
}

/**
 * Function ajaxSetInterval
 * @param $func
 * @param int $interval
 */
function ajaxSetInterval($func, $interval = 1000, $before_func = false, $after_func = false)
{
    $interval = ($interval) ? ', ' . $interval : '';

    echo '<script>
        $(document).ready(function () {
            ' . (($before_func) ? $before_func . ';' : '') . '
            setInterval(function() {
                ' . $func . ';
            }' . $interval . ');
            ' . (($after_func) ? $after_func . ';' : '') . '
        });
    </script>';
}

/**
 * Function ajaxSetTimeout
 * @param $func
 * @param null $interval
 */
function ajaxSetTimeout($func, $interval = null)
{
    $interval = ($interval) ? ', ' . $interval : '';

    echo '<script>
        $(document).ready(function () {
            setTimeout(function() {
                ' . $func . ';
            }' . $interval . ');
        });
    </script>';
}

/**
 * Function ajaxKeyDown
 * @param int $key
 * @param $func (alert("Text"))
 * @param bool $shift
 * @param bool $ctrl
 * @param bool $alt
 * @param bool $prevent
 * @param string $element
 */
function ajaxKeyDown($key = 13, $func, $shift = false, $ctrl = false, $alt = false, $prevent = true, $element = 'body')
{
    if ($shift === true)
        $shift = ' && event.shiftKey';
    else
        $shift = ' && !event.shiftKey';

    if ($ctrl === true)
        $ctrl = ' && event.ctrlKey';
    else
        $ctrl = ' && !event.ctrlKey';

    if ($alt === true)
        $alt = ' && event.altKey';
    else
        $alt = ' && !event.altKey';

    if ($prevent === true)
        $prevent = 'event.preventDefault();';
    else
        $prevent = '';

    echo '<script>
        $(document).ready(function () {
            $("' . $element . '").keydown(function(event){
                if (event.which == ' . $key . $shift . $ctrl . $alt . ') {
                    ' . $prevent . $func . '
                }
            });
        });
    </script>';
}

function ajaxFill($target, $content = false, $field = false)
{
    if ($field === false)
        $value = 'ajaxFill(\'' . $target . '\'';
    else
        $value = 'ajaxFillField(\'' . $target . '\'';
    if ($content !== false)
        $value .= ', \'' . $content . '\'';
    $value .= ');';

    return $value;
}
/* End of file */