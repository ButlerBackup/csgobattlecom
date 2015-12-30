<?php

$permission = array(

    'index' => array(

        '*' => array(

            'allow' => false,

            'redirect' => url('main')

        ),

        'guest' => array(

            'allow' => true,

        )

    ),



    'main' => array(

        '*' => array(

            'allow' => true

        ),

        'guest' => array(

            'allow' => true,

            'redirect' => url()

        )

    ),



    'auth' => array(

        '*' => array(

            'allow' => false,

            'redirect' => url('main')

        ),

        'guest' => array(

            'allow' => true,

        )

    ),



    'reg' => array(

        '*' => array(

            'allow' => false,

            'redirect' => url('main')

        ),

        'guest' => array(

            'allow' => true,

        )

    )
);



/* End of file */