<?php
namespace libs\classes;

/**
 * HttpException class
 * 
 * Ý tưởng: Tạo ra một đối tượng exception tùy chỉnh, và hiển thị dưới dạng mã lỗi
 * http code 500 về cho người client
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link https://github.com/tuanquynh0508/phpstore
 * @copyright 2015 I-Designer
 * @license https://github.com/tuanquynh0508/phpstore/license/
 * @package classes
 * @see Exception
 * @since 1.0
 */
class HttpException extends \Exception 
{
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct($message, $code = 0, Exception $previous = null) 
	{
		set_exception_handler(array("libs\classes\HttpException", "getStaticException"));
		parent::__construct($message, $code, $previous);
	}
	
	/**
	 * __toString
	 * 
	 * {@inheritdoc}
	 * 
	 * @return string
	 */
	public function __toString() 
	{
		http_response_code($this->getCode());
		$message = "<h1>Error {$this->getCode()}</h1>";
		$message .= "<p>" . htmlentities($this->getMessage()) . " in <strong>{$this->getFile()}</strong> on line <strong>{$this->getLine()}</strong></p>";
		return $message;
	}
	
	/**
	 * Trả về Exception
	 */
	public function getException() 
	{
		print $this;
	}
	
	/**
	 * Trả về đối tượng Exception tĩnh
	 * 
	 * @param Exception $exception
	 */
	public static function getStaticException($exception) 
	{
		$exception->getException();
	}

}
