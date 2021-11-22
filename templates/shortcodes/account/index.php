<?php

/**
 *  Account widget template
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

use Elementor\Icons_Manager;

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

/**
 * @var string   $linkAttr
 * @var string   $iconActive
 * @var array    $icon
 * @var array    $links
 * @var string   $textGuest
 * @var string   $textUser
 * @var string   $textActive
 * @var string   $accountItemsAlign
 * @var \WP_User $currentUser
 */

?>
    <div class="user-account">
        <a <?= $linkAttr ?>>
			<?php if ( $iconActive === 'yes' ) { ?>
                <div class="account-icon">
					<?php Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
                </div>
			<?php } ?>
			<?php if ( $textActive === 'yes' ) { ?>
                <div class="account-text">
					<?php
					if ( is_user_logged_in() ) {
						echo '<b>' . $currentUser->display_name . '</b> <br>' . $textUser;

					} else {
						echo $textGuest;
					}
					?>

                </div>
			<?php } ?>
        </a>
    </div>
<?php if ( !empty($links) ) { ?>
    <div uk-dropdown="pos: bottom-<?= str_replace('to-', '', $accountItemsAlign) ?>">
        <ul class="uk-nav uk-dropdown-nav user-account-links">
			<?php foreach ( $links as $link ) { ?>
                <li><a <?= $link['linkAttr'] ?>>
						<?php Icons_Manager::render_icon($link['icon'], ['aria-hidden' => 'true']); ?>
						<?= $link['title'] ?></a></li>
			<?php } ?>
        </ul>
    </div>
<?php } ?>