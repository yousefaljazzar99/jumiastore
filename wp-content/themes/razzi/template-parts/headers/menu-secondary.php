<?php
/**
 * Template part for display secondary menu
 *
 * @package Razzi
 */
?>
<nav id="secondary-menu" class="main-navigation secondary-navigation">
	<?php
		if ( ! has_nav_menu( 'secondary' ) ) {
			return;
		}

		if ( class_exists( '\Razzi\Addons\Modules\Mega_Menu\Walker' ) ) {
			wp_nav_menu( apply_filters( 'razzi_navigation_secondary_content', array(
				'theme_location' => 'secondary',
				'container'      => null,
				'menu_class'     => 'menu nav-menu',
				'walker' 		=>  new \Razzi\Addons\Modules\Mega_Menu\Walker()
			) ) );

		} else {
			wp_nav_menu( apply_filters( 'razzi_navigation_secondary_content', array(
				'theme_location' => 'secondary',
				'container'      => null,
				'menu_class'     => 'menu nav-menu',
			) ) );
		}
	?>
</nav>
