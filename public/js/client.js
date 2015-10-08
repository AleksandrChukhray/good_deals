$('#Articles .like_active').click(function(){
	var likeBlock = $(this);
	var likeAmount = parseInt(likeBlock.text())+1;
	
	if (typeof(likeBlock.attr('attr-url')) == 'undefined')
		return false;
		
	$.get(likeBlock.attr('attr-url'), function(response){
		if (response.length == 0){
			$('#Articles .like_active').each(function(){
				$(this).text(likeAmount);
				$(this).removeAttr('attr-url');
				$(this).removeClass('like_active').addClass('liked');
			});
		}
		else {
			var json = JSON.parse(response);
			alert(json.error);
		}
	});
});