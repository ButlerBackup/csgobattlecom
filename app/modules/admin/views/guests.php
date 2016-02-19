<div class="box">
    <h1>{L:GUESTS_TITLE}</h1>

    <?php
    echo '<div>24h: '.$this->online24h.'</div>';
    echo '<div>Google: '.$this->google->num_rows.'</div>';
    echo '<div>Bing: '.$this->bing->num_rows.'</div>';

    echo '<hr/>';

    echo '<table class="case-table">'
        .'<tr>'
        .'<th>{L:GUESTS_IP}</th>'
        .'<th>{L:GUESTS_BROWSER}</th>'
        .'<th>{L:GUESTS_REFERER}</th>'
        .'<th>{L:GUESTS_COUNT}</th>'
        .'<th>{L:GUESTS_TIME}</th>'
        .'</tr>';

    while ($list = mysqli_fetch_object($this->list))
    {
        echo '<tr>'
            .'<td>'.$list->ip.'</td>'
            .'<td>'.$list->browser.'</td>'
            .'<td>'.$list->referer.'</td>'
            .'<td>'.$list->count.'</td>'
            .'<td>'.printTime($list->time).'</td>'
        .'</tr>';
    }
    echo '</table>';
    ?>
</div>