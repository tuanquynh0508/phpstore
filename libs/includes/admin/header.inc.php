<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Trang quản trị</title>
	<link href="css/reset.css" rel="stylesheet" type="text/css"/>
	<link href="css/admin.css" rel="stylesheet" type="text/css"/>
</head>

<body>
	<div class="wrapper" id="adminBoard">
		<header id="pageHeader">
			<h1>PHPStore Administrator Page</h1>
			<div id="userBox">
				Hello Admin!. [<a href="#">Đổi mật khẩu</a> | <a href="#">Thoát</a>]
			</div>
		</header>

		<nav id="pageNav">
			<ul class="clearfix">
				<li class="active"><a href="admin.php">Trang chủ</a></li>
				<li><a href="admin_user.php">Người dùng</a></li>
				<li><a href="admin_category.php">Danh mục</a></li>
				<li><a href="admin_firm.php">Đối tác</a></li>
				<li><a href="admin_product.php">Sản phẩm</a></li>
				<li><a href="admin_order.php">Đơn hàng</a></li>
			</ul>
		</nav>

		<div id="pageBody">
