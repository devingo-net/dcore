<?php

/**
 *  Tabbed Menu widget shortcode
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

use Elementor\Icons_Manager;

/**
 * @var string $widgetID
 * @var array  $items
 */

?>
<div class="tabbed-menu">
    <div class="uk-flex">
        <div class="uk-width-1-4">
            <div>
                <ul class="menu-tabs uk-tab-left" uk-tab="connect: #tabbed-menu-content-<?= $widgetID ?>;">
					<?php
					if ( !empty($items) ) {
						foreach ( $items as $item ) {
							?>
                            <li <?= isset($item['first']) ? 'class="uk-active"' : '' ?>>
                                <a <?= $item['linkAttr'] ?>>
                                    <?php Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                    <?= $item['title'] ?>
                                </a>
                            </li>
							<?php
						}
					}
					?>
                </ul>
            </div>
        </div>
        <div class="uk-width-3-4">
            <ul class="uk-switcher" id="tabbed-menu-content-<?= $widgetID ?>">
				<?php
				if ( !empty($items) ) {
					foreach ( $items as $item ) {
						echo '<li class="tab-content-menus">';
						if ( $item['menu'] !== false ) {
							wp_nav_menu([
								'container'  => false,
								'menu'       => $item['menu']
							]);
						}
						echo '</li>';
					}
				}
				?>
            </ul>
        </div>
    </div>
</div>