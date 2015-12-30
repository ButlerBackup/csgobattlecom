<?php
$permission = array(
    'index' => array(
        '*' => array(
            'allow' => true
        ),
        'guest' => array(
            'allow' => false,
            'redirect' => url('page')
        ),
        'claim' => array(
            'allow' => false,
            'redirect' => url('profile','verification')
        ),
        'ban' => array(
            'allow' => false,
            'redirect' => url('profile','ban')
        )
    )
);

/* End of file */