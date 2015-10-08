//Обработка клика на стрелку вправо
$(document).on('click', ".carousel-button-right_down",function(){ 
	var carusel_down = $(this).parents('.disscused_articles_block');
	right_carusel_a(carusel_down);
	return false;
});
//Обработка клика на стрелку влево
$(document).on('click',".carousel-button-left_down",function(){ 
	var carusel_down = $(this).parents('.disscused_articles_block');
	left_carusel_a(carusel_down);
	return false;
});
function left_carusel_a(carusel_down){
   var block_width = $(carusel_down).find('.carousel-block_down').outerWidth();
   $(carusel_down).find(".carousel-items_down .carousel-block_down").eq(-1).clone().prependTo($(carusel_down).find(".carousel-items_down")); 
   $(carusel_down).find(".carousel-items_down").css({"left":"-"+block_width+"px"});
   $(carusel_down).find(".carousel-items_down .carousel-block_down").eq(-1).remove();    
   $(carusel_down).find(".carousel-items_down").animate({left: "0px"}, 200); 
   
}
function right_carusel_a(carusel_down){
   var block_width = $(carusel_down).find('.carousel-block_down').outerWidth();
   $(carusel_down).find(".carousel-items_down").animate({left: "-"+ block_width +"px"}, 200, function(){
	  $(carusel_down).find(".carousel-items_down .carousel-block_down").eq(0).clone().appendTo($(carusel_down).find(".carousel-items_down")); 
      $(carusel_down).find(".carousel-items_down .carousel-block_down").eq(0).remove(); 
      $(carusel_down).find(".carousel-items_down").css({"left":"0px"}); 
   }); 
}

$(function() {
//Раскомментируйте строку ниже, чтобы включить автоматическую прокрутку карусели
//	auto_right('.carousel:first');
})

// Автоматическая прокрутка
function auto_right_a(carusel_down){
	setInterval(function(){
		if (!$(carusel_down).is('.hover'))
			right_carusel_a(carusel_down);
	}, 1000)
}
// Навели курсор на карусель
$(document).on('mouseenter', '.carousel_down', function(){$(this).addClass('hover')})
//Убрали курсор с карусели
$(document).on('mouseleave', '.carousel_down', function(){$(this).removeClass('hover')})