<ul class="page_nav">
    <li><a href="{URL:matches}">{L:MATCHES_TITLE}</a></li>
    <li><a class="active" href="{URL:matches/challenges}">{L:CHALLENGES_TITLE}<?php echo ((Request::getParam('countChallenges') > 0) ? ' (+'.Request::getParam('countChallenges').')' : ''); ?></a></li>
    <li><a href="{URL:matches/history}">{L:HISTORY_TITLE}</a></li>
</ul>

<table class="ladderTable">
    <tr>
        <th>#</th>
        <th>{L:MATCHES_USER}</th>
        <th>{L:MATCHES_STATUS}</th>
    </tr>
    <?php
    while ($list = mysqli_fetch_object($this->list))
    {
        echo '<tr>';
        echo '<td>'.++Pagination::$start.'</td>';
        echo '<td><a href="'.url('match'.$list->id).'">'.$list->nickname.' {L:CHALLENGES_CHALLENGED_YOU}';
            if ($list->country)
                echo '<img src="'._SITEDIR_.'public/images/country/'.mb_strtolower($list->country).'.png">';
        echo '</a></td>';
        echo '<td></td>';
        echo '</tr>';
    }
    ?>
</table>

<?php
echo '<div class="pagin">'.Pagination::printPagination().'</div>';
?>