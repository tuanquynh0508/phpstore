<?php

/**
 * Trả về icon cho trạng thái is_active
 *
 * @param object $item
 * @param string $urlBase
 * @return string
 */
function renderActive($item, $urlBase) {
	$html = '<a href="' . $urlBase . '?action=active&id=' . $item->id . '">';
	$html .= '<img src="img/admin/' . ((intval($item->is_active) === 1) ? 'unlock' : 'lock') . '.png">';
	$html .= '</a>';

	return $html;
}

/**
 * Hàm tạo slug cho tiếng Việt
 *
 * @param string $str
 * @return string
 */
function slugify($str) {
	$tmp = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	$tmp = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $tmp);
	$tmp = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $tmp);
	$tmp = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $tmp);
	$tmp = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $tmp);
	$tmp = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $tmp);
	$tmp = preg_replace("/(đ)/", 'd', $tmp);
	$tmp = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $tmp);
	$tmp = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $tmp);
	$tmp = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $tmp);
	$tmp = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $tmp);
	$tmp = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $tmp);
	$tmp = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $tmp);
	$tmp = preg_replace("/(Đ)/", 'D', $tmp);
	$tmp = strtolower(trim($tmp));
	//$tmp = str_replace('-','',$tmp);
	$tmp = str_replace(' ', '-', $tmp);
	$tmp = str_replace('_', '-', $tmp);
	$tmp = str_replace('.', '', $tmp);
	$tmp = str_replace("'", '', $tmp);
	$tmp = str_replace('"', '', $tmp);
	$tmp = str_replace('"', '', $tmp);
	$tmp = str_replace('"', '', $tmp);
	$tmp = str_replace("'", '', $tmp);
	$tmp = str_replace('̀', '', $tmp);
	$tmp = str_replace('&', '', $tmp);
	$tmp = str_replace('@', '', $tmp);
	$tmp = str_replace('^', '', $tmp);
	$tmp = str_replace('=', '', $tmp);
	$tmp = str_replace('+', '', $tmp);
	$tmp = str_replace(':', '', $tmp);
	$tmp = str_replace(',', '', $tmp);
	$tmp = str_replace('{', '', $tmp);
	$tmp = str_replace('}', '', $tmp);
	$tmp = str_replace('?', '', $tmp);
	$tmp = str_replace('\\', '', $tmp);
	$tmp = str_replace('/', '', $tmp);
	$tmp = str_replace('quot;', '', $tmp);

	return $tmp;
}

/**
 * Lấy tất cả danh mục
 *
 * @param mysqli $condb Đối tượng kết nối database
 * @return array
 * @throws Exception Lỗi xảy ra khi truy vấn lỗi
 */
function getCategoryList($condb) {
	$list = array();
	$sql = "SELECT * FROM category";
	if ($result = $condb->query($sql)) {
		while ($obj = $result->fetch_object()) {
			$list[] = $obj;
		}
		$result->close();
	} else {
		throw new Exception($condb->error);
	}

	return $list;
}

/**
 * Lấy tất cả hãng sản xuất
 *
 * @param mysqli $condb Đối tượng kết nối database
 * @return array
 * @throws Exception Lỗi xảy ra khi truy vấn lỗi
 */
function getFirmList($condb) {
	$list = array();
	$sql = "SELECT * FROM firm";
	if ($result = $condb->query($sql)) {
		while ($obj = $result->fetch_object()) {
			$list[] = $obj;
		}
		$result->close();
	} else {
		throw new Exception($condb->error);
	}

	return $list;
}

/**
 * Kiểm tra file có tồn tại hay không
 * Hàm sử dụng đệ quy
 *
 * @param string $file Tên file cần kiểm tra
 * @param string $dir Thư mục chứa file cần kiểm tra
 * @param integer $autoIncr Số tự tăng để nối sau file
 * @return string
 */
function checkExistFile($file, $dir, $autoIncr = 0) {
	//Format lại file name
	$filePart = pathinfo($file);
	$fileCompare = $filePart['filename'];
	$fileCompare .= ($autoIncr > 0) ? '-' . $autoIncr : '';
	$fileCompare .= '.' . $filePart['extension'];

	//Kiểm tra xem file có tồn tại hay không, nếu tồn tại thì gọi lại hàm kiểm tra
	if (file_exists($dir . $fileCompare)) {
		return checkExistFile($file, $dir, $autoIncr + 1);
	} else {
		//Nếu chưa tồn tại thì trả về tên file
		return $fileCompare;
	}
}

