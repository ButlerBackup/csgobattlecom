<div class="box">

    <a href="<?php echo url('settings','general'); ?>">General</a> |
    <a href="<?php echo url('settings','favorites'); ?>">Favorites</a> |
    <a href="<?php echo url('settings','rig'); ?>">My Rig</a> |
    <a href="<?php echo url('settings','social'); ?>">Social</a>

    <div id="status"></div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('FAVORITES_PLAYER'); ?>:
        </div>
        <div class="formRowField">
            <textarea id="iPlayer"><?php echo Request::getParam('user')->iPlayer; ?></textarea>
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('FAVORITES_TEAM'); ?>:
        </div>
        <div class="formRowField">
            <textarea id="iTeam"><?php echo Request::getParam('user')->iTeam; ?></textarea>
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('FAVORITES_GAME'); ?>:
        </div>
        <div class="formRowField">
            <textarea id="iGame"><?php echo Request::getParam('user')->iGame; ?></textarea>
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('FAVORITES_ROLE'); ?>:
        </div>
        <div class="formRowField">
            <textarea id="iRole"><?php echo Request::getParam('user')->iRole; ?></textarea>
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('FAVORITES_MUSIC'); ?>:
        </div>
        <div class="formRowField">
            <textarea id="iMusic"><?php echo Request::getParam('user')->iMusic; ?></textarea>
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('FAVORITES_FOOD'); ?>:
        </div>
        <div class="formRowField">
            <textarea id="iFood"><?php echo Request::getParam('user')->iFood; ?></textarea>
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('FAVORITES_SPORT'); ?>:
        </div>
        <div class="formRowField">
            <textarea id="iSport"><?php echo Request::getParam('user')->iSport; ?></textarea>
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">

        </div>
        <div class="formRowField">
            <button onclick="<?php echo ajaxLoad(url('settings','favorites_save'), 'favorites', '#iPlayer!|#iTeam!|#iGame!|#iRole!|#iMusic!|#iFood!|#iSport!'); ?>">
                <?php echo Lang::translate('FAVORITES_BUTTON_SAVE'); ?>
            </button>
        </div>
    </div>

</div>