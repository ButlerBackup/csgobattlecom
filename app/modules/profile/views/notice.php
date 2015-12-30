<a class="btn" href="{URL:profile/read_all_notice}" style="float: right;">{L:NOTICE_READ_ALL}</a>
<h1>{L:NOTICE_TITLE}</h1>

<table class="case-table">
    <tr>
        <th style="width: 150px;">{L:NOTICE_TIME}</th>
        <th>{L:NOTICE_MESSAGE}</th>
        <th style="width: 150px;">{L:NOTICE_ACTION}</th>
    </tr>

    <?php
    while($list = mysqli_fetch_object($this->list))
    {
        echo '<tr '.(($list->read == 0) ? 'class="gray"' : '').'>'
            .'<td style="font-size: 12px;">'.printTime($list->time, "H:i / m.d.Y").'</td>'
            .'<td style="font-size: 12px;">'.reFilter($list->text).'</td>'
            .'<td>';
        if ($list->read == 0)
            echo '<div id="nt'.$list->id.'"><div class="btn" onclick="'.ajaxLoad(url('profile','read_notice'), 'read_notice', 'id:'.$list->id).' delClass(this);">{L:NOTICE_READ}</div></div>';
        else
            echo '-';
        echo '</td>';
        echo '</tr>';
    }
    ?>
</table>
<?php echo '<div class="pagin">'.Pagination::printPagination().'</div>'; ?>