/**
 * Upload file Tài liệu khác
 *
 * @param string $field Tên trường upload
 * @return string Tên file upload thành công
 */
function uploadDocFile($field) {
	if (isset($_FILES[$field]) && $_FILES[$field]['name'] != '') {
		//Format lại tên file
		$filePart = pathinfo($_FILES[$field]['name']);
		$fileName = strtolower(slugify($filePart['filename']) . '.' . $filePart['extension']);
		//Kiểm tra xem file đã tồn tại chưa, nếu tồn tại rồi thì tự động truyền thêm hậu tố _<số tự tăng>
		$fileName = checkExistFile($fileName, UPLOAD_DIR);

		$tmp = $_FILES[$field]['tmp_name'];
		//Di chuyển file vào thư mục tạm của upload
		if (move_uploaded_file($tmp, UPLOAD_DIR . $fileName)) {

			return $fileName;
		}
	}

	return '';
}

/**
 * Upload file Ảnh
 *
 * @param string $field Tên trường upload
 * @return string Tên file upload thành công
 */
function uploadImgFile($field) {
	if (isset($_FILES[$field]) && $_FILES[$field]['name'] != '') {
		//Format lại tên file
		$filePart = pathinfo($_FILES[$field]['name']);
		$fileName = strtolower(slugify($filePart['filename']) . '.' . $filePart['extension']);
		//Kiểm tra xem file đã tồn tại chưa, nếu tồn tại rồi thì tự động truyền thêm hậu tố _<số tự tăng>
		$fileName = checkExistFile($fileName, UPLOAD_DIR);

		$tmp = $_FILES[$field]['tmp_name'];
		//Di chuyển file vào thư mục tạm của upload
		if (move_uploaded_file($tmp, UPLOAD_DIR . 'tmp/' . $fileName)) {
			//Thay đổi kích thước của file xuống kích thước theo cấu hình, và copy vào thư mục uploads
			generateThumbnail(UPLOAD_DIR . 'tmp/' . $fileName, UPLOAD_DIR, UPLOAD_W, UPLOAD_H, UPLOAD_QUANTITY);
			//Tạo thumbnail nhỏ hơn, theo kích thước như cấu hình và copy vào thư mục uploads/thumbs/
			generateThumbnail(UPLOAD_DIR . $fileName, UPLOAD_DIR . 'thumbs/', UPLOAD_THUMB_W, UPLOAD_THUMB_H, UPLOAD_QUANTITY);
			//Xóa file ở thư mục tạm đi
			unlink(UPLOAD_DIR . 'tmp/' . $fileName);

			return $fileName;
		}
	}

	return '';
}

/**
 * Hàm tạo thumbnail
 *
 * @param string $src
 * @param string $des
 * @param integer $width
 * @param integer $height
 * @param integer $quality
 * @return boolean
 */
function generateThumbnail($src, $des, $width, $height, $quality = 80) {
	//File đích copy đến
	$desFile = $des . pathinfo($src, PATHINFO_BASENAME);

	//Lấy phần mở rộng của file
	$type = pathinfo($src, PATHINFO_EXTENSION);
	if ($type == 'jpeg')
		$type = 'jpg';
	//Tạo đối tượng ảnh nguồn tùy theo loại ảnh
	switch ($type) {
		case 'bmp': $source_image = imagecreatefromwbmp($src);
			break;
		case 'gif': $source_image = imagecreatefromgif($src);
			break;
		case 'jpg': $source_image = imagecreatefromjpeg($src);
			break;
		case 'png': $source_image = imagecreatefrompng($src);
			break;
		default : return false;
	}
	//Lấy kích thước gốc của ảnh
	$originW = imagesx($source_image);
	$originH = imagesy($source_image);
	//Tính toán kích thước mới dựa trên kích thước gốc và kích thước muốn resize
	$newW = $originW;
	$newH = $originH;
	if ($originW >= $width || $originH >= $height) {
		//Tính tỷ lệ theo các chiều
		if ($originW > 0)
			$ratioW = $width / $originW;
		if ($originH > 0)
			$ratioH = $height / $originH;
		//Lấy ra tỷ lệ bé nhất
		if ($ratioW > $ratioH) {
			$ratio = $ratioH;
		} else {
			$ratio = $ratioW;
		}
		//Tính kích thước ảnh theo tỷ lệ mới
		$newW = intval($originW * $ratio);
		$newH = intval($originH * $ratio);
	}
	//Tạo file ảnh thumbnail theo kích thước mới
	$virtual_image = imagecreatetruecolor($newW, $newH);
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $newW, $newH, $originW, $originH);
	//Ghi file ảnh thumbnail ra đĩa cứng
	switch ($type) {
		case 'bmp': imagewbmp($virtual_image, $desFile);
			break;
		case 'gif': imagegif($virtual_image, $desFile);
			break;
		case 'jpg': imagejpeg($virtual_image, $desFile, $quality);
			break;
		case 'png': imagepng($virtual_image, $desFile);
			break;
		default : return false;
	}

	return true;
}

