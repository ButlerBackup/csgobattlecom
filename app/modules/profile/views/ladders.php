<ul class="page_nav">
    <li><a <?php echo $this->active['low']; ?> href="{URL:ladders}">{L:LADDERS_GREEN}</a></li>
    <li><a <?php echo $this->active['intermediate']; ?> href="{URL:ladders/intermediate}">{L:LADDERS_RED}</a></li>
    <li><a <?php echo $this->active['high']; ?> href="{URL:ladders/high}">{L:LADDERS_BLACK}</a></li>
</ul>

<?php
if (Request::getParam('user')->steamid && Request::getParam('user')->role != 'claim' && Request::getParam('user')->ladder == 0) {
    $join = '<a href="'.url('ladders','join').'">{L:LADDERS_JOIN}</a>';
} else {
    if (Request::getParam('user')->ladder == 1)
        $join = '{L:LADDERS_JOIN_OK}';
    else
        $join = '{L:LADDERS_JOIN_NO}';
}
echo '<div class="ladderJoin">{L:LADDERS_ACTION}: '.$join.'</div>';
?>

<table class="ladderTable">
    <tr>
        <th>#</th>
        <th>{L:LADDERS_USER}</th>
        <th>{L:LADDERS_WINS}</th>
        <th>{L:LADDERS_TIES}</th>
        <th>{L:LADDERS_LOSSES}</th>
        <th>{L:LADDERS_RANK}</th>
    </tr>
    <?php
    while ($list = mysqli_fetch_object($this->list))
    {
        echo '<tr>';
            echo '<td>'.++Pagination::$start.'</td>';
            echo '<td>'.(($list->country) ? '<img src="'._SITEDIR_.'public/images/country/'.mb_strtolower($list->country).'.png">' : '').' <a href="'.url($list->id).'">'.$list->nickname.'</a></td>';
            echo '<td>'.$list->wins.'</td>';
            echo '<td>'.$list->ties.'</td>';
            echo '<td>'.$list->losses.'</td>';
            echo '<td>'.getRank($list->elo).'</td>';
        echo '</tr>';
    }
    ?>
</table>

<?php
echo '<div class="pagin">'.Pagination::printPagination().'</div>';
?>