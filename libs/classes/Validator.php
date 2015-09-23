<?php
namespace libs\classes;

use libs\classes\HttpException;

/**
 * Validator class
 * 
 * Ý tưởng: Tạo ra một đối tượng xử lý việc kiểm tra dữ liệu tập trung. Việc kiểm
 * tra dữ liệu rất quan trọng trước khi ta lưu trữ vào database hoặc là thực hiện
 * các tính toán khác. Các kiểm tra dữ liệu thường gặp là:
 * required: Yêu cầu nhập
 * length: Độ dài ký tự chuỗi
 * email
 * url
 * number
 * unique: Tính duy nhất của dữ liệu.
 * pattern: Kiểm tra theo mẫu regular expression
 * compare: So sánh hai giá trị
 * Việc kiểm tra dữ liệu sẽ được thực hiện trước khi dữ liệu được ghi vào database
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link https://github.com/tuanquynh0508/phpstore
 * @copyright 2015 I-Designer
 * @license https://github.com/tuanquynh0508/phpstore/license/
 * @package classes
 * @since 1.0
 */
class Validator
{
	/**
	 * Danh sách các lỗi
	 * @var array
	 */
    protected $errors = array();
	
	/**
	 * Danh sách các giá trị lấy từ form
	 * @var array
	 */
    protected $attributes = array();
	
	/**
	 * Đối tượng kết nối đến database, chỉ khai báo khi sử dụng check unique
	 * @var mysqli 
	 */
    protected $condb = null;
	
	/**
	 * Mảng khai báo kiểm tra, khai báo tất cả các bước check dữ liệu. Ví dụ về mảng
	 * đó như sau:
	 * @var array
	 */
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
	//			'operator' => '==',
	//			'message' => 'message'
	//		),
	//	);
    protected $validates = array();

	/**
	 * __construct
	 * 
	 * @param array $validates Mảng khai báo kiểm tra
	 * @param mysqli $condb Đối tượng kết nối database, chỉ khai báo khi sử dụng check unique
	 */
    public function __construct($validates, $condb = null)
    {
        $this->validates = $validates;
        $this->condb = $condb;
    }
	
	/**
	 * Lấy giá trị từ form
	 * 
	 * @param type $attributes
	 */
    public function bindData($attributes)
    {
        $this->attributes = $attributes;
    }
	
	/**
	 * Thực hiện kiểm tra dữ liệu
	 * 
	 * @return boolean
	 */
    public function validate()
    {
		//Nếu mảng kiểm tra rỗng thì không làm gì cả
        if(empty($this->validates)) {
            return true;
        }
		
        foreach ($this->validates as $prototype) {
            $callCheck = 'test'.ucfirst($prototype['type']);
			//Kiểm tra xem function có trang class không. Nếu có thì gọi đến function
			//tương ứng để kiểm tra dữ liệu. Các hàm kiểm tra được viết ở trong class này
			if(method_exists($this, $callCheck)) {
				$this->$callCheck($prototype);
			}
        }
		
		//Nếu mảng lỗi không có giá trị, có nghĩa là việc kiểm tra không có lỗi
		if(empty($this->errors)) {
            return true;
        }

		return false;
    }
	
	/**
	 * Thêm vào danh sách lỗi
	 * 
	 * @param string $key Mã lỗi, thường là trên trường trên form
	 * @param string $message Nội dung lỗi
	 */
    public function addError($key, $message)
    {
		//Nếu chưa tồn tại mã lỗi, thì khởi tạo là một mảng rỗng
		if(!isset($this->errors[$key])) {
			$this->errors[$key] = array();
		}

        $this->errors[$key][] = $message;
    }
	
	/**
	 * Trả về tất cả lỗi, hoặc lỗi theo tên trường
	 * 
	 * @param string $key Tên trường cần lấy lỗi
	 * @return array
	 */
    public function getError($key = null)
    {
		//Nếu truyền vào tên trường, thì lấy ra lỗi theo tên trường
		if($key !== null) {
			return array_key_exists($key, $this->errors)?$this->errors[$key]:'';
		}
		
		//Không thì trả về toàn bộ lỗi
        return $this->errors;
    }
	
	/**
	 * Kiểm tra lỗi có tồn tại không
	 * 
	 * @param string $key Tên trường cần kiểm tra
	 * @return boolean
	 */
    public function checkError($key)
    {
        return array_key_exists($key, $this->errors);
    }
	
	/**
	 * Trả về html lỗi theo trường
	 * 
	 * @param string $filed Tên trường
	 * @return string
	 */
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

	
	/**
	 * Kiểm tra yêu cầu nhập
	 * 
	 * @param array $prototype
	 */
	protected function testRequired($prototype)
	{
		//Tên trường
		$field = $prototype['field'];
		//Giá trị của trường
		$value = $this->attributes[$field];
		//Nội dung lỗi
		$message = $prototype['message'];
		
		//Nếu giá trị rỗng, thì thêm một lỗi vào danh sách lỗi
		if($value == '') {
			$this->addError($field, $message);
		}
	}
	
	/**
	 * Kiểm tra độ dài ký tự chuỗi
	 * 
	 * @param array $prototype
	 */
	protected function testLength($prototype)
	{
		//Tên trường
		$field = $prototype['field'];
		//Giá trị của trường
		$value = $this->attributes[$field];
		//Nội dung lỗi
		$message = $prototype['message'];
		//Giá trị độ dài nhỏ nhất
		$min = (isset($prototype['min']))?intval($prototype['min']):0;
		//Giá trị độ dài lớn nhất
		$max = (isset($prototype['max']))?intval($prototype['max']):0;
		
		//Nếu độ dài giá trị của trường nhỏ hơn giá trị độ dài nhỏ nhất hoặc
		//độ dài giá trị của trường lớn hơn giá trị độ dài lớn nhất
		//thì thêm một lỗi vào danh sách lỗi
		if(($min >  0 && strlen($value) < $min) || ($max >  0 && strlen($value) > $max)) {
			$this->addError($field, $message);
		}
	}
	