/**
 * Xóa file upload cùng với thumbnail
 *
 * @param string $file
 */
function deleteFileUpload($file) {
	//Xóa file
	if ($file != '' && file_exists(UPLOAD_DIR . $file)) {
		unlink(UPLOAD_DIR . $file);
	}
	//Xóa file thumbnail
	if ($file != '' && file_exists(UPLOAD_DIR . 'thumbs/' . $file)) {
		unlink(UPLOAD_DIR . 'thumbs/' . $file);
	}
}

/**
 * Format lại hiển thị tiền Việt
 *
 * @param float $money
 * @return type
 */
function vietnameseMoneyFormat($money, $symbol = '') {
	return number_format($money, 0, '.', ',') . ' ' . $symbol;
}

/**
 * Send Email
 *
 * @param  string $to
 * @param  string $subject
 * @param  string $message
 * @param  string $fromEmail
 * @param  string $fromName
 * @return boolean
 */
function sendEmail($to, $subject, $message, $fromEmail = '', $fromName = '') {
	//mb_language('Japanese');
	mb_internal_encoding('UTF-8');
	$headers = "From: " . mb_encode_mimeheader($fromName) . "<" . $fromEmail . ">\n";
	$headers .= "Reply-To: " . $fromEmail . "\n";
	$headers .= "Content-type: text/html;\n"; //text/plain
	$headers .= "charset=\"utf-8\";\n";
	$parameters = '-f  ' . $fromEmail;
	return @mb_send_mail($to, $subject, $message, $headers, $parameters);
}

/**
 * Lấy nội dung từ template có truyền đối số vào
 *
 * @param string $filename Đường dẫn file template
 * @param array $params Mảng đối số
 * @return string
 */
function getTemplate($filename, $params = array()) {
	$content = file_get_contents($filename);
	if(!empty($params)) {
		foreach ($params as $key => $value) {
			$content = preg_replace('/{%'.$key.'%}/', $value, $content);
		}
	}

	return $content;
}

/**
 * Tạo ra chuỗi ngẫu nhiên
 *
 * @param  int  $length
 * @return string
 */
function stringRandom($length = 16)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
}

/**
 * Hàm copy tất cả các file và thư mục
 * 
 * @param string $source
 * @param string $dest
 */
function copyFolder($source, $dest)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }
    
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        copyFolder("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}

/**
 * Xóa tất cả thư mục và file
 * 
 * @param string $dirPath
 */
function deleteDirectory($dirPath) {
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object !="..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
    reset($objects);
    rmdir($dirPath);
    }
}
////////////////////////////////////////////////////////////////////////////////

/**
 * Kiểm tra xem đã đăng nhập chưa
 *
 * @return boolean
 */
function checkAuthentication() {
	$user = getUserSession();

	if (null != $user) {
		return true;
	}

	return false;
}

/**
 * Lưu trữ user vào session
 *
 * @param stdClass $user Đối tượng người dùng
 */
function setUserSession($user) {
	$_SESSION['user'] = $user;
}

/**
 * Trả về user được lưu trong session
 *
 * @return stdClass
 */
function getUserSession() {
	if (!isset($_SESSION['user'])) {
		$_SESSION['user'] = null;
	}

	$user = $_SESSION['user'];

	if (null != $user) {
		return $user;
	}

	return null;
}

/**
 * Xóa user trong biến session đi
 */
function removeUserSession() {
	unset($_SESSION['user']);
}

/**
 * Gán giá trị cho trường của user trong session
 *
 * @param string $key
 * @param string $value
 */
function setUserAttrSession($key, $value) {
	if ($user = $_SESSION['user']) {
		$user->$key = $value;
		$_SESSION['user'] = $user;
	}
}

/**
 * Lấy thông tin từ một trường của user session
 *
 * @param string $key
 * @return string
 */
function getUserAttrSession($key) {
	if ($user = $_SESSION['user']) {
		return $user->$key;
	}

	return '';
}

/**
 * Tạo mật khẩu cho user
 *
 * @param string $password
 * @return string
 */
