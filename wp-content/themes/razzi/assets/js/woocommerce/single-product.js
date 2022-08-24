(function ($) {
    'use strict';

    var razzi = razzi || {};
    razzi.init = function () {
        razzi.$body = $(document.body),
            razzi.$window = $(window),
            razzi.$header = $('#site-header');

        // Single product
        this.productVariation();

        // Product Layout
        this.singleProductV1();
        this.singleProductV2();
        this.singleProductV3();
        this.singleProductV4();
        this.singleProductV5();
        this.productTabs();

        this.stickyATC();

        this.productVideo();
        this.productVideoPopup();

        this.relatedProductsCarousel($('.products.related'));
        this.relatedProductsCarousel($('.products.upsells'));

        this.updateFreeShippingBar();
        this.productAutoBackground();

    };

    /**
     * Product Thumbnails
     */
    razzi.productThumbnails = function ($vertical) {
        var $gallery = $('.woocommerce-product-gallery'),
            $thumbnail = $gallery.find('.flex-control-thumbs'),
            $video = $gallery.find('.woocommerce-product-gallery__image.razzi-product-video');

        $gallery.imagesLoaded(function () {
            setTimeout(function () {

                var columns = $gallery.data('columns');

                $thumbnail.wrap('<div class="woocommerce-product-gallery__thumbs-carousel swiper-container" style="opacity:0"></div>');
                $thumbnail.addClass('swiper-wrapper');
                $thumbnail.find('li').addClass('swiper-slide');
                $thumbnail.after('<span class="razzi-svg-icon rz-thumbs-button-prev rz-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></span>');
                $thumbnail.after('<span class="razzi-svg-icon rz-thumbs-button-next rz-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></span>');

                var options = {
                    slidesPerView: columns,
                    loop: false,
                    autoplay: false,
                    speed: 800,
                    watchOverflow: true,
                    spaceBetween: 15,
                    navigation: {
                        nextEl: '.rz-thumbs-button-next',
                        prevEl: '.rz-thumbs-button-prev',
                    },
                    on: {
                        init: function () {
                            $thumbnail.parent().css('opacity', 1);
                        }
                    },
                    breakpoints: {
                        300: {
                            spaceBetween: 0,
                            allowTouchMove: false,
                        },
                        991: {
                            spaceBetween: 15,
                        },
                    }
                };

                if ($vertical) {
                    options.direction = 'vertical';
                } else {
                    options.direction = 'horizontal';
                }

                new Swiper($thumbnail.parent(), options);

                // Add an <span> to thumbnails for responsive bullets.
                $('li', $thumbnail).append('<span/>');

                if ($video.length > 0) {
                    var videoNumber = $('.woocommerce-product-gallery').data('video') - 1;
                    $('.woocommerce-product-gallery').addClass('has-video');
                    $thumbnail.find('li').eq(videoNumber).append('<div class="razzi-i-video"></div>');
                }

            }, 200);

        });
    };

    /**
     * Single Product V1
     */
    razzi.singleProductV1 = function () {
        var $product = $('div.product.layout-v1');

        if (!$product.length) {
            return;
        }
        razzi.productThumbnails(false);
        $('.woocommerce-product-gallery').on('product_thumbnails_slider_horizontal', function(){
            razzi.productThumbnails(false);
        });

    };

    /**
     * Single Product V2
     */
    razzi.singleProductV2 = function () {

        var $product = $('div.product.layout-v2');

        if (!$product.length) {
            return;
        }
        razzi.productThumbnails(true);

        $('.woocommerce-product-gallery').on('product_thumbnails_slider_vertical', function(){
            razzi.productThumbnails(true);
        });
    };

    /**
     * Single Product V3
     */
    razzi.singleProductV3 = function () {
        var $product = $('div.product.layout-v3');

        if (!$product.length) {
            return;
        }

        razzi.responsiveProductGallery();
        razzi.zoomProductImages();
        $('.woocommerce-product-gallery').on('razzi_gallery_init_zoom', function(){
            razzi.zoomProductImages();
        });
    };

    /**
     * Single Product V4
     */
    razzi.singleProductV4 = function () {
        var $product = $('div.product.layout-v4');

        if (!$product.length) {
            return;
        }

        gallery_slider_v4();
        $('.woocommerce-product-gallery').on('product-images-slider', function(){
            gallery_slider_v4();
        });

        function gallery_slider_v4() {
            var $gallery = $('.woocommerce-product-gallery'),
            $galleryWrap = $gallery.find('.woocommerce-product-gallery__wrapper');

            $gallery.imagesLoaded(function () {

                if ($gallery.find('.woocommerce-product-gallery__image').length < 2) {
                    return;
                }

                $gallery.addClass('swiper-container');
                $galleryWrap.addClass('swiper-wrapper');
                $galleryWrap.children().addClass('swiper-slide');
                $galleryWrap.after('<span class="razzi-svg-icon rz-swiper-button-prev rz-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></span>');
                $galleryWrap.after('<span class="razzi-svg-icon rz-swiper-button-next rz-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></span>');
                $galleryWrap.after('<div class="swiper-pagination"></div>');

                var options = {
                    loop: false,
                    autoplay: false,
                    speed: 800,
                    watchOverflow: true,
                    spaceBetween: 10,
                    navigation: {
                        nextEl: '.rz-swiper-button-next',
                        prevEl: '.rz-swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        type: 'bullets',
                        clickable: true
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 1,
                            slidesPerGroup: 1
                        },
                        481: {
                            slidesPerView: 2,
                            slidesPerGroup: 1
                        },
                    }
                };

                new Swiper($gallery, options);
            });

        }

        razzi.zoomProductImages();
        $('.woocommerce-product-gallery').on('razzi_gallery_init_zoom', function(){
            razzi.zoomProductImages();
        });

    };

    /**
     * Single Product V5
     */
    razzi.singleProductV5 = function () {
        var $product = $('div.product.layout-v5');

        if (!$product.length) {
            return;
        }

        razzi.zoomProductImages();
        $('.woocommerce-product-gallery').on('razzi_gallery_init_zoom', function(){
            razzi.zoomProductImages();
        });
        razzi.responsiveProductGallery();
    };

    razzi.zoomProductImages = function() {
        // Init zoom for product gallery images
        $('.woocommerce-product-gallery').find('.woocommerce-product-gallery__image').each(function () {
            razzi.zoomSingleProductImage(this);
        });
    }

    razzi.productAutoBackground = function() {
        if( ! razzi.$body.hasClass('product-has-background') ) {
            return;
        }
        var $product = $('div.product');

        productWidth();

        razzi.$window.on( 'resize', function() {
            productWidth();
        } );

        // Change background color
        if ( !$product.hasClass( 'background-set' )  ) {
            razzi.productBackgroundFromGallery( $product.find('.razzi-product-background-content') );
        }

        /**
		 * Set product width
		 */
		function productWidth() {
			var width = razzi.$window.width(),
                $entryBG = $product.find('.razzi-product-background-content');

			$entryBG.width( width );

			if ( razzi.$body.hasClass('rtl') ) {
				$entryBG.css( 'margin-right', -width / 2 );
			} else {
				$entryBG.css( 'margin-left', -width / 2 );
			}
		}
    }

     /**
     * Product Tabs
     */
      razzi.productTabs = function () {
        var $product = $('div.product');

        if (!$product.hasClass('product-tabs-under-summary')) {
            return;
        }

        // Product tabs
        var $tabs = $product.find('.woocommerce-tabs'),
            $hash = window.location.hash;

        if ($hash.toLowerCase().indexOf("comment-") >= 0 || $hash === "#reviews" || $hash === "#tab-reviews") {
            $tabs.find(".tab-title-reviews").addClass("active");
            $tabs.find(".woocommerce-Tabs-panel--reviews").show();
        }

        $(".woocommerce-review-link").on("click", function () {
            $(".razzi-accordion-title.tab-title-reviews").trigger('click');
        });

        $tabs.on("click", ".razzi-accordion-title", function (e) {
            e.preventDefault();

            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $(this).siblings(".woocommerce-Tabs-panel").stop().slideUp(300);
            } else {
                $tabs.find(".razzi-accordion-title").removeClass("active");
                $tabs.find(".woocommerce-Tabs-panel").slideUp();
                $(this).addClass("active");
                $(this).siblings(".woocommerce-Tabs-panel").stop().slideDown(300);
            }
        });
    };

    /**
     * Related & ppsell products carousel.
     */
    razzi.relatedProductsCarousel = function ($related) {
        if (!$related.length) {
            return;
        }

        var $products = $related.find('ul.products');
        var spaceBetween = razzi.$body.hasClass('razzi-product-card-solid') ? false : true;

        $products.wrap('<div class="swiper-container linked-products-carousel" style="opacity: 0;"></div>');
        $products.addClass('swiper-wrapper');
        $products.find('li.product').addClass('swiper-slide');

        var $number = razzi.$body.hasClass('product-full-width') ? 5 : 4;

        var options = {
            loop: false,
            on: {
                init: function () {
                    this.$el.css('opacity', 1);
                }
            },
            spaceBetween: spaceBetween,
            breakpoints: {
                300: {
                    slidesPerView: razziData.mobile_portrait == '' ? 2 : razziData.mobile_portrait,
                    slidesPerGroup: razziData.mobile_portrait == '' ? 2 : razziData.mobile_portrait,
                    spaceBetween: spaceBetween == true ? 15 : 0,
                },
                480: {
                    slidesPerView: razziData.mobile_landscape == '' ? 3 : razziData.mobile_landscape,
                    slidesPerGroup: razziData.mobile_landscape == '' ? 3 : razziData.mobile_landscape,
                },
                768: {
                    spaceBetween: spaceBetween == true ? 30 : 0,
                    slidesPerView: 3,
                    slidesPerGroup: 3
                },
                992: {
                    slidesPerView: 3,
                    slidesPerGroup: 3
                },
                1200: {
                    slidesPerView: $number,
                    slidesPerGroup: $number,
                    spaceBetween: spaceBetween == true ? 30 : 0,
                }
            }
        };

        if( razziProductData.related_product_navigation == 'scrollbar' ) {
            $products.after('<div class="swiper-scrollbar"></div>');
            options['scrollbar'] = {
                el: '.swiper-scrollbar',
                hide: false,
                draggable: true
            };
        } else if( razziProductData.related_product_navigation == 'arrows' ) {
            $products.after('<span class="razzi-svg-icon rz-swiper-button-prev rz-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></span>');
            $products.after('<span class="razzi-svg-icon rz-swiper-button-next rz-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></span>');

            options['navigation'] = {
                nextEl: $related.find('.rz-swiper-button-next'),
                prevEl: $related.find('.rz-swiper-button-prev'),
           };

        } else {
            $products.after('<div class="swiper-pagination"></div>');

            options['pagination'] = {
                el: $related.find('.swiper-pagination'),
                type: 'bullets',
                clickable: true
             };
        }

        new Swiper($related.find('.linked-products-carousel'), options);
    };

    razzi.productVariation = function () {

        razzi.$body.on('tawcvs_initialized', function () {
            $('.variations_form').off('tawcvs_no_matching_variations');
            $('.variations_form').on('tawcvs_no_matching_variations', function (event, $el) {
                event.preventDefault();

                $('.variations_form').find('.woocommerce-variation.single_variation').show();
                if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                    $('.variations_form').find('.single_variation').slideDown(200).html('<p>' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>');
                }
            });

        });

        $('.variations_form').on('found_variation.wc-variation-form', function (event, variation) {
            var $sku = $('.div.product').find('.sku_wrapper .sku');

            if (typeof $sku.wc_set_content !== 'function') {
                return;
            }

            if (typeof $sku.wc_reset_content !== 'function') {
                return;
            }


            if (variation.sku) {
                $sku.wc_set_content(variation.sku);
            } else {
                $sku.wc_reset_content();
            }

        });

    };

    /**
     * Zoom an image.
     * Copy from WooCommerce single-product.js file.
     */
    razzi.zoomSingleProductImage = function (zoomTarget) {
        if (typeof wc_single_product_params == 'undefined' || !$.fn.zoom) {
            return;
        }

        if ('1' !== razziData.product_image_zoom) {
            return;
        }

        var $target = $(zoomTarget),
            width = $target.width(),
            zoomEnabled = false;

        $target.each(function (index, target) {
            var $image = $(target).find('img');
            if ($image.data('large_image_width') > width) {
                zoomEnabled = true;
                return false;
            }
        });

        // Only zoom if the img is larger than its container.
        if (zoomEnabled) {
            var zoom_options = $.extend({
                touch: false
            }, wc_single_product_params.zoom_options);

            if ('ontouchstart' in document.documentElement) {
                zoom_options.on = 'click';
            }

            $target.trigger('zoom.destroy');
            $target.zoom(zoom_options);
        }
    }

    /**
     * Init slider for product gallery on mobile.
     */
    razzi.responsiveProductGallery = function () {
        if (typeof razziData.product_gallery_slider === 'undefined') {
            return;
        }

        if (razziData.product_gallery_slider || !$.fn.wc_product_gallery) {
            return;
        }

        var $window = $(window),
            $product = $('.woocommerce div.product'),
            default_flexslider_enabled = false,
            default_flexslider_options = {};

        if (!$product.length) {
            return;
        }

        var $gallery = $('.woocommerce-product-gallery', $product),
            $originalGallery = $gallery.clone(),
            $video = $gallery.find('.woocommerce-product-gallery__image.razzi-product-video'),
            sliderActive = false;

        $originalGallery.children('.woocommerce-product-gallery__trigger').remove();

        // Turn off events then we init them again later.
        $originalGallery.off();

        if (typeof wc_single_product_params !== undefined) {
            default_flexslider_enabled = wc_single_product_params.flexslider_enabled;
            default_flexslider_options = wc_single_product_params.flexslider;
        }

        initProductGallery();
        $window.on('resize', initProductGallery);

        // Init product gallery
        function initProductGallery() {
            if ($window.width() >= 992) {
                if (!sliderActive) {
                    return;
                }

                if (typeof wc_single_product_params !== undefined) {
                    wc_single_product_params.flexslider_enabled = default_flexslider_enabled;
                    wc_single_product_params.flexslider = default_flexslider_options;
                }

                // Destroy is not supported at this moment.
                $gallery.replaceWith($originalGallery.clone());
                $gallery = $('.woocommerce-product-gallery', $product);

                $gallery.each(function () {
                    $(this).wc_product_gallery();
                });

                $('form.variations_form select', $product).trigger('change');

                // Init zoom for product gallery images
                if ('1' === razziData.product_image_zoom && $product.hasClass('layout-v3', 'layout-v5')) {
                    $gallery.find('.woocommerce-product-gallery__image').each(function () {
                        razzi.zoomSingleProductImage(this);
                    });
                }

                sliderActive = false;
            } else {
                if (sliderActive) {
                    return;
                }

                if (typeof wc_single_product_params !== undefined) {
                    wc_single_product_params.flexslider_enabled = true;
                    wc_single_product_params.flexslider.controlNav = true;
                }

                $gallery.replaceWith($originalGallery.clone());
                $gallery = $('.woocommerce-product-gallery', $product);

                setTimeout(function () {
                    $gallery.each(function () {
                        $(this).wc_product_gallery();
                    });
                }, 100);

                $('form.variations_form select', $product).trigger('change');

                sliderActive = true;

                if ($video.length > 0) {
                    $('.woocommerce-product-gallery').addClass('has-video');
                }
            }
        }
    };

    /**
     * Init sticky add to cart
     */
    razzi.stickyATC = function () {
        var $selector = $('#razzi-sticky-add-to-cart'),
            $btn = $selector.find('.razzi-sticky-add-to-cart__content-button');

        if (!$selector.length) {
            return;
        }

        if (!$('div.product .entry-summary .cart').length) {
            return;
        }

        var headerHeight = 0,
            cartHeight;

        if (razzi.$body.hasClass('admin-bar')) {
            headerHeight += 32;
        }

        var isTop = $selector.hasClass('razzi-sticky-atc_top') ? true : false;

        function stickyAddToCartToggle() {
            cartHeight = $('.entry-summary .cart').offset().top + $('.entry-summary .cart').outerHeight() - headerHeight;

            if (window.pageYOffset > cartHeight) {
                $selector.addClass('open');

                if (razzi.$body.hasClass('header-sticky') && isTop) {
                    razzi.$body.find('.site-header').addClass('rz-header_sticky-act-active');
                }
            } else {
                $selector.removeClass('open');
                razzi.$body.find('.site-header').removeClass('rz-header_sticky-act-active');
            }

            if (!isTop) {
                var documentHeight = document.body.scrollHeight;
                if (window.pageYOffset > documentHeight - window.innerHeight) {
                    $selector.removeClass('open');
                }
            }
        }

        razzi.$window.on('scroll', function () {
            stickyAddToCartToggle();
        }).trigger('scroll');

        if (!$btn.hasClass('ajax_add_to_cart')) {
            $btn.on('click', function (event) {
                event.preventDefault();

                $('html,body').stop().animate({
                        scrollTop: $(".entry-summary").offset().top
                    },
                    'slow');
            });
        }
    };

    /**
     * Init product video
     */
    razzi.productVideo = function () {
        var $gallery = $('.woocommerce-product-gallery');
        var $video = $gallery.find('.woocommerce-product-gallery__image.razzi-product-video');
        var $thumbnail = $gallery.find('.flex-control-thumbs');

        if ($video.length < 1) {
            return;
        }

        $thumbnail.on('click', 'li', function () {

            var $video = $gallery.find('.razzi-product-video');

            var $iframe = $video.find('iframe'),
                $wp_video = $video.find('video.wp-video-shortcode');

            if ($iframe.length > 0) {
                $iframe.attr('src', $iframe.attr('src'));
            }

            if ($wp_video.length > 0) {
                $wp_video[0].pause();
            }

            return false;

        });

        $video.find('.video-vimeo > iframe').attr('width', '100%').attr('height', 500);

        $thumbnail.find('li').on('click', '.razzi-i-video', function (e) {
            e.preventDefault();
            $(this).closest('li').find('img').trigger('click');
        });

    };

     /**
     * Init product video
     */
      razzi.productVideoPopup = function () {
        var $video_icon = $('.woocommerce-product-gallery').find('.razzi-product-video--icon');
        if ($video_icon.length < 1) {
            return;
        }

        var options = {
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 300,
            preloader: false,
            fixedContentPos: false,
            iframe: {
                markup: '<div class="mfp-iframe-scaler">' +
                        '<div class="mfp-close"></div>' +
                        '<iframe class="mfp-iframe" frameborder="0" allow="autoplay"></iframe>' +
                        '</div>',
                patterns: {
                    youtube: {
                        index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

                        id: 'v=', // String that splits URL in a two parts, second part should be %id%
                        src: 'https://www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe.
                    },
                    vimeo: {
                        index: 'vimeo.com/',
                        id: '/',
                        src: '//player.vimeo.com/video/%id%?autoplay=1'
                    }
                },

                srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
            }
        };

        $video_icon.magnificPopup( options);

    };

    razzi.updateFreeShippingBar = function() {
        $( document.body ).on( 'removed_from_cart', function( e, response ) {
            if( $('.single-product div.product').find('.razzi-free-shipping-bar').length && $(response['div.widget_shopping_cart_content']).length ) {
               if( $(response['div.widget_shopping_cart_content']).find('.razzi-free-shipping-bar').length  ) {
                    $('.single-product div.product').find('.razzi-free-shipping-bar').replaceWith($(response['div.widget_shopping_cart_content']).find('.razzi-free-shipping-bar'));
               } else {
                    $('.single-product div.product').find('.razzi-free-shipping-bar').hide();
               }
            }
        } );

        $( document.body ).on( 'added_to_cart', function( e, response ) {
            if( $('.single-product div.product').find('.razzi-free-shipping-bar').length && $(response['div.widget_shopping_cart_content']).length && $(response['div.widget_shopping_cart_content']).find('.razzi-free-shipping-bar').length ) {
                $('.single-product div.product').find('.razzi-free-shipping-bar').replaceWith($(response['div.widget_shopping_cart_content']).find('.razzi-free-shipping-bar'));
            }

        } );
    }

    /**
	 * Set the product background similar to product gallery images
	 */
	razzi.productBackgroundFromGallery = function( $product ) {
		if ( typeof BackgroundColorTheif == 'undefined' ) {
			return;
		}

		var $gallery = $product.find( '.woocommerce-product-gallery' ),
			$image = $gallery.find( '.wp-post-image' ),
			imageColor = new BackgroundColorTheif();

		// Change background base on main image.
		$image.one( 'load', function() {
			setTimeout( function() {
				changeProductBackground( $image.get( 0 ) );
			}, 100 );
		} ).each( function() {
			if ( this.complete ) {
				$( this ).trigger( 'load' );
			}
		} );


		// Support Jetpack images lazy loads.
		$gallery.on( 'jetpack-lazy-loaded-image', '.wp-post-image', function() {
			$( this ).one( 'load', function() {
				changeProductBackground( this );
			} );
		} );

		/**
		 * Change product backgound color
		 */
		function changeProductBackground( image ) {
			// Stop if this image is not loaded.
			if ( image.src === '' ) {
				return;
			}

			if ( image.classList.contains( 'jetpack-lazy-image' ) ) {
				if ( ! image.dataset['lazyLoaded'] ) {
					return;
				}
			}

			var rgb = imageColor.getBackGroundColor( image );
			$('body.product-has-background').css('--rz-product-background-color', 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ')' );
		}
	}

    /**
     * Document ready
     */
    $(function () {
        razzi.init();
    });

})(jQuery);