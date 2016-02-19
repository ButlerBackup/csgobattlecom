<ul class="page_nav">
    <li><a href="{URL:friends}">{L:INDEX_TITLE}</a></li>
    <li><a class="active" href="{URL:friends/online}">{L:ONLINE_LINK}</a></li>
    <li><a href="{URL:friends/incoming}">{L:REQUESTS_LINK} <?php echo ((Request::getParam('countRequests') > 0) ? '(+'.Request::getParam('countRequests').')' : ''); ?></a></li>
    <li><a href="{URL:friends/blacklist}">{L:BLACKLIST_TITLE}</a></li>
</ul>

<?php
if (getCookie('error'))
    echo getCookie('error');

echo '<div class="inbox clearfix">';

if (count($this->online) > 0) {
    foreach ($this->online as $friend) {
        $id = ($friend->uid == Request::getParam('user')->id)? $friend->pid : $friend->uid ;

        echo '<div class="tile">';
            echo '<div class="tileImg">';
                echo '<img src="'.getAvatar($id).'">';
                echo '<a href="'.url($id).'"></a>';
            echo '</div>';

            echo '<div class="tileInfo">';
                echo '<div><a class="tileName" href="'.url($id).'">'.$friend->name.'</a></div>';
                echo '<div>'.getRank($friend->elo).'</div>';
                echo '<a class="mailBtn" href="{URL:mail'.$id.'}" title="{L:INDEX_SEND_MAIL}"></a>';
                echo '<a class="challengeBtn" onclick="'.ajaxLoad(url('profile','challenge'), 'challenge', 'pid:'.$id).'">{L:INDEX_CHALLENGE}</a>';
            echo '</div>';
        echo '<div id="challenge'.$id.'"></div>';
        echo '</div>';
    }
}

echo '</div>';

echo '<div class="pagin">'.Pagination::printPagination().'</div>';
?>