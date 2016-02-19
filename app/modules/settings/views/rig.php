<div class="box">

    <a href="<?php echo url('settings','general'); ?>">General</a> |
    <a href="<?php echo url('settings','favorites'); ?>">Favorites</a> |
    <a href="<?php echo url('settings','rig'); ?>">My Rig</a> |
    <a href="<?php echo url('settings','social'); ?>">Social</a>

    <div id="status"></div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_VIDEO_CARD'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="videoCard" value="<?php echo Request::getParam('user')->videoCard; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_SOUND_CARD'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="soundCard" value="<?php echo Request::getParam('user')->soundCard; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_CPU'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="cpu" value="<?php echo Request::getParam('user')->cpu; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_RAM'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="ram" value="<?php echo Request::getParam('user')->ram; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_HARD_DRIVE'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="hardDrive" value="<?php echo Request::getParam('user')->hardDrive; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_OS'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="os" value="<?php echo Request::getParam('user')->os; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_HEADSET'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="headset" value="<?php echo Request::getParam('user')->headset; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_MOUSE'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="mouse" value="<?php echo Request::getParam('user')->mouse; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_MOUSEPAD'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="mousepad" value="<?php echo Request::getParam('user')->mousepad; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_KEYBOARD'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="keyboard" value="<?php echo Request::getParam('user')->keyboard; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_MONITOR'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="monitor" value="<?php echo Request::getParam('user')->monitor; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('RIG_CASE'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" id="iCase" value="<?php echo Request::getParam('user')->iCase; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">

        </div>
        <div class="formRowField">
            <button onclick="<?php echo ajaxLoad(url('settings','rig_save'), 'rig', '#videoCard!|#soundCard!|#cpu!|#ram!|#hardDrive!|#os!|#headset!|#mouse!|#mousepad!|#keyboard!|#monitor!|#iCase!'); ?>">
                <?php echo Lang::translate('RIG_BUTTON_SAVE'); ?>
            </button>
        </div>
    </div>

</div>