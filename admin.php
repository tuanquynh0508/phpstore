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

//Kiểm tra đăng nhập, chưa đăng nhập thì chuyển đến trang đăng nhập
if(!checkAuthentication()) {
	header("Location: admin_login.php");
	exit;
}

$sql = 'SELECT COUNT(*) FROM orders WHERE order_status=0';
$orderSuspended = $oDBAccess->scalarBySQL($sql);

$sql = 'SELECT COUNT(*) FROM orders WHERE order_status=1';
$orderNew = $oDBAccess->scalarBySQL($sql);

$sql = 'SELECT COUNT(*) FROM orders WHERE order_status=2';
$orderProgress = $oDBAccess->scalarBySQL($sql);

$sql = 'SELECT COUNT(*) FROM orders WHERE order_status=3';
$orderFinished = $oDBAccess->scalarBySQL($sql);

$sql = 'SELECT COUNT(*) FROM category';
$countCategory = $oDBAccess->scalarBySQL($sql);

$sql = 'SELECT COUNT(*) FROM product';
$countProduct = $oDBAccess->scalarBySQL($sql);

$dataChartCurrentMonth = statisticByCurrentMonth($oDBAccess);
?>

<?php
$cssBlock = <<<CSSBLOCK
<link href="js/morris/morris.css" rel="stylesheet" type="text/css"/>
CSSBLOCK;
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>
<h2 id="pageTitle">Trang điều khiển</h2>

<h3>Biểu đồ doanh thu tháng <?= date('m/Y') ?></h3>
<hr/>
<div id="graph"></div>

<h3>Thống kê đơn hàng</h3>
<hr/>
<p>Đơn hàng mới: <a href="admin_order.php"><?= $orderNew ?></a></p>
<p>Đơn hàng đang xử lý: <a href="admin_order.php"><?= $orderProgress ?></a></p>
<p>Đơn hàng hoàn thành: <a href="admin_order.php"><?= $orderFinished ?></a></p>
<p>Đơn hàng hủy: <a href="admin_order.php"><?= $orderSuspended ?></a></p>

<h3>Thống kê sản phẩm</h3>
<hr/>
<p>Tổng số danh mục: <a href="admin_order.php"><?= $countCategory ?></a></p>
<p>Tổng số sản phẩm: <a href="admin_order.php"><?= $countProduct ?></a></p>

<?php
$jsBlock = <<<JSBLOCK
<script src="js/morris/raphael-min.js"></script>
<script src="js/morris/morris.min.js"></script>
<script>
var data = $dataChartCurrentMonth;
window.m = Morris.Line({
  element: 'graph',
  data: data,
  xkey: 'day',
  ykeys: ['value'],
  labels: ['Doanh thu']
});
</script>
JSBLOCK;
?>

<?php include 'libs/includes/admin/footer.inc.php'; ?>
