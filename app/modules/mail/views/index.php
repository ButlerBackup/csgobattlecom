<h1>{L:INDEX_TITLE}</h1>

<?php
if ($this->list->num_rows > 0) {
    echo '<div class="inbox clearfix">';

    while ($list = mysqli_fetch_object($this->list))
    {
        $count = false;
        if ($list->uid1 == Request::getParam('user')->id) {
            $id = $list->uid2;
            if ($list->countMsg1 > 0)
                $count = ' <span class="mail_count">+'.$list->countMsg1.'</span>';
        } else {
            $id = $list->uid1;
            if ($list->countMsg2 > 0)
                $count = ' <span class="mail_count">+'.$list->countMsg2.'</span>';
        }

        echo '<div class="tile">';
            echo '<div class="tileImg">';
                echo '<img src="'.getAvatar($id).'">';
                echo '<a href="'.url($id).'"></a>';
            echo '</div>';

            echo '<div class="tileInfo">';
                echo '<div><a class="tileName" href="'.url('mail'.$id).'">'.$list->nickname.'</a>'.$count.'</div>';
                echo '<div>'.getRank($list->elo).'</div>';
                echo '<a class="mailBtn" href="{URL:mail'.$id.'}" title="{L:INDEX_SEND_MAIL}"></a>';
                echo '<a class="challengeBtn" href="">{L:INDEX_CHALLENGE}</a>';
            echo '</div>';
        echo '</div>';

        //printTime($list->time)
        //$list->message
    }

    echo '</div>';

    echo '<div class="pagin">'.Pagination::printPagination().'</div>';
} else {
    echo '{L:INDEX_NO_DIALOGS}';
}
?>