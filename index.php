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

$categories = getCategoryList($oDBAccess);
?>
<?php include 'libs/includes/frontend/header.inc.php'; ?>

			<section id="bodyPage" class="clearfix">

				<section id="leftPage">
					<nav id="leftMenu"><?= renderFrontendLeftMenu($oDBAccess) ?></nav><!-- /#topMenu -->
				</section><!-- /#leftPage -->

				<section id="rightPage">
					
					<?php include "libs/includes/frontend/flash_message.inc.php"; ?>
					
					<p><img src="img/frontend/banner.jpg"></p>

					<?php
					if(!empty($categories)):
					foreach ($categories as $category):
						$products = getProductByCategory($oDBAccess, $category);
						if(empty($products)) {
							continue;
						}
					?>
					<div class="category">
						<div class="title-box clearfix">
							<h2><?= $category->title ?></h2>
						</div><!-- /.title-box -->
						<div class="clearfix">
							<?php foreach ($products as $item): ?>
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
					<?php
					endforeach;
					endif;
					?>

				</section><!-- /#rightPage -->

			</section><!-- /#bodyPage -->

<?php include 'libs/includes/frontend/footer.inc.php'; ?>
