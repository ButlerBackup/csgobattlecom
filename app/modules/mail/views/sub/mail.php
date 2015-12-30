<div class="box_right">
    <?php
    if ($this->rightList->num_rows > 0) {
        while ($list = mysqli_fetch_object($this->rightList)) {
            $count = false;
            if ($list->uid1 == Request::getParam('user')->id) {
                $user = $list->uid2;
                if ($list->countMsg1 > 0)
                    $count = '<div class="mail_count">+'.$list->countMsg1.'</div>';
            } else {
                $user = $list->uid1;
                if ($list->countMsg2 > 0)
                    $count = '<div class="mail_count">+'.$list->countMsg2.'</div>';
            }

            echo '<div class="mail_box mail_link">'
                .'<a href="{URL:mail'.$user.'}"></a>'
                .$count
                .'<div class="mail_image"><img src="'.getAvatar($user, 's').'"></div>'
                .'<div class="mail_name">'.$list->nickname.'</div>'
                .'<div class="mail_msg"><span class="mail_time">'.printTime($list->time).'</span></div>'
                .'</div>';
        }
    } else {
        echo '{L:INDEX_NO_DIALOGS}';
    }
    ?>
</div>