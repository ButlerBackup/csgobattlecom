<?php
$permission = array(
    '*' => array(
        '*' => array(
            'allow' => true,
            'redirect' => url('main')
        ),
        'guest' => array(
            'allow' => false,
            'redirect' => url()
        ),
        'ban' => array(
            'allow' => false,
            'redirect' => url('profile','ban')
        )
    )
);

/* End of file */