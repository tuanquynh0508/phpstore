<?php

namespace libs\classes;

class HttpException extends \Exception 
{

	public function __construct($message, $code = 0, Exception $previous = null) 
	{
		set_exception_handler(array("libs\classes\HttpException", "getStaticException"));
		parent::__construct($message, $code, $previous);
	}

	public function __toString() 
	{
		http_response_code($this->getCode());
		$message = "<h1>Error {$this->getCode()}</h1>";
		$message .= "<p>" . htmlentities($this->getMessage()) . " in <strong>{$this->getFile()}</strong> on line <strong>{$this->getLine()}</strong></p>";
		return $message;
	}

	public function getException() 
	{
		print $this;
	}

	public static function getStaticException($exception) 
	{
		$exception->getException();
	}

}
