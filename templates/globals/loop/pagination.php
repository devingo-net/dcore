<?php

/**
 *  pagination template
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$paginateArgs = [
    'type' => 'list',
    'prev_text' => '<i class="fas fa-angle-left"></i>',
    'next_text' => '<i class="fas fa-angle-right"></i>'
];
if (isset($midSize)) {
    $paginateArgs['mid_size'] = $midSize;
}
if (isset($paginateKey)) {
    $paginateArgs['format'] = '?' . $paginateKey . '=%#%';
}
if (isset($totalPages)) {
    $paginateArgs['total'] = $totalPages;
}
if (isset($totalPages) && $totalPages <= 1) {
    return;
}
if (isset($currentPage)) {
    $paginateArgs['current'] = $currentPage;
}
if (isset($base)) {
    $paginateArgs['base'] = $base;
}
?>

<div class="widget-pagination">
    <?php echo paginate_links($paginateArgs); ?>
</div>