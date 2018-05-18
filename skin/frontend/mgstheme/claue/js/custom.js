function parallaxInit() {
	mgsjQuery('.parallax').parallax("30%", 0.1);
}

function initSlider(el,number,aplay,stophv,nav,pag){
	mgsjQuery("#"+el).owlCarousel({
		items : number,
		lazyLoad : true,
		navigation : nav,
		pagination : pag,
		autoPlay: aplay,
		stopOnHover: stophv,
		navigationText: ["<i class='pe-7s-angle-left'></i>","<i class='pe-7s-angle-right'></i>"],
		itemsDesktop: [1199,number],
		itemsDesktopSmall: [970,number],
		itemsTablet: [768,2],
		itemsTabletSmall: false,
		itemsMobile: [480,1],
		itemsCustom: false
	}); 
}
function sliderTesmonial(el,number,aplay,stophv,nav,pag){
	mgsjQuery("#"+el).owlCarousel({
		items : number,
		lazyLoad : true,
		navigation : nav,
		pagination : pag,
		autoPlay: aplay,
		stopOnHover: stophv,
		navigationText: ["<i class='pe-7s-angle-left'></i>","<i class='pe-7s-angle-right'></i>"],
		itemsDesktop: [1199,number],
		itemsDesktopSmall: [991,1],
		itemsTablet: [768,1],
		itemsTabletSmall: false,
		itemsMobile: [480,1],
		itemsCustom: false
	}); 
}

function socialSlider(el,number){
	mgsjQuery("#"+el).owlCarousel({
		items : number,
		lazyLoad : true,
		navigation : true,
		pagination : false,
		autoPlay: true,
		stopOnHover: true,
		navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		itemsDesktop: [1199,number],
		itemsDesktopSmall: [970,number],
		itemsTablet: [768,2],
		itemsTabletSmall: false,
		itemsMobile: [480,1],
		itemsCustom: false
	}); 
}
function toggleEl(el){
	//mgsjQuery('.toggle-el').hide();
	mgsjQuery('#'+el).slideToggle('fast');
}

function disableRightSide(){
	mgsjQuery('body').removeClass('enable-side-right');
}
function enableRightSide(){
	mgsjQuery('body').addClass('enable-side-right');
}
function disableLeftSide(){
	mgsjQuery('body').removeClass('enable-side-left');
}
function enableLeftSide(){
	mgsjQuery('body').addClass('enable-side-left');
}

function initThemeJs(){
	// init tooltip
	mgsjQuery('.tooltip-links').tooltip({
		selector: "[data-toggle=tooltip]",
		container: "body"
	});
	
	// init height for product info box
	/* if(mgsjQuery(window).width() > 991) {
		mgsjQuery(".product-info-box").css("min-height", "auto");
		mgsjQuery(".products-grid").each(function() {
			var wrapper = $(this);
			var minBoxHeight = 0;
			mgsjQuery(".product-info-box", wrapper).each(function() {
				if(mgsjQuery(this).height() > minBoxHeight)
					minBoxHeight = mgsjQuery(this).height();
			});
			mgsjQuery(".product-info-box", wrapper).height(minBoxHeight);
		});
	} else {
		mgsjQuery(".product-info-box").css("min-height", "auto");
	} */
}

