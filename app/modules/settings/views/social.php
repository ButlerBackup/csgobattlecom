<div class="box">

    <a href="<?php echo url('settings','general'); ?>">General</a> |
    <a href="<?php echo url('settings','favorites'); ?>">Favorites</a> |
    <a href="<?php echo url('settings','rig'); ?>">My Rig</a> |
    <a href="<?php echo url('settings','social'); ?>">Social</a>

    <div id="status"></div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('SOCIAL_FACEBOOK'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="facebook" value="<?php echo Request::getParam('user')->facebook; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('SOCIAL_TWITTER'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="twitter" value="<?php echo Request::getParam('user')->twitter; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('SOCIAL_STEAM'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="steam" value="<?php echo Request::getParam('user')->steam; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('SOCIAL_TWITCH'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="twitch" value="<?php echo Request::getParam('user')->twitch; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('SOCIAL_YOUTUBE'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="youtube" value="<?php echo Request::getParam('user')->youtube; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">

        </div>
        <div class="formRowField">
            <button onclick="<?php echo ajaxLoad(url('settings','social_save'), 'social', '#facebook!|#twitter!|#steam!|#twitch!|#youtube!'); ?>">
                <?php echo Lang::translate('SOCIAL_BUTTON_SAVE'); ?>
            </button>
        </div>
    </div>

</div>