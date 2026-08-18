$(function(){
	$(window).scroll(function(){
		var top = $(window).scrollTop();
		if(top >= 778 && $(window).width() > 767){
			$(".nav-controls").addClass("fixed");
			$("#btn-support-fixed").fadeIn();
		}else{
			$(".nav-controls.fixed").removeClass("fixed");
			$("#btn-support-fixed").fadeOut();
		}
	});
});