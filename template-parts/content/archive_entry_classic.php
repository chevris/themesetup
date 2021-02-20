<?php
/**
 * Template part for displaying classic entry in the archive loop.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

// @todo: function that retrieves archive loop data.
$context = [];

$article_classes = [ 'entry', 'entry-classic' ];
$article_classes = implode( ' ', $article_classes );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $article_classes ); ?>>

	<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
	<?php themesetup()->comments_count(); ?>

</article><!-- #post-<?php the_ID(); ?> -->
