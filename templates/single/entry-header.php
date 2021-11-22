<?php
/**
 * Displays the post header
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}


if(is_page() && get_post_meta(get_the_ID(),'page-title',true) === "0"){
	return;
}
?>

<header class="entry-header">
    <div class="entry-header-inner section-inner">
		<?php
		the_title('<h1 class="entry-title">', '</h1>');
		?>
    </div>
</header>
