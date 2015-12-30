<h1>{L:INDEX_VERIFY_USERS}</h1>

<?php
while($list = mysqli_fetch_object($this->list))
{
    echo '<div>';
        echo '<a href="'.url($list->id).'">'.$list->nickname.'</a>';
        echo ' <span id="verify'.$list->id.'">(';
            echo '<a onclick="'.ajaxLoad(url('admin','verify_users_submit'), 'verifys', 'id:'.$list->id).'">'.Lang::translate('VERIFY_USERS_SUBMIT').'</a> | ';
            echo '<a onclick="'.ajaxLoad(url('admin','verify_users_reject'), 'verifyr', 'id:'.$list->id).'">'.Lang::translate('VERIFY_USERS_REJECT').'</a>';
        echo ')</span>';

        echo ' / Steam ';
        if ($list->steamid)
            echo '<span class="c_green">&#10004;</span>';
        else
            echo '<span class="c_red">&#10006;</span>';
    echo '</div>';
}
?>