mgsjQuery(window).load(function() {
	mgsjQuery(window).bind('body', function() {
		parallaxInit();
	});
	
	var $container = mgsjQuery('.masonry-grid');
	// initialize
	$container.masonry({
	  itemSelector: '.item'
	});
	
	initThemeJs();
	
	if(mgsjQuery('.scroll-to-top').length){
		mgsjQuery(window).scroll(function(){
			if (mgsjQuery(this).scrollTop() > 1) {
				mgsjQuery('.scroll-to-top').css({bottom:"25px"});
			} else {
				mgsjQuery('.scroll-to-top').css({bottom:"-100px"});
			}
		});

		mgsjQuery('.scroll-to-top').click(function(){
			mgsjQuery('html, body').animate({scrollTop: '0px'}, 800);
			return false;
		});
	}
	if(mgsjQuery(window).width() > 991){
		mgsjQuery(window).scroll(function(){
			if(mgsjQuery(window).scrollTop() > 25){
				mgsjQuery('.sticky-menu').addClass('header-sticky-menu');
			}else{
				mgsjQuery('.sticky-menu').removeClass('header-sticky-menu');
			}
		});	
	}
	
	
});
var pushMenu = function(){

	mgsjQuery('.btn-responsive-nav').on('click',function(e){

		e.preventDefault();

		var add_html =  '<div class="mask-overlay">';

		mgsjQuery('body').toggleClass('show-menu');

		mgsjQuery(add_html).hide().appendTo('body').fadeIn('fast');

		mgsjQuery('.mask-overlay, .nav-close').on('click',function(){

			mgsjQuery('body').removeClass('show-menu');

			mgsjQuery('.mask-overlay').remove();

		});

	});

}
var pushMiniCart = function(){			
			mgsjQuery('.trigger-open-cart').on('click',function(e){
				e.preventDefault();
				var add_html =  '<div class="mask-overlay">';
				mgsjQuery('body').toggleClass('open-topcart');
				mgsjQuery(add_html).hide().appendTo('body').fadeIn('fast');
				mgsjQuery('.mask-overlay, .push-mini-cart .nav-close').on('click',function(){
					mgsjQuery('body').removeClass('open-topcart');
					mgsjQuery('.mask-overlay').remove();
				});
			});
			
		}
/* responsive menu */
var reponsiveMenu = function(){	
		if(mgsjQuery(window).width() <= 991){
			mgsjQuery('.header-v2 .nav-main-collapse, .header-v9 .nav-main-collapse, .header-v3 .nav-main-collapse, .header-v4 .nav-main-collapse, .header-v5 .header-menu .nav-main-collapse').addClass('push-menu');
		}else{
			mgsjQuery('.header-v3 .nav-main-collapse, .header-v9 .nav-main-collapse, .header-v4 .nav-main-collapse, .header-v5 .header-menu .nav-main-collapse').removeClass('push-menu');
		}
	if(mgsjQuery(window).width() <= 1199){
			mgsjQuery('.header-v2 .nav-main-collapse,.header-v5 .header-menu .nav-main-collapse, .header-v7 .header-menu .nav-main-collapse, .header-v8 .nav-main-collapse').addClass('push-menu');
		}else{
			mgsjQuery('.header-v2 .nav-main-collapse, .header-v5 .header-menu .nav-main-collapse, .header-v7 .header-menu .nav-main-collapse, .header-v8 .nav-main-collapse').removeClass('push-menu');
		}
}
var toggleMenu =  function(){
	mgsjQuery('.push-menu .mega-menu .nav-main .static-menu.dropdown .sub-menu li.level-1 > .toggle-menu > a, .push-menu .mega-menu .nav-main .static-menu li.dropdown-submenu > .toggle-menu > a').click(function(e){
		e.preventDefault();
		if(mgsjQuery(this).hasClass('active')){
						mgsjQuery(this).removeClass('active');
						mgsjQuery(this).parent().siblings('ul').slideUp('fade');	
					}else{
						mgsjQuery('.push-menu .mega-menu .nav-main .static-menu.dropdown .sub-menu li.level-1 .sub-menu, .push-menu .mega-menu .nav-main .static-menu li.dropdown-submenu .sub-menu,.push-menu .mega-menu .nav-main li .level-1.dropdown-submenu .dropdown-menu').slideUp('fade');	
						mgsjQuery('.push-menu .mega-menu .nav-main .static-menu.dropdown .sub-menu li.level-1 > .toggle-menu > a, .push-menu .mega-menu .nav-main .static-menu li.dropdown-submenu > .toggle-menu > a').removeClass('active');
						mgsjQuery(this).parent().siblings('ul').slideDown('fade');	
						mgsjQuery(this).addClass('active');
					}	
	});
}
var toggleSidebar = function(){
	if(mgsjQuery(window).width() < 992){
		mgsjQuery('.sidebar .block:not(.block-layered-nav) .block-title').on('click',function(){
			if(mgsjQuery(this).hasClass('active')){
				mgsjQuery(this).removeClass('active');
				mgsjQuery(this).siblings('.block-content').slideUp('fade');	
			}else{
						mgsjQuery(this).siblings('.block-content').slideDown('fade');	
						mgsjQuery(this).addClass('active');
					}
		});
		mgsjQuery('.sidebar .block-layered-nav .filter-list dl dt').on('click',function(){
			if(mgsjQuery(this).hasClass('active')){
				mgsjQuery(this).removeClass('active');
				mgsjQuery(this).siblings('dd').slideUp('fade');	
			}else{
						mgsjQuery(this).siblings('dd').slideDown('fade');	
						mgsjQuery(this).addClass('active');
					}
		});
	}
	
}/* 
var pushMenu = function(){
	mgsjQuery('.btn-responsive-nav').on('click',function(e){
		e.preventDefault();
		var add_html =  '<div class="mask-overlay">';
		mgsjQuery('body').toggleClass('show-menu');
		mgsjQuery(add_html).hide().appendTo('body').fadeIn('fast');
		mgsjQuery('.mask-overlay, .nav-close').on('click',function(){
			mgsjQuery('body').removeClass('show-menu');
			mgsjQuery('.mask-overlay').remove();
		});
	});
} */
var setWidth = function(){
	var container_w = mgsjQuery('.header .container').width() - 60;
	var menu_w = mgsjQuery('.widget-vertical-menu .vertical-menu').width();
	var sub_menuW = Math.round(container_w - menu_w);
	mgsjQuery('.vertical-menu .mega-menu-fullwidth .mega-content-wrap').width(sub_menuW);
	
}
function toggleVerticalMenu(el){
	mgsjQuery(el).slideToggle();
}
mgsjQuery(document).ready(function(){	
	pushMenu();
	
	mgsjQuery('.search-form .action-search').on('click',function(e){
		e.preventDefault();		
		mgsjQuery('.search-form').toggleClass('open');	
		mgsjQuery('.search-form form input#advancedsearch').focus();
		mgsjQuery('.close-search').on('click',function(){
			mgsjQuery('.search-form').removeClass('open');			
		});
	});
	reponsiveMenu();
	mgsjQuery(window).on('resize', function(){
		reponsiveMenu();
	});
	toggleMenu();
	toggleSidebar();
	mgsjQuery('.header-v5 .nav-main li.mega-menu-fullwidth:not(.custom-col-2)').hover(function(){
		if(mgsjQuery(window).width() > 1199){			
			var widthMenu = mgsjQuery('body .container').width() - 15;
			mgsjQuery(this).find('ul.dropdown-menu').width(widthMenu);
		}
	});
	
	mgsjQuery(document).on("click",".products-grid .product-content .product-top > a",function(e){
		if(mgsjQuery(window).width() < 992){
			if(!mgsjQuery(this).hasClass('active')){
				mgsjQuery('.products-grid .product-content .product-top > a.active').removeClass('active');
				event.returnValue = false;
				event.preventDefault();
				mgsjQuery(this).addClass('active');
			}
		}
	});
	if(mgsjQuery(window).width() > 1199){
		setWidth();
	}else{
		mgsjQuery('.vertical-menu .mega-menu-fullwidth .mega-content-wrap').css('width','');
	}
});

