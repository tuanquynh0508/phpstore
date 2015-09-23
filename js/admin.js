$('.btn-delete').click(function(e){
    if(confirm('Bạn có chắc chắn xóa không?') === false) {
        e.preventDefault();
    }
});

$('.valid').keypress(function(){
    $(this).removeClass('valid');
});

$('.invalid').keypress(function(){
    $(this).removeClass('invalid');
	$(this).next('.errors').remove();
});
