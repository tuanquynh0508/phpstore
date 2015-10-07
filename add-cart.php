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

$oDBAccess = new DBAccess();

$response = new stdClass();
$response->status = false;
$response->total = 0;
$response->message = 'Không hỗ trợ phương thức GET.';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$productId = $_POST['id'];
	$quantity = $_POST['quantity'];
	
	$product = $oDBAccess->findOneById('product', $productId);
	if(null === $product) {
		$response->message = 'Sản phẩm này không tồn tại';
	}
	
	$response->status = true;
	$response->message = 'Sản phẩm đã được thêm vào giỏ hàng';
	addCart($productId, $quantity);
	$response->total = getTotalProductInCart();
}

header('Content-type: application/json');
print json_encode($response);
?>
