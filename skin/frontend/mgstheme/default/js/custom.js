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
		navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		itemsDesktop: [1199,number],
		itemsDesktopSmall: [970,number],
		itemsTablet: [768,2],
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

var newCount = 2;
var hotCount = 2;
var featuredCount = 2;
var saleCount = 2;

// load more products
function loadMore(count, type, productCount, perRow){
	mgsjQuery('#'+type+'_loadmore_button .loading').show();
	var request = new Ajax.Request(WEB_URL+'mpanel/loadmore/'+type+'?perrow='+perRow+'&p='+count+'&limit='+productCount, {
		onSuccess: function(response) {
			result = response.responseText;
			mgsjQuery('#'+type+'_product_container').append(result);
			mgsjQuery('#'+type+'_loadmore_button .loading').hide();
			
			initThemeJs();
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


// Ajax catalog load
function shopMore(url) {
	oldHtml = mgsjQuery('.category-products ul.products-grid').html();
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
					mgsjQuery('.category-products ul.products-grid').prepend(oldHtml);
				}
				initThemeJs();
				closeOverlay();
			}
		});
	} catch (e) {}
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