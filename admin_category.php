<?php
include 'libs/config.php';
include 'libs/functions.php';

use libs\classes\DBAccess;
use libs\classes\FlashMessage;
use libs\classes\DBPagination;
use libs\classes\HttpException;

$oFlashMessage = new FlashMessage();
$oDBAccess = new DBAccess();

$pageAliasName = 'category';
$pageTitle = 'Quản lý Danh mục';

$keyword = '';
if(isset($_GET['keyword'])) {
	$keyword = $_GET['keyword'];
}

$where = '';
if(!empty($keyword)) {
$where = "WHERE title LIKE '%$keyword%' OR slug LIKE '%$keyword%'";
}

//Delete record
if(isset($_GET['action']) && isset($_GET['id']) && $_GET['action']=='delete'){
	try {
		$oDBAccess->deleteById('category', $_GET['id']);
		$oFlashMessage->setFlashMessage('success', 'Đã xóa bản ghi có id là '.$_GET['id']);
	} catch (HttpException $e) {
		$oFlashMessage->setFlashMessage('error', "Không thể xóa bản ghi có id {$_GET['id']} được.");
	}

	header("Location: admin_$pageAliasName.php");
	exit;
}

//Get total record for pagination
$totalRecord = intval($oDBAccess->scalarBySQL("SELECT COUNT(*) FROM category ".$where));
$oDBPagination = new DBPagination($totalRecord, 10);
//Get list
$list = $oDBAccess->findAllBySql("SELECT * FROM category $where ORDER BY created_at DESC {$oDBPagination->getLimit()}");
?>

<?php include 'libs/includes/admin/header.inc.php'; ?>

<h2 id="pageTitle"><?= $pageTitle ?></h2>
<p><a href="admin_<?= $pageAliasName ?>_form.php">Thêm mới</a></p>

<?php include "libs/includes/admin/flash_message.inc.php"; ?>

<?php if(!empty($list)): ?>
<div class="search-box m-b-10">
	<form action="" method="GET">
		<a href="admin_<?= $pageAliasName ?>.php"><img src="img/admin/refresh.png" class="v-middle"/></a>
		<input type="image" src="img/admin/search.png" class="v-middle"/>
		<input type="text" name="keyword" value="<?= $keyword ?>" placeholder="Từ khóa..." required aria-required="true" minlength="3" maxlength="255"/>
	</form>
</div><!-- /.search-box -->

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Tiêu đề</th>
			<th>Slug</th>
			<th>Trạng thái</th>
			<th>Thao tác</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($list as $item): ?>
		<tr>
			<td><?= $item->id ?></td>
			<td><a href="admin_<?= $pageAliasName ?>_form.php?id=<?= $item->id ?>"><?= $item->title ?></a></td>
			<td><?= $item->slug ?></td>
			<td class="text-center"><?= renderActive($item, 'admin_'.$pageAliasName.'.php') ?></td>
			<td class="text-center">
				<a href="admin_<?= $pageAliasName ?>_form.php?id=<?= $item->id ?>"><img src="img/admin/edit.png"/></a>
				<a href="admin_<?= $pageAliasName ?>.php?action=delete&id=<?= $item->id ?>" class="btn-delete"><img src="img/admin/trash.png"/></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>

<?php if($oDBPagination->getMaxPage() > 1): ?>
<div class="pagination-link">
	<?= $oDBPagination->renderPagination('admin_'.$pageAliasName.'.php') ?>
</div>
<?php endif; ?>

<?php include 'libs/includes/admin/footer.inc.php'; ?>
