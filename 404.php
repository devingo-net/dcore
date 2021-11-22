<?php
/**
 * The template for displaying the 404 template in the Twenty Twenty theme.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>
<div class="uk-container">
    <div class="section-inner error404-content">

        <h1 class="entry-title"><?php _e( 'Page Not Found', THEME_TEXTDOMAIN ); ?></h1>

        <div class="intro-text"><p><?php _e( 'The page you were looking for could not be found. It might have been removed, renamed, or did not exist in the first place.', THEME_TEXTDOMAIN ); ?></p></div>

		<?php
		get_search_form(
			array(
				'label' => __( '404 not found', THEME_TEXTDOMAIN ),
			)
		);
		?>

    </div>
</div>
<?php
get_footer();
