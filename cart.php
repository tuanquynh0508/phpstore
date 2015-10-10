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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$cart = $_POST['quantity'];
	setCart($cart);
	$oFlashMessage->setFlashMessage('success', 'Đã cập nhật giỏ hàng');
	header("Location: cart.php");
	exit;
}

$cart = getCart();
if(!empty($cart)) {
	$productListId = array_keys($cart);
	$sql = "SELECT * FROM product WHERE id IN (".  implode(',', $productListId).")";
	$productList = $oDBAccess->findAllBySql($sql);
}
?>
<?php include 'libs/includes/frontend/header.inc.php'; ?>

			<section id="bodyPage" class="clearfix">

				<section id="leftPage">
					<nav id="leftMenu"><?= renderFrontendLeftMenu($oDBAccess) ?></nav><!-- /#topMenu -->
				</section><!-- /#leftPage -->

				<section id="rightPage">

					<h1 class="pageTitle">Giỏ hàng</h1>

					<?php include "libs/includes/frontend/flash_message.inc.php"; ?>

					<form action="" method="POST">
						<?php if(!empty($cart)): ?>
						<table class="cart">
							<thead>
								<tr>
									<th>STT</th>
									<th>Sản phẩm</th>
									<th>Đơn giá</th>
									<th>Số lượng</th>
									<th>Thành tiền</th>
									<th>Xóa</th>
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
									<td class="text-center">
										<input type="number" name="quantity[<?= $item->id ?>]" value="<?= $cart[$item->id] ?>" class="product-quantity numeric" min="1" />
									</td>
									<td class="text-center"><span class="real-price"><?= vietnameseMoneyFormat($realPrice, 'VND') ?></span></td>
									<td class="text-center">
										<a href="#" class="btn-delete"><img src="img/frontend/trash.png"/></a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4">Tổng số:</td>
									<td colspan="2"><span class="total-price"><?= vietnameseMoneyFormat($totalPrice, 'VND') ?></span></td>
								</tr>
							</tfoot>
						</table>
						<?php else:?>
						<p>Không có sản phẩm nào trong giỏ hàng</p>
						<?php endif; ?>

						<p class="text-right">
							<button type="button" class="button-link" data-url="index.php">Tiếp tục mua hàng</button>
							<?php if(!empty($cart)): ?>
							<button type="button" class="button-link" data-url="checkout.php">Tạo đơn hàng</button>
							<button type="submit">Cập nhật giỏ hàng</button>
							<?php endif; ?>
						</p>

					</form>

				</section><!-- /#rightPage -->

			</section><!-- /#bodyPage -->

<?php include 'libs/includes/frontend/footer.inc.php'; ?>
