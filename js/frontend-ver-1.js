//Product Tab
function activeCurrentTab(tabId) {
	//Chuyển tất cả các tab về mặc định
	$('.product-detail-box .tab-title li').removeClass('active');
	$('.product-detail-box .tab-content .tab-item').removeClass('active');
	//Kích hoạt tab theo tabId
	$('.product-detail-box .tab-title li').each(function(){
		if($(this).find('a:first').attr('href') == tabId) {
			$(this).addClass('active');
			$(tabId).addClass('active');
		}
	});
}

function checkStateTab() {
	var tabId = window.location.hash;
	if(tabId == ''){
		return false;
	}
	
	activeCurrentTab(tabId);
}

function productTabInit() {
	//Kiểm tra tab mặc định
	checkStateTab();
	
	//Định nghĩa Tab
	$('.product-detail-box .tab-title li').on('click', 'a', function(){
		activeCurrentTab($(this).attr('href'));
	});
}

//Cart
function cartInit() {
	$('.cart .btn-delete').click(function(){
		if(confirm('Bạn có muốn xóa sản phẩm khỏi giỏ hàng không?')) {
			$(this).closest('tr').remove();
			cartUpdateTotalPrice();
		}
	});
	
	$('.cart input[type="number"]').change(function(){
		var row = $(this).closest('tr');
		$('.real-price', row).html(parseInt($('.price', row).html())*parseInt($(this).val()));
		cartUpdateTotalPrice();
	}).on('keyup keypress', function(e) {
		var code = e.keyCode || e.which;
		if (code == 13) { 
			e.preventDefault();
			return false;
		}
	});
}

function cartUpdateTotalPrice() {
	var totalPrice = 0;
	$('.cart .real-price').each(function(){
		totalPrice += parseInt($(this).html());
	});
	$('.total-price').html(totalPrice);
}

////////////////////////////////////////////////////////////////////////////////
//Khởi tạo Tab
productTabInit();

//Khởi tạo cart
cartInit();

$(".fancybox").fancybox();