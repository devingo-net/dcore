<?php

/**
 * blog posts template
 *
 * @category    Template
 * @version     1.0.0
 * @since       1.0.0
 */

if (!defined('ABSPATH')) {
    die('direct access not allowed!');
}

use DCore\Theme;

/**
 * @var array $posts
 * @var string $totalPages
 * @var string $currentPage
 * @var string $columns
 * @var string $columns_tablet
 * @var string $columns_mobile
 * @var array $columnsPadding
 * @var string $paginateType
 * @var string $paginateKey
 * @var int $midSize
 */

?>
<div class="grid-content-area uk-grid-small uk-child-width-1-<?= $columns ?>@l uk-child-width-1-<?= $columns_tablet ?>@s
uk-child-width-1-<?= $columns_mobile ?>
uk-flex-center
uk-text-center uk-grid-match"
     uk-grid>
    <?php foreach ($posts as $postItem) {
        echo '<div class="grid-item">';
        echo $postItem;
        echo '</div>';
    } ?>
</div>

<?php if ($paginateType === 'number') {
    Theme::getTemplatePart('globals/loop/pagination', [
        'paginateKey' => $paginateKey,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
        'midSize' => $midSize
    ]);
} else if ($paginateType === 'loadmore') { ?>
    <div class="widget-load-more">
        <button class="load-more-btn button button-primary" type="button"
                aria-label="<?= __('Load more', THEME_TEXTDOMAIN) ?>">
            <?= __('Load more ...', THEME_TEXTDOMAIN) ?>
        </button>
    </div>
<?php } else if ($paginateType === 'autoload') { ?>
    <div class="widget-auto-load uk-text-center">
        <span uk-spinner="ratio: 3"></span>
    </div>
<?php } ?>
