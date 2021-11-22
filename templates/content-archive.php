<?php

/**
 * Archive page content
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	die('direct access not allowed!');
}

use DCore\Theme;

$archiveColumns = getThemeOptions('blog-archive-columns', 'right-sidebar');
if ( is_rtl() ) {
	if ( $archiveColumns === 'right-sidebar' ) {
		$archiveColumns = 'left-sidebar';
	} else if ( $archiveColumns === 'left-sidebar' ) {
		$archiveColumns = 'right-sidebar';
	}
}

?>

<div class="uk-container blog-archive-container">
    <div class="uk-flex uk-flex-wrap uk-flex-wrap-around <?= $archiveColumns === 'left-sidebar' ? 'uk-flex-row-reverse' : '' ?>">
        <div class="uk-width-expand archive-posts-column">
			<?php

			$archiveTitle    = '';
			$archiveSubTitle = '';

			if ( is_search() ) {
				global $wp_query;

				$archiveTitle = sprintf('%1$s %2$s', '<b class="action-type">' . __('Search:', THEME_TEXTDOMAIN) . '</b>', '&ldquo;' . get_search_query() . '&rdquo;');

				if ( $wp_query->found_posts ) {
					$archiveSubTitle = sprintf(/* translators: %s: Number of search results. */ _n('We found %s result for your search.', 'We found %s results for your search.', $wp_query->found_posts, THEME_TEXTDOMAIN), number_format_i18n($wp_query->found_posts));
				} else {
					$archiveSubTitle = __('We could not find any results for your search. You can give it another try through the search form below.', THEME_TEXTDOMAIN);
				}
			} elseif ( is_archive() && !have_posts() ) {
				$archiveTitle = __('Nothing Found', THEME_TEXTDOMAIN);
			} elseif ( !is_home() ) {
				$archiveTitle    = get_the_archive_title();
				$archiveSubTitle = get_the_archive_description();
			}

			if ( $archiveTitle || $archiveSubTitle ) {
				?>

                <header class="archive-header">

                    <div class="archive-header-inner">

						<?php if ( $archiveTitle ) { ?>
                            <h1 class="archive-title"><?php echo wp_kses_post($archiveTitle); ?></h1>
						<?php } ?>

						<?php if ( $archiveSubTitle ) { ?>
                            <div class="archive-subtitle"><?php echo wp_kses_post(wpautop($archiveSubTitle)); ?></div>
						<?php } ?>

                    </div>

                </header>

				<?php
			}

			if ( have_posts() ) {
				$cardStyle = getThemeOptions('blog-card-style', false);

				if ( $cardStyle !== false ) {
					while ( have_posts() ) {
						the_post();
						Theme::getTemplatePart(THEME_TEMPLATES_DIR . DSP . 'globals' . DSP . 'cards' . DSP . $cardStyle, [], true);
					}
				} else {
					_e('Blog cards style not set!', THEME_TEXTDOMAIN);
				}
				Theme::getTemplatePart('globals/loop/pagination');
			} elseif ( is_search() ) { ?>

                <div class="no-search-results-form">

					<?php
					get_search_form([
						'label' => __('search again', THEME_TEXTDOMAIN),
					]);
					?>

                </div>

			<?php } ?>
        </div>

		<?php if ( $archiveColumns !== 'full-width' ) { ?>
            <div class="sidebar-column">
				<?php
				get_sidebar('blog')
				?>
            </div>
		<?php } ?>
    </div>
</div>