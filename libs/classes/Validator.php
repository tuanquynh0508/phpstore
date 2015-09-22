<?php

namespace libs\classes;

use libs\classes\HttpException;

class Validator
{
    protected $errors = array();

    protected $attributes = array();

    protected $condb = null;

    // $validates = array(
    //     'field 1' => array(
    //         'label' => 'Field label',
    //         'required' => true,
    //         'length' => array('min' => 3, 'max' => 255),
    //         'type' => 'string',
    //         'unique' => array('table' => 'example', 'value' => 'Vi du', 'sql' => ''),
    //         'pattern' => 'Vi du',
    //         'message' => array(
    //             'required' => array('message' => '', 'params' => array()),
    //             'length' => array('message' => '', 'params' => array()),
    //             'type' => array('message' => '', 'params' => array()),
    //             'unique' => array('message' => '', 'params' => array()),
    //             'pattern' => array('message' => '', 'params' => array()),
    //         ),
    //     ),
    //     'field 2' => array(
    //         'label' => 'Field label',
    //         'required' => true,
    //         'length' => array('min' => 3, 'max' => 255),
    //         'type' => 'string',
    //         'unique' => array('table' => 'example', 'value' => 'Vi du', 'sql' => ''),
    //         'pattern' => 'Vi du',
    //         'message' => array(
    //             'required' => array('message' => '', 'params' => array()),
    //             'length' => array('message' => '', 'params' => array()),
    //             'type' => array('message' => '', 'params' => array()),
    //             'unique' => array('message' => '', 'params' => array()),
    //             'pattern' => array('message' => '', 'params' => array()),
    //         ),
    //     ),
    // );
    protected $validates = array();

    protected $messages = array(
                'required' => array('message' => 'Không được để trống trường %s', 'params' => array()),
                'length' => array('message' => '', 'params' => array()),
                'type' => array('message' => '', 'params' => array()),
                'unique' => array('message' => '', 'params' => array()),
                'pattern' => array('message' => '', 'params' => array()),
            );

    public function __construct($validates, $condb = null)
    {
        $this->validates = $validates;
        $this->condb = $condb;
    }

    public function bind($attributes)
    {
        $this->attributes = $attributes;
    }

    public function validate()
    {
        if(empty($this->validates)) {
            return true;
        }

        foreach ($this->validates as $field => $prototype) {
            foreach ($prototype as $checkType => $params) {
                $callCheck = 'test'.ucfirst($checkType);
                if(method_exists($this, $callCheck)) {
                    $this->$callCheck($field, $prototype, $params);
                }
            }
        }
    }

    public function addError($key, $message)
    {
        $this->errors[$key][] = $message;
    }

    public function getError($key, $message)
    {
        return $this->errors;
    }

    public function checkError($key)
    {
        return array_key_exists($key, $this->errors);
    }
}
