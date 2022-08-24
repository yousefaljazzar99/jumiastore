jQuery(document).ready(function ($) {
	"use strict";

	$( '#custom_badges_bg' ).not( '[id*="__i__"]' ).wpColorPicker({
		change: function(e, ui) {
			$(e.target).val(ui.color.toString());
			$(e.target).trigger('change');
		},
		clear: function(e, ui) {
			$(e.target).trigger('change');
		}
	});

	$( '#custom_badges_color' ).not( '[id*="__i__"]' ).wpColorPicker({
		change: function(e, ui) {
			$(e.target).val(ui.color.toString());
			$(e.target).trigger('change');
		},
		clear: function(e, ui) {
			$(e.target).trigger('change');
		}
	});

	$( '#product_background_color' ).not( '[id*="__i__"]' ).wpColorPicker({
		change: function(e, ui) {
			$(e.target).val(ui.color.toString());
			$(e.target).trigger('change');
		},
		clear: function(e, ui) {
			$(e.target).trigger('change');
		}
	});

	var $save_attributes = 0;
	$( '#woocommerce-product-data' ).on( 'click', '.save_attributes', function() {
		$save_attributes = 1;
	} );

	$( '#woocommerce-product-data' ).on( 'click', '.advanced_options', function() {
		if( $save_attributes == 0 ) {
			return false;
		}

		$( '#razzi-product-attributes' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		var postID = $('#post_ID').val();
		$.ajax({
			url     : ajaxurl,
			dataType: 'json',
			method  : 'post',
			data    : {
				action : 'razzi_wc_product_attributes',
				post_id: postID
			},
			success : function (response) {
				$('#razzi-product-attributes').html(response.data);
				$('#razzi-product-attributes').unblock();
				$save_attributes = 0;
			}
		});

	} );

});