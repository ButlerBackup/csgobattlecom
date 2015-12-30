<?php //echo 'test';

if ($this->profile->dateLast >= time()-300)

    $online = '<div class="on"></div>';

else

    $online = '<div class="off"></div>';



echo '<div class="profile_left">';

    echo '<div class="profile-details"><div class="avatar"><img src="'.getAvatar($this->profile->id).'">'.$online.'</div>';



    echo '<div class="profile_left_info">';

        echo '<div class="nick-and-rate"><div class="nickname">';

            echo $this->profile->nickname;



            if (Request::getParam('user')->id && Request::getParam('user')->id == $this->profile->id)

                echo ' <a class="red-text" href="'.url('settings','general').'">Edit</a>';

        echo '</div>';



        echo '<div>'.getRank($this->profile->elo).'</div></div>';//{L:INDEX_RANK}:







        echo '<div>';

            echo '{L:INDEX_RATING}: <span id="rating">'.$this->profile->rating.'</span>';

            echo ' <span onclick="'.ajaxLoad(url('profile','voice_plus'), 'voice_plus', 'pid:'.$this->profile->id.'|rat:'.$this->profile->rating).'">+</span> ';

        echo '</div>';



        echo '<div>{L:INDEX_ELO}: '.$this->profile->elo.'</div>';

        echo '<div>{L:INDEX_WINS}: '.$this->profile->wins.'</div>';

        echo '<div>{L:INDEX_TIES}: '.$this->profile->ties.'</div>';

        echo '<div>{L:INDEX_LOSSES}: '.$this->profile->losses.'</div>';

    echo '</div></div>';



    // Write message

    if (Request::getParam('user')->id && Request::getParam('user')->id != $this->profile->id) {

        echo '<div class="profile-menu">';

        if ($this->challenge)

            echo '<div id="challenge"><a onclick="'.ajaxLoad(url('profile','challenge'), 'challenge', 'pid:'.$this->profile->id).'">'.Lang::translate('INDEX_CHALLENGE').'</a></div>';



        echo '<a href="'.url('mail'.$this->profile->id).'">'.Lang::translate('INDEX_WRITE_MESSAGE').'</a>'; //ban??



        if (empty($this->friend)) {

            echo '<span id="request">'

                . '<a onclick="'.ajaxLoad(url('friends','sendRequest'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate('INDEX_ADD_FRIEND').'</a>'

                . '<a onclick="'.ajaxLoad(url('friends','banRequest'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_BAN_REQUEST").'</a>'

                . '</span>';

        } elseif($this->friend['status']) {

            echo '<span id="request">'//'.Lang::translate("INDEX_FRIENDS_NOW").'

                . '<a onclick="'.ajaxLoad(url('friends','deleteFriend'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_DELETE_FROM_FRIENDS").'</a>'

                . '<a onclick="'.ajaxLoad(url('friends','banRequest'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_BAN_REQUEST").'</a>'

                . '</span>';

        } elseif(!$this->friend['status'] && !$this->friend['ban'] ) {

            if ($this->friend['uid'] == Request::getParam('user')->id)

                echo '<span id="request">'.Lang::translate("INDEX_REQEST_SENT_BY").''

                    . '<a onclick="'.ajaxLoad(url('friends','cancelRequest'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_CANCEL_REQUEST").'</a>'

                    . '<a onclick="'.ajaxLoad(url('friends','banRequest'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_BAN_REQUEST").'</a>'

                    . '</span>';

            elseif ($this->friend['uid'] == $this->profile->id)

                echo '<span id="request">'.Lang::translate("INDEX_REQEST_SENT_TO").''

                    . '<a onclick="'.ajaxLoad(url('friends','acceptRequest'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_REQEST_SENT_TO_ACCEPT").'</a>'

                    . '<a onclick="'.ajaxLoad(url('friends','declineRequest'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_REQEST_SENT_TO_DECLINE").'</a>'

                    . '<a onclick="'.ajaxLoad(url('friends','banRequest'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_BAN_REQUEST").'</a>'

                    . '</span>';

        } elseif (!$this->friend['status'] && $this->friend['ban']) {

            if ($this->friend['uid'] == Request::getParam('user')->id)

                echo '<span id="request">'.Lang::translate("INDEX_BANNED_BY").''

                    . '<a onclick="'.ajaxLoad(url('friends','cancelBan'), 'reqest', 'pid:'.$this->profile->id).'">'.Lang::translate("INDEX_BAN_CANCEL").'</a>'

                    . '</span>';

            elseif ($this->friend['uid'] == $this->profile->id)

                echo '<span id="request">'.Lang::translate("INDEX_BANNED_TO")

                    . '</span>';

        }

        echo '</div>';

    }else{
        echo '<div class="profile-menu small-menu">';
        if (empty($this->discover->available)) {

            echo '<span id="available">'

                . '<a  class="available-h" onclick="'.ajaxLoad(url('profile','playerVisibility'), 'reqest','task:show|mid:'.$this->profile->id).'">'.Lang::translate('INDEX_SHOW').'</a>'

                . '</span>';

        } else {

            echo '<span id="available">'

                . '<a  class="available" onclick="'.ajaxLoad(url('profile','playerVisibility'), 'reqest','task:hide|mid:'.$this->profile->id).'">'.Lang::translate("INDEX_HIDE").'</a>'

                . '</span>';

        }

        if (empty($this->discover->looking)) {

            echo '<span id="looking">'

                . '<input id="challenge-amount" class="challenge-amount" list="amount" value="5" name="amount">
                    <datalist  id="amount">
                     <option value="5">5$</option>
                     <option value="10">10$</option>
                     <option value="20">20$</option>
                     <option value="50">50$</option>
                     <option value="100">100$</option>
                   </datalist>
                   <a  class="looking-h" onclick="ajaxLoad(\''

                . url('profile','playerVisibility')

                . "', 'reqest', 'task:look|amount:'+ $('#challenge-amount').val() +'|mid:"

                . $this->profile->id

                . '\')">' . Lang::translate('INDEX_LOOKING_CHALLENGE') . '</a>'

                . '</span>';

        } else{

            echo '<span id="looking">'

                . '<a  class="looking" onclick="'.ajaxLoad(url('profile','playerVisibility'), 'reqest','task:sttop|mid:'.$this->profile->id).'">'.Lang::translate("INDEX_STOP_LOOKING").'</a>'

                . '</span>';

        }
        echo '</div>';
    }

echo '</div>';

echo "<div id='iwannaanswer'></div>";



echo '<div class="profile_right">';

    echo '<div class="pr_block left">';

        // General

        echo '<div class="pTitle">'.Lang::translate('INDEX_PERSONAL').'</div>';

        if ($this->profile->realname)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_REALNAME').': '.$this->profile->realname.'</div>';



        if ($this->profile->country)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_COUNTRY').': '.$this->country->name.' <img src="'._SITEDIR_.'public/images/country/'.mb_strtolower($this->profile->country).'.png"></div>';



        if ($this->profile->city)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_CITY').': '.$this->profile->city.'</div>';



        if ($this->profile->sex)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_SEX').': '.Lang::translate('INDEX_SEX_'.$this->profile->sex).'</div>';



        if ($this->profile->mm OR $this->profile->dd OR $this->profile->yyyy)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_AGE').': '.(($this->profile->mm) ? $this->profile->mm : '**').'/'.(($this->profile->dd) ? $this->profile->dd : '**').'/'.(($this->profile->yyyy) ? $this->profile->yyyy : '****').'</div>';



        if ($this->profile->about)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_ABOUT').': '.$this->profile->about.'</div>';

    echo '</div>';



    echo '<div class="pr_block right">';

        // Social

        echo '<div class="pTitle">'.Lang::translate('INDEX_SOCIAL_TITLE').'</div>';

        if ($this->profile->facebook)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_SOCIAL_FACEBOOK').': '.$this->profile->facebook.'</div>';



        if ($this->profile->twitter)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_SOCIAL_TWITTER').': '.$this->profile->twitter.'</div>';



        //if ($this->profile->steam)

            //echo '<div class="profileRowInfo">'.Lang::translate('INDEX_SOCIAL_STEAM').': '.$this->profile->steam.'</div>';



        // Steam ID

        if ($this->profile->steamid OR Request::getParam('user')->id == $this->profile->id)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_SOCIAL_STEAM').': '.(($this->profile->steamid) ? '<a href="http://steamcommunity.com/profiles/'.$this->profile->steamid.'">'.$this->profile->steamid.'</a>' : '<a href="'.url('profile','steam').'">'.Lang::translate('INDEX_CHECK_STEAMID').'</a>').'</div>';

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_SOCIAL_TRADE_LINK').': <span id="steam-trade-span"> <a style="font-size:12px;cursor:pointer" class="steam-trade-link" >'.$this->steamtradelink.'</a></span></div>';
            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_TD_CAN_FIND_HERE').' <a href="http://steamcommunity.com/id/tassadarcsgo/tradeoffers/privacy" >"here"</a>.</div>';

        if ($this->profile->twitch)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_SOCIAL_TWITCH').': '.$this->profile->twitch.'</div>';



        if ($this->profile->youtube)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_SOCIAL_YOUTUBE').': '.$this->profile->youtube.'</div>';

    echo '</div>';

    echo '<div class="pHr"></div>';



    echo '<div class="pr_block left">';

        // My rig

        echo '<div class="pTitle">'.Lang::translate('INDEX_RIG_TITLE').'</div>';

        if ($this->profile->videoCard)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_VIDEO_CARD').': '.$this->profile->videoCard.'</div>';



        if ($this->profile->soundCard)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_SOUND_CARD').': '.$this->profile->soundCard.'</div>';



        if ($this->profile->cpu)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_CPU').': '.$this->profile->cpu.'</div>';



        if ($this->profile->ram)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_RAM').': '.$this->profile->ram.'</div>';



        if ($this->profile->hardDrive)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_HARD_DRIVE').': '.$this->profile->hardDrive.'</div>';



        if ($this->profile->os)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_OS').': '.$this->profile->os.'</div>';



        if ($this->profile->headset)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_HEADSET').': '.$this->profile->headset.'</div>';



        if ($this->profile->mouse)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_MOUSE').': '.$this->profile->mouse.'</div>';



        if ($this->profile->mousepad)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_MOUSEPAD').': '.$this->profile->mousepad.'</div>';



        if ($this->profile->keyboard)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_KEYBOARD').': '.$this->profile->keyboard.'</div>';



        if ($this->profile->monitor)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_MONITOR').': '.$this->profile->monitor.'</div>';



        if ($this->profile->iCase)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_RIG_CASE').': '.$this->profile->iCase.'</div>';

    echo '</div>';



    echo '<div class="pr_block right">';

        // Favorites

        echo '<div class="pTitle">'.Lang::translate('INDEX_FAVORITES_TITLE').'</div>';

        if ($this->profile->iPlayer)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_FAVORITES_PLAYER').': '.$this->profile->iPlayer.'</div>';



        if ($this->profile->iTeam)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_FAVORITES_TEAM').': '.$this->profile->iTeam.'</div>';



        if ($this->profile->iGame)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_FAVORITES_GAME').': '.$this->profile->iGame.'</div>';



        if ($this->profile->iRole)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_FAVORITES_ROLE').': '.$this->profile->iRole.'</div>';



        if ($this->profile->iMusic)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_FAVORITES_MUSIC').': '.$this->profile->iMusic.'</div>';



        if ($this->profile->iFood)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_FAVORITES_FOOD').': '.$this->profile->iFood.'</div>';



        if ($this->profile->iSport)

            echo '<div class="profileRowInfo">'.Lang::translate('INDEX_FAVORITES_SPORT').': '.$this->profile->iSport.'</div>';

    echo '</div>';

    echo '<div class="pHr"></div>';



    echo '<div class="pr_block left">';

        // Referral

        echo '<div class="pTitle">'.Lang::translate('INDEX_INVITE').'</div>';

        echo '<div class="profileRowInfo">'.Lang::translate('INDEX_REFERRAL').': '.$this->ref_count.'</div>';



        // Reg code

        if (Request::getParam('user')->id && Request::getParam('user')->id == $this->profile->id)

            echo '<div><a onclick="'.ajaxLoad(url('profile','regcode'), 'reg_code').'">'.Lang::translate('INDEX_GET_REG_CODE').'</a>: <span id="reg_code"></span></div>';

    echo '</div>';



    echo '<div class="pr_block right">';

        // Info

        echo '<div class="pTitle">'.Lang::translate('INDEX_INFO').'</div>';

        echo '<div class="profileRowInfo">'.Lang::translate('INDEX_LAST_VISIT').': '.printTime($this->profile->dateLast).'</div>';

        echo '<div class="profileRowInfo">'.Lang::translate('INDEX_DATE_REG').': '.printTime($this->profile->dateReg).'</div>';

    echo '</div>';

    echo '<div class="pHr"></div>';



    echo '<div class="merits">';

        if ($this->profile->steamid)

            echo '<div><img src="'._SITEDIR_.'public/images/img/steamverie.jpg" title=""></div>';



        if ($this->profile->role != 'guest' && $this->profile->role != 'claim')

            echo '<div><img src="'._SITEDIR_.'public/images/img/verifieduser.jpg" title=""></div>';

    echo '</div>';

echo '</div>';

echo '<div class="clear"></div>';

?>