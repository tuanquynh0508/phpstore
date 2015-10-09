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
?>
<?php include 'libs/includes/frontend/header.inc.php'; ?>

			<section id="bodyPage" class="clearfix">

				<section id="leftPage">
					<nav id="leftMenu"><?= renderFrontendLeftMenu($oDBAccess) ?></nav><!-- /#topMenu -->
				</section><!-- /#leftPage -->

				<section id="rightPage">

					<h1 class="pageTitle">Giới thiệu</h1>
					
					<p>Nội dung bài viết giới thiệu</p>

				</section><!-- /#rightPage -->

			</section><!-- /#bodyPage -->

<?php include 'libs/includes/frontend/footer.inc.php'; ?>