// init gmap
function initGmap(address, html, image){
	mgsjQuery.ajax({
		type: "GET",
		dataType: "json",
		url: "https://maps.googleapis.com/maps/api/geocode/json",
		data: {'address': address,'sensor':false},
		success: function(data){
			if(data.results.length){
				latitude = data.results[0].geometry.location.lat;
				longitude = data.results[0].geometry.location.lng;
				
				var locations = [
			[html, latitude, longitude, 2]
			];
		
			var map = new google.maps.Map(document.getElementById('map'), {
			  zoom: 14,
				scrollwheel: false,
				navigationControl: true,
				mapTypeControl: false,
				scaleControl: false,
				draggable: true,
				center: new google.maps.LatLng(latitude, longitude),
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			});
		
			var infowindow = new google.maps.InfoWindow();
		
			var marker, i;
		
			for (i = 0; i < locations.length; i++) {  
		  
				marker = new google.maps.Marker({ 
				position: new google.maps.LatLng(locations[i][1], locations[i][2]), 
				map: map ,
				icon: image
				});
		
		
			  google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
				  infowindow.setContent(locations[i][0]);
				  infowindow.open(map, marker);
				}
			  })(marker, i));
			}
			}
		}
	});
}

function initGmapFooter(address, html, image){
	mgsjQuery.ajax({
		type: "GET",
		dataType: "json",
		url: "https://maps.googleapis.com/maps/api/geocode/json",
		data: {'address': address,'sensor':false},
		success: function(data){
			if(data.results.length){
				latitude = data.results[0].geometry.location.lat;
				longitude = data.results[0].geometry.location.lng;
				
				var locations = [
			[html, latitude, longitude, 2]
			];
		
			var map = new google.maps.Map(document.getElementById('map_footer'), {
			  zoom: 14,
				scrollwheel: false,
				navigationControl: true,
				mapTypeControl: false,
				scaleControl: false,
				draggable: true,
				center: new google.maps.LatLng(latitude, longitude),
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			});
		
			var infowindow = new google.maps.InfoWindow();
		
			var marker, i;
		
			for (i = 0; i < locations.length; i++) {  
		  
				marker = new google.maps.Marker({ 
				position: new google.maps.LatLng(locations[i][1], locations[i][2]), 
				map: map ,
				icon: image
				});
		
		
			  google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
				  infowindow.setContent(locations[i][0]);
				  infowindow.open(map, marker);
				}
			  })(marker, i));
			}
			}
		}
	});
}

