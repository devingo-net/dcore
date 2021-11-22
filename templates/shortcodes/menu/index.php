<?php

/**
 *  Menu widget template
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

/**
 * @var int $menu
 */

?>
<div class="main-nav-menu">
	<?php
	if ( $menu !== false ) {
		wp_nav_menu([
			'menu'       => $menu,
			'walker'     => new \DCore\Walker\Menu(),
			'menu_class' => 'menu-inner'
		]);
	}
	?>
</div>