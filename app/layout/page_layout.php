<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $this->title; ?>{L:TITLE}</title>
        <link href="<?php echo _SITEDIR_; ?>public/css/novecentosanswide.css" type="text/css" rel="stylesheet" />
        <link href="<?php echo _SITEDIR_; ?>public/css/style_page.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="<?php echo _SITEDIR_; ?>public/images/favicon.ico">
        <script src="<?php echo _SITEDIR_; ?>public/js/jquery.min.js"></script>
        <script src="<?php echo _SITEDIR_; ?>public/js/ajax_function.js"></script>
        <script src="<?php echo _SITEDIR_; ?>public/js/function.js"></script>
 		<script src="<?php echo _SITEDIR_; ?>public/js/jquery.bpopup.min.js"></script>
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <header>
            <div class="wrap">
                <div class="logo"><a href="{URL:/}"></a></div>

                <div class="login">
                    <div class="box_title">{L:INDEX_LOG_IN}</div>

                    <form method="post" action="{URL:page/auth}">
                        <div class="field email"><input type="email" name="email" placeholder="{L:EMAIL}"></div>
                        <div class="field pass"><input type="password" name="password" placeholder="{L:PASSWORD}"></div>
                        <div class="submit"><input type="submit" value="{L:LOGIN}"></div>
                      <?php    if (getCookie('login_error'))
            echo '<div class="errormsg">'.getCookie('login_error').'</div>';
        ?>


                    <div class="recovery">
                        <a href="{URL:page/recovery}">{L:FORGOT_PASSWORD}?</a>
                        <a class="sign-up"  href="{URL:page/reg}">{L:MENU_REGISTRATION}</a>
                    </div>
                    <div>

                        <label for="remember-me">Remember me</label><input id="remember-me" type="checkbox" value="1" name="remember-me" >
                       <!-- <a style="float: right;" href="{URL:page/steam}">{L:LOGIN_WITH_STEAM}</a> -->
                    </div>
                    </form>
                </div>
            </div>
        </header>

        <?php echo $this->Content(); ?>

        <footer>
            2015 &copy; <?php echo SITE_NAME; ?>
        </footer>

        <script>
		
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-67305554-1', 'auto');
            ga('send', 'pageview');

        </script>
    </body>
</html>