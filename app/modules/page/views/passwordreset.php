<div class="box">
    <?php
    if (getCookie('error'))
        echo getCookie('error');
    ?>
    
    <?php
    if ($this->msg)
        echo $this->msg;
    
    if (!$this->success):
    ?>

    <form action="<?php echo url('page','passwordReset',$this->hash); ?>" method="POST">
        <div class="formRow">
            <div class="formRowTitle">
                {L:PASSWORD_RESET_EMAIL}
            </div>
            <div class="formRowField">
                <input type="email" name="email" value="<?php echo (post('email'))? post('email') : '' ;  ?>" required="required" />
            </div>
        </div>
        <div class="formRow">
            <div class="formRowTitle">
                {L:PASSWORD_RESET_PASSWORD}
            </div>
            <div class="formRowField">
                <input type="password" name="password" value="<?php echo (post('password'))? post('password') : '' ;  ?>" required="required" />
            </div>
        </div>
        <div class="formRow">
            <div class="formRowTitle">
                {L:PASSWORD_RESET_RE_PASSWORD}
            </div>
            <div class="formRowField">
                <input type="password" name="password2" value="<?php echo (post('password2'))? post('password2') : '' ;  ?>" required="required" />
            </div>
        </div>
        <div class="formRow">
            <div class="formRowField">
                <input type="submit" value="Reset" />
            </div>
        </div>
    </form>
    
    <?php endif; ?>
</div>