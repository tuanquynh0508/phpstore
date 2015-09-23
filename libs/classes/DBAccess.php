<?php
namespace libs\classes;

use libs\classes\HttpException;

/**
 * DBAccess class
 * 
 * Ý tưởng tạo ra một lớp xử lý kết nối Database và thực hiện các truy vấn trên
 * database tập trung. Sử dụng kế thừa từ đối tượng mysqli của PHP chuẩn
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link https://github.com/tuanquynh0508/phpstore
 * @copyright 2015 I-Designer
 * @license https://github.com/tuanquynh0508/phpstore/license/
 * @package classes
 * @see mysqli
 * @since 1.0
 */
class DBAccess extends \mysqli
{

	/**
	 * {@inheritdoc}
	 * @throws HttpException Lỗi xảy ra khi không kết nối được database
	 */
	public function __construct()
	{
		//Kết nối đến db
		@parent::__construct(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
		//Kiểm tra xem có lỗi không
		if ($this->connect_errno) {
			$message = "Failed to connect to MySQL: (" . $this->connect_errno . ") " . $this->connect_error;
			throw new HttpException($message, 500);
		}
		//Set charset là UTF-8, tương đương với câu SET NAMES UTF-8; của mysql
		$this->set_charset("utf8");
	}
	
	/**
	 * {@inheritdoc}
	 * Hàm hủy, đóng kết nối tới db khi giải phóng class
	 */
	public function __destruct() {
		$this->close();
	}
	
	/**
	 * Xóa bản ghi theo id
	 * 
	 * @param string $tableName Tên bảng
	 * @param integer $id Id cần xóa
	 * @return boolean
	 * @throws HttpException Lỗi xảy ra khi không thực hiện được
	 */
	public function deleteById($tableName, $id)
	{
		$value = false;
		//Tạo câu truy vấn sql xóa
		$sql = "DELETE FROM $tableName WHERE id=$id";
		if ($result = $this->query($sql)) {
			//Lấy ra số bản ghi đã được xóa
			$affectedRows = $this->affected_rows;
			if($affectedRows > 0) {
				$value = true;
			}
		} else {
			throw new HttpException($this->error, 500);
		}
		
		return $value;
	}
	
	/**
	 * Lấy ra một bản ghi theo Id
	 * 
	 * @param string $tableName Tên bảng
	 * @param integer $id Id cần tìm
	 * @return object
	 * @throws HttpException Lỗi xảy ra khi không thực hiện được
	 */
	public function findOneById($tableName, $id)
	{
		$record = NULL;
		//Tạo câu truy vấn sql tìm bản ghi
		$sql = "SELECT * FROM $tableName WHERE id=$id";
		if ($result = $this->query($sql)) {
			//Trả về kết quả dưới dạng object
			$record = $result->fetch_object();
			$result->close();
		} else {
			throw new HttpException($this->error, 500);
		}
		
		return $record;
	}
	
	/**
	 * Lấy giá trị đầu tiên kết quả từ câu truy vấn COUNT, MAX..
	 * 
	 * @param string $sql SQL truyền vào
	 * @return string
	 * @throws HttpException Lỗi xảy ra khi không thực hiện được
	 */
	public function scalarBySQL($sql)
	{
		$value = NULL;
		if ($result = $this->query($sql)) {
			//Lấy kết quả trả về dưới dạng mảng
			$record = $result->fetch_row();
			//Lấy kết quả đầu tiên trả về
			if(!empty($record[0])) {
				$value = $record[0];
			}
			$result->close();
		} else {
			throw new HttpException($this->error, 500);
		}
		
		return $value;
	}
	
	/**
	 * Tìm tất cả các bản ghi theo SQL
	 * 
	 * @param string $sql Câu truy vấn 
	 * @return array
	 * @throws HttpException Lỗi xảy ra khi không thực hiện được
	 */
	public function findAllBySql($sql)
	{
		$list = array();
		if ($result = $this->query($sql)) {
			//Lấy tất cả các bản ghi dưới dạng object và gán vào mảng trả về
			while($obj = $result->fetch_object()){
				$list[] = $obj;
			}
			$result->close();
		} else {
			throw new HttpException($this->error, 500);
		}
		
		return $list;
	}
	
	/**
	 * Thêm mới hoặc cập nhật bản ghi
	 * 
	 * @param string $tableName Tên bảng
	 * @param array $attributes Mảng giá trị thuộc tính
	 * @param string $pkName Tên của khóa chính, truyền vào nếu thực hiện cập nhật
	 * @return object
	 * @throws HttpException Lỗi xảy ra khi không thực hiện được
	 */
	public function save($tableName, $attributes, $pkName = null)
	{
		$record = NULL;
		//Kiểm tra xem có truyền vào tên khóa chính không
		//Nếu không có sẽ là thêm mới, nếu có sẽ là cập nhật
		$isAddNew = (null === $pkName)?true:false;

		if($isAddNew) {
			//Tạo câu truy vấn thêm mới
			$sql = $this->buildInsertSql($tableName, $attributes);
		} else {
			//Tạo câu truy vấn cập nhật
			$sql = $this->buildUpdateSql($tableName, $attributes, $pkName);
		}

		try {
			//Khởi tạo một transaction
			$this->autocommit(FALSE);
			
			//Thực hiện truy vấn
			if ($this->query($sql)) {				
				if($isAddNew) {
					//Nếu thêm mới, thì lấy id mới nhất được thêm vào
					$insertId = $this->insert_id;
				} else {
					//Nếu cập nhật sẽ lấy id từ khóa chính truyền vào
					$insertId = $attributes[$pkName];
				}
				//Lấy ra bản ghi vừa thêm vào, với id 
				$record = $this->findOneById($tableName, $insertId);
			} else {
				throw new HttpException($this->error, 500);
			}

			//Kết thúc transaction
			$this->commit();
			$this->autocommit(TRUE);

			return $record;
		} catch (Exception $e) {
			throw new HttpException($e->getMessage(), 500);
			//Nếu có lỗi thì rollback lại hết, coi như chưa thực hiện các bước trên
			$this->rollback();
			$this->autocommit(TRUE);
		}
	}
	
	/**
	 * Tạo câu truy vấn thêm mới
	 * 
	 * @param string $tableName Tên bảng
	 * @param array $attributes Mảng giá trị thuộc tính
	 * @return string
	 */
	public function buildInsertSql($tableName, $attributes)
	{
		//Danh sách các trường, là index của mảng giá trị thuộc tính
		$fields = array_keys($attributes);
		//Danh sách giá trị
		$values = array();

		foreach ($attributes as $value) {
			//Lấy giá trị và thực hiện escape các ký tự đặc biệt trước khi đưa vào sql
			$values[] = $this->real_escape_string($value);
		}
		
		$sql = "INSERT INTO $tableName(".implode(",", $fields).") ";
		$sql .= "VALUES ('".implode("','", $values)."')";

		return $sql;
	}
	
	/**
	 * Tạo câu truy vấn cập nhật
	 * 
	 * @param string $tableName
	 * @param array $attributes
	 * @param string $pkName
	 * @return string
	 */
	public function buildUpdateSql($tableName, $attributes, $pkName = null)
	{
		//Danh sách các trường cập nhật
		$fields = array();

		foreach ($attributes as $key => $value) {
			if($key !== $pkName) {
				//Lấy giá trị và thực hiện escape các ký tự đặc biệt trước khi đưa vào sql
				$fields[] = $key."=".(($value !== '')?"'".$this->real_escape_string($value)."'":'NULL');
			}
		}

		$sql = "UPDATE $tableName SET ".implode(', ', $fields)." WHERE $pkName={$attributes[$pkName]}";

		return $sql;
	}

}
