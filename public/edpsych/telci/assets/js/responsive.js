// JavaScript Document


var showMenu = function() {
	$('body').toggleClass("active-nav");
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
		
});