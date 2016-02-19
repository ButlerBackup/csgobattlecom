<?php
/**
* PRE DISPATCH
*/

// UserInc
incFile('modules/profile/system/inc/User.inc.php');
UserInc::start();

if (Request::getParam('user'))
{
    // Role
    Request::setRole(Request::getParam('user')->role);
    // Language
    Lang::setLanguage(Request::getParam('user')->lang);
}
else
{
    // Role
    Request::setRole('guest');
    // Language
    Lang::setLanguage();
}

/* End of file */