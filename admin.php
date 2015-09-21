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
			<h2 id="pageTitle">Quản lý Người dùng</h2>
			<p><a href="admin_user_form.php">Thêm mới</a></p>
			<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Tài khoản</th>
						<th>Email</th>
						<th>Trạng thái</th>
						<th>Thao tác</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>admin</td>
						<td>tuanquynh0508@gmail.com</td>
						<td class="text-center">
							<img src="img/admin/lock.png"/>
						</td>
						<td class="text-center">
							<a href="admin_user_form.php?id=1"><img src="img/admin/edit.png"/></a>
							<a href="admin_user.php?action=delete&id=1" class="btn-delete"><img src="img/admin/trash.png"/></a>
						</td>
					</tr>
					<tr>
						<td>1</td>
						<td>admin</td>
						<td>tuanquynh0508@gmail.com</td>
						<td class="text-center">
							<img src="img/admin/lock.png">
						</td>
						<td class="text-center">
							<a href="admin_user_form.php?id=1"><img src="img/admin/edit.png"/></a>
							<a href="admin_user.php?action=delete&id=1" class="btn-delete"><img src="img/admin/trash.png"/></a>
						</td>
					</tr>
					<tr>
						<td>1</td>
						<td>admin</td>
						<td>tuanquynh0508@gmail.com</td>
						<td class="text-center">
							<img src="img/admin/unlock.png">
						</td>
						<td class="text-center">
							<a href="admin_user_form.php?id=1"><img src="img/admin/edit.png"/></a>
							<a href="admin_user.php?action=delete&id=1" class="btn-delete"><img src="img/admin/trash.png"/></a>
						</td>
					</tr>
					<tr>
						<td>1</td>
						<td>admin</td>
						<td>tuanquynh0508@gmail.com</td>
						<td class="text-center">
							<img src="img/admin/lock.png">
						</td>
						<td class="text-center">
							<a href="admin_user_form.php?id=1"><img src="img/admin/edit.png"/></a>
							<a href="admin_user.php?action=delete&id=1" class="btn-delete"><img src="img/admin/trash.png"/></a>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="pagination-link">
				<ul class="clearfix">
					<li><a href="#" class="disabled"><img src="img/admin/first.png"/></a></li>
					<li><a href="#" class="disabled"><img src="img/admin/prev.png"/></a></li>
					<li><a href="#" class="active">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li><a href="#"><img src="img/admin/next.png"/></a></li>
					<li><a href="#"><img src="img/admin/last.png"/></a></li>
				</ul>
			</div>


			<h2 id="pageTitle">Quản lý Người dùng - Thêm mới</h2>

			<div class="alert-box">
				<p>Đã thêm mới bản ghi thành công</p>
			</div>

			<div class="error-box">
				<p>Đã thêm mới bản ghi thành công</p>
			</div>

			<form action="" method="POST" enctype="multipart/form-data">
				<div class="form-row clearfix error">
					<label class="form-label">Tài khoản <span class="required">*</span>:</label>
					<div class="form-control">
						<input type="text" name="username" value="" class="input-md" required/>
					</div>
				</div><!-- /.form-row clearfix -->
				<div class="form-row clearfix">
					<label class="form-label">Mật khẩu <span class="required">*</span>:</label>
					<div class="form-control">
						<input type="password" name="password" value="" class="input-md" required/>
					</div>
				</div><!-- /.form-row clearfix -->
				<div class="form-row clearfix">
					<label class="form-label">Họ và tên <span class="required">*</span>:</label>
					<div class="form-control">
						<input type="text" name="fullname" value="" class="input-md" required/>
					</div>
				</div><!-- /.form-row clearfix -->
				<div class="form-row clearfix">
					<label class="form-label">Email <span class="required">*</span>:</label>
					<div class="form-control">
						<input type="email" name="email" value="" class="input-md" required/>
					</div>
				</div><!-- /.form-row clearfix -->
				<div class="form-row clearfix">
					<label class="form-label">Trạng thái:</label>
					<div class="form-control">
						<label><input type="checkbox" name="is_active" value="1"/> Hoạt động</label>
					</div>
				</div><!-- /.form-row clearfix -->

				<div class="form-row clearfix">
					<label class="form-label">&nbsp;</label>
					<div class="form-control">
						<p>
							<button type="submit">Thêm mới</button>
							<button type="reset">Nhập lại</button>
							- <a href="admin_user_form.php">Thêm mới</a>
							- <a href="admin_user.php">Danh sách</a>
						</p>
						<p>
							Các trường có dấu <span class="required">*</span> là các trường bắt buộc cần nhập.
						</p>
					</div>
				</div><!-- /.form-row clearfix -->
			</form>


		</div><!-- /#pageBody -->

		<footer id="pageFooter">
			Copyright &copy; <?= date('Y') ?> I-Designer
		</footer>
	</div><!-- /.wrapper -->

	<!-- jQuery -->
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/admin.js"></script>
</body>
</html>
