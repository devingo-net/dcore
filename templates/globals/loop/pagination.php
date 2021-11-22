<?php

/**
 *  pagination template
 *
 * @category   Template
 * @version    1.0.0
 * @since      1.0.0
 */

if ( !defined('ABSPATH') ) {
	exit;
}

$paginateArgs = [
	'type'      => 'list',
	'show_all'  => true,
	'prev_text' => '<i class="far fa-angle-left"></i>',
	'next_text' => '<i class="far fa-angle-right"></i>',
];
if ( isset($paginateKey) ) {
	$paginateArgs['format'] = '?' . $paginateKey . '=%#%';
}
if ( isset($totalPages) ) {
	$paginateArgs['total'] = $totalPages;
}
if ( isset($totalPages) && $totalPages <= 1 ) {
	return;
}
if ( isset($currentPage) ) {
	$paginateArgs['current'] = $currentPage;
}
if ( isset($base) ) {
	$paginateArgs['base'] = $base;
}
?>

<div class="widget-pagination">
	<?= paginate_links($paginateArgs) ?>
</div>