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
		array('label' => 'Shop', 'url' => 'index.php', 'active' => array('index.php')),
		array('label' => 'Trang chủ', 'url' => 'admin.php', 'active' => array('admin.php')),
		array('label' => 'Danh mục', 'url' => 'admin_category.php', 'active' => array('admin_category.php', 'admin_category_form.php')),
		array('label' => 'Đối tác', 'url' => 'admin_firm.php', 'active' => array('admin_firm.php', 'admin_firm_form.php')),
		array('label' => 'Sản phẩm', 'url' => 'admin_product.php', 'active' => array('admin_product.php', 'admin_product_form.php')),
		array('label' => 'Đơn hàng', 'url' => 'admin_order.php', 'active' => array('admin_order.php', 'admin_order_detail.php')),
	);

	if(getUserAttrSession('is_admin') == 1) {
		$menus[] = array('label' => 'Người dùng', 'url' => 'admin_user.php', 'active' => array('admin_user.php', 'admin_user_form.php'));
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
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Trang quản trị</title>
	<link href="css/reset.css" rel="stylesheet" type="text/css"/>
	<?php
		if(isset($cssBlock)) {
			echo $cssBlock;
		}
	?>
	<link href="css/admin.css" rel="stylesheet" type="text/css"/>
</head>

<body>
	<div class="wrapper" id="adminBoard">
		<header id="pageHeader">
			<h1>PHPStore Administrator Page</h1>
			<?php if(checkAuthentication()): ?>
			<div id="userBox">
				Xin chào <?= getUserAttrSession('fullname') ?>!.
				[<a href="admin_profile.php" onClick="ga('send', 'event', 'button', 'click', 'Go to Profile');">Hồ sơ</a> | <a href="admin_logout.php" onClick="ga('send', 'event', 'button', 'click', 'Logout');">Thoát</a>]
			</div>
			<?php endif; ?>
		</header>

		<?php if(checkAuthentication()): ?>
		<nav id="pageNav"><?= renderMenuTop() ?></nav>
		<?php endif; ?>

		<div id="pageBody">
