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
        'claim' => array(
            'allow' => false,
            'redirect' => url('profile','verification')
        ),
        'ban' => array(
            'allow' => false,
            'redirect' => url('profile','ban')
        )
    ),

    'index' => array(
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
    ),

    'control_match' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('main')
        ),
        'admin' => array(
            'allow' => true
        )
    ),

    'steam' => array(
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
    ),

    'verification' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('main')
        ),
        'claim' => array(
            'allow' => true,
        )
    ),

    'exit' => array(
        '*' => array(
            'allow' => true
        ),
        'guest' => array(
            'allow' => true
        )
    ),

    'ban' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('main')
        ),
        'ban' => array(
            'allow' => true,
        )
    )
);

/* End of file */