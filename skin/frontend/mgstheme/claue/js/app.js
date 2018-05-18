/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    design
 * @package     rwd_default
 * @copyright   Copyright (c) 2006-2014 X.commerce, Inc. (http://www.magento.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

// =============================================
// Primary Break Points
// =============================================

// These should be used with the bp (max-width, xx) mixin
// where a min-width is used, remember to +1 to break correctly
// If these are changed, they must also be updated in _var.scss

var bp = {
    xsmall: 479,
    small: 599,
    medium: 770,
    large: 979,
    xlarge: 1199
}


/**
 * This class provides an easy and abstracted mechanism to determine the
 * best pointer behavior to use -- that is, is the user currently interacting
 * with their device in a touch manner, or using a mouse.
 *
 * Since devices may use either touch or mouse or both, there is no way to
 * know the user's preferred pointer type until they interact with the site.
 *
 * To accommodate this, this class provides a method and two events
 * to determine the user's preferred pointer type.
 *
 * - getPointer() returns the last used pointer type, or, if the user has
 *   not yet interacted with the site, falls back to a Modernizr test.
 *
 * - The mouse-detected event is triggered on the window object when the user
 *   is using a mouse pointer input, or has switched from touch to mouse input.
 *   It can be observed in this manner: $j(window).on('mouse-detected', function(event) { // custom code });
 *
 * - The touch-detected event is triggered on the window object when the user
 *   is using touch pointer input, or has switched from mouse to touch input.
 *   It can be observed in this manner: $j(window).on('touch-detected', function(event) { // custom code });
 */
var PointerManager = {
    MOUSE_POINTER_TYPE: 'mouse',
    TOUCH_POINTER_TYPE: 'touch',
    POINTER_EVENT_TIMEOUT_MS: 500,
    standardTouch: false,
    touchDetectionEvent: null,
    lastTouchType: null,
    pointerTimeout: null,
    pointerEventLock: false,

    getPointerEventsSupported: function() {
        return this.standardTouch;
    },

    getPointerEventsInputTypes: function() {
        if (window.navigator.pointerEnabled) { //IE 11+
            //return string values from http://msdn.microsoft.com/en-us/library/windows/apps/hh466130.aspx
            return {
                MOUSE: 'mouse',
                TOUCH: 'touch',
                PEN: 'pen'
            };
        } else if (window.navigator.msPointerEnabled) { //IE 10
            //return numeric values from http://msdn.microsoft.com/en-us/library/windows/apps/hh466130.aspx
            return {
                MOUSE:  0x00000004,
                TOUCH:  0x00000002,
                PEN:    0x00000003
            };
        } else { //other browsers don't support pointer events
            return {}; //return empty object
        }
    },

    /**
     * If called before init(), get best guess of input pointer type
     * using Modernizr test.
     * If called after init(), get current pointer in use.
     */
    getPointer: function() {
        // On iOS devices, always default to touch, as this.lastTouchType will intermittently return 'mouse' if
        // multiple touches are triggered in rapid succession in Safari on iOS


        if(this.lastTouchType) {
            return this.lastTouchType;
        }


    },

    setPointerEventLock: function() {
        this.pointerEventLock = true;
    },
    clearPointerEventLock: function() {
        this.pointerEventLock = false;
    },
    setPointerEventLockTimeout: function() {
        var that = this;

        if(this.pointerTimeout) {
            clearTimeout(this.pointerTimeout);
        }

        this.setPointerEventLock();
        this.pointerTimeout = setTimeout(function() { that.clearPointerEventLock(); }, this.POINTER_EVENT_TIMEOUT_MS);
    },

    triggerMouseEvent: function(originalEvent) {
        if(this.lastTouchType == this.MOUSE_POINTER_TYPE) {
            return; //prevent duplicate events
        }

        this.lastTouchType = this.MOUSE_POINTER_TYPE;
        $j(window).trigger('mouse-detected', originalEvent);
    },
    triggerTouchEvent: function(originalEvent) {
        if(this.lastTouchType == this.TOUCH_POINTER_TYPE) {
            return; //prevent duplicate events
        }

        this.lastTouchType = this.TOUCH_POINTER_TYPE;
        mgsjQuery(window).trigger('touch-detected', originalEvent);
    },

    initEnv: function() {
        if (window.navigator.pointerEnabled) {
            this.standardTouch = true;
            this.touchDetectionEvent = 'pointermove';
        } else if (window.navigator.msPointerEnabled) {
            this.standardTouch = true;
            this.touchDetectionEvent = 'MSPointerMove';
        } else {
            this.touchDetectionEvent = 'touchstart';
        }
    },

    wirePointerDetection: function() {
        var that = this;

        if(this.standardTouch) { //standard-based touch events. Wire only one event.
            //detect pointer event
            mgsjQuery(window).on(this.touchDetectionEvent, function(e) {
                switch(e.originalEvent.pointerType) {
                    case that.getPointerEventsInputTypes().MOUSE:
                        that.triggerMouseEvent(e);
                        break;
                    case that.getPointerEventsInputTypes().TOUCH:
                    case that.getPointerEventsInputTypes().PEN:
                        // intentionally group pen and touch together
                        that.triggerTouchEvent(e);
                        break;
                }
            });
        } else { //non-standard touch events. Wire touch and mouse competing events.
            //detect first touch
            mgsjQuery(window).on(this.touchDetectionEvent, function(e) {
                if(that.pointerEventLock) {
                    return;
                }

                that.setPointerEventLockTimeout();
                that.triggerTouchEvent(e);
            });

            //detect mouse usage
            mgsjQuery(document).on('mouseover', function(e) {
                if(that.pointerEventLock) {
                    return;
                }

                that.setPointerEventLockTimeout();
                that.triggerMouseEvent(e);
            });
        }
    },

    init: function() {
        this.initEnv();
        this.wirePointerDetection();
    }
};

/**
 * This class manages the main navigation and supports infinite nested
 * menus which support touch, mouse click, and hover correctly.
 *
 * The following is the expected behavior:
 *
 * - Hover with an actual mouse should expand the menu (at any level of nesting)
 * - Click with an actual mouse will follow the link, regardless of any children
 * - Touch will follow links without children, and toggle submenus of links with children
 *
 * Caveats:
 * - According to Mozilla's documentation (https://developer.mozilla.org/en-US/docs/Web/Guide/Events/Touch_events),
 *   Firefox has disabled Apple-style touch events on desktop, so desktop devices using Firefox will not support
 *   the desired touch behavior.
 */

// ==============================================
// jQuery Init
// ==============================================

// Use $j(document).ready() because Magento executes Prototype inline
mgsjQuery(document).ready(function () {

    // ==============================================
    // Shared Vars
    // ==============================================

    // Document
    var w = mgsjQuery(window);
    var d = mgsjQuery(document);
    var body = mgsjQuery('body');

    // Used to swap primary product photo from thumbnails.

    var mediaListLinks = mgsjQuery('.media-list').find('a');
    var mediaPrimaryImage = mgsjQuery('.primary-image').find('img');

    if (mediaListLinks.length) {
        mediaListLinks.on('click', function (e) {
            e.preventDefault();

            var self = mgsjQuery(this);

            mediaPrimaryImage.attr('src', self.attr('href'));
        });
    }

 
});

// ==============================================
// PDP - image zoom - needs to be available outside document.ready scope
// ==============================================


var settings = {
    preloading: 1, //by default, load all the images, if 0, then only load images after activated (PLACEHOLDER FOR NEXT VERSION)
	zoomLevel: 1, //default zoom level of image
	scrollZoom: false, //allow zoom on mousewheel, true to activate
	scrollZoomIncrement: 0.1,  //steps of the scrollzoom
	minZoomLevel: false,
	maxZoomLevel: false,
	easing: true,
	easingAmount: 12,
	lensSize: 300,
	zoomType: "inner",
	zoomWindowOffetx: 0,
	zoomWindowOffety: 0,
	zoomWindowPosition: 1,
	lensFadeIn: true,
	lensFadeOut: true,
	debug: false,
	zoomWindowFadeIn: true,
	zoomWindowFadeOut: true,
	zoomWindowAlwaysShow: false,
	zoomTintFadeIn: false,
	zoomTintFadeOut: false,
	showLens: true,
	containLensZoom: false,
	lenszoom: true,
	loadingIcon: false, //http://www.example.com/spinner.gif
	cursor:"default", // user should set to what they want the cursor as, if they have set a click function
	responsive:true,
}


var ProductMediaManager = {
    IMAGE_ZOOM_THRESHOLD: 20,
    imageWrapper: null,

    destroyZoom: function() {
		mgsjQuery('.parentZoom').trigger('zoom.destroy');
    },

    createZoom: function(image) {

        if(image.length <= 0) { //no image found
            return;
        }
		zoomElementQr(image);
    },

    swapImage: function(targetImage) {
        targetImage = mgsjQuery(targetImage);
        targetImage.addClass('gallery-image img-responsive');

        mgsjQuery('.parentZoom').trigger('zoom.destroy');
		
		var galleryType = mgsjQuery('#gallery_type').val();
        var imageGallery = mgsjQuery('.product-image-gallery');
		
		var imageGalleryList = mgsjQuery('.product-gallery-image');
		if(galleryType != 'quickview_media'){
			if(targetImage[0].complete) { //image already loaded -- swap immediately

				imageGallery.find('.gallery-image').removeClass('visible');
				imageGalleryList.find('.swatch-placeholder').removeClass('no-margin');

				//move target image to correct place, in case it's necessary
				imageGallery.prepend(targetImage);
				
				imageGalleryList.find('.swatch-placeholder').html(targetImage);
				
				if(galleryType == 2 || galleryType == 3){
					mgsjQuery('html, body').animate({
						scrollTop: mgsjQuery(".product-gallery-list .swatch-placeholder").offset().top - 80
					}, 150);
				}else if(galleryType == 1){
					mgsjQuery('.swatch-placeholder-container').removeClass('no-display');
				}
				//reveal new image
				targetImage.addClass('visible');

				//wire zoom on new image
				zoomElementQr(targetImage);
				
			} else { //need to wait for image to load
				
				//add spinner
				imageGallery.addClass('loading');

				//move target image to correct place, in case it's necessary
				imageGallery.prepend(targetImage);
				imageGalleryList.find('.swatch-placeholder').removeClass('no-margin');
				imageGalleryList.find('.swatch-placeholder').html(targetImage);
				if(galleryType == 2 || galleryType == 3){
					mgsjQuery('html, body').animate({
						scrollTop: mgsjQuery(".product-gallery-list .swatch-placeholder").offset().top - 80
					}, 150);
				}else if(galleryType == 1){
					mgsjQuery('.swatch-placeholder-container').removeClass('no-display');
				}

				//wait until image is loaded
				imagesLoaded(targetImage, function() {
					//remove spinner
					imageGallery.removeClass('loading');

					//hide old image
					imageGallery.find('.gallery-image').removeClass('visible');

					//reveal new image
					targetImage.addClass('visible');

					//wire zoom on new image
					zoomElementQr(targetImage);
				});

			}
		}else {
			var quickviewGallery = mgsjQuery('.gallery-quickview'); 
			if(targetImage[0].complete) {
				quickviewGallery.html(targetImage);
			} else { //need to wait for image to load
				
				//add spinner
				quickviewGallery.addClass('loading');

				quickviewGallery.html(targetImage);

				//wait until image is loaded
				imagesLoaded(targetImage, function() {
					//remove spinner
					quickviewGallery.removeClass('loading');

				});
				
				zoomElementQr(targetImage);

			}
		}
    },

    wireThumbnails: function() {
        //trigger image change event on thumbnail click
        mgsjQuery('.product-image-thumbs .thumb-link').click(function(e) {
			mgsjQuery('.product-image-thumbs .thumb-link.active').removeClass('active');
			mgsjQuery(this).addClass('active');
            e.preventDefault();
            var jlink = mgsjQuery(this);
            var target = mgsjQuery('#image-' + jlink.data('image-index'));

            ProductMediaManager.swapImage(target);
        });
    },

    initZoom: function() {
		zoomElementQr(mgsjQuery(".gallery-image.visible"));
    },

    init: function() {
        ProductMediaManager.imageWrapper = mgsjQuery('.product-img-box');

        // Re-initialize zoom on viewport size change since resizing causes problems with zoom and since smaller
        // viewport sizes shouldn't have zoom
        mgsjQuery(window).on('delayed-resize', function(e, resizeEvent) {
            ProductMediaManager.initZoom();
        });

        ProductMediaManager.initZoom();

        ProductMediaManager.wireThumbnails();

        mgsjQuery(document).trigger('product-media-loaded', ProductMediaManager);
    }
};

mgsjQuery(document).ready(function() {
    ProductMediaManager.init();
});