// open overlay popup
function openOverlay(){
	mgsjQuery('#theme-popup').show();
}

// close overlay popup
function closeOverlay(){
	mgsjQuery('#theme-popup').hide();
}

var active = false;
var data = "";

// Price slider
function sliderAjax(url) {
	if (!active) {
		active = true;
		openOverlay();		
		oldUrl = url;
		try {
			mgsjQuery.ajax({
				url: url,
				dataType: 'json',
				type: 'post',
				data: data,
				success: function(data) {
					if (data.leftcontent) {
						if (mgsjQuery('.block-layered-nav')) {
							mgsjQuery('.block-layered-nav').empty();
							mgsjQuery('.block-layered-nav').append(data.leftcontent);
						}
					}
					if (data.maincontent) {
						mgsjQuery('#product-list-container').empty();
						mgsjQuery('#product-list-container').append(data.maincontent);
					}
					var hist = url.split('?');
					if(window.history && window.history.pushState){
						window.history.pushState('GET', data.title, url);
					}
					initThemeJs();
					closeOverlay();
				}
			});
		} catch (e) {}

		active = false;
	}
	return false;
}

function setTabBackground(url){
	$('tab-background').setStyle({backgroundImage: 'url('+url+')'});
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
} 

function dontShowPopup(el){
	if($(el).checked==true){
		var d = new Date();
		d.setTime(d.getTime() + (24*60*60*1000*365));
		var expires = "expires="+d.toUTCString();
		document.cookie = 'newsletterpopup' + "=" + 'nevershow' + "; " + expires;
	}else{
		document.cookie = 'newsletterpopup' + "= ''; -1";
	}
	
	
}
function closeMgs() {
	mgsjQuery.magnificPopup.close();
}
function showMenu(el) {
	if(!mgsjQuery("."+el).hasClass('collapse')){
		mgsjQuery(".mega-menu>.nav-main>li>.toggle-menu>.icon-drop").parent().siblings('.dropdown-menu').slideUp('fade');	
		mgsjQuery(".mega-menu>.nav-main>li>.toggle-menu>.icon-drop").removeClass('collapse');
		mgsjQuery("#"+el).slideDown('fade');
		mgsjQuery("."+el).addClass('collapse');
	}else{
		mgsjQuery("#"+el).slideUp('fade');
		mgsjQuery("."+el).removeClass('collapse');
	}
}
function showMenu2(el) {
	if(!mgsjQuery("."+el).hasClass('collapse')){
		mgsjQuery(".mega-menu>.nav-main>li>.dropdown-menu li>.toggle-menu>.icon-drop-2").parent().siblings('.sub-menu').slideUp('fade');	
		mgsjQuery(".mega-menu>.nav-main>li>.dropdown-menu li>.toggle-menu>.icon-drop-2").removeClass('collapse');
		mgsjQuery("#"+el).slideDown('fade');
		mgsjQuery("."+el).addClass('collapse');
	}else{
		mgsjQuery("#"+el).slideUp('fade');
		mgsjQuery("."+el).removeClass('collapse');
	}
}