<?php

echo ajaxSetInterval(ajaxLoad(url('chat','get'), 'chat_get', '#pid!', 'chatScroll'), 1300);

echo ajaxKeyDown(13, '$(".chat_submit").click();');

if ($this->profile->dateLast >= time()-300)

    $online = '<div class="on"></div>';

else

    $online = '<div class="off"></div>';

?>
<?php $onlines=$this->onlines; 
 $list = array_reverse((array)$this->list);
?>

<div class="hed">
<h1>{L:INDEX_TITLE}</h1>
</div>

<div class="online">

<div class="online1"><div class="hd">Who is Online</div></div>
<ul class="ulist"><?php foreach($onlines as $user)
{ 

 
 
            

?>
<li class="listu"><?php echo $user['nickname']; 
 ?><a onclick="ajaxLoad('/friends/sendRequest', 'reqest', 'pid:<?php echo $user['id'];?>');"><span class="addfreiend">+</span></a></li>
<?php } ?>

</ul>	
</div>

<div class="chatMain">

    <div class="chatBody" id="dialog">

        <?php

       // $list = array_reverse((array)$this->list);

        if ($list) {

            foreach ($list as $value)

            {

                $msg = ' '.$value['message'];

                if (strpos($msg, Request::getParam('user')->nickname) !== false)

                    $color = ' chat_your_msg';

                else

                    $color = false;



                echo '<div class="chat_message'.$color.'">'

                        .'<div class="chat_img"><a href="'.url($value['uid']).'" target="_blank"><img src="'.getAvatar($value['uid'], 's').'"></a></div>'

                        .'<div class="chat_text">'

                            .'<div><span class="chat_nickname" onclick="chatNickname(\''.$value['uName'].'\');">'.$value['uName'].'</span> <span class="chat_time">'.printTime($value['time']).'</span></div>'

                            .'<div>'.$value['message'].'</div>'

                        .'</div>'

                    .'</div>';

                setSession('chat_lmid', $value['id']);

            }

        }

        ?>

    </div>



    <script>

        var height = winH()-450;

        if (height < 400)

            height = 400;

        $('#dialog').css('max-height', height);

        chatScroll('dialog');

    </script>



    <div class="chatInput">

        <input id="msg" class="chatMsg" type="text" autocomplete="off" maxlength="1000">

        <div class="chatSubmit" onclick="<?php echo ajaxLoad(url('chat','send'), 'chat_get', '#msg|#pid!', 'ajaxFocus'); ?>">{L:INDEX_SEND}</div>

    </div>

</div>