<style>
.popup_btns{
  height: auto;
    margin: 0 auto;
    padding-top: 3px;
    position: relative;
    width: 100%;	
	}
.popbtns {
  background: #33393e none repeat scroll 0 0;
    border-radius: 4px;
    color: #ffffff;
    display: block;
    font-family: Arial;
    font-size: 13px;
    margin: 0 auto;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    width: 80px;
}

.popbtns:hover {
  background: #A3B1BB;
  text-decoration: none;
   color: #33393E;
}

</style>

<ul class="page_nav">
    <li><a class="active" href="{URL:matches}">{L:MATCHES_TITLE}</a></li>
    <li><a href="{URL:matches/challenges}">{L:CHALLENGES_TITLE}<?php echo ((Request::getParam('countChallenges') > 0) ? ' (+'.Request::getParam('countChallenges').')' : ''); ?></a></li>
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
          //  echo '<td><a href="'.url('match'.$list->id).'">{L:MATCHES_MATCH_VS} '.$list->nickname;
		  echo '<td><a href="'.url('match'.$list->id).'" class="match-link"  onclick="return showpopup()">{L:MATCHES_MATCH_VS} '.$list->nickname;

                if ($list->country)
                    echo '<img src="'._SITEDIR_.'public/images/country/'.mb_strtolower($list->country).'.png">';
            echo '</a>';

                if($list->pready==0 AND $list->uready==0)
            echo '<span id="cancelMatch'.$list->id.'" class="cancel-match-btn" ><a onclick="ajaxLoad(\''.url('profile','matchCancel').'\',\'reqest\',\'mid:'.$list->id.'\');" >{L:MATCH_CANCEL}</a></span>';

        echo '</td><td></td>';
        echo '</tr>';
    }
    ?>
</table>

<?php
echo '<div class="pagin">'.Pagination::printPagination().'</div>';
?>