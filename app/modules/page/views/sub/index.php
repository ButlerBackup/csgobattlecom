<?php

$pageLocalModel = new ProfileModel();
$stamina = 0;
$uid = Request::getParam('user')->id;
$pageLocalModel->checkStamina($uid);
 $stamina = $pageLocalModel->getStamina($uid);
 $staminaMax = $pageLocalModel->getStaminaMax($uid);
$staminaPercent = $stamina * 100 / $staminaMax;
$staminaPercent = ($staminaPercent<0)? 0: $staminaPercent;
?>
<?php ?><a href="{URL:/main}"><div class="logo"></div></a>

<?php

if (Request::getParam('user')->id) {

    echo '<div class="profile_bar">';

    echo '<div class="nav_profile">';

    echo '<img class="avatar" src="'.getAvatar(Request::getParam('user')->id).'" alt="Avatar" />';

    echo '<div class="nav_profile_name"><a href="'.url(Request::getParam('user')->id).'">'.Request::getParam('user')->nickname.'</a></div>';

    echo '<div class="stamina-bar"><div class="full" style="width:'.$staminaPercent.'%"></div></div>';

    echo '</div>';



        echo '<ul class="nav_personal">';

            echo '<li class="friends-icon"><a href="'.url('friends').'" title="{L:FRIENDS}">'.((Request::getParam('countRequests') > 0) ? '(+'.Request::getParam('countRequests').')' : '').'</a></li>';

            echo '<li class="mail-icon"><a href="'.url('mail').'" title="{L:MAIL}">'.((Request::getParam('countMsg') > 0) ? '(+'.Request::getParam('countMsg').')' : '').'</a></li>';

            echo '<li class="settings-icon"><a href="'.url('settings').'" title="{L:SETTINGS}"></a></li>';

            echo '<li class="exit-icon"><a href="'.url('profile','exit').'" title="{L:EXIT}"></a></li>';

        echo '</ul>';

    echo '</div>';



    echo '<div class="nav_menu">';
        if (Request::getRole() == 'moder' OR Request::getRole() == 'admin')
            echo '<a class="admin-panel" href="{URL:admin}">{L:ADMIN_PANEL}</a>';

        echo '<a class="matches" href="{URL:matches}">{L:MENU_MATCHES}'.((Request::getParam('countChallenges') > 0) ? ' (+'.Request::getParam('countChallenges').')' : '').'</a>';

        echo '<a class="notice" href="{URL:notice}">{L:MENU_NOTICE}'.((Request::getParam('user')->notice > 0) ? ' (+'.Request::getParam('user')->notice.')' : '').'</a>';

        echo '<a class="discover" href="{URL:discover}">{L:MENU_DISCOVER_PAGE}</a>';

        echo '<a class="chat" href="{URL:chat}">{L:MENU_CHAT}</a>';

        echo '<a class="ladders" href="{URL:ladders}">{L:MENU_LADDERS}</a>';

        echo '<a class="servers" href="{URL:servers}">{L:MENU_SERVERS}</a>';

        echo '<a class="maps" href="{URL:maps}">{L:MENU_MAPS}</a>';

        echo '<a class="news-main" href="{URL:main}">{L:MENU_NEWS}</a>';
    echo '</div>';

} else {



}

?>