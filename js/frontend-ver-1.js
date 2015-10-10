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
	
	$('.btn-add-cart').click(function(){
		var productId = $(this).attr('data-id');
		var quantity = $(this).attr('data-quantity');
		if(quantity === undefined) {
			quantity = 1;
		}
		addCart(productId, quantity);
	});	
	
	$('.cart input[type="number"], .product-quantity').change(function(){
		var row = $(this).closest('tr');
		var realPrice = parseInt($('.price', row).attr('data-value'))*parseInt($(this).val());
		$('.real-price', row).html(realPrice.formatMoney(0,'.',',')+' VND');
		cartUpdateTotalPrice();
		
		if($('.btn-add-cart').length > 0) {
			$('.btn-add-cart').attr('data-quantity', $(this).val());
		}
	}).on('keyup keypress', function(e) {
		var code = e.keyCode || e.which;
		if (code == 13) { 
			e.preventDefault();
			return false;
		}
	});
	
	$('.button-link').click(function(){
		var url = $(this).attr('data-url');
		if(url !== undefined) {
			window.location.href = url;
		}
	});
}

function cartUpdateTotalPrice() {
	var totalPrice = 0;
	$('.cart .price').each(function(){
		var row = $(this).closest('tr');
		totalPrice += parseInt($(this).attr('data-value'))*parseInt($('input[type="number"]', row).val());
	});
	$('.total-price').html(totalPrice.formatMoney(0,'.',',')+' VND');
}

function addCart(productId, quantity) {
	$.ajax({
		url: 'add-cart.php',
		type: 'POST',
		dataType: 'json',
		cache: false,
		data: {
			id: productId,
			quantity: quantity
		},
		beforeSend: function() {
			$('body').css("cursor", 'wait');
			$('.btn-add-cart').attr("disabled", true);
		},
		success: function(data, textStatus, xhr) {
			$('body').css("cursor", 'default');
			$('.btn-add-cart').removeAttr("disabled");
			if(data.status == true) {
				$("#totalInCart").html(data.total);
			}
			alert(data.message);
		},
		error: function(xhr, textStatus, errorThrown) {
			$('body').css("cursor", 'default');
			$('.btn-add-cart').removeAttr("disabled");
			alert('Lỗi hệ thống, không thêm được vào giỏ hàng.');
		}
	});
}
////////////////////////////////////////////////////////////////////////////////
//Khởi tạo Tab
productTabInit();

//Khởi tạo cart
cartInit();

$('.product-item').matchHeight();

$(".fancybox").fancybox();

$(".numeric").keypress(function (event) {
	// Backspace, tab, enter, end, home, left, right
	// We don't support the del key in Opera because del == . == 46.
	var controlKeys = [8, 9, 13, 35, 36, 37, 39];
	// IE doesn't support indexOf
	var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
	// Some browsers just don't raise events for control keys. Easy.
	// e.g. Safari backspace.
	if (!event.which || // Control keys in most browsers. e.g. Firefox tab is 0
		(49 <= event.which && event.which <= 57) || // Always 1 through 9
		(48 == event.which && $(this).attr("value")) || // No 0 first digit
		isControlKey) { // Opera assigns values for control keys.
	  return;
	} else {
	  event.preventDefault();
	}
});

Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
	c = isNaN(c = Math.abs(c)) ? 2 : c, 
	d = d == undefined ? "." : d, 
	t = t == undefined ? "," : t, 
	s = n < 0 ? "-" : "", 
	i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
	j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
