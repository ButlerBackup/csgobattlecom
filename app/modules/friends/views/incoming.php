<ul class="page_nav">

    <li><a href="{URL:friends}">{L:INDEX_TITLE}</a></li>

    <li><a href="{URL:friends/online}">{L:ONLINE_LINK}</a></li>

    <li><a class="active" href="{URL:friends/incoming}">{L:REQUESTS_LINK} <?php echo((Request::getParam('countRequests') > 0) ? '(+' . Request::getParam('countRequests') . ')' : ''); ?></a></li>

    <li><a href="{URL:friends/blacklist}">{L:BLACKLIST_TITLE}</a></li>

</ul>



<div>

    <a href="<?php echo url('friends', 'incoming'); ?>"><b>{L:INCOMING_LINK}</b> <?php echo((Request::getParam('countRequests') > 0) ? '(+' . Request::getParam('countRequests') . ')' : ''); ?></a>

    | <a href="<?php echo url('friends', 'outgoing'); ?>">{L:OUTGOING_LINK}</a>

</div>



<?php

if (getCookie('error'))

    echo getCookie('error');



echo '<div class="inbox clearfix">';



if (count($this->incoming) > 0) {

    foreach ($this->incoming as $friend) {

        $id = ($friend->uid == Request::getParam('user')->id) ? $friend->pid : $friend->uid;



        echo '<div class="tile">';

            echo '<div class="tileImg">';

                echo '<img src="'.getAvatar($id).'">';

                echo '<a href="'.url($id).'"></a>';

            echo '</div>';



            echo '<div class="tileInfo">';

                echo '<div><a class="tileName" href="'.url($id).'">'.$friend->name.'</a></div>';

                echo '<div>'.getRank($friend->elo).'</div>';

				echo '<div class="yes">';

                echo '<a href="" class="due" onclick="'.ajaxLoad(url('friends', 'acceptRequest'), 'reqest', 'pid:'.$id).'">{L:INCOMING_REQEST_SENT_TO_ACCEPT}</a>';
				
                echo '<a href="" class="due1" onclick="'.ajaxLoad(url('friends', 'declineRequest'), 'reqest', 'pid:'.$id).'">{L:INCOMING_REQEST_SENT_TO_DECLINE}</a>';

            echo '</div>';
			echo '</div>';

        echo '</div>';

    }

}



echo '</div>';



echo '<div class="pagin">'.Pagination::printPagination().'</div>';

?>