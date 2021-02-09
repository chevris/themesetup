<?php
/**
 * Themesetup\Comments\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Comments;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;
use function Themesetup\themesetup;

/**
 * Class for managing comments UI.
 *
 * Exposes template tags:
 * * `themesetup()->get_discussion_data()`
 * * `themesetup()->the_comments( array $args = [] )`
 *
 * @link https://wordpress.org/plugins/amp/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'comments';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_comment_reply_script' ] );
		add_filter( 'themesetup_css_files', [ $this, 'filter_css_files_comments' ] );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `themesetup()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return [
			'get_discussion_data' => [ $this, 'get_discussion_data' ],
			'the_comments'        => [ $this, 'the_comments' ],
		];
	}

	/**
	 * Enqueues the WordPress core 'comment-reply' script as necessary.
	 */
	public function action_enqueue_comment_reply_script() {

		// If the AMP plugin is active, return early.
		if ( themesetup()->is_amp() ) {
			return;
		}

		// Enqueue comment script on singular post/page views only.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Filters default CSS files.
	 *
	 * @param array $css_files Associative array of CSS files, as $handle => $data pairs.
	 * @return array Associative array of $handle => $data pairs.
	 */
	public function filter_css_files_comments( $css_files ): array {

		// CSS files to add.
		$comments_css_file = [
			'themesetup-comments' => [
				'file'             => 'in-body/comments.css',
				'preload_callback' => function() {
					return themesetup()->has_comments();
				},
			],
		];

		// Enqueue and preload css files only if they exist.
		$css_files = array_merge( $comments_css_file, $css_files );

		return $css_files;

	}

	/**
	 * Returns information about the current post's discussion, with cache support.
	 *
	 * @return object $discussion Cached object of the discussion information for post ID
	 */
	public function get_discussion_data() {

		static $discussion, $post_id;

		$current_post_id = get_the_ID();
		if ( $current_post_id === $post_id ) {
			return $discussion; /* If we have discussion information for post ID, return cached object */
		} else {
			$post_id = $current_post_id;
		}

		$comments = get_comments(
			[
				'post_id' => $current_post_id,
				'orderby' => 'comment_date_gmt',
				'order'   => get_option( 'comment_order', 'asc' ), /* Respect comment order from Settings » Discussion. */
				'status'  => 'approve',
				'number'  => 20, /* Only retrieve the last 20 comments, as the end goal is just 6 unique authors */
			]
		);

		$authors = [];
		foreach ( $comments as $comment ) {
			$authors[] = ( (int) $comment->user_id > 0 ) ? (int) $comment->user_id : $comment->comment_author_email;
		}

		$authors    = array_unique( $authors );
		$discussion = (object) [
			'authors'   => array_slice( $authors, 0, 6 ),           /* Six unique authors commenting on the post. */
			'responses' => get_comments_number( $current_post_id ), /* Number of responses. */
		];

		return $discussion;
	}

	/**
	 * Displays the list of comments for the current post.
	 *
	 * Internally this method calls `wp_list_comments()`. However, in addition to that it will render the wrapping
	 * element for the list, so that must not be added manually. The method will also take care of generating the
	 * necessary markup if amp-live-list should be used for comments.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_list_comments()` documentation for a list of supported
	 *                    arguments.
	 */
	public function the_comments( array $args = [] ) {
		$args = array_merge(
			$args,
			[
				'style'      => 'ol',
				'short_ping' => true,
			]
		);

		$amp_live_list = themesetup()->using_amp_live_list_comments();

		if ( $amp_live_list ) {
			$comment_order     = get_option( 'comment_order' );
			$comments_per_page = get_option( 'page_comments' ) ? (int) get_option( 'comments_per_page' ) : 10000;
			$poll_inverval     = MINUTE_IN_SECONDS * 1000;

			?>
			<amp-live-list
				id="amp-live-comments-list-<?php the_ID(); ?>"
				<?php echo ( 'asc' === $comment_order ) ? ' sort="ascending" ' : ''; ?>
				data-poll-interval="<?php echo esc_attr( $poll_inverval ); ?>"
				data-max-items-per-page="<?php echo esc_attr( $comments_per_page ); ?>"
			>
			<?php

			add_filter( 'navigation_markup_template', [ $this, 'filter_add_amp_live_list_pagination_attribute' ] );
		}

		?>
		<ol class="comment-list"<?php echo $amp_live_list ? ' items' : ''; ?>>
			<?php wp_list_comments( $args ); ?>
		</ol><!-- .comment-list -->

		<?php
		if ( have_comments() ) {
			$prev_icon     = themesetup()->get_svg( 'ui', 'chevron_left', 22 );
			$next_icon     = themesetup()->get_svg( 'ui', 'chevron_right', 22 );
			$comments_text = apply_filters( 'themesetup_comments_name_plural', __( 'Comments', 'themesetup' ) );
			the_comments_navigation(
				[
					'prev_text' => sprintf( '%s <span class="nav-prev-text"><span class="primary-text">%s</span> <span class="secondary-text">%s</span></span>', $prev_icon, __( 'Previous', 'themesetup' ), $comments_text ),
					'next_text' => sprintf( '<span class="nav-next-text"><span class="primary-text">%s</span> <span class="secondary-text">%s</span></span> %s', __( 'Next', 'themesetup' ), $comments_text, $next_icon ),
				]
			);
		}

		if ( $amp_live_list ) {
			remove_filter( 'navigation_markup_template', [ $this, 'filter_add_amp_live_list_pagination_attribute' ] );

			?>
				<div update>
					<button class="button" on="tap:amp-live-comments-list-<?php the_ID(); ?>.update"><?php esc_html_e( 'New comment(s)', 'themesetup' ); ?></button>
				</div>
			</amp-live-list>
			<?php
		}
	}

	/**
	 * Adds a pagination reference point attribute for amp-live-list when theme supports AMP.
	 *
	 * This is used by the navigation_markup_template filter in the comments template.
	 *
	 * @link https://www.ampproject.org/docs/reference/components/amp-live-list#pagination
	 *
	 * @param string $markup Navigation markup.
	 * @return string Filtered markup.
	 */
	public function filter_add_amp_live_list_pagination_attribute( string $markup ): string {
		return preg_replace( '/(\s*<[a-z0-9_-]+)/i', '$1 pagination ', $markup, 1 );
	}
}
