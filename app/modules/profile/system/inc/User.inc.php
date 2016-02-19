<?php
/**
* USER
*/

class UserInc
{
    static public function start()
    {
        // Include model
        incFile('modules/profile/system/Model.php');
         incFile('../mail/class.phpmailer.php');
        // Connect to DB
        $model = new ProfileModel();
        if(getSession('user')){
            $id = getSession('user');
        }else{
            $id = getCookie('user');
            setSession('user', $id, false);
        }
      //  $id = (getSession('user')) ? getSession('user') : getCookie('user') ;


        if ($id)
        {
            $uData = array();
            // Update user
            $uData['controller'] = CONTROLLER;
            $uData['action'] = ACTION;
            $uData['dateLast'] = time();
            $model->updateUserByID($uData, $id);
            // Get data user
            Request::setParam('user', $model->getUserByID($id));
            // Count new message
            Request::setParam('countMsg', $model->countMsg($id));
            // Count new message
            Request::setParam('countRequests', $model->countRequests($id));
            // Count challenges
            Request::setParam('countChallenges', $model->countChallengesList($id));
        }
        else
        {
            $gip = ip2long($_SERVER['REMOTE_ADDR']);
            // Null
            Request::setParam('user', null);
            // Guest
            Request::setParam('guest', $model->getGuestByIP($gip));
            // Role
            Request::setRole('guest');

            /*
            // Language
            if (CONTROLLER == 'page' && ACTION == 'lang') {
                if (Request::getUri(0) == 'ru' OR Request::getUri(0) == 'en')
                    setMyCookie('lang', Request::getUri(0), time() + 365 * 86400);
            }

            $lang = getCookie('lang');

            if ($lang == 'ru' OR $lang == 'en')
                Lang::setLanguage($lang);
            else
                Lang::setLanguage();
            */

            if (Request::getParam('guest')->id) {
                $gData['count'] = Request::getParam('guest')->count+1;
                $gData['time'] = time();
                $model->update('guests', $gData, "`id` = '".Request::getParam('guest')->id."' LIMIT 1");
            } else {
                $gData['ip'] = $gip;
                $gData['browser'] = $_SERVER['HTTP_USER_AGENT'];
                $gData['referer'] = $_SERVER['HTTP_REFERER'];
                $gData['count'] = 1;
                $gData['time'] = time();
                $model->insert('guests', $gData);
            }
        }

        // Count users online
        Request::setParam('countUsersOnline', $model->countUsersOnline());
        // Count guests online
        Request::setParam('countGuestsOnline', $model->countGuestsOnline());
    }
}

/* End of file */