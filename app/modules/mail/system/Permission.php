<?php
$permission = array(
    '*' => array(
        '*' => array(
            'allow' => true
        ),
        'guest' => array(
            'allow' => false,
            'redirect' => url()
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