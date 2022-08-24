(function ($) {
    'use strict';

	function fbtProduct () {
		if ( typeof razziFbt === 'undefined' ) {
			return false;
		}

		if (! $('body').hasClass('single-product')) {
			return;
		}

		var $fbtProducts = $('#razzi-product-fbt');

		if ($fbtProducts.length <= 0) {
			return;
		}

		var $priceAt = $fbtProducts.find('.razzi-fbt-total-price .woocommerce-Price-amount'),
			$button = $fbtProducts.find('.razzi-fbt-add-to-cart'),
			totalPrice = parseFloat($fbtProducts.find('#razzi-data_price').data('price')),
			currency = razziFbt.currency_symbol,
			thousand = razziFbt.thousand_sep,
			decimal = razziFbt.decimal_sep,
			price_decimals = razziFbt.price_decimals,
			currency_pos = razziFbt.currency_pos;

		$fbtProducts.find('.products-list').on('click', 'a', function (e) {
			e.preventDefault();
			if( $(this).closest('li').hasClass('product-current') ) {
				return false;
			}
			var id = $(this).data('id');
			$(this).closest('li').toggleClass('uncheck');
			var currentPrice = parseFloat($(this).closest('li').find('.s-price').data('price'));

			if ($(this).closest('li').hasClass('uncheck')) {
				$fbtProducts.find('#fbt-product-' + id).addClass('un-active');
				totalPrice -= currentPrice;

			} else {
				$fbtProducts.find('#fbt-product-' + id).removeClass('un-active');
				totalPrice += currentPrice;
			}

			var $product_ids = '0',
				$product_titles = '';
			$fbtProducts.find('.products-list li').each(function () {
				if (!$(this).hasClass('uncheck')) {
					$product_ids += ',' + $(this).find('a').data('id');
					if( $product_titles ) {
						$product_titles += ', ';
					}
					$product_titles += $(this).find('a').data('title');
				}
			});

			$button.attr('value', $product_ids);
			$fbtProducts.find('.rz_product_id').attr('data-title', $product_titles);

			$priceAt.html(formatNumber(totalPrice));
		});


		function formatNumber(number) {
			var n = number;
			if (parseInt(price_decimals) > 0) {
				number = number.toFixed(price_decimals) + '';
				var x = number.split('.');
				var x1 = x[0],
					x2 = x.length > 1 ? decimal + x[1] : '';
				var rgx = /(\d+)(\d{3})/;
				while (rgx.test(x1)) {
					x1 = x1.replace(rgx, '$1' + thousand + '$2');
				}

				n = x1 + x2
			}


			switch (currency_pos) {
				case 'left' :
					return currency + n;
					break;
				case 'right' :
					return n + currency;
					break;
				case 'left_space' :
					return currency + ' ' + n;
					break;
				case 'right_space' :
					return n + ' ' + currency;
					break;
			}
		}

	}

	// Add to cart ajax
    function fbtAddToCartAjax () {

		if (! $('body').hasClass('single-product')) {
			return;
		}

		var $fbtProducts = $('#razzi-product-fbt');

        if ($fbtProducts.length <= 0) {
            return;
        }

        $fbtProducts.on('click', '.razzi-fbt-add-to-cart.ajax_add_to_cart', function (e) {
            e.preventDefault();

            var $singleBtn = $(this);

			if ($singleBtn.data('requestRunning')) {
				return;
			}

			$singleBtn.data('requestRunning', true);
			$singleBtn.addClass('loading');

			var $cartForm = $singleBtn.closest('fbt-cart'),
				formData = $cartForm.serializeArray(),
				formAction = $cartForm.attr('action');

			if ($singleBtn.val() != '') {
				formData.push({name: $singleBtn.attr('name'), value: $singleBtn.val()});
			}

			$(document.body).trigger('adding_to_cart', [$singleBtn, formData]);
			$.ajax({
				url: formAction,
				method: 'post',
				data: formData,
				error: function (response) {
					window.location = formAction;
				},
                success: function (response) {
                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                            window.location = wc_add_to_cart_params.cart_url;
                            return;
                        }
                    }

					var $message = '',
					className = 'info';
					if ($(response).find('.woocommerce-message').length > 0) {
						$(document.body).trigger('wc_fragment_refresh');

						if( $('.single-product div.product').find('.razzi-free-shipping-bar').length && $(response).find('div.product .razzi-free-shipping-bar').length ) {
							$('.single-product div.product').find('.razzi-free-shipping-bar').replaceWith($(response).find('div.product .razzi-free-shipping-bar'));
						}

					} else {
						if (!$.fn.notify) {
							return;
						}

						var $checkIcon = '<span class="razzi-svg-icon message-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></span>',
							$closeIcon = '<span class="razzi-svg-icon svg-active"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 1L1 14M1 1L14 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>';

						if ($(response).find('.woocommerce-error').length > 0) {
							$message = $(response).find('.woocommerce-error').html();
							className = 'error';
							$checkIcon = '<span class="razzi-svg-icon message-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></span>';
						} else if ($(response).find('.woocommerce-info').length > 0) {
							$message = $(response).find('.woocommerce-info').html();
						}

						$.notify.addStyle('razzi', {
							html: '<div>' + $checkIcon + '<ul class="message-box">' + $message + '</ul>' + $closeIcon + '</div>'
						});

						$.notify('&nbsp', {
							autoHideDelay: 5000,
							className: className,
							style: 'razzi',
							showAnimation: 'fadeIn',
							hideAnimation: 'fadeOut'
						});
					}

					$singleBtn.removeClass('loading');
					$singleBtn.data('requestRunning', false);
                }
			});

        });

    };


    /**
     * Document ready
     */
    $(function () {
        fbtProduct();
		fbtAddToCartAjax();
    });

})(jQuery);