function generateUserPassword($username, $password) {
	return hash("sha256", $password . SECRET_CODE . $username);
}

/**
 * Tạo token để reset mật khẩu cho user
 *
 * @param string $username
 * @return string
 */
function generateUserResetToken($username) {
	return hash("sha256", date('YmdH') . $username . SECRET_CODE);
}

////////////////////////////////////////////////////////////////////////////////

//CART
/**
 * Thêm sản phẩm vào giỏ hàng
 * 
 * @param integer $productId
 * @param integer $quantity
 */
function addCart($productId, $quantity) {
	$cart = getCart();

	if(array_key_exists($productId, $cart)) {
		$cart[$productId] += intval($quantity);
	} else {
		$cart[$productId] = intval($quantity);
	}

	$_SESSION['cart'] = $cart;
}

/**
 * Lấy thông tin giỏ hàng
 * 
 * @return array
 */
function getCart() {
	if(!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}

	$cart = $_SESSION['cart'];

	return $cart;
}

/**
 * Lưu thông tin giỏ hàng
 * 
 * @param array $cart
 */
function setCart($cart) {
	$_SESSION['cart'] = $cart;
}

/**
 * Xóa giỏ hàng
 */
function removeCart() {
	$_SESSION['cart'] = array();
}

/**
 * Lấy số lượng sản phẩm trong giỏ hàng
 * 
 * @return integer
 */
function getTotalProductInCart() {
	$cart = getCart();

	$total = 0;
	if(!empty($cart)) {
		foreach($cart as $quantity) {
			$total += intval($quantity);
		}
	}

	return $total;
}

/**
 * Tạo bảng sản phẩm của giỏ hàng cho gửi mail
 * 
 * @param array $productList
 * @return string
 */
function renderCartTableProductForEmail($productList) {
	$cart = getCart();

	$html = '<table border="1">';
	$html .= '	<thead>';
	$html .= '		<tr>';
	$html .= '			<th>STT</th>';
	$html .= '			<th>Sản phẩm</th>';
	$html .= '			<th>Đơn giá</th>';
	$html .= '			<th>Số lượng</th>';
	$html .= '			<th>Thành tiền</th>';
	$html .= '		</tr>';
	$html .= '	</thead>';
	$html .= '	<tbody>';

	$totalPrice = 0;
	foreach($productList as $item) {
		$realPrice = $item->price*$cart[$item->id];
		$totalPrice += $realPrice;
		$html .= '		<tr>';
		$html .= '			<td class="text-center">1</td>';
		$html .= '			<td>';
		if($item->thumbnail !='' && file_exists(UPLOAD_DIR.$item->thumbnail)){
			$html .= '				<img src="'.APP_URL.UPLOAD_DIR.'thumbs/'.$item->thumbnail .'" height="50"/>';
		}
		$html .= $item->title;
		$html .= '			</td>';
		$html .= '			<td class="text-center">'.vietnameseMoneyFormat($item->price, 'VND').'</td>';
		$html .= '			<td class="text-center">'.$cart[$item->id].'</td>';
		$html .= '			<td class="text-center">'.vietnameseMoneyFormat($realPrice, 'VND').'</td>';
		$html .= '		</tr>';
	}

	$html .= '	</tbody>';
	$html .= '	<tfoot>';
	$html .= '		<tr>';
	$html .= '			<td colspan="4">Tổng số:</td>';
	$html .= '			<td colspan="1" class="text-center">'.vietnameseMoneyFormat($totalPrice, 'VND').'</td>';
	$html .= '		</tr>';
	$html .= '	</tfoot>';
	$html .= '</table>';

	return $html;
}

/**
 * Tạo trạng thái đơn hàng
 * 
 * @param integer $status
 * @return string
 */
function renderCartStatus($status) {
	$statusList = getCartStatusList();
	$messag = $statusList[0];

	if(isset($statusList[$status])) {
		$messag = $statusList[$status];
	}

	switch ($status) {
		case 1:
			$type = 'new';
			break;
		case 2:
			$type = 'progress';
			break;
		case 3:
			$type = 'finished';
			break;
		default:
			$type = 'suspended';
			break;
	}

	$html = '<span class="cart-status cart-status-'.$type.'">';
	$html .= $messag;
	$html .= '</span>';

	return $html;
}

/**
 * Danh sách các trạng thái của đơn hàng
 * 
 * @return array
 */
function getCartStatusList() {
	return array(
		0 => 'Bị hủy',
		1 => 'Chưa xử lý',
		2 => 'Đang xử lý',
		3 => 'Đã hoàn thành'
	);
}

