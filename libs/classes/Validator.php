<?php

namespace libs\classes;

use libs\classes\HttpException;

class Validator
{
    protected $errors = array();

    protected $attributes = array();

    protected $condb = null;

//	$validates = array(
//		array(
//			'type' => 'required',
//			'field' => 'field 1',
//			'message' => 'message'
//		),
//		array(
//			'type' => 'length',
//			'field' => 'field 1',
//			'min' => 3,
//			'max' => 255,
//			'message' => 'message'
//		),
//		array(
//			'type' => 'string',
//			'field' => 'field 1',
//			'message' => 'message'
//		),
//		array(
//			'type' => 'email',
//			'field' => 'field 1',
//			'message' => 'message'
//		),
//		array(
//			'type' => 'url',
//			'field' => 'field 1',
//			'message' => 'message'
//		),
//		array(
//			'type' => 'number',
//			'field' => 'field 1',
//			'message' => 'message'
//		),
//		array(
//			'type' => 'unique',
//			'field' => 'field 1',
//			'table' => 'example',
//			'sql' => '',
//			'message' => 'message'
//		),
//		array(
//			'type' => 'pattern',
//			'field' => 'field 1',
//			'regex' => '',
//			'message' => 'message'
//		),
//		array(
//			'type' => 'compare',
//			'field' => 'field 1',
//			'value' => 'Value',
//			'operator' => '',
//			'message' => 'message'
//		),
//	);
    protected $validates = array();

    public function __construct($validates, $condb = null)
    {
        $this->validates = $validates;
        $this->condb = $condb;
    }

    public function bindData($attributes)
    {
        $this->attributes = $attributes;
    }

    public function validate()
    {
        if(empty($this->validates)) {
            return true;
        }

        foreach ($this->validates as $prototype) {
            $callCheck = 'test'.ucfirst($prototype['type']);
			if(method_exists($this, $callCheck)) {
				$this->$callCheck($prototype);
			}
        }

		if(empty($this->errors)) {
            return true;
        }

		return false;
    }

    public function addError($key, $message)
    {
		if(!isset($this->errors[$key])) {
			$this->errors[$key] = array();
		}

        $this->errors[$key][] = $message;
    }

    public function getError($key = null)
    {
		if($key !== null) {
			return array_key_exists($key, $this->errors)?$this->errors[$key]:'';
		}

        return $this->errors;
    }

    public function checkError($key)
    {
        return array_key_exists($key, $this->errors);
    }

	public function fieldError($filed)
	{
		$error = $this->getError($filed);
		if(empty($error)) {
			return '';
		}

		$html = '<ul class="errors">';
		$html .= '<li>'.implode('<br/>', $error).'</li>';
		$html .= '</ul>';

		return $html;
	}

	////////////////////////////////////////////////////////////////////////////
	protected function testRequired($prototype)
	{
		$field = $prototype['field'];
		$value = $this->attributes[$field];
		$message = $prototype['message'];

		if($value == '') {
			$this->addError($field, $message);
		}
	}

	protected function testLength($prototype)
	{
		$field = $prototype['field'];
		$value = $this->attributes[$field];
		$message = $prototype['message'];
		$min = (isset($prototype['min']))?intval($prototype['min']):0;
		$max = (isset($prototype['max']))?intval($prototype['max']):0;

		if(($min >  0 && strlen($value) < $min) || ($max >  0 && strlen($value) > $max)) {
			$this->addError($field, $message);
		}
	}

	protected function testString($prototype)
	{
	}

	protected function testEmail($prototype)
	{
		$field = $prototype['field'];
		$value = $this->attributes[$field];
		$message = $prototype['message'];

		if(!preg_match("/^\w(\.?[\w-])*@\w(\.?[-\w])*\.[a-z]{2,4}$/i", $value)) {
			$this->addError($field, $message);
		}
	}

	protected function testUrl($prototype)
	{
		$field = $prototype['field'];
		$value = $this->attributes[$field];
		$message = $prototype['message'];

		if(!preg_match("/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i", $value)) {
			$this->addError($field, $message);
		}
	}

	protected function testNumber($prototype)
	{
		$field = $prototype['field'];
		$value = $this->attributes[$field];
		$message = $prototype['message'];

		if(!is_numeric($value)) {
			$this->addError($field, $message);
		}
	}

	protected function testPattern($prototype)
	{
		$field = $prototype['field'];
		$value = $this->attributes[$field];
		$message = $prototype['message'];
		$regex = $prototype['regex'];

		if(!preg_match($regex, $value)) {
			$this->addError($field, $message);
		}
	}

	protected function testUnique($prototype)
	{
		if($this->condb === null) {
			return false;
		}

		$field = $prototype['field'];
		$value = $this->attributes[$field];
		$message = $prototype['message'];
		$table = $prototype['table'];
		$sql = (isset($prototype['sql']))?$prototype['sql']:'';
		if($sql == '') {
			$id = (array_key_exists('id', $this->attributes))?intval($this->attributes['id']):0;
			$sql = "SELECT COUNT(*) FROM $table WHERE $field='$value' AND id != $id;";
		}

		$check = intval($this->condb->scalarBySQL($sql));
		if($check > 0) {
			$this->addError($field, $message);
		}
	}

	protected function testCompare($prototype)
	{
		$field = $prototype['field'];
		$value1 = $this->attributes[$field];
		$value2 = $prototype['value'];
		if(array_key_exists($value2, $this->attributes)) {
			$value2 = $this->attributes[$value2];
		}
		$message = $prototype['message'];
		$operator = $prototype['operator'];

		if(eval("return '$value1' $operator '$value2';") === false) {
			$this->addError($field, $message);
		}
	}
}
