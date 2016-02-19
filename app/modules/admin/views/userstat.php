<div class="box">
    <h1>{L:USERSTAT_TITLE}</h1>

    <?php
    echo '<div>{L:USERSTAT_USERS} ({L:USERSTAT_USERS_ALL} / {L:USERSTAT_USERS_24H} / {L:USERSTAT_USERS_ON}): '.$this->count.' / '.$this->count24h.' / '.$this->list->num_rows.'</div><hr/>';

    echo '<table class="case-table">'
        .'<tr>'
            .'<th>{L:USERSTAT_NICKNAME}</th>'
            .'<th>{L:USERSTAT_ELO}</th>'
            .'<th>{L:USERSTAT_WINS}</th>'
            .'<th>{L:USERSTAT_LOSSES}</th>'
            .'<th style="width: 60px;">{L:USERSTAT_TIME}</th>'
        .'</tr>';

    while ($list = mysqli_fetch_object($this->list))
    {
        echo '<tr>'
            .'<td><a href="{URL:'.$list->id.'}">'.$list->nickname.'</a></td>'
            .'<td>'.$list->elo.'</td>'
            .'<td>'.$list->wins.'</td>'
            .'<td>'.$list->losses.'</td>'
            .'<td>'.printTime($list->dateLast, "H:i:s").'</td>'
        .'</tr>';
    }
    echo '</table>';
    ?>
</div>