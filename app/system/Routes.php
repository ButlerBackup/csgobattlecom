<?php
/**
* ROUTE
*/

// Default route
$route[] = array(
    'pattern' => '~^([0-9]{1,20})~',
    'controller' => 'profile',
    'action' => 'index',
);
    
// Page route
$route[] = array(
    'pattern' => '~^mail([0-9]{1,10})~',
    'controller' => 'mail',
    'action' => 'mail',
);

// Reg route
$route[] = array(
    'pattern' => '~^reg_([A-Za-z0-9]{20,40})~',
    'controller' => 'page',
    'action' => 'reg',
);

$route[] = array(
    'pattern' => '~^regs_([A-Za-z0-9]{20,40})~',
    'controller' => 'page',
    'action' => 'regs',
);
$route[] = array(
    'pattern' => '~^imageverification_([A-Za-z0-9]{20,40})~',
    'controller' => 'page',
    'action' => 'imageverification',
);

//imagebuilder
$route[] = array(
    'pattern' => '~^captcha([A-Za-z0-9]{20,40})~',
    'controller' => 'page',
    'action' => 'captcha',
);

// Match route
$route[] = array(
    'pattern' => '~^match([0-9]{1,10})~',
    'controller' => 'profile',
    'action' => 'match',
);

// Main
$route[] = array(
    'pattern' => '~^main~',
    'controller' => 'page',
    'action' => 'main',
);

// Servers
$route[] = array(
    'pattern' => '~^servers~',
    'controller' => 'page',
    'action' => 'servers',
);

// Maps
$route[] = array(
    'pattern' => '~^maps~',
    'controller' => 'page',
    'action' => 'maps',
);

// Ladders join
$route[] = array(
    'pattern' => '~^ladders/join$~',
    'controller' => 'profile',
    'action' => 'ladders_join',
);

// Ladders
$route[] = array(
    'pattern' => '~^ladders~',
    'controller' => 'profile',
    'action' => 'ladders',
);

// Matches
$route[] = array(
    'pattern' => '~^matches$~',
    'controller' => 'profile',
    'action' => 'matches',
);

// Matches challenges
$route[] = array(
    'pattern' => '~^matches/challenges$~',
    'controller' => 'profile',
    'action' => 'challenges',
);

// Matches history
$route[] = array(
    'pattern' => '~^matches/history$~',
    'controller' => 'profile',
    'action' => 'history',
);

// Matches get chat
$route[] = array(
    'pattern' => '~^matchgetchat$~',
    'controller' => 'profile',
    'action' => 'get_chat',
);

// Matches send chat
$route[] = array(
    'pattern' => '~^matchsendchat$~',
    'controller' => 'profile',
    'action' => 'send_chat',
);

// Notice
$route[] = array(
    'pattern' => '~^notice$~',
    'controller' => 'profile',
    'action' => 'notice',
);

// Match save
$route[] = array(
    'pattern' => '~^matchsave$~',
    'controller' => 'profile',
    'action' => 'match_save',
);

// Match save
$route[] = array(
    'pattern' => '~^matchaccept$~',
    'controller' => 'profile',
    'action' => 'match_accept',
);

// Match save
$route[] = array(
    'pattern' => '~^matchreject$~',
    'controller' => 'profile',
    'action' => 'match_reject',
);

// Online
$route[] = array(
    'pattern' => '~^online$~',
    'controller' => 'page',
    'action' => 'online',
);

// Online
$route[] = array(
    'pattern' => '~^footer$~',
    'controller' => 'page',
    'action' => 'online',
);
/* End of file */

// Discover page
$route[] = array(
    'pattern' => '~^discover$~',
    'controller' => 'profile',
    'action' => 'discover',
);

// EXIT page
$route[] = array(
    'pattern' => '~^exit$~',
    'controller' => 'profile',
    'action' => 'exit',
);
/* End of file */