<?php
/**
* POPUP
*/

class PopupInc
{
    static public function SimplePopup($part = false, $popup = array())
    {
        // Window width
        if (!$popup['winW'])
            $popup['winW'] = 0;
        else
            $popup['winW'] = $popup['winW'] - 350 - 40;

        // Window height
        if (!$popup['winH'])
            $popup['winH'] = 0;
        else
            $popup['winH'] = $popup['winH'] - 40;


        // Popup width
        if ($popup['popupW']) {
            $width = 'width: '.$popup['popupW'].'px;';
        } else {
            if ($popup['popupW'] !== false) {
                $popup['popupW'] = 400;
                $width = ' width: '.$popup['popupW'].'px;';
            }
        }

        // Popup height
        if ($popup['popupH']) {
            $height = ' height: '.$popup['popupH'].'px;';
        } else {
            if ($popup['popupH'] !== false) {
                $popup['popupH'] = 400;
                $height = ' height: '.$popup['popupH'].'px;';
            }
        }

        $style = $width.$height;

        $margin = abs(($popup['winH'] - $popup['popupH']) / 2);

        $html = '<div class="popup">'
                .'<div class="popup_space">'
                    .'<div class="popup_fon" onclick="closePopup(\'#popup\');"></div>'
                    .'<div class="popup_body" style="'.$style.' margin-top: '.$margin.'px; background-color: #2d2d2d;">'
                        .'<div class="popup_main" style="'.$style.'">'
                            .'<div class="popup_close" onclick="closePopup(\'#popup\');"></div>'
                            .$part
                        .'</div>'
                    .'</div>'
                .'</div>'
            .'</div>';

        return $html;
    }
}

/* End of file */