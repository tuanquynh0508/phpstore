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

//Kiểm tra đăng nhập, chưa đăng nhập thì chuyển đến trang đăng nhập
if(!checkAuthentication()) {
	header("Location: admin_login.php");
	exit;
}

//Tạo các đối tượng cần dùng
$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

//Khai báo tiêu đề và module cho page
$pageAliasName = 'order';
$pageTitle = 'Quản lý Đơn hàng';

$id = 0;
if(isset($_GET['id'])) {
	$id = $_GET['id'];
}

$record = $oDBAccess->findOneById('orders', $id);
if(null === $record) {
	throw new HttpException('Đơn hàng này không tồn tại', 500);
}
$sql = "SELECT p.*, op.quantity "
	."FROM product p "
	."RIGHT JOIN order_product op ON p.id = op.product_id "
	."WHERE op.order_id=".$record->id;
$productList = $oDBAccess->findAllBySql($sql);

//Xử lý khi có một POST form từ client lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$attributes = $_POST;
	
	$attributes['updated_at'] = date('Y-m-d H:i:s');
	$record = $oDBAccess->save('orders', $attributes, 'id');
	//Ghi flash message
	$oFlashMessage->setFlashMessage('success', 'Cập nhật bản ghi thành công');
	header("Location: admin_{$pageAliasName}_view.php?id={$record->id}");
	exit;
}
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>
<h2 id="pageTitle"><?= $pageTitle ?> - Chi tiết</h2>

<?php include "libs/includes/admin/flash_message.inc.php"; ?>

<form action="" method="POST">
	<input type="hidden" name="id" value="<?= $id ?>"/>
	
	<div class="clearfix">
		<div class="w-50p pull-left">
			<p><strong>Họ và tên</strong>: <?= $record->customer_name ?></p>
			<p><strong>Email</strong>: <a href="mailto:<?= $record->customer_email ?>"><?= $record->customer_email ?></a></p>
			<p><strong>Điện thoại</strong>: <?= $record->customer_tel ?></p>
			<p><strong>Địa chỉ</strong>: <?= $record->customer_address ?></p>
		</div>
		<div class="w-50p pull-left">
			<p><strong>Chú thích</strong>:</p>
			<?= $record->note ?>
		</div>
	</div>	
	
	<hr/>
	<h3>Chi tiết đơn hàng</h3>
		<?php if(!empty($productList)): ?>
		<table class="cart" width="100%">
			<thead>
				<tr>
					<th>STT</th>
					<th>Sản phẩm</th>
					<th>Đơn giá</th>
					<th>Số lượng</th>
					<th>Thành tiền</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$totalPrice = 0;
				foreach($productList as $item):	
					$realPrice = $item->price*$item->quantity;
					$totalPrice += $realPrice;
				?>
				<tr>
					<td class="text-center">1</td>
					<td>
						<a href="product.php?slug=<?= $item->slug ?>" title="<?= $item->title ?>" target="_blank">
							<?php if($item->thumbnail !='' && file_exists(UPLOAD_DIR.$item->thumbnail)): ?>
							<img src="<?= UPLOAD_DIR.'thumbs/'.$item->thumbnail ?>" height="50"/>
							<?php endif; ?>
							<?= $item->title ?>
						</a>
					</td>
					<td class="text-center"><span class="price"><?= vietnameseMoneyFormat($item->price, 'VND') ?></span></td>
					<td class="text-center"><?= $item->quantity ?></td>
					<td class="text-center"><span class="real-price"><?= vietnameseMoneyFormat($realPrice, 'VND') ?></span></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4">Tổng số:</td>
					<td colspan="1" class="text-center"><span class="total-price"><?= vietnameseMoneyFormat($totalPrice, 'VND') ?></span></td>
				</tr>
			</tfoot>
		</table>
		<?php else:?>
		<p>Không có sản phẩm nào trong giỏ hàng</p>
		<?php endif; ?>
	<hr/>
	<p><strong>Trạng thái</strong>:
		<?php if($record->order_status != 3): ?>
		<select name="order_status">
		<?php
		$listStatus = getCartStatusList();
		foreach ($listStatus as $status => $statusTest) {
			$selected = '';
			if($status == $record->order_status){
				$selected = 'selected';
			}
			echo "<option value=\"$status\" $selected>$statusTest</option>";
		}
		?>
		</select>
		<?php else: ?>
		<?= renderCartStatus($record->order_status) ?>
		<?php endif; ?>
	</p>
	<p><strong>Ngày tạo</strong>: <?= $record->created_at ?></p>
	<p><strong>Cập nhật</strong>: <?= $record->updated_at ?></p>
	<p>
		<?php if($record->order_status != 3): ?>
		<button type="submit">Cập nhật</button> - 
		<?php endif; ?>
		<a href="admin_<?= $pageAliasName ?>.php">Danh sách</a>
	</p>
	
</form>
<?php include 'libs/includes/admin/footer.inc.php'; ?>
