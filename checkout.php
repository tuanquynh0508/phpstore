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

$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

//Khởi tạo đối tượng đầu tiên cho form, các trường của đối tượng là các trường của form
$record = new stdClass();
$record->customer_name = '';
$record->customer_email = '';
$record->customer_tel = '';
$record->customer_address = '';
$record->note = '';

//Khai báo mảng danh sách kiểm tra
$validates = array(
	array('type'=>'required', 'field'=>'customer_name', 'message'=>'Cần nhập Họ và tên'),
	array('type'=>'required', 'field'=>'customer_email', 'message'=>'Cần nhập Email'),
	array('type'=>'required', 'field'=>'customer_tel', 'message'=>'Cần nhập Điện thoại'),
	array('type'=>'required', 'field'=>'customer_address', 'message'=>'Cần nhập Địa chỉ'),
	array('type'=>'length', 'field'=>'customer_name', 'min'=>3, 'max'=>60, 'message'=>'Độ dài Họ và tên tối thiểu là 3, lớn nhất là 60 ký tự'),
	array('type'=>'length', 'field'=>'customer_tel', 'min'=>7, 'max'=>20, 'message'=>'Độ dài Điện thoại tối thiểu là 7, lớn nhất là 20 ký tự'),
	array('type'=>'length', 'field'=>'customer_address', 'min'=>6, 'max'=>255, 'message'=>'Độ dài Địa chỉ tối thiểu là 6, lớn nhất là 255 ký tự'),
	array('type'=>'email', 'field'=>'customer_email', 'message'=>'Sai định dạng Email'),
);
$oValidator = new Validator($validates, $oDBAccess);

