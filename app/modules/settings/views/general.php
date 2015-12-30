<div class="box">

    <a href="<?php echo url('settings','general'); ?>">General</a> |
    <a href="<?php echo url('settings','favorites'); ?>">Favorites</a> |
    <a href="<?php echo url('settings','rig'); ?>">My Rig</a> |
    <a href="<?php echo url('settings','social'); ?>">Social</a>

    <div id="status"></div>

    <form action="<?php echo url('settings','general'); ?>" method="post" enctype="multipart/form-data">
        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_NICKNAME'); ?>:
            </div>
            <div class="formRowField">
                <input type="text" name="nickname" value="<?php echo Request::getParam('user')->nickname; ?>" disabled>
            </div>
        </div>

        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_REALNAME'); ?>:
            </div>
            <div class="formRowField">
                <input type="text" name="realname" value="<?php echo Request::getParam('user')->realname; ?>">
            </div>
        </div>

        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_COUNTRY'); ?>:
            </div>
            <div class="formRowField">
                <select name="country">
                    <option value=""><?php echo Lang::translate('GENERAL_SELECT_COUNTRY'); ?></option>
                    <?php
                    while ($country = mysqli_fetch_object($this->countrysList))
                    {
                        if ($country->code == Request::getParam('user')->country)
                            $sel = 'selected="selected"';
                        else
                            $sel = null;
                        echo '<option value="'.$country->code.'" '.$sel.'>'.$country->name.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_CITY'); ?>:
            </div>
            <div class="formRowField">
                <input type="text" name="city" value="<?php echo Request::getParam('user')->city; ?>">
            </div>
        </div>

        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_SEX'); ?>:
            </div>
            <div class="formRowField">
                <select name="sex">
                    <?php
                    if (Request::getParam('user')->sex == 0) $sel = 'selected="selected"'; else $sel = null;
                    echo '<option value="0" '.$sel.'>'.Lang::translate('GENERAL_SEX_0').'</option>';
                    if (Request::getParam('user')->sex == 1) $sel = 'selected="selected"'; else $sel = null;
                    echo '<option value="1" '.$sel.'>'.Lang::translate('GENERAL_SEX_1').'</option>';
                    if (Request::getParam('user')->sex == 2) $sel = 'selected="selected"'; else $sel = null;
                    echo '<option value="2" '.$sel.'>'.Lang::translate('GENERAL_SEX_2').'</option>';
                    ?>
                </select>
            </div>
        </div>

        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_AGE'); ?>:
            </div>
            <div class="formRowField">
                <input class="w75px" type="text" name="mm" value="<?php echo Request::getParam('user')->mm; ?>" maxlength="2" placeholder="mm">
                <input class="w75px" type="text" name="dd" value="<?php echo Request::getParam('user')->dd; ?>" maxlength="2" placeholder="dd">
                <input class="w75px" type="text" name="yyyy" value="<?php echo Request::getParam('user')->yyyy; ?>" maxlength="4" placeholder="yyyy">
            </div>
        </div>

        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_AVATAR'); ?>:
            </div>
            <div class="formRowField">
                <div class="avatar-table">
                    <div class="avatar-full"><img src="<?php echo getAvatar(Request::getParam('user')->id); ?>" alt=""></div>
                    <div class="avatar-medium"><img src="<?php echo getAvatar(Request::getParam('user')->id, 'm'); ?>" alt=""></div>
                    <div class="avatar-icon"><img src="<?php echo getAvatar(Request::getParam('user')->id, 's'); ?>" alt=""></div>
                </div>
                <input type="file" name="file">
            </div>
        </div>

        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_TRADE_LINK'); ?>:
            </div>
            <div class="formRowField">
                <input type="text" name="tradelink" value="<?php if(Request::getParam('user')->partner && Request::getParam('user')->token) echo "https://steamcommunity.com/tradeoffer/new/?partner=".Request::getParam('user')->partner."&token=".Request::getParam('user')->token; ?>" />
            </div>
        </div>
        
        <div class="formRow">
            <div class="formRowTitle">
                <?php echo Lang::translate('GENERAL_ABOUT'); ?>:
            </div>
            <div class="formRowField">
                <textarea name="about"><?php echo Request::getParam('user')->about; ?></textarea>
            </div>
        </div>

        <div class="formRow">
            <div class="formRowTitle">

            </div>
            <div class="formRowField">
                <input type="submit" value="<?php echo Lang::translate('GENERAL_BUTTON_SAVE'); ?>">
            </div>
        </div>
    </form>

</div>