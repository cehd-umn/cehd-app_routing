// JavaScript Document


var showSidebar = function() {
	$('body').removeClass("active-nav").toggleClass("active-extra");
	$('#tabnav').removeClass("active-button");					
	$('#tabextra').toggleClass("active-button");
}

var showMenu = function() {
	$('body').removeClass("active-extra").toggleClass("active-nav");
	$('#tabextra').removeClass("active-button");				
	$('#tabnav').toggleClass("active-button");	
}

// add/remove classes everytime the window resize event fires
//jQuery(window).resize(function(){
//		$("body").removeClass("active-extra active-nav show-msearch")	
//});	

jQuery(document).ready(function($) {
		// Toggle for nav menu
		$('#tabnav').click(function(e) {
			e.preventDefault();
			showMenu();							
		});	
		// Toggle for sidebar
		$('#tabextra').click(function(e) {
			e.preventDefault();
			showSidebar();									
		});	
		$('#mobilesearch').click(function(e) {
			$('body').toggleClass("show-msearch");												
		});
		
		
});

$(document).ready(function(){
  $('.bxslider').bxSlider({
  auto: true,
  speed: 2000,
  pause: 4000,
  mode: "fade",
  ease: true,
  slideMargin: 5,
  pager: false,
  autoHover: true
});
});