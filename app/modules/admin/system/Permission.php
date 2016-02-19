<?php
$permission = array(
    '*' => array(
        '*' => array(
            'allow' => false,
            'redirect' => url('main')
        ),
        'admin' => array(
            'allow' => true
        ),
        'moder' => array(
            'allow' => true
        )
    ),
);

/* End of file */