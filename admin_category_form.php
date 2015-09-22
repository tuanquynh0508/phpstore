<?php
include 'libs/config.php';
include 'libs/functions.php';

use libs\classes\DBAccess;
use libs\classes\FlashMessage;
use libs\classes\DBPagination;
use libs\classes\HttpException;

$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

$pageAliasName = 'category';
$pageTitle = 'Quản lý Danh mục';

$isAddNew = true;
$id = 0;

$record = new stdClass();
$record->title = '';
$record->slug = '';
$record->is_active = 1;

$formError = array();
$fieldsRequired = array('title','slug');

if(isset($_GET['id'])) {
	$isAddNew = false;
	$id = $_GET['id'];
	$record = $oDBAccess->findOneById('category', $id);
}

//Check POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$attributes = $_POST;

	if(!isset($attributes['is_active'])) {
		$attributes['is_active'] = 0;
	}

	//Validate input data
	foreach($attributes as $key => $value){
		$record->$key = $value;
		if(in_array($key, $fieldsRequired) && empty($value)) {
			$formError[$key] = "Cần nhập giá trị $key";
		}
	}

	if(empty($formError)) {
		if($isAddNew) {
			//Add Record
			$attributes['created_at'] = date('Y-m-d H:i:s');
			$record = $oDBAccess->save('category', $attributes);
			$oFlashMessage->setFlashMessage('success', 'Thêm mới bản ghi thành công');
		} else {
			//Update Record
			$attributes['updated_at'] = date('Y-m-d H:i:s');
			$record = $oDBAccess->save('category', $attributes, 'id');
			$oFlashMessage->setFlashMessage('success', 'Cập nhật bản ghi thành công');
		}
		header("Location: admin_{$pageAliasName}_form.php?id={$record->id}");
		exit;
	}
}
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>
<h2 id="pageTitle"><?= $pageTitle ?> - <?= ($isAddNew)?'Thêm mới':'Cập nhật' ?></h2>

<?php include "libs/includes/admin/flash_message.inc.php"; ?>

<form action="" method="POST">
	<?php if(!$isAddNew): ?>
	<input type="hidden" name="id" value="<?= $id ?>"/>
	<?php endif; ?>

	<?php if(!empty($formError)): ?>
	<ul class="errors">
		<?php foreach ($formError as $error): ?>
		<li><?= $error ?></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<div class="form-row clearfix error">
		<label class="form-label">Tiêu đề <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="title" value="<?= $record->title ?>" class="input-md" required aria-required="true" minlength="3" maxlength="255"/>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Slug <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="slug" value="<?= $record->slug ?>" class="input-md" required aria-required="true"/>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Trạng thái:</label>
		<div class="form-control">
			<label><input type="checkbox" name="is_active" value="1" <?= ($record->is_active==1)?'checked="checked"':'' ?>/> Hoạt động</label>
		</div>
	</div><!-- /.form-row clearfix -->

	<?php if(!$isAddNew): ?>
	<div class="form-row clearfix">
		<label class="form-label">Ngày tạo:</label>
		<div class="form-control">
			<?= $record->created_at ?>
		</div>
	</div><!-- /.form-row clearfix -->

	<div class="form-row clearfix">
		<label class="form-label">Cập nhật:</label>
		<div class="form-control">
			<?= $record->updated_at ?>
		</div>
	</div><!-- /.form-row clearfix -->
	<?php endif; ?>

	<div class="form-row clearfix">
		<label class="form-label">&nbsp;</label>
		<div class="form-control">
			<p>
				<button type="submit"><?= ($isAddNew)?'Thêm mới':'Cập nhật' ?></button>
				<button type="reset">Nhập lại</button>
				<?php if(!$isAddNew): ?>
				- <a href="admin_<?= $pageAliasName ?>_form.php">Thêm mới</a>
				<?php endif; ?>
				- <a href="admin_<?= $pageAliasName ?>.php">Danh sách</a>
			</p>
			<p>
				Các trường có dấu <span class="required">*</span> là các trường bắt buộc cần nhập.
			</p>
		</div>
	</div><!-- /.form-row clearfix -->
</form>
<?php include 'libs/includes/admin/footer.inc.php'; ?>
