<?php
/**
 * Template part for displaying the archive content title template
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

themesetup()->print_styles( 'themesetup-archive-content-title' );
?>

<section class="archive-content-title">

	<?php
	// Author avatar.
	if ( is_author() ) {

		$author_obj    = get_queried_object();
		$author_avatar = '';

		if ( function_exists( 'coauthors_posts_links' ) ) {

			// Check whether it's a guest author post type.
			if ( 'guest-author' === get_post_type( $author_obj->{ 'ID' } ) ) {

				// If guest author, make sure the author actually has an avatar set.
				if ( get_post_thumbnail_id( $author_obj->{ 'ID' } ) ) {
					$author_avatar = coauthors_get_avatar( $author_obj, 120 );
				} else {
					// If there is no avatar, force it to return the current fallback image.
					$author_avatar = get_avatar( ' ' );
				}
			} else {
				$author_avatar = coauthors_get_avatar( $author_obj, 120 );
			}
		} else {

			$author_id     = get_query_var( 'author' );
			$author_avatar = get_avatar( $author_id, 120 );

		}

		if ( $author_avatar ) {
			echo wp_kses( $author_avatar, themesetup()->sanitize_avatars() );
		}
	}

	// Archive title.
	if ( is_home() && ! is_front_page() ) {
		?>
		<h1 class="content-title latest-post-title">
			<?php single_post_title(); ?>
		</h1>
		<?php
	} elseif ( is_search() ) {
		?>
		<h1 class="content-title search-title">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Search Results for: %s', 'themesetup' ),
				'<span>' . get_search_query() . '</span>'
			);
			?>
		</h1>
		<?php
	} elseif ( is_archive() || is_home() ) {
		the_archive_title( '<h1 class="content-title archive-title">', '</h1>' );
	}

	// Taxonomy description.
	if ( is_tax() || is_category() || is_tag() ) {
		the_archive_description( '<div class="taxonomy-description">', '</div>' );
	}

	// Author bio and social info.
	if ( is_author() ) {

		the_archive_description( '<div class="author-bio">', '</div>' );

		?>
		<div class="author-social">
			<?php
			$author_email = get_the_author_meta( 'user_email', get_query_var( 'author' ) );
			if ( '' !== $author_email ) {
				?>
				<a class="author-email" href="<?php echo 'mailto:' . esc_attr( $author_email ); ?>">
					<?php echo wp_kses( themesetup()->get_svg( 'social', 'mail', 18 ), themesetup()->sanitize_svgs() ); ?>
					<?php echo esc_html( $author_email ); ?>
				</a>
				<?php
			}

			$label = get_theme_mod( 'themesetup_archive_author_social_links_label', __( 'Follow on ', 'themesetup' ) );
			themesetup()->yoast_author_social_links( get_the_author_meta( 'ID' ), 20, esc_html( $label ) );
			?>
		</div>

		<?php
	}
	?>

</section>
