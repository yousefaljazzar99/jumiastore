class RazziMotionParallaxHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		const options = {
			addBackgroundLayerTo: '',
			classes: {
				element: 'elementor-motion-parallax',
				container: 'elementor-motion-effects-container',
				layer: 'elementor-motion-effects-layer',
			}
		};

		return options;
	}

	addBackgroundLayer() {
		const settings = this.getSettings();

		this.elements.$motionParallaxContainer = jQuery( '<div>', { class: settings.classes.container } );
		this.elements.$motionParallaxLayer = jQuery( '<div>', { class: settings.classes.layer } );
		this.elements.$motionParallaxContainer.prepend( this.elements.$motionParallaxLayer );

		const $addBackgroundLayerTo = settings.addBackgroundLayerTo ? this.$element.find( settings.addBackgroundLayerTo ) : this.$element;
		$addBackgroundLayerTo.prepend( this.elements.$motionParallaxContainer );
	}

	removeBackgroundLayer() {
		if ( this.elements.$motionParallaxContainer ) {
			this.elements.$motionParallaxContainer.remove();
		}
	}

	activate() {
		this.addBackgroundLayer();
		this.$element.addClass( this.getSettings( 'classes.element' ) );

		this.elements.$motionParallaxLayer.jarallax({
			speed: 0.8
		});
	}

	deactivate() {
		this.$element.removeClass( this.getSettings( 'classes.element' ) );
		this.removeBackgroundLayer();
	}

	toggle() {
		if ( this.getElementSettings( 'background_motion_fx_motion_fx_scrolling' ) ) {
			this.activate();
		} else {
			this.deactivate();
		}
	}


	onInit() {
		super.onInit();

		this.toggle();
	}
}

jQuery( window ).on( 'elementor/frontend/init', () => {

	if ( typeof ElementorProFrontendConfig === 'undefined' ) {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/section', ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( RazziMotionParallaxHandler, { $element: $element } );
		});

		elementorFrontend.hooks.addAction( 'frontend/element_ready/column', ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( RazziMotionParallaxHandler, { $element: $element, addBackgroundLayerTo: ' > .elementor-element-populated' } );
		});
	}
} );
