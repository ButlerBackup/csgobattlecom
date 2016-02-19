<h1>{L:INDEX_TITLE}</h1>
<?php
$model = new SettingsModel();
$email = mysqli_fetch_object($model->getEmail());
if ($this->error)
    echo $this->error;
?>

<form action="<?php echo url('settings'); ?>" method="post" enctype="multipart/form-data">
    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('INDEX_OLD_PASSWORD'); ?>:
        </div>
        <div class="formRowField">
            <input type="password" name="password" autocomplete="off">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('INDEX_NEW_PASSWORD'); ?>:
        </div>
        <div class="formRowField">
            <input type="password" name="password1" autocomplete="off">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('INDEX_NEW_PASSWORD_REPEAT'); ?>:
        </div>
        <div class="formRowField">
            <input type="password" name="password2" autocomplete="off">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('INDEX_NEW_EMAIL'); ?>:
        </div>
        <div class="formRowField">
            <input type="text" name="email" autocomplete="off" value="<?php echo $email->email; ?>">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle">
            <?php echo Lang::translate('INDEX_NEWS_EMAIL'); ?>:
        </div>
        <div class="formRowField">
            <input type="checkbox" name="news" value="1" autocomplete="off" checked="checked">
        </div>
    </div>

    <div class="formRow">
        <div class="formRowTitle"></div>
        <div class="formRowField">
            <input type="submit" value="<?php echo Lang::translate('INDEX_BUTTON_SAVE'); ?>">
        </div>
    </div>
</form>