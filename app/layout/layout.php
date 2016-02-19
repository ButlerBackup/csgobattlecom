<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $this->title; ?>{L:TITLE}</title>
        <link href="<?php echo _SITEDIR_; ?>public/css/style.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="<?php echo _SITEDIR_; ?>public/images/favicon.ico">
        <!--<script src="<?php echo _SITEDIR_; ?>public/js/jquery.min.js"></script>-->
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo _SITEDIR_; ?>public/js/ajax_function.js"></script>
        <script src="<?php echo _SITEDIR_; ?>public/js/function.js"></script>
        <script src="<?php echo _SITEDIR_; ?>public/js/jquery.bpopup.min.js"></script>

        <script src="<?php echo _SITEDIR_; ?>public/js/small-functions.js"></script>

        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="wrapper">

            <div class="fixed_menu">
                <ul>
                    <li><a href="/page/howtoplay" >{L:HOW_TO_PLAY}</a></li>
                    <li><a href="/page/rules" >{L:RULES}</a></li>
                    <li><a href="https://csgobattle.supportsystem.com/" >{L:CUSTOMER_SUPPORT}</a></li>
                    <li><a href="/discover" >{L:PLAY_NOW}</a></li>
                </ul>
            </div>

            <div class="left_col">
                <div class="left_body">
                    <?php echo $this->Load('leftMenu'); ?>
                </div>
            </div>

            <div class="main_col">
                <div class="main_body">
                    <?php
                    echo $this->Content();
                    echo $this->Load('rightMenu');
                    ?>
                </div>
            </div>
        </div>
		
		<footer>
            <div class="footer-wrapper">
                <div class="footer_menu">
                <h3>{L:FOOTER_SOCIAL}</h3>
                <ul>
                    <li><a href="#" >{L:TWITCH}</a></li>
                    <li><a href="#" >{L:FACEBOOK}</a></li>
                    <li><a href="#" >{L:TWITTER}</a></li>
                    <li><a href="#" >{L:REDDIT}</a></li>
                </ul>
                </div>
                <div class="footer_menu">
                    <h3>{L:FOOTER_INFO}</h3>
                    <ul>
                        <li><a href="/main" >{L:MENU_NEWS}</a></li>
                        <li><a href="#" >{L:FOOTER_CONTACT_US}</a></li>
                        <li><a href="/servers" >{L:MENU_SERVERS}</a></li>
                        <li><a href="#" >{L:FOOTER_FAQ}</a></li>
                    </ul>
                </div>
                <div class="footer_menu">
                    <h3>{L:FOOTER_FEATURES}</h3>
                    <ul>
                        <li><a href="/chat" >{L:MENU_CHAT}</a></li>
                        <li><a href="/discover" >{L:MENU_DISCOVER_PAGE}</a></li>
                        <li><a href="/ladders" >{L:MENU_LADDERS}</a></li>
                        <li><a href="/maps" >{L:MENU_MAPS}</a></li>
                    </ul>
                </div>
            </div>
        </footer>

        <div id="popup"></div>

        <script>
		jQuery(function()
		{
			setInterval(function(){
			jQuery.ajax({url: "/chat/updateuseronline", success: function(result){
			
		}}); }, 3000);
		});
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-67305554-1', 'auto');
            ga('send', 'pageview');

        </script>
    </body>
</html>
