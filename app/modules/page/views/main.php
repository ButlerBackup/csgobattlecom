<?php $model = new ProfileModel(); ?>
<style scoped>
    .start-battle-button button{
        display: inline-block;
        border: none;
        background: #a1192a;
        color: #cecaca;
        padding: 15px 30px;
        -webkit-border-radius:5px;
        -moz-border-radius:5px;
        border-radius:5px;
        font-size: 16px;
        font-family: Novecentosanswide-Bold, sans-serif;
        margin: 10px 0 0 0;
    }
    .neve{
        font-family: Novecentosanswide-Bold, sans-serif;
    }
    .start-battle-button button:hover{
        background: #e63a19;
    }
    .start-battle-button{
        text-align: center;
    }
    .ladder-top{
        margin:0 0 25px 0;
    }
</style>
<div class="start-battle-button">
    <a href="/discover"><button>Start your first game!</button></a>
</div>
<div class="ladder-top">
    <p class="neve">LEADERBOARDS</p>
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
        while ($list = mysqli_fetch_object($this->ladder_list))
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
</div>
<div class="last-battles">
    <style scoped>
        .lastMatches{
            margin:0 0 25px 0;
            text-align: center;
        }
        .nicknames{
            font-size: 16px;
        }
    </style>
    <p class="neve">LAST MATCHES</p>
    <table class="ladderTable lastMatches">
        <?php
        while ($list = mysqli_fetch_assoc($this->last_matches))
        {
            $columns = array('nickname','country');
            $uInfoRes = $model->getUserInfo($list['uid'],$columns);
            $uInfo = mysqli_fetch_object($uInfoRes);
            $pInfoRes = $model->getUserInfo($list['pid'],$columns);
            $pInfo = mysqli_fetch_object($pInfoRes);
            $timeAgo = ceil((time()-$list['startTime'])/60/60) . "h ago";
            $playerInfo = (($pInfo->country) ? '<img src="'._SITEDIR_.'public/images/country/'.mb_strtolower($pInfo->country).'.png">' : '').$pInfo->nickname;
            $userInfo = (($uInfo->country) ? '<img src="'._SITEDIR_.'public/images/country/'.mb_strtolower($uInfo->country).'.png">' : '').$uInfo->nickname;

            echo "<tr>";
            echo '<td><p><span class="nicknames">';

            echo ($list['pwin']>$list['uwin'])? $playerInfo." won VS ".$userInfo : $userInfo." won VS ".$playerInfo;
            echo '</span><br />'.$timeAgo.'</p>';
            echo '</td><td><a href="/match'.$list['id'].'">Link to Match</a></td>';
            echo "</tr>";
        }
        ?>
    </table>
</div>
<div class="last-registred">
    <style scoped>
        .user-list .profile-img img{
            display: block;
            height: 120px;
            width: 120px;
            position: relative;
            z-index: 1;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            border-radius: 60px;
            -webkit-box-shadow: 0 1px 7px 2px rgba(0, 0, 0, 0.67);
            -moz-box-shadow: 0 1px 7px 2px rgba(0, 0, 0, 0.67);
            box-shadow: 0 1px 7px 2px rgba(0, 0, 0, 0.67);
        }
        .user-list .profile-img {
            width: 120px;
            height: 120px;
            text-align: center;
            margin: 0 auto;
        }
        .margin-top-zero {
            margin-top: 0;
        }
        .margin-bottom-zero {
            margin-bottom: 0;
        }
        .player-info .player-name {
            font-size: 20px;
            font-family: Novecentosanswide-Bold,sans-serif;
        }
        .player-info .challenge-button {
            position: absolute;
            bottom: 25px;
            width: 70px;
            left: 50%;
            right: 50%;
            margin-left: -45px;
        }
        .player-info .challenge-button a{
            color: #ffffff;
            font-size: 16px;
        }
        .player-info .challenge-button a:hover{
            color: #ffffff;
        }
        .player-info .challenge-button:hover {
            opacity: 0.7;
            -webkit-box-shadow: 0 1px 12px 2px rgba(0, 0, 0, 0.67);
            -moz-box-shadow: 0 1px 12px 2px rgba(0, 0, 0, 0.67);
            box-shadow: 0 1px 12px 2px rgba(0, 0, 0, 0.67);
        }
        .player-info .challenge-button {
            background-color: #FF0000;
            display: inline-block;
            padding: 6px 10px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            cursor: pointer;

        }
        .player-info{
            float:left;
            position: relative;
            padding: 25px;
            margin-left: 20px;
            width: 150px;
            height: 230px;
            text-align: center;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            background-color: rgba(0,0,0,0.7);
        }
        .user-list ul{
            margin-bottom: 20px;
        }
        .user-list li{
            display: inline-block;
            padding: 0 10px;
        }
        .discover-page-wrapper h2{
            font-weight: 400;
        }
        .discover-page-wrapper h1{
            margin-bottom: 8px;
        }
        .player-info{
            margin-bottom: 25px;
        }
        .user-list{
            margin:0 0 25px 0;
            height: 280px;
        }
    </style>
    <p class="neve">LAST JOINED</p>
    <div class="user-list">
    <?php
    $userID = Request::getParam('user')->id;
    while ($list = mysqli_fetch_assoc($this->last_reg_list)){

        $lastRegistered = ceil((time() - $list["dateReg"])/60/60/24);


            $matchCount = $model->checkMatchExist($userID,$list["uid"]);
            $text = "Joined ".$lastRegistered."d ago";

            ?>

            <div class="player-info">
                <div class="profile-img">
                    <a href="/<?php echo $list["uid"]; ?>">
                        <img src="/app/public/images/img/avatar.jpg" alt="Player photo">
                    </a>
                </div>
                <p class="margin-bottom-zero">
                    <a href="/<?php echo $list["uid"]; ?>">
                        <span class="player-name" ><?php echo $list["nickname"]; ?></span>
                    </a>
                </p>
                <p  class="margin-top-zero"><span class="player-status" ><?php echo $text; ?></span></p>

                <?php if(($list["uid"] != $userID) AND ($matchCount<1)){ ?>
                    <div class="challenge-button hideOnClick" ><a class="challengeBtn" onclick="ajaxLoad('/profile/challenge', 'challenge', 'pid:<?php echo $list["uid"]; ?>');">{L:DISCOVER_CHALLENGE}</a></div>

                    <div id="challenge<?php echo $list["uid"]; ?>"></div>
                <?php }elseif($matchCount>0){ ?>
                    <div id="challenge<?php echo $list["uid"]; ?>">You have already challenge for this profile!</div>
                <?php }else{ ?>
                    <div id="challenge<?php echo $list["uid"]; ?>">You can't challenge yourself!</div>
                <?php } ?>

            </div>

            <?php

    }
    ?>
        </div>

</div>