$cart = getCart();
if(!empty($cart)) {
	$productListId = array_keys($cart);
	$sql = "SELECT * FROM product WHERE id IN (".  implode(',', $productListId).")";
	$productList = $oDBAccess->findAllBySql($sql);
} else {
	header("Location: cart.php");
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$attributes = $_POST;

	//Truyền lại giá trị cho đối tượng form
	foreach($attributes as $key => $value){
		$record->$key = $value;
	}

	//Đẩy giá trị vào cho đối tượng kiểm tra
	$oValidator->bindData($attributes);

	//Nếu việc kiểm tra không có lỗi thì thực hiện ghi hoặc cập nhật dữ liệu vào database
	if($oValidator->validate()) {

		$attributes['created_at'] = date('Y-m-d H:i:s');
		$attributes['order_status'] = 1;
		$record = $oDBAccess->save('orders', $attributes);

		foreach($productList as $product) {
			$orderProduct = array(
				'order_id' => $record->id,
				'product_id' => $product->id,
				'price' => $product->price,
				'quantity' => $cart[$product->id]
			);
			$oDBAccess->save('order_product', $orderProduct);
		}

		//Send email
		$subject = "Đơn đặt hàng #{$record->id} từ {$record->customer_name} trên ".APP_NAME;
		$filename = __DIR__.'/libs/templates/email/order.html';
		$tableProduct = renderCartTableProductForEmail($productList);
		$params = array(
			'app_name' => APP_NAME,
			'order_id' => $record->id,
			'fullname' => $record->customer_name,
			'email' => $record->customer_email,
			'tel' => $record->customer_tel,
			'address' => $record->customer_address,
			'note' => $record->note,
			'table_product' => $tableProduct,
			'link' => APP_URL.'admin_order_view.php?id='.$record->id,
		);
		$body = getTemplate($filename, $params);
		sendEmail(ORDER_EMAIL, $subject, $body, $record->customer_email, $record->customer_name);

		removeCart();

		$oFlashMessage->setFlashMessage('success', 'Tạo đơn hàng thành công, cảm ơn bạn đã đặt hàng tại '.APP_NAME);
		header("Location: index.php");
		exit;
	}
}
?>
<?php include 'libs/includes/frontend/header.inc.php'; ?>

			<section id="bodyPage" class="clearfix">

				<section id="leftPage">
					<nav id="leftMenu"><?= renderFrontendLeftMenu($oDBAccess) ?></nav><!-- /#topMenu -->
				</section><!-- /#leftPage -->

				<section id="rightPage">

					<h1 class="pageTitle">Tạo đơn hàng</h1>

					<?php include "libs/includes/frontend/flash_message.inc.php"; ?>

					<?php if(!empty($cart)): ?>
					<table class="cart">
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
							$i = 0;
							foreach($productList as $item):
								$realPrice = $item->price*$cart[$item->id];
								$totalPrice += $realPrice;
								$i++;
							?>
							<tr>
								<td class="text-center"><?= $i ?></td>
								<td>
									<a href="product.php?slug=<?= $item->slug ?>" title="<?= $item->title ?>">
										<?php if($item->thumbnail !='' && file_exists(UPLOAD_DIR.$item->thumbnail)): ?>
										<img src="<?= UPLOAD_DIR.'thumbs/'.$item->thumbnail ?>" height="50"/>
										<?php endif; ?>
										<?= $item->title ?>
									</a>
								</td>
								<td class="text-center">
									<span class="price" data-value="<?= $item->price ?>">
										<?php if($item->price != 0): ?>
										<?= vietnameseMoneyFormat($item->price, 'VND') ?>
										<?php else: ?>
										Liên hệ
										<?php endif; ?>
									</span>
								</td>
								<td class="text-center"><?= $cart[$item->id] ?></td>
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

					<h2>Thông tin khách hàng</h2>
					<hr/>
					<form action="" method="POST">
						<div class="clearfix">

							<div class="pull-left w-50p">

								<div class="form-row clearfix">
									<label class="form-label">Họ và tên <span class="required">*</span>:</label>
									<div class="form-control">
										<input type="text" name="customer_name" value="<?= $record->customer_name ?>" class="input-sm <?= $oValidator->checkError('customer_name')?'invalid':'' ?>"/>
										<?= $oValidator->fieldError('customer_name') ?>
									</div>
								</div><!-- /.form-row clearfix -->

								<div class="form-row clearfix">
									<label class="form-label">Email <span class="required">*</span>:</label>
									<div class="form-control">
										<input type="text" name="customer_email" value="<?= $record->customer_email ?>" class="input-sm <?= $oValidator->checkError('customer_email')?'invalid':'' ?>"/>
										<?= $oValidator->fieldError('customer_email') ?>
									</div>
								</div><!-- /.form-row clearfix -->

							</div>

							<div class="pull-right w-50p">

								<div class="form-row clearfix">
									<label class="form-label">Điện thoại <span class="required">*</span>:</label>
									<div class="form-control p-r-10">
										<input type="text" name="customer_tel" value="<?= $record->customer_tel ?>" class="input-sm <?= $oValidator->checkError('customer_tel')?'invalid':'' ?>"/>
										<?= $oValidator->fieldError('customer_tel') ?>
									</div>
								</div><!-- /.form-row clearfix -->

								<div class="form-row clearfix">
									<label class="form-label">Địa chỉ <span class="required">*</span>:</label>
									<div class="form-control p-r-10">
										<input type="text" name="customer_address" value="<?= $record->customer_address ?>" class="input-sm <?= $oValidator->checkError('customer_address')?'invalid':'' ?>"/>
										<?= $oValidator->fieldError('customer_address') ?>
									</div>
								</div><!-- /.form-row clearfix -->

							</div>

						</div>

						<div class="form-row clearfix">
							<label class="form-label">Chú thích:</label>
							<div class="form-control">
								<textarea id="content" name="note" rows="5" class="input-xlg <?= $oValidator->checkError('note')?'invalid':'' ?>"><?= $record->note ?></textarea>
								<?= $oValidator->fieldError('note') ?>
							</div>
						</div><!-- /.form-row clearfix -->

						<div class="form-row clearfix">
							<label class="form-label">&nbsp;</label>
							<div class="form-control">
								<p>
									<button type="button" class="button-link" data-url="index.php">Tiếp tục mua hàng</button>
									<button type="button" class="button-link" data-url="cart.php">Cập nhật giỏ hàng</button>
									<button type="submit">Tạo đơn hàng</button>
									<button type="reset">Nhập lại</button>
								</p>
								<p>
									Các trường có dấu <span class="required">*</span> là các trường bắt buộc cần nhập.
								</p>
							</div>
						</div><!-- /.form-row clearfix -->
					</form>

				</section><!-- /#rightPage -->

			</section><!-- /#bodyPage -->

<?php include 'libs/includes/frontend/footer.inc.php'; ?>
