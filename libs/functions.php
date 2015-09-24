<?php
/**
 * Trả về icon cho trạng thái is_active
 *
 * @param object $item
 * @param string $urlBase
 * @return string
 */
function renderActive($item, $urlBase)
{
	$html = '<a href="'.$urlBase.'?action=active&id='.$item->id.'">';
	$html .= '<img src="img/admin/'.((intval($item->is_active) === 1)?'unlock':'lock').'.png">';
	$html .= '</a>';

	return $html;
}

/**
 * Hàm tạo slug cho tiếng Việt
 *
 * @param string $str
 * @return string
 */
function slugify($str)
{
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
	$tmp = str_replace(' ','-',$tmp);
	$tmp = str_replace('_','-',$tmp);
	$tmp = str_replace('.','',$tmp);
	$tmp = str_replace("'",'',$tmp);
	$tmp = str_replace('"','',$tmp);
	$tmp = str_replace('"','',$tmp);
	$tmp = str_replace('"','',$tmp);
	$tmp = str_replace("'",'',$tmp);
	$tmp = str_replace('̀','',$tmp);
	$tmp = str_replace('&','',$tmp);
	$tmp = str_replace('@','',$tmp);
	$tmp = str_replace('^','',$tmp);
	$tmp = str_replace('=','',$tmp);
	$tmp = str_replace('+','',$tmp);
	$tmp = str_replace(':','',$tmp);
	$tmp = str_replace(',','',$tmp);
	$tmp = str_replace('{','',$tmp);
	$tmp = str_replace('}','',$tmp);
	$tmp = str_replace('?','',$tmp);
	$tmp = str_replace('\\','',$tmp);
	$tmp = str_replace('/','',$tmp);
	$tmp = str_replace('quot;','',$tmp);

	return $tmp;
}

/**
 * Lấy tất cả danh mục
 *
 * @param mysqli $condb Đối tượng kết nối database
 * @return array
 * @throws Exception Lỗi xảy ra khi truy vấn lỗi
 */
function getCategoryList($condb)
{
    $list = array();
    $sql = "SELECT * FROM category";
    if($result = $condb->query($sql)) {
        while($obj = $result->fetch_object()) {
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
function getFirmList($condb)
{
    $list = array();
    $sql = "SELECT * FROM firm";
    if($result = $condb->query($sql)) {
        while($obj = $result->fetch_object()) {
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
function checkExistFile($file, $dir, $autoIncr = 0)
{
	//Format lại file name
	$filePart = pathinfo($file);
	$fileCompare = $filePart['filename'];
	$fileCompare .= ($autoIncr > 0)?'-'.$autoIncr:'';
	$fileCompare .= '.'.$filePart['extension'];

	//Kiểm tra xem file có tồn tại hay không, nếu tồn tại thì gọi lại hàm kiểm tra
	if(file_exists($dir.$fileCompare)) {
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
function uploadDocFile($field)
{
	if(isset($_FILES[$field]) && $_FILES[$field]['name'] != '') {
		//Format lại tên file
		$filePart = pathinfo($_FILES[$field]['name']);
		$fileName = strtolower(slugify($filePart['filename']).'.'.$filePart['extension']);
		//Kiểm tra xem file đã tồn tại chưa, nếu tồn tại rồi thì tự động truyền thêm hậu tố _<số tự tăng>
		$fileName = checkExistFile($fileName, UPLOAD_DIR);

		$tmp = $_FILES[$field]['tmp_name'];
		//Di chuyển file vào thư mục tạm của upload
        if(move_uploaded_file($tmp, UPLOAD_DIR.$fileName)) {

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
function uploadImgFile($field)
{
	if(isset($_FILES[$field]) && $_FILES[$field]['name'] != '') {
		//Format lại tên file
		$filePart = pathinfo($_FILES[$field]['name']);
		$fileName = strtolower(slugify($filePart['filename']).'.'.$filePart['extension']);
		//Kiểm tra xem file đã tồn tại chưa, nếu tồn tại rồi thì tự động truyền thêm hậu tố _<số tự tăng>
		$fileName = checkExistFile($fileName, UPLOAD_DIR);

		$tmp = $_FILES[$field]['tmp_name'];
		//Di chuyển file vào thư mục tạm của upload
        if(move_uploaded_file($tmp, UPLOAD_DIR.'tmp/'.$fileName)) {
			//Thay đổi kích thước của file xuống kích thước theo cấu hình, và copy vào thư mục uploads
            generateThumbnail(UPLOAD_DIR.'tmp/'.$fileName, UPLOAD_DIR, UPLOAD_W, UPLOAD_H, UPLOAD_QUANTITY);
			//Tạo thumbnail nhỏ hơn, theo kích thước như cấu hình và copy vào thư mục uploads/thumbs/
			generateThumbnail(UPLOAD_DIR.$fileName, UPLOAD_DIR.'thumbs/', UPLOAD_THUMB_W, UPLOAD_THUMB_H, UPLOAD_QUANTITY);
			//Xóa file ở thư mục tạm đi
			unlink(UPLOAD_DIR.'tmp/'.$fileName);

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
function generateThumbnail($src, $des, $width, $height, $quality = 80)
{
	//File đích copy đến
	$desFile = $des.pathinfo($src, PATHINFO_BASENAME);
	
	//Lấy phần mở rộng của file
	$type = pathinfo($src, PATHINFO_EXTENSION);
	if($type == 'jpeg') $type = 'jpg';
	//Tạo đối tượng ảnh nguồn tùy theo loại ảnh
	switch($type){
	  case 'bmp': $source_image = imagecreatefromwbmp($src); break;
	  case 'gif': $source_image = imagecreatefromgif($src); break;
	  case 'jpg': $source_image = imagecreatefromjpeg($src); break;
	  case 'png': $source_image = imagecreatefrompng($src); break;
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
		if ($originW > 0) $ratioW = $width/$originW;
		if ($originH > 0) $ratioH = $height/$originH;
		//Lấy ra tỷ lệ bé nhất
		if ($ratioW>$ratioH) {
			$ratio=$ratioH;
		} else {
			$ratio=$ratioW;
		}
		//Tính kích thước ảnh theo tỷ lệ mới
		$newW = intval($originW*$ratio);
		$newH = intval($originH*$ratio);
	}
	//Tạo file ảnh thumbnail theo kích thước mới
	$virtual_image = imagecreatetruecolor($newW, $newH);
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $newW, $newH, $originW, $originH);
	//Ghi file ảnh thumbnail ra đĩa cứng
	switch($type){
	  case 'bmp': imagewbmp($virtual_image, $desFile); break;
	  case 'gif': imagegif($virtual_image, $desFile); break;
	  case 'jpg': imagejpeg($virtual_image, $desFile, $quality); break;
	  case 'png': imagepng($virtual_image, $desFile); break;
	  default : return false;
	}

	return true;
}

/**
 * Xóa file upload cùng với thumbnail
 * 
 * @param string $file
 */
function deleteFileUpload($file)
{
	//Xóa file
	if($file !='' && file_exists(UPLOAD_DIR.$file)) {
		unlink(UPLOAD_DIR.$file);
	}
	//Xóa file thumbnail
	if($file !='' && file_exists(UPLOAD_DIR.'thumbs/'.$file)) {
		unlink(UPLOAD_DIR.'thumbs/'.$file);
	}
}

/**
 * Format lại hiển thị tiền Việt
 * 
 * @param float $money
 * @return type
 */
function vietnameseMoneyFormat($money, $symbol = '')
{
	return number_format($money, 0, '.', ',').' '.$symbol;
}