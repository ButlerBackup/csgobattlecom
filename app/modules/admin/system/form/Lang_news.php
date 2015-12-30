<?php
class Lang_newsForm extends Form
{
    /*
    'field' => array(
        'type' => 'text',
        'name' => 'nick',
        'label' => Lang::translate('REG_NICK'),
        'id' => 'nick',
        'class' => 'myclass',
        'placeholder' => Lang::translate('REG_SUBMIT'),
        'autocomplete' => false,
        'value' => '',
        'filter' => array(
            'required' => true,
            'number' => true,
            'email' => true,
            'value' => true,
            'equal' => 'password2',
            'lengthMin' => 3,
            'lengthMax' => 10,
            'rangeMin' => 10,
            'rangeMax' => 100,
            'regx' => '~^([0-9]{2})/([0-9]{2})/([0-9]{4})+$~i',
            'return' => false, // if empty not return
        )
    )
    */

    public function __construct()
    {
        $elements = array(
            'name' => array(
                'filter' => array(
                    'required' => true
                )
            ),
            'lang' => array(
                'filter' => array(
                    'required' => true,
                    'lengthMin' => 2,
                    'lengthMax' => 2
                )
            ),
            'text' => array(
                'filter' => array(
                    'required' => true
                )
            )
        );

        $this->setElements($elements);
    }
}

/* End of file */