<?php

namespace libs\classes;

use libs\classes\HttpException;

class DBPagination
{
	protected $page;
	protected $limit;
	protected $offset;
	protected $maxPage;
	protected $totalRecord;

	public function __construct($totalRecord, $limit = 10)
	{
		$this->limit = $limit;

		//Pagination calculator
		$this->page = 1;
		if(isset($_GET['page'])) {
			$this->page = intval($_GET['page']);
			$this->page = ($this->page > 0)?$this->page:1;
		}

		$this->offset = ($this->page-1) * $this->limit;
		$this->totalRecord = $totalRecord;
		$this->maxPage = ceil($this->totalRecord/$this->limit);
	}

	public function getLimit()
	{
		return "LIMIT {$this->offset},{$this->limit}";
	}

	public function renderPagination($urlBase)
	{
		$first = 1;
		$last = $this->maxPage;
		$prev = ($this->page-1 > 0)?$this->page-1:1;
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

	public function setVarQueryString($key, $value)
	{
		$params = array();
		$_GET[$key] = $value;
		foreach($_GET as $key => $value) {
			$params[] = $key.'='.$value;
		}

		return implode('&', $params);
	}

	public function getMaxPage()
	{
		return $this->maxPage;
	}
}
