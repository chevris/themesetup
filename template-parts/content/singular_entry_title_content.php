<?php
/**
 * Template part for displaying the singular entry title content template
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<h1 class="entry-title"><?php echo wp_kses_post( get_the_title() ); ?></h1>

<div class="entry-metas">
	<?php
	$author_args = [ 'has_prefix' => true, 'prefix' => 'written by', 'has_link' => true ];
	$date_args = [ 'has_icon' => true, 'has_prefix' => true, 'prefix' => '' ];
	$categories_args = [ 'has_icon' => true, 'has_prefix' => true, 'prefix' => '' ];
	$comments_args = [ 'has_icon' => true ];
	$rtime_args = [ 'has_icon' => true ];
	themesetup()->metas( [ 'author', 'date', 'categories', 'comments', 'rtime' ], $author_args, $date_args, $categories_args, $comments_args, $rtime_args );
	?>
</div>

<div class="entry-taxonomies">
	<?php themesetup()->categories(); ?>
</div>
