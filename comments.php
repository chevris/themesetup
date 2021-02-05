<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themesetup
 */

namespace Themesetup;

use function Themesetup\themesetup;

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

themesetup()->print_styles( 'themesetup-comments' );

?>
<div id="comments" class="comments-area">
	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) {
		?>

		<div class="comments-title-wrap">

			<h2 class="comments-title">
				<?php echo esc_html( apply_filters( 'themesetup_comment_section_title_have_comments', __( 'Join the Conversation', 'themesetup' ) ) ); ?>
			</h2><!-- .comments-title -->

			<?php
			$discussion = themesetup()->get_discussion_data();
			if ( $discussion->responses > 0 ) {
				$meta_label = apply_filters(
					'themesetup_number_comments_label',
					/* translators: %1(X comments)$s */
					sprintf( _n( '%d Comment', '%d Comments', $discussion->responses, 'themesetup' ), $discussion->responses )
				);

			} else {
				$meta_label = esc_html( apply_filters( 'themesetup_no_comments', __( 'No comments', 'themesetup' ) ) );
			}
			?>
			<div class="comments-meta">
				<p class="comments-meta-info">
					<?php echo wp_kses( themesetup()->get_svg( 'ui', 'comment', 24 ), themesetup()->sanitize_svgs() ); ?>
					<span><?php echo esc_html( $meta_label ); ?></span>
				</p>
			</div><!-- .comments-meta -->

		</div><!-- .comments-title-wrap -->

		<?php
		$comments_args = [
			'avatar_size' => 60,
		];
		themesetup()->the_comments( $comments_args );
		?>

		<?php
		if ( ! comments_open() ) {
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'themesetup' ); ?></p>
			<?php
		}
	}

	comment_form();
	?>
</div><!-- #comments -->
