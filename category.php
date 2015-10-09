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

$category = $oDBAccess->findOneBySlug('category', $slug);
if(null === $category) {
	throw new HttpException('Danh mục này không tồn tại', 500);
}

$where = "WHERE category_id={$category->id}";

//Lấy ra tổng số bản ghi
$totalRecord = intval($oDBAccess->scalarBySQL("SELECT COUNT(*) FROM product ".$where));
//Tạo ra đối tượng phân trang
$oDBPagination = new DBPagination($totalRecord, 16);
//Lấy ra danh sách các bản ghi
$list = $oDBAccess->findAllBySql("SELECT * FROM product $where ORDER BY created_at DESC {$oDBPagination->getLimit()}");

$breadcrumbList = array(
	array(
		'url' => 'category.php?slug='.$category->slug,
		'title' => $category->title,
	)
);
?>
<?php include 'libs/includes/frontend/header.inc.php'; ?>

			<section id="bodyPage" class="clearfix">

				<section id="leftPage">
					<nav id="leftMenu"><?= renderFrontendLeftMenu($oDBAccess) ?></nav><!-- /#topMenu -->
				</section><!-- /#leftPage -->

				<section id="rightPage">
					
					<?= renderBreadcrumb($breadcrumbList) ?>

					<div class="category">
						<div class="title-box clearfix">
							<h2><?= $category->title ?></h2>
						</div><!-- /.title-box -->
						<div class="clearfix">
							<?php if(!empty($list)): ?>
							<?php foreach ($list as $item): ?>
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
							<?php else: ?>
							<p>Danh mục không có sản phẩm.</p>
							<?php endif; ?>
						</div>
					</div><!-- /.category -->

					<?php if($oDBPagination->getMaxPage() > 1): ?>
					<div class="pagination-link">
						<?= $oDBPagination->renderPagination('category.php') ?>
					</div>
					<?php endif; ?>

				</section><!-- /#rightPage -->

			</section><!-- /#bodyPage -->

<?php include 'libs/includes/frontend/footer.inc.php'; ?>
