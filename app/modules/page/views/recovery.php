<div class="box">
    <?php
    if (getCookie('error'))
        echo getCookie('error');
    ?>
    
    <?php
    if ($this->msg):
        echo $this->msg;
        echo '<br/><a href="'.url('page','recovery').'">'.Lang::translate("RECOVERY_GO_BACK").'</a>';
    else:
    ?>

    <form action="<?php echo url('page','recovery'); ?>" method="POST">
        <div class="formRow">
            <div class="formRowTitle">
                {L:RECOVERY_EMAIL}
            </div>
            <div class="formRowField">
                <input type="email" name="email" value="<?php echo (post('email'))? post('email') : '' ;  ?>" required="required" />
            </div>
        </div>
        
        <div class="formRow">
            <div class="formRowField">
                <input type="submit" value="Search" />
            </div>
        </div>
    </form>
    <?php endif;  ?>
</div>