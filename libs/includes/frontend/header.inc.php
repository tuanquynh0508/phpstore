<?php
/**
 * Trả về HTML của menu
 *
 * Ý tưởng: Hiển thị menu và đặt trạng thái active vào menu hiện tại theo danh sách
 * các active mà người dùng định nghĩa
 *
 * @return string
 */
function renderMenuTop()
{
	$menus = array(
		array('label' => 'Trang chủ', 'url' => 'index.php', 'active' => array('index.php')),
		array('label' => 'Giới thiệu', 'url' => 'about.php', 'active' => array('about.php')),
		array('label' => 'Hướng dẫn mua hàng', 'url' => 'help.php', 'active' => array('help.php')),
		array('label' => 'Liên hệ', 'url' => 'contact.php', 'active' => array('contact.php')),
		array('label' => 'Download source code', 'url' => 'https://github.com/tuanquynh0508/phpstore', 'active' => array('')),
	);

	if(checkAuthentication()) {
		$menus[] = array('label' => 'Quản trị', 'url' => 'admin.php', 'active' => array('admin.php'));
	}

	$html = '<ul class="clearfix">';
	foreach($menus as $menu) {
		$active = '';
		//Kiểm tra xem trang hiện tại có nằm trong danh sách active hay không, nếu
		//nằm trong danh sách active thì thêm đặt class active cho menu
		if(in_array(basename($_SERVER['PHP_SELF']), $menu['active'])) {
			$active = 'class="active"';
		}
		$html .= '<li '.$active.'><a href="'.$menu['url'].'" onClick="ga(\'send\', \'event\', \'button\', \'click\', \''.$menu['label'].'\');">'.$menu['label'].'</a></li>';
	}
	$html .= '</ul>';

	return $html;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHPStore</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/reset.css" rel="stylesheet" type="text/css"/>
		<link href="js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
		<link href="css/frontend-ver-1.css" rel="stylesheet" type="text/css"/>
		<!--[if lt IE 9]>
			<script src="js/html5shiv.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="wrapper">

			<header>
				<a href="index.php" id="branchLogo"><span>PHPStore (Demo version)</span></a>
				<div id="shoppingCart"><a href="cart.php" onClick="ga('send', 'event', 'button', 'click', 'Go to Cart');">Giỏ hàng có <span id="totalInCart"><?= getTotalProductInCart() ?></span> sản phẩm</a></div>
			</header><!-- /header -->

			<nav id="topMenu"><?= renderMenuTop() ?></nav><!-- /#topMenu -->
