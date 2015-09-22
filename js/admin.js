$('.btn-delete').click(function(e){
	if(confirm('Bạn có chắc chắn xóa không?') === false) {
		e.preventDefault();
	}
});

$('.valid').focus(function(){
	$(this).removeClass('.valid').next('.errors').remove();
});

$('.invalid').keydown(function(){
	$(this).removeClass('.invalid').next('.errors').remove();
});
