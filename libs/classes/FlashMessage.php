<?php
namespace libs\classes;

/**
 * FlashMessage class
 *
 * Ý tưởng của flash message là sử dung SESSION để lưu trữ một message, khi ta 
 * chuyển trang, message sẽ được lưu trữ và gọi ra để hiển thị cho người dùng xem.
 * Sau khi hiển thị xong, message sẽ được xóa khỏi SESSION
 * 
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link https://github.com/tuanquynh0508/phpstore
 * @copyright 2015 I-Designer
 * @license https://github.com/tuanquynh0508/phpstore/license/
 * @package classes
 * @since 1.0
 */
class FlashMessage
{
	
	/**
	 * Kiểm tra flash message có tồn tại hay không
	 * 
	 * @param string $key Mã của flash message
	 * @return boolean
	 */
	public function checkFlashMessage($key) {
		$mes = $this->getSesion('flash_'.$key);
		return ($mes!='')?true:false;
	}
	
	/**
	 * Sét giá trị cho flash message
	 * 
	 * @param string $key Mã của message
	 * @param string $message Nội dung của message
	 */
	public function setFlashMessage($key, $message)
	{
		$_SESSION['flash_'.$key] = $message;
	}
	
	/**
	 * Trả về nội dung của message
	 * 
	 * @param string $key Mã của message
	 * @return string
	 */
	public function getFlashMessage($key)
	{
		$message = $this->getSesion('flash_'.$key);
		//Sau khi lấy ra nội dung, thì xóa đi ngay
		$this->removeSesion('flash_'.$key);
		return $message;
	}
	
	/**
	 * Xóa tất cả các flash message
	 */
	public function clearAllFlashMessage()
	{
		if(!empty($_SESSION)) {
			foreach ($_SESSION as $key => $value) {
				if(preg_match('/flash_(.*)/', $key)) {
					$this->removeSesion($key);
				}
			}
		}
	}
	
	/**
	 * Lấy giá trị từ biến SESSION
	 * 
	 * @param string $key Tên biến
	 * @return string
	 */
	public function getSesion($key)
	{
		if(isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}

		return '';
	}
	
	/**
	 * Xóa biến SESSION
	 * 
	 * @param type $key Tên biến
	 */
	public function removeSesion($key)
	{
		if(isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}
}