	/**
	 * Kiểm tra xem có phải là chuỗi hay không
	 * 
	 * @param array $prototype
	 */
	protected function testString($prototype)
	{
	}
	
	/**
	 * Kiểm tra có phải kiểu email không
	 * 
	 * @param array $prototype
	 */
	protected function testEmail($prototype)
	{
		//Tên trường
		$field = $prototype['field'];
		//Giá trị của trường
		$value = $this->attributes[$field];
		//Nội dung lỗi
		$message = $prototype['message'];
		
		//Nếu giá trị của trường không phải là kiểu email, thì thêm một lỗi vào danh sách lỗi
		if(!preg_match("/^\w(\.?[\w-])*@\w(\.?[-\w])*\.[a-z]{2,4}$/i", $value)) {
			$this->addError($field, $message);
		}
	}
	
	/**
	 * Kiểm tra có phải kiểu url không
	 * 
	 * @param array $prototype
	 */
	protected function testUrl($prototype)
	{
		//Tên trường
		$field = $prototype['field'];
		//Giá trị của trường
		$value = $this->attributes[$field];
		//Nội dung lỗi
		$message = $prototype['message'];
		
		//Nếu giá trị của trường không phải là kiểu url, thì thêm một lỗi vào danh sách lỗi
		if(!preg_match("/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i", $value)) {
			$this->addError($field, $message);
		}
	}
	
	/**
	 * Kiểm tra có phải kiểu number không
	 * 
	 * @param array $prototype
	 */
	protected function testNumber($prototype)
	{
		//Tên trường
		$field = $prototype['field'];
		//Giá trị của trường
		$value = $this->attributes[$field];
		//Nội dung lỗi
		$message = $prototype['message'];
		
		//Nếu giá trị của trường không phải là kiểu number, thì thêm một lỗi vào danh sách lỗi
		if(!is_numeric($value)) {
			$this->addError($field, $message);
		}
	}
	
	/**
	 * Kiểm tra dữ liệu theo mẫu regular expression
	 * 
	 * @param array $prototype
	 */
	protected function testPattern($prototype)
	{
		//Tên trường
		$field = $prototype['field'];
		//Giá trị của trường
		$value = $this->attributes[$field];
		//Nội dung lỗi
		$message = $prototype['message'];
		//Mẫu regular expression
		$regex = $prototype['regex'];
		
		//Nếu giá trị của trường không thỏa mãn mẫu kiểm tra, thì thêm một lỗi vào danh sách lỗi
		if(!preg_match($regex, $value)) {
			$this->addError($field, $message);
		}
	}
	
	/**
	 * Kiểm tra giá trị lặp trong database
	 * 
	 * @param array $prototype
	 */
	protected function testUnique($prototype)
	{
		//Nếu đối tượng kết nối chưa được truyền vào, thì không làm gì cả
		if($this->condb === null) {
			return false;
		}
		
		//Tên trường
		$field = $prototype['field'];
		//Giá trị của trường
		$value = $this->attributes[$field];
		//Nội dung lỗi
		$message = $prototype['message'];
		//Tên bảng cần kiểm tra
		$table = $prototype['table'];
		//Lấy câu SQL do người dùng truyền vào
		$sql = (isset($prototype['sql']))?$prototype['sql']:'';
		//Nếu người dùng không truyền vào thì tạo câu SQL để kiểm tra
		if($sql == '') {
			//Lấy ra giá trị khóa chính của bảng theo form
			$id = (array_key_exists('id', $this->attributes))?intval($this->attributes['id']):0;
			//Tạo ra câu SQL kiểm tra và loại bỏ bản ghi hiện tại, có nghĩa là loại bỏ bản ghi
			//hiện tại ra khỏi kết quả tìm thấy. Tự nó không check lặp chính nó
			$sql = "SELECT COUNT(*) FROM $table WHERE $field='$value' AND id != $id;";
		}
		
		//Thực hiện truy vấn, lấy kết quả từ hàm COUNT trả về
		$check = intval($this->condb->scalarBySQL($sql));
		//Nếu kết quả của hàm COUNT lớn hơn 0. Có nghĩa là tồn tại một bản ghi có
		//giá trị giống giá trị cần check, thêm một lỗi vào danh sách lỗi
		if($check > 0) {
			$this->addError($field, $message);
		}
	}
	
	/**
	 * So sánh hai giá trị
	 * 
	 * @param array $prototype
	 */
	protected function testCompare($prototype)
	{
		//Tên trường
		$field = $prototype['field'];
		//Giá trị của trường
		$value1 = $this->attributes[$field];
		//Giá trị cần so sánh
		$value2 = $prototype['value'];
		//Nếu người dùng truyền vào tên trường thứ 2 tồn tại trong form thay vì
		//truyền vào giá trị cần so sánh. Thì lấy giá trị của trường đó ra
		if(array_key_exists($value2, $this->attributes)) {
			$value2 = $this->attributes[$value2];
		}
		//Nội dung lỗi
		$message = $prototype['message'];
		//Toán tử logic thực hiện so sánh. Ví dụ: ==, >=, <=, >, <
		$operator = $prototype['operator'];
		
		//Nếu phép toán trả về là false, thì thêm một lỗi vào danh sách lỗi
		if(eval("return '$value1' $operator '$value2';") === false) {
			$this->addError($field, $message);
		}
	}
}
