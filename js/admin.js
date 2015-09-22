$('.btn-delete').click(function(e){
	if(confirm('Bạn có chắc chắn xóa không?') === false) {
		e.preventDefault();
	}
});

$('.error input').focus(function(){
	$(this).closest('.error').removeClass('error');
});
