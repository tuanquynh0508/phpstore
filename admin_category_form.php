<?php include 'libs/includes/admin/header.inc.php'; ?>
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
			<input type="text" name="username" value="" class="input-md" required aria-required="true" minlength="3" maxlength="255"/>
		</div>
	</div><!-- /.form-row clearfix -->
	<div class="form-row clearfix">
		<label class="form-label">Mật khẩu <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="password" name="password" value="" class="input-md" required aria-required="true" minlength="6" maxlength="255"/>
		</div>
	</div><!-- /.form-row clearfix -->
	<div class="form-row clearfix">
		<label class="form-label">Họ và tên <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="fullname" value="" class="input-md" required aria-required="true"/>
		</div>
	</div><!-- /.form-row clearfix -->
	<div class="form-row clearfix">
		<label class="form-label">Email <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="email" name="email" value="" class="input-md" required aria-required="true"/>
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
<?php include 'libs/includes/admin/footer.inc.php'; ?>
