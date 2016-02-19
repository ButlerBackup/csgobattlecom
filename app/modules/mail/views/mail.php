<?php
echo ajaxSetInterval(ajaxLoad(url('mail','get'), 'mail_get', '#hash!', 'chatScroll'), 1300);
echo ajaxKeyDown(13, '$(".chatSubmit").click();');
?>

<h1><a href="{URL:<?php echo $this->receiver; ?>}"><?php echo $this->name; ?></a></h1>

<div class="chatMain">
    <div class="chatBody" id="dialog">
        <?php
        $list = array_reverse((array)$this->list);
        if ($list) {
            foreach ($list as $value)
            {
                echo '<div class="chat_message">'
                    .'<div class="chat_img"><a href="'.url($value['uid']).'" target="_blank"><img src="'.getAvatar($value['uid'], 's').'"></a></div>'
                    .'<div class="chat_text">'
                    .'<div><span class="chat_nickname">'.$value['name'].'</span> <span class="chat_time">'.printTime($value['time']).'</span></div>'
                    .'<div>'.$value['message'].'</div>'
                    .'</div>'
                    .'</div>';
                setSession('mail_last_message'.$value['did'], $value['id']);
            }
        }
        ?>
    </div>

    <script>
        var height = winH()-450;
        if (height < 400)
            height = 400;
        $('#dialog, #userList').css('max-height', height);
        chatScroll('dialog');
    </script>

    <div class="chatInput">
        <input type="hidden" id="hash" value="<?php echo $this->hash; ?>">
        <input id="msg" class="chatMsg" type="text" autocomplete="off" maxlength="1000">
        <div class="chatSubmit" onclick="<?php echo ajaxLoad(url('mail','send'), 'mail_send', '#msg|#hash!', 'ajaxScroll|ajaxFocus'); ?>">{L:MAIL_SEND}</div>
    </div>
</div>