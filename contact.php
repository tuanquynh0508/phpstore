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

//Khởi tạo đối tượng đầu tiên cho form, các trường của đối tượng là các trường của form
$record = new stdClass();
$record->fullname = '';
$record->email = '';
$record->tel = '';
$record->subject = '';
$record->content = '';

//Khai báo mảng danh sách kiểm tra
$validates = array(
	array('type'=>'required', 'field'=>'fullname', 'message'=>'Cần nhập Họ và tên'),
	array('type'=>'required', 'field'=>'email', 'message'=>'Cần nhập Email'),
	array('type'=>'required', 'field'=>'subject', 'message'=>'Cần nhập Tiêu đề'),
	array('type'=>'required', 'field'=>'content', 'message'=>'Cần nhập Nội dung'),
	array('type'=>'length', 'field'=>'fullname', 'min'=>3, 'max'=>60, 'message'=>'Độ dài Họ và tên tối thiểu là 3, lớn nhất là 60 ký tự'),	
	array('type'=>'length', 'field'=>'subject', 'min'=>3, 'max'=>255, 'message'=>'Độ dài Tiêu đề tối thiểu là 3, lớn nhất là 255 ký tự'),
	array('type'=>'length', 'field'=>'content', 'min'=>8, 'max'=>255, 'message'=>'Độ dài Nội dung tối thiểu là 8, lớn nhất là 255 ký tự'),
	array('type'=>'email', 'field'=>'email', 'message'=>'Sai định dạng Email'),
);
$oValidator = new Validator($validates, $oDBAccess);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$attributes = $_POST;
	
	//Truyền lại giá trị cho đối tượng form
	foreach($attributes as $key => $value){
		$record->$key = $value;
	}
	
	//Đẩy giá trị vào cho đối tượng kiểm tra
	$oValidator->bindData($attributes);
	
	//Nếu việc kiểm tra không có lỗi thì thực hiện ghi hoặc cập nhật dữ liệu vào database
	if($oValidator->validate()) {				
		//Send email
		$subject = $record->subject;
		$filename = __DIR__.'/libs/templates/email/contact.html';
		$params = array(
			'app_name' => APP_NAME,
			'fullname' => $record->fullname,
			'email' => $record->email,
			'tel' => $record->tel,
			'content' => $record->content,
		);
		$body = getTemplate($filename, $params);
		sendEmail(WEBMASTER_EMAIL, $subject, $body, $record->email, $record->fullname);
		
		$oFlashMessage->setFlashMessage('success', 'Cảm ơn bạn đã gửi liên hệ đến '.APP_NAME.'.');
		header("Location: contact.php");
		exit;
	}
}
?>
<?php include 'libs/includes/frontend/header.inc.php'; ?>

			<section id="bodyPage" class="clearfix">

				<section id="leftPage">
					<nav id="leftMenu"><?= renderFrontendLeftMenu($oDBAccess) ?></nav><!-- /#topMenu -->
				</section><!-- /#leftPage -->

				<section id="rightPage">

					<h1 class="pageTitle">Liên hệ</h1>
					
					<div class="clearfix">
						<div class="w-40p pull-left">
							<p><strong>I-Designer</strong></p>
							<p>Dia chi: 52, ngo 6 Ba Trieu, Ha Dong, Ha Noi</p>
							<p>Tel: 0903258221</p>
							<p>Email: <a href="mailto:tuanquynh0508@gmail.com">tuanquynh0508@gmail.com</a></p>

							<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
							<div id="gmap" class="border-1" style="overflow:hidden;height:300px;width:250px;">
								<div id="gmap_canvas" style="height:300px;width:250px;"></div>
								<style>#gmap_canvas img{max-width:none!important;background:none!important}</style>
								<a class="google-map-code" href="http://www.themecircle.net/photography/" id="get-map-data">themecircle.net</a>
							</div>
							<script type="text/javascript"> function init_map(){var myOptions = {zoom:16,center:new google.maps.LatLng(20.967646058829484,105.78064436573482),mapTypeId: google.maps.MapTypeId.ROADMAP};map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(20.967646058829484, 105.78064436573482)});infowindow = new google.maps.InfoWindow({content:"<b>I-Designer</b><br/>52, ngo 6 Ba Trieu, Ha Dong, Ha Noi<br/> Hanoi" });google.maps.event.addListener(marker, "click", function(){infowindow.open(map,marker);});infowindow.open(map,marker);}google.maps.event.addDomListener(window, 'load', init_map);</script>
						</div>
						<div class="w-60p pull-left">
							
							<form action="" method="POST">
								
								<div class="form-row clearfix">
									<label class="form-label">&nbsp;</label>
									<div class="form-control">
										<?php include "libs/includes/frontend/flash_message.inc.php"; ?>
									</div>
								</div><!-- /.form-row clearfix -->
								
								<div class="form-row clearfix">
									<label class="form-label">Họ và tên <span class="required">*</span>:</label>
									<div class="form-control">
										<input type="text" name="fullname" value="<?= $record->fullname ?>" class="input-md <?= $oValidator->checkError('fullname')?'invalid':'' ?>"/>
										<?= $oValidator->fieldError('fullname') ?>
									</div>
								</div><!-- /.form-row clearfix -->

								<div class="form-row clearfix">
									<label class="form-label">Email <span class="required">*</span>:</label>
									<div class="form-control">
										<input type="text" name="email" value="<?= $record->email ?>" class="input-md <?= $oValidator->checkError('email')?'invalid':'' ?>"/>
										<?= $oValidator->fieldError('email') ?>
									</div>
								</div><!-- /.form-row clearfix -->
								
								<div class="form-row clearfix">
									<label class="form-label">Điện thoại:</label>
									<div class="form-control p-r-10">
										<input type="text" name="tel" value="<?= $record->tel ?>" class="input-md <?= $oValidator->checkError('tel')?'invalid':'' ?>"/>
										<?= $oValidator->fieldError('tel') ?>
									</div>
								</div><!-- /.form-row clearfix -->
								
								<div class="form-row clearfix">
									<label class="form-label">Tiêu đề <span class="required">*</span>:</label>
									<div class="form-control p-r-10">
										<input type="text" name="subject" value="<?= $record->subject ?>" class="input-md <?= $oValidator->checkError('subject')?'invalid':'' ?>"/>
										<?= $oValidator->fieldError('subject') ?>
									</div>
								</div><!-- /.form-row clearfix -->
								
								<div class="form-row clearfix">
									<label class="form-label">Nội dung <span class="required">*</span>:</label>
									<div class="form-control">
										<textarea id="content" name="content" rows="10" class="input-md <?= $oValidator->checkError('content')?'invalid':'' ?>"><?= $record->content ?></textarea>
										<?= $oValidator->fieldError('content') ?>
									</div>
								</div><!-- /.form-row clearfix -->
								
								<div class="form-row clearfix">
									<label class="form-label">&nbsp;</label>
									<div class="form-control">
										<p>
											<button type="submit">Gửi liên hệ</button>
											<button type="reset">Nhập lại</button>
										</p>
										<p>
											Các trường có dấu <span class="required">*</span> là các trường bắt buộc cần nhập.
										</p>
									</div>
								</div><!-- /.form-row clearfix -->
								
							</form>
						</div>
					</div>

				</section><!-- /#rightPage -->

			</section><!-- /#bodyPage -->

<?php include 'libs/includes/frontend/footer.inc.php'; ?>
