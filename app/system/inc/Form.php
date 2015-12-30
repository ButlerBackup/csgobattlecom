<?php

/**
 * FORM
 */
class Form
{
    protected $method = 'post';

    protected $action = '';

    protected $id = '';

    protected $class = '';

    protected $elements = array();

    public $data = array();

    public $error = array();

    /**
     * @param string $method
     */
    protected function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param string $action
     */
    protected function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param string $id
     */
    protected function setID($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $class
     */
    protected function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @param array $elements
     */
    protected function setElements(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * @param mixed $error
     */
    protected function setError($error)
    {
        $this->error[] = mb_strtoupper($error);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isValid(array $data)
    {
        foreach ($this->elements as $key => $value) {
            $localError = 0;
            // Required
            if ($value['filter']['required'] === true) {
                if (!$data[$key]) {
                    $this->setError($key . '_required');
                    $localError = 1;
                }
            }

            // E-mail
            if ($value['filter']['email'] === true) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } elseif (!checkEmail($data[$key])) {
                    $this->setError($key . '_email');
                    $localError = 1;
                }
            }

            // Number
            if ($value['filter']['number'] === true) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } elseif (!is_numeric($data[$key]) && $data[$key] != 0) {
                    $this->setError($key . '_number');
                    $localError = 1;
                }
            }

            // Regx
            if ($value['filter']['regx']) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } elseif (!preg_match($value['filter']['regx'], trim($data[$key]))) {
                    $this->setError($key . '_regx');
                    $localError = 1;
                }
            }

            // Equal
            if ($value['filter']['equal']) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } elseif ($data[$key] != $data[$value['filter']['equal']]) {
                    $this->setError($key . '_equal_' . $value['filter']['equal']);
                    $localError = 1;
                }
            }

            // Length min
            if ($value['filter']['lengthMin']) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } elseif (mb_strlen($data[$key]) < intval($value['filter']['lengthMin'])) {
                    $this->setError($key . '_lengthMin');
                    $localError = 1;
                }
            }

            // Length max
            if ($value['filter']['lengthMax']) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } elseif (mb_strlen($data[$key]) > intval($value['filter']['lengthMax'])) {
                    $this->setError($key . '_lengthMax');
                    $localError = 1;
                }
            }

            // Range min
            if ($value['filter']['rangeMin']) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } elseif ($data[$key] < intval($value['filter']['rangeMin'])) {
                    $this->setError($key . '_rangeMin');
                    $localError = 1;
                }
            }

            // Range max
            if ($value['filter']['rangeMax']) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } elseif ($data[$key] > intval($value['filter']['rangeMax'])) {
                    $this->setError($key . '_rangeMax');
                    $localError = 1;
                }
            }

            if ($localError == 0) {
                if ($value['filter']['required'] !== true && !$data[$key] && $value['filter']['return'] === false) {
                    //
                } else {
                    $this->data[$key] = $data[$key];
                }
            }
        }

        if (empty($this->error))
            return true;
        else
            return false;
    }

    public function printForm()
    {
        $partForm = '';
        $fields = '';

        foreach ($this->elements as $key => $input) {
            // Data
            $data = allPost();

            // Filter
            $filter = $input['filter'];

            // Class
            if ($input['class'])
                $fields .= '<div class="' . $input['class'] . '">';

            // Label
            if ($input['label']) {
                $fields .= '<label';
                if ($input['name'])
                    $fields .= ' for="' . $input['name'] . '"';

                if ($filter['required'])
                    $fields .= ' class="required"';
                $fields .= '>' . $input['label'] . (($filter['required']) ? '*' : '') . '</label>';
            }

            // Input
            $fields .= '<input';
            if ($input['type'])
                $fields .= ' type="' . $input['type'] . '"';

            if ($input['name'])
                $fields .= ' name="' . $input['name'] . '"';

            if ($input['id'])
                $fields .= ' id="' . $input['id'] . '"';

            if ($input['value'])
                $fields .= ' value="' . $input['value'] . '"';

            if ($filter['value'] === true)
                $fields .= ' value="' . $data[$input['name']] . '"';

            if ($input['placeholder'])
                $fields .= ' placeholder="' . $input['placeholder'] . '"';

            if ($input['autocomplete'] === false)
                $fields .= ' autocomplete="off"';
            $fields .= '>';

            if ($input['class'])
                $fields .= '</div>';
        }

        // Form
        if ($this->id)
            $partForm .= ' id="' . $this->name . '"';
        if ($this->class)
            $partForm .= ' class="' . $this->class . '"';
        $partForm .= ' method="' . $this->method . '" action="' . $this->action . '"';

        $form = '<form' . $partForm . '>' . $fields . '</form>';

        return $form;
    }
}
/* End of file */