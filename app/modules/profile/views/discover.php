<style>
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
  /*  .user-list .profile-img:before {
        content: "";
        background-color: #1f1f1f;
        position: absolute;
        width: 120px;
        height: 120px;
        text-align: center;
        -webkit-border-radius: 60px;
        -moz-border-radius: 60px;
        border-radius: 60px;

    }*/
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

</style>
<div class="discover-page-wrapper">
<h1>{L:DISCOVER_WELCOME}</h1>
<h2>{L:DISCOVER_ABOUT}</h2>
<div class="user-list">

        <?php
        $userID = Request::getParam('user')->id;
               while ($list = mysqli_fetch_assoc($this->list)){

                     $lastLooking = ceil((time() - $list["last_looking"])/60);
                     $lastAvailable = ceil((time() - $list["dateLast"])/60);

                    if(($lastLooking>60)&&($list["looking"]==1))
                    {
                        $model = new ProfileModel();
                        $model->updateDiscoverRecord($list["id"],"`looking`=0");
                    }else{
                        $model = new ProfileModel();
                        $matchCount = $model->checkMatchExist($userID,$list["uid"]);
                        $text = ($list["looking"]==1)? Lang::translate('DISCOVER_LOOKING_TEXT')." $list[amount]$ ".$lastLooking.Lang::translate('DISCOVER_MINUTES_AGO') : Lang::translate('DISCOVER_LAST_ONLINE').$lastAvailable.Lang::translate('DISCOVER_MINUTES_AGO') ;

                      ?>

                            <div class="player-info">
                                <div class="profile-img">
                                    <a href="/<?php echo $list["uid"]; ?>">
                                     <img src="/app/public/images/img/avatar.jpg" alt="Player photo">
                                    </a>
                                </div>
                                <p class="margin-bottom-zero">
                                    <a href="/<?php echo $list["uid"]; ?>">
                                        <span class="player-name" ><?php echo $list["username"]; ?></span>
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
               }
        ?>

</div>
</div>