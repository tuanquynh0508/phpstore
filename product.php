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

$slug = '';
if(isset($_GET['slug'])) {
	$slug = $_GET['slug'];
}

$product = $oDBAccess->findOneBySlug('product', $slug);
if(null === $product) {
	throw new HttpException('Sản phẩm này không tồn tại', 500);
}

$productCategory = $oDBAccess->findOneById('category', $product->category_id);
$productFirm = $oDBAccess->findOneById('firm', $product->firm_id);
?>
<?php include 'libs/includes/frontend/header.inc.php'; ?>

			<section id="bodyPage" class="clearfix">

				<section id="leftPage">
					<nav id="leftMenu"><?= renderFrontendLeftMenu($oDBAccess) ?></nav><!-- /#topMenu -->
				</section><!-- /#leftPage -->

				<section id="rightPage">

					<div class="product-summary-box clearfix">
						<?php if($product->thumbnail !='' && file_exists(UPLOAD_DIR.$product->thumbnail)): ?>
						<div class="pull-left">
							<p><img src="<?= UPLOAD_DIR.'thumbs/'.$product->thumbnail ?>"/></p>
							<p><a href="<?= UPLOAD_DIR.$product->thumbnail ?>" class="fancybox"><img src="img/frontend/zoom.png"> Xem ảnh lớn</a></p>
						</div>
						<?php endif; ?>
						<div class="pull-right">
							<h1><?= $product->title ?></h1>
							<p>Mã sản phẩm: <?= $product->id ?></p>
							<p>Danh mục: <?= $productCategory->title ?></p>
							<p>Hãng sản xuất: <?= $productFirm->title ?></p>
							<p class="price">Giá: <?= vietnameseMoneyFormat($product->price, 'VND') ?></p>
							<p>
								Số lượng: <input type="number" value="1" class="product-quantity numeric" min="1"/>
								<button class="btn-add-cart" data-id="<?= $product->id ?>">Mua sản phẩm</button>
							</p>
						</div>
					</div><!-- /.product-summary-box -->
					
					<div class="product-detail-box">
						<ul class="tab-title clearfix">
							<li class="active"><a href="#detailTab">Chi tiết</a></li>
							<li><a href="#reviewTab">Đánh giá người dùng</a></li>
						</ul>
						<div class="tab-content">
							
							<div class="tab-item active" id="detailTab">
								<?= $product->content ?>
							</div><!-- /.tab-item -->
							
							<div class="tab-item" id="reviewTab">
								Đánh giá người dùng
							</div><!-- /.tab-item -->
							
						</div><!-- /.tab-content -->
					</div><!-- /.product-detail-box -->
					
					<?php /*
					<h2>Các sản phẩm đã xem</h2>
					<hr/>
					<div class="category">
						<div class="clearfix">
							<div class="product-item">
								<div class="thumbs"><a href="#"><img src="uploads/thumbs/micro-intel-core-i7-4790.jpg"></a></div>
								<p class="title"><a href="#" title="Sản phẩm 1">Sản phẩm 1</a></p>
								<p class="price">3.000 VND</p>
								<button class="btn-add-cart">Mua sản phẩm</button>
							</div><!-- /.product-item -->
							<div class="product-item">
								<div class="thumbs"><a href="#"><img src="uploads/thumbs/micro-intel-core-i7-4790.jpg"></a></div>
								<p class="title"><a href="#" title="Sản phẩm 1">Sản phẩm 1</a></p>
								<p class="price">3.000 VND</p>
								<button class="btn-add-cart">Mua sản phẩm</button>
							</div><!-- /.product-item -->
							<div class="product-item">
								<div class="thumbs"><a href="#"><img src="uploads/thumbs/micro-intel-core-i7-4790.jpg"></a></div>
								<p class="title"><a href="#" title="Sản phẩm 1">Sản phẩm 1</a></p>
								<p class="price">3.000 VND</p>
								<button class="btn-add-cart">Mua sản phẩm</button>
							</div><!-- /.product-item -->
							<div class="product-item">
								<div class="thumbs"><a href="#"><img src="uploads/thumbs/micro-intel-core-i7-4790.jpg"></a></div>
								<p class="title"><a href="#" title="Sản phẩm 1">Sản phẩm 1</a></p>
								<p class="price">3.000 VND</p>
								<button class="btn-add-cart">Mua sản phẩm</button>
							</div><!-- /.product-item -->
						</div>
					</div><!-- /.category -->
					 */?>

				</section><!-- /#rightPage -->

			</section><!-- /#bodyPage -->

<?php include 'libs/includes/frontend/footer.inc.php'; ?>