/**
 * Thống kê doanh thu theo tháng hiện tại
 * 
 * @param mysqli $condb
 * @return string
 */
function statisticByCurrentMonth($condb) {
	$sql ="SELECT DATE_FORMAT(o.updated_at, '%d')  AS `day`,
		SUM(op.price*op.quantity) AS cost
		FROM orders o
		LEFT JOIN order_product op ON op.order_id = o.id
		WHERE o.order_status = 3 AND DATE_FORMAT(o.updated_at, '%m%Y') = ".date('mY')."
		GROUP BY DATE_FORMAT(o.updated_at, '%d%m%Y')";
	$listCost = $condb->findAllBySql($sql);
	//Lấy ngày cuối cùng của tháng hiện tại
	$lastDay = intval(date('t'));
	$list = array();
	for($d=1;$d<=$lastDay;$d++) {
		$day = ($d < 10)?'0'.$d:$d;
		$item = new stdClass();
		$item->day = date('Y-m').'-'.$day;
		$item->value = 0;
		foreach($listCost as $row) {
			if($row->day == $day) {
				$item->value = $row->cost;
			}
		}
		$list[] = $item;
	}

	return json_encode($list);
}
////////////////////////////////////////////////////////////////////////////////

//FRONTEND

/**
 * Tạo menu left frontend
 *
 * @param mysqli $condb
 * @return string
 */
function renderFrontendLeftMenu($condb) {
	$slug = '';
	if(isset($_GET['slug'])) {
		$slug = $_GET['slug'];
	}

	$categories = getCategoryList($condb);
	$html = '<ul>';
	foreach ($categories as $category) {
		$class = '';
		if($slug == $category->slug) {
			$class = 'class="active"';
		}
		$html .= '	<li '.$class.'><a href="category.php?slug='.$category->slug.'">'.$category->title.'</a></li>';
	}
	$html .= '</ul>';

	return $html;
}

/**
 * Tìm sản phẩm theo danh mục
 *
 * @param mysqli $condb
 * @param stdClass $category
 * @return array
 * @throws Exception Nếu có lỗi xảy ra
 */
function getProductByCategory($condb, $category) {
	$list = array();
	$sql = "SELECT * FROM product WHERE category_id=".$category->id." LIMIT 0,4";
	if ($result = $condb->query($sql)) {
		while ($obj = $result->fetch_object()) {
			$list[] = $obj;
		}
		$result->close();
	} else {
		throw new Exception($condb->error);
	}

	return $list;
}

/**
 * Tạo đường dẫn Breadcrumb
 * 
 * @param array $pages
 * @return string
 */
function renderBreadcrumb($pages = array()) {
	$html = '<div class="pageBreadcrumb">';
	$html .= '	<ul class="clearfix">';
	$html .= '		<li><a href="index.php">Trang chủ</a></li>';
	if(!empty($pages)) {
		foreach ($pages as $page) {
			$html .= '<li><a href="'.$page['url'].'">'.$page['title'].'</a></li>';
		}
	}
	$html .= '	</ul>';
	$html .= '</div>';
	
	return $html;
}

//PRODUCT SAW
/**
 * Thêm sản phẩm vào danh sách đã xem
 * 
 * @param integer $productId
 */
function addProductView($productId) {
	$productIds = getProductView();
	if(count($productIds) >= NUMBER_PRODUCT_VIEW) {
		array_shift($productIds);
	}
	
	if(!in_array($productId, $productIds)) {
		$productIds[] = $productId;
	}
	
	setProductView($productIds);
}

/**
 * Lấy danh sách sản phẩm đã xem
 * 
 * @return array
 */
function getProductView() {
	if(!isset($_SESSION['product_view'])) {
		$_SESSION['product_view'] = array();
	}

	$products = $_SESSION['product_view'];

	return $products;
}

/**
 * Lưu thông tin sản phẩm đã xem
 * 
 * @param array $list
 */
function setProductView($list) {
	$_SESSION['product_view'] = $list;
}

/**
 * Lấy thông tin sản phẩm đã xem từ database
 * 
 * @param mysqli $condb
 * @return array
 */
function getDataProductView($condb, $currentId) {
	$productIds = getProductView();
	if(!empty($productIds)) {
		$sql = "SELECT * FROM product WHERE id IN(".  implode(',', $productIds).") AND id != $currentId";
		$list = $condb->findAllBySql($sql);
		
		return $list;
	}
	
	return array();
}