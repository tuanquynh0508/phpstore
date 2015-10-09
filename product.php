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

$breadcrumbList = array(
	array(
		'url' => 'category.php?slug='.$productCategory->slug,
		'title' => $productCategory->title,
	),
	array(
		'url' => 'product.php?slug='.$product->slug,
		'title' => $product->title,
	)
);

//Thêm vào danh sách đã xem
addProductView($product->id);
$listProductView = getDataProductView($oDBAccess, $product->id);
?>
<?php include 'libs/includes/frontend/header.inc.php'; ?>

			<section id="bodyPage" class="clearfix">

				<section id="leftPage">
					<nav id="leftMenu"><?= renderFrontendLeftMenu($oDBAccess) ?></nav><!-- /#topMenu -->
				</section><!-- /#leftPage -->

				<section id="rightPage">
					
					<?= renderBreadcrumb($breadcrumbList) ?>
					
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
								<p>Đánh giá người dùng có thể sử dụng các plugin của Facebook hoặc các plugin về comment của các dịch vụ cung cấp khác.</p>
							</div><!-- /.tab-item -->
							
						</div><!-- /.tab-content -->
					</div><!-- /.product-detail-box -->
					
					<?php if(!empty($listProductView)): ?>
					<h2>Các sản phẩm đã xem</h2>
					<hr/>
					<div class="category">
						<div class="clearfix">
							<?php foreach ($listProductView as $item): ?>
							<div class="product-item">
								<div class="thumbs">
									<a href="product.php?slug=<?= $item->slug ?>" title="<?= $item->title ?>">
										<?php if($item->thumbnail !='' && file_exists(UPLOAD_DIR.$item->thumbnail)): ?>
										<img src="<?= UPLOAD_DIR.'thumbs/'.$item->thumbnail ?>"/>
										<?php endif; ?>
									</a>
								</div>
								<p class="title"><a href="product.php?slug=<?= $item->slug ?>" title="<?= $item->title ?>"><?= $item->title ?></a></p>
								<p class="price"><?= vietnameseMoneyFormat($item->price, 'VND') ?></p>
								<button class="btn-add-cart" data-id="<?= $item->id ?>">Mua sản phẩm</button>
							</div><!-- /.product-item -->
							<?php endforeach; ?>
						</div>
					</div><!-- /.category -->
					 <?php endif; ?>

				</section><!-- /#rightPage -->

			</section><!-- /#bodyPage -->

<?php include 'libs/includes/frontend/footer.inc.php'; ?>
