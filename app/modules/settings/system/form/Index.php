<?php
class IndexForm extends Form
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
            'min' => 3,
            'max' => 10,
        )
    )
    */

    public function __construct()
    {
        $elements = array(
            'password' => array(
                'filter' => array(
                    'required' => true,
                    'min' => 6,
                    'max' => 20
                )
            ),
            'password1' => array(
                'filter' => array(
                    'min' => 6,
                    'max' => 20,
                    'equal' => 'password2'
                )
            ),
            'password2' => array(
                'filter' => array(
                    'min' => 6,
                    'max' => 20
                )
            ),
            'email' => array(
                'filter' => array(
                    'email' => true
                )
            ),
            'news' => array()
        );

        $this->setElements($elements);
    }
}

/* End of file */