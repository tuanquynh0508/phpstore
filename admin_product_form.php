<?php
//Gọi cấu hình, thư viện được sử dụng
include 'libs/config.php';
include 'libs/functions.php';

//Khai báo các class sử dụng
use libs\classes\DBAccess;
use libs\classes\FlashMessage;
use libs\classes\DBPagination;
use libs\classes\HttpException;
use libs\classes\Validator;

//Tạo các đối tượng cần dùng
$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

//Khai báo tiêu đề và module cho page
$pageAliasName = 'product';
$pageTitle = 'Quản lý Sản phẩm';

$isAddNew = true;
$id = 0;

//Khởi tạo đối tượng đầu tiên cho form, các trường của đối tượng là các trường của form
$record = new stdClass();
$record->title = '';
$record->slug = '';
$record->is_active = 1;
$record->summary = '';
$record->content = '';
$record->price = 0;
$record->thumbnail = '';
$record->category_id = 0;
$record->firm_id = 0;

//Kiểm tra xem id có tồn tại trên URL hay không, nếu tồn tại có nghĩa là form đang
//ở trạng thái cập nhật. Còn không thì là trạng thái thêm mới
if(isset($_GET['id'])) {
	$isAddNew = false;
	$id = $_GET['id'];
	$record = $oDBAccess->findOneById('product', $id);
}

//Khai báo mảng danh sách kiểm tra
$validates = array(
	array('type'=>'required', 'field'=>'category_id', 'message'=>'Cần nhập Danh mục'),
	array('type'=>'required', 'field'=>'firm_id', 'message'=>'Cần nhập Hãng sản xuất'),
	array('type'=>'required', 'field'=>'title', 'message'=>'Cần nhập Tiêu đề'),
	array('type'=>'required', 'field'=>'slug', 'message'=>'Cần nhập Slug'),
	array('type'=>'length', 'field'=>'title', 'min'=>3, 'max'=>255, 'message'=>'Độ dài Tiêu đề tối thiểu là 3, lớn nhất là 255 ký tự'),
	array('type'=>'length', 'field'=>'slug', 'min'=>3, 'max'=>255, 'message'=>'Độ dài Slug tối thiểu là 3, lớn nhất là 255 ký tự'),
	array('type'=>'unique','field'=>'slug','table'=>'product', 'message'=>'Slug này đã tồn tại trong hệ thống'),
	array('type'=>'number', 'field'=>'price', 'message'=>'Giá phải là số'),
	array('type'=>'required', 'field'=>'content', 'message'=>'Cần nhập Chi tiết'),
	array('type'=>'length', 'field'=>'content', 'min'=>3, 'max'=>255, 'message'=>'Độ dài Chi tiết tối thiểu là 3, lớn nhất là 255 ký tự'),
);
$oValidator = new Validator($validates, $oDBAccess);

//Xử lý khi có một POST form từ client lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$attributes = $_POST;
	
	if(!isset($attributes['is_active'])) {
		$attributes['is_active'] = 0;
	}

	//Truyền lại giá trị cho đối tượng form
	foreach($attributes as $key => $value){
		$record->$key = $value;
	}
	
	//Nếu slug là rỗng thì tạo slug từ title
	if(!isset($attributes['slug'])) {
		$attributes['slug'] = slugify($attributes['title']);
		$record->slug = $attributes['slug'];
	}
	
	//Đẩy giá trị vào cho đối tượng kiểm tra
	$oValidator->bindData($attributes);
	
	//Nếu việc kiểm tra không có lỗi thì thực hiện ghi hoặc cập nhật dữ liệu vào database
	if($oValidator->validate()) {
		if($isAddNew) {
			//Trường hợp thêm mới
			$attributes['created_at'] = date('Y-m-d H:i:s');
			$record = $oDBAccess->save('product', $attributes);
			//Ghi flash message
			$oFlashMessage->setFlashMessage('success', 'Thêm mới bản ghi thành công');
		} else {
			//Trường hợp cập nhật
			$attributes['updated_at'] = date('Y-m-d H:i:s');
			$record = $oDBAccess->save('product', $attributes, 'id');
			//Ghi flash message
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
	
	<div class="form-row clearfix">
		<label class="form-label">Danh mục <span class="required">*</span>:</label>
		<div class="form-control">
			<select name="category_id">
				<option value="">-- Hãy chọn danh mục --</option>
			<?php
			$listCategory = getCategoryList($oDBAccess);
			foreach ($listCategory as $category) {
				$selected = '';
				if($category->id == $record->category_id){
					$selected = 'selected';
				}
				echo "<option value=\"$category->id\" $selected>$category->title</option>";
			}
			?>
			</select>
			<?= $oValidator->fieldError('category_id') ?>
		</div>
	</div><!-- /.form-row clearfix -->
	
	<div class="form-row clearfix">
		<label class="form-label">Hãng sản xuất <span class="required">*</span>:</label>
		<div class="form-control">
			<select name="firm_id">
				<option value="">-- Hãy chọn hãng --</option>
			<?php
			$listFirm = getFirmList($oDBAccess);
			foreach ($listFirm as $firm) {
				$selected = '';
				if($firm->id == $record->firm_id){
					$selected = 'selected';
				}
				echo "<option value=\"$firm->id\" $selected>$firm->title</option>";
			}
			?>
			</select>
			<?= $oValidator->fieldError('firm_id') ?>
		</div>
	</div><!-- /.form-row clearfix -->
	
	<div class="form-row clearfix">
		<label class="form-label">Tiêu đề <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="title" value="<?= $record->title ?>" class="input-md <?= $oValidator->checkError('title')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('title') ?>
		</div>
	</div><!-- /.form-row clearfix -->
	
	<?php if($record->slug != ''): ?>
	<div class="form-row clearfix">
		<label class="form-label">Slug <span class="required">*</span>:</label>
		<div class="form-control">
			<input type="text" name="slug" value="<?= $record->slug ?>" class="input-md <?= $oValidator->checkError('slug')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('slug') ?>
		</div>
	</div><!-- /.form-row clearfix -->
	<?php endif; ?>
	
	<div class="form-row clearfix">
		<label class="form-label">Tóm tắt:</label>
		<div class="form-control">
			<textarea name="summary" rows="3" class="input-md <?= $oValidator->checkError('summary')?'invalid':'' ?>"><?= $record->summary ?></textarea>
			<?= $oValidator->fieldError('summary') ?>
		</div>
	</div><!-- /.form-row clearfix -->
	
	<div class="form-row clearfix">
		<label class="form-label">Chi tiết <span class="required">*</span>:</label>
		<div class="form-control">
			<textarea name="content" rows="3" class="input-md <?= $oValidator->checkError('content')?'invalid':'' ?>"><?= $record->content ?></textarea>
			<?= $oValidator->fieldError('content') ?>
		</div>
	</div><!-- /.form-row clearfix -->
	
	<div class="form-row clearfix">
		<label class="form-label">Giá:</label>
		<div class="form-control">
			<input type="text" name="price" value="<?= $record->price ?>" class="input-md <?= $oValidator->checkError('price')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('price') ?>
		</div>
	</div><!-- /.form-row clearfix -->
	
	<div class="form-row clearfix">
		<label class="form-label">Ảnh sản phẩm:</label>
		<div class="form-control">
			<input type="file" name="thumbnail" value="" class="input-md <?= $oValidator->checkError('thumbnail')?'invalid':'' ?>"/>
			<?= $oValidator->fieldError('thumbnail') ?>
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
