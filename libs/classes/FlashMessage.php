<?php

namespace libs\classes;

class FlashMessage
{

	public function checkFlashMessage($key) {
		$mes = $this->getSesion('flash_'.$key);
		return ($mes!='')?true:false;
	}

	public function setFlashMessage($key, $message)
	{
		$_SESSION['flash_'.$key] = $message;
	}

	public function getFlashMessage($key)
	{
		$message = $this->getSesion('flash_'.$key);
		$this->removeSesion('flash_'.$key);
		return $message;
	}

	public function clearAllFlashMessage()
	{
		if(!empty($_SESSION)) {
			foreach ($_SESSION as $key => $value) {
				if(preg_match('/flash_(.*)/', $key)) {
					unset($_SESSION[$key]);
				}
			}
		}
	}

	public function getSesion($key)
	{
		if(isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}

		return '';
	}

	public function removeSesion($key)
	{
		if(isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}
}
