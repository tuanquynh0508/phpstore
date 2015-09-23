<?php
namespace libs\classes;

use libs\classes\HttpException;

/**
 * DBPagination class
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link https://github.com/tuanquynh0508/phpstore
 * @copyright 2015 I-Designer
 * @license https://github.com/tuanquynh0508/phpstore/license/
 * @package classes
 * @since 1.0
 */
class DBPagination
{
	/**
	 * Trang hiện tại
	 * @var integer
	 */
	protected $page;
	
	/**
	 * Số bản ghi trên một trang
	 * @var integer
	 */
	protected $limit;
	
	/**
	 * Điển bắt đầu phân đoạn của một trang
	 * @var integer
	 */
	protected $offset;
	
	/**
	 * Tổng số trang
	 * @var integer
	 */
	protected $maxPage;
	
	/**
	 * Tổng số bản ghi
	 * @var integer
	 */
	protected $totalRecord;
	
	/**
	 * __construct
	 * 
	 * @param integer $totalRecord Tổng số bản ghi
	 * @param integer $limit Số bản ghi trên một trang, mặc định là 10
	 */
	public function __construct($totalRecord, $limit = 10)
	{
		$this->totalRecord = $totalRecord;
		$this->limit = $limit;

		//Pagination calculator
		$this->page = 1;
		//Lấy trang hiện tại truyền vào từ URL
		if(isset($_GET['page'])) {
			$this->page = intval($_GET['page']);
			$this->page = ($this->page > 0)?$this->page:1;
		}
		
		//Tính toán phân đoạn cần lấy
		//Công thức: Phân đoạn = (Trang hiện tại - 1) * Số bản ghi trên một trang
		$this->offset = ($this->page-1) * $this->limit;
		//Tính toán tổng số trang từ Tổng số bản ghi, và số bản ghi trên một trang
		//Công thức: Tống số trang = Lấy cận trên của phép chia ( Tống số bản ghi / Số bản ghi trên một trang )
		$this->maxPage = ceil($this->totalRecord/$this->limit);
	}
	
	/**
	 * Trả về câu LIMIT sử dụng SQL trong lấy danh sách các bản ghi
	 * 
	 * @return string
	 */
	public function getLimit()
	{
		return "LIMIT {$this->offset},{$this->limit}";
	}
	
	/**
	 * Tạo ra các link của phân trang
	 * 
	 * @param string $urlBase Đường dẫn URL của trang cần áp dụng phân trang
	 * @return string
	 */
	public function renderPagination($urlBase)
	{
		//Trang đầu tiên
		$first = 1;
		//Trang cuối cùng
		$last = $this->maxPage;
		//Trang trước
		$prev = ($this->page-1 > 0)?$this->page-1:1;
		//Trang sau
		$next = ($this->page+1 <= $this->maxPage)?$this->page+1:$this->maxPage;

		$html = '<ul class="clearfix">';

		if($this->page > 1) {
			$html .= '	<li><a href="'.$urlBase.'?'.$this->setVarQueryString('page', $first).'"><img src="img/admin/first.png"/></a></li>';
			$html .= '	<li><a href="'.$urlBase.'?'.$this->setVarQueryString('page', $prev).'"><img src="img/admin/prev.png"/></a></li>';
		} else {
			$html .= '	<li><a href="#" class="disabled"><img src="img/admin/first.png"/></a></li>';
			$html .= '	<li><a href="#" class="disabled"><img src="img/admin/prev.png"/></a></li>';
		}

		for($p = 1; $p <= $this->maxPage; $p++) {
			if($p === $this->page) {
				$html .= '	<li><a href="#" class="active">'.$p.'</a></li>';
			} else {
				$html .= '	<li><a href="'.$urlBase.'?'.$this->setVarQueryString('page', $p).'">'.$p.'</a></li>';
			}
		}

		if($this->page < $this->maxPage) {
			$html .= '	<li><a href="'.$urlBase.'?'.$this->setVarQueryString('page', $next).'"><img src="img/admin/next.png"/></a></li>';
			$html .= '	<li><a href="'.$urlBase.'?'.$this->setVarQueryString('page', $last).'"><img src="img/admin/last.png"/></a></li>';
		} else {
			$html .= '	<li><a href="#" class="disabled"><img src="img/admin/next.png"/></a></li>';
			$html .= '	<li><a href="#" class="disabled"><img src="img/admin/last.png"/></a></li>';
		}

		$html .= '</ul>';

		return $html;
	}
	
	/**
	 * Thêm biến lên đường link URL
	 * 
	 * Ví dụ ta có đường link hiện tại là: http://example.com?page=1&keyword=test-search
	 * Ta muốn thêm hoặc thay đổi giá trị của biến page mà không làm mất đi các biến
	 * đã có. Ví dụ đổi giá trị của biến page thành 2
	 * echo 'http://example.com?'.setVarQueryString('page', 2)
	 * Như vậy ta sẽ có kết quả là: http://example.com?page=2&keyword=test-search
	 * Việc tạo ra đường link như thế này nhằm đảm bảo khi ta chuyển trang, thì không làm
	 * thay đổi các biến hiện có như search chẳng hạn
	 * 
	 * @param string $key Tên của biến
	 * @param string|integer $value Giá trị của biến
	 * @return string
	 */
	public function setVarQueryString($key, $value)
	{
		$params = array();
		$_GET[$key] = $value;
		foreach($_GET as $key => $value) {
			$params[] = $key.'='.$value;
		}

		return implode('&', $params);
	}
	
	/**
	 * Trả về Tổng số trang
	 * 
	 * @return integer
	 */
	public function getMaxPage()
	{
		return $this->maxPage;
	}
}
