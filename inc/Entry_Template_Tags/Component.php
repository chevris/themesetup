<?php
/**
 * Themesetup\Entry_Template_Tags\Component class
 *
 * @package themesetup
 */

namespace Themesetup\Entry_Template_Tags;

use Themesetup\Component_Interface;
use Themesetup\Templating_Component_Interface;
use \WPSEO_Primary_Term;
use function Themesetup\themesetup;

/**
 * Class for managing post template tags.
 *
 * Exposes template tags:
 * * `themesetup()->post_thumbnail()`
 * * `themesetup()->get_posted_by()`
 * * `themesetup()->posted_by()`
 * * `themesetup()->get_posted_on()`
 * * `themesetup()->posted_on()`
 * * `themesetup()->get_categories()`
 * * `themesetup()->categories()`
 * * `themesetup()->get_comments_count()`
 * * `themesetup()->comments_count()`
 * * `themesetup()->get_post_read_time()`
 * * `themesetup()->post_read_time()`
 * * `themesetup()->metas()`
 * * `themesetup()->trim_down_text()`
 * * `themesetup()->the_summary()`
 * * `themesetup()->edit_post()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'entry_template_tags';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'get_the_date', [ $this, 'filter_date_to_allow_time_ago_format' ], 10, 3 );
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
			'post_thumbnail' => [ $this, 'post_thumbnail' ],
			'get_posted_by' => [ $this, 'get_posted_by' ],
			'posted_by' => [ $this, 'posted_by' ],
			'get_posted_on' => [ $this, 'get_posted_on' ],
			'posted_on' => [ $this, 'posted_on' ],
			'get_categories' => [ $this, 'get_categories' ],
			'categories' => [ $this, 'categories' ],
			'get_comments_count' => [ $this, 'get_comments_count' ],
			'comments_count' => [ $this, 'comments_count' ],
			'get_post_read_time' => [ $this, 'get_post_read_time' ],
			'post_read_time' => [ $this, 'post_read_time' ],
			'metas' => [ $this, 'metas' ],
			'trim_down_text' => [ $this, 'trim_down_text' ],
			'the_summary' => [ $this, 'the_summary' ],
			'edit_post' => [ $this, 'edit_post' ],
		];
	}

	/**
	 * Filters the date to allow 'time ago' format if enabled in the Customizer.
	 *
	 * @param string      $the_date The formatted date.
	 * @param string      $format PHP date format.
	 * @param int|WP_Post $post The post object or ID.
	 *
	 * @return string The formatted date.
	 */
	public function filter_date_to_allow_time_ago_format( $the_date, $format, $post ) {

		// Whether the 'time ago' format is enabled.
		$time_ago_enabled = get_theme_mod( 'themesetup_date_format_time_ago_activate', false );

		// Filters only if $time_ago_enabled is enabled and not using a machine-readable format (for datetime).
		if ( true === $time_ago_enabled && 'Y-m-d\TH:i:sP' !== $format ) {

			$current_time     = current_time( 'timestamp' ); // Retrieve the time.
			$post_time        = strtotime( $post->post_date ); // parse the date string into a Unix timestamp.
			$limit            = get_theme_mod( 'themesetup_date_format_time_ago_days_number', 14 ); // Limit in days.
			$limit_in_seconds = $limit * 86400; // Transform from days to seconds.
			$time_elapsed     = $current_time - $post_time; // Time since publication.

			if ( $limit_in_seconds >= $time_elapsed ) {
				$the_date = sprintf(
					/* translators: %s: Time ago date format */
					esc_html__( '%s ago', 'themesetup' ),
					human_time_diff( $post_time, $current_time ) // The difference between two timestamps is returned in a human readable format.
				);
			}
		}

		return $the_date;

	}

	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in a figure tag and in an anchor element on non singular pages.
	 *
	 * @param string|array $size Optional. Image size to use. Accepts any valid image size, or an array
	 *                                     of width and height values in pixels (in that order).
	 *                                     Default 'post-thumbnail' (1568 x 0 infinite height).
	 * @param string $layout Optional. Additionnal class for img container. Default empty.
	 * @param string $class Optional. Additionnal class for img container. Default empty.
	 */
	public function post_thumbnail( $size = 'themesetup-featured-image', $layout = '', $class = '' ) {

		// Audio or video attachments can have featured images, so they need to be specifically checked.
		$support_slug = get_post_type();
		if ( 'attachment' === $support_slug ) {
			if ( wp_attachment_is( 'audio' ) ) {
				$support_slug .= ':audio';
			} elseif ( wp_attachment_is( 'video' ) ) {
				$support_slug .= ':video';
			}
		}

		if ( post_password_required() || ! post_type_supports( $support_slug, 'thumbnail' ) || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) {
			?>
			<figure class="post-thumbnail<?php echo esc_attr( $class ? ' ' . $class : '' ); ?>">

				<?php
				// If using cover, add the object-fit attribute for AMP.
				if ( 'cover' === $layout ) {
					the_post_thumbnail(
						$size,
						[
							'object-fit' => 'cover',
							'alt' => the_title_attribute(
								[
									'echo' => false,
								]
							),
						]
					);

				} else {

					if ( 'responsive' === $layout ) {
						the_post_thumbnail(
							$size,
							[
								'layout' => 'responsive',
								'alt' => the_title_attribute(
									[
										'echo' => false,
									]
								),
							]
						);
					} else {
						the_post_thumbnail(
							$size,
							[
								'alt'   => the_title_attribute(
									[
										'echo' => false,
									]
								),
							]
						);
					}

					$caption = get_the_excerpt( get_post_thumbnail_id() );
					// Check the existance of the caption separately, so filters -- like ones that add ads -- don't interfere.
					$caption_exists = get_post( get_post_thumbnail_id() )->post_excerpt;

					if ( $caption_exists ) {
						?>
						<figcaption><?php echo wp_kses_post( $caption ); ?></figcaption>
						<?php
					}
				}

				?>

			</figure><!-- .post-thumbnail -->
			<?php
		} else {
			?>
			<figure class="post-thumbnail<?php echo esc_attr( $class ? ' ' . $class : '' ); ?>">
				<a class="post-thumbnail-link" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php
					the_post_thumbnail(
						$size,
						[
							'alt' => the_title_attribute(
								[
									'echo' => false,
								]
							),
						]
					);
					?>
				</a><!-- .post-thumbnail-link -->
			</figure><!-- .post-thumbnail -->
			<?php
		}

	}

	/**
	 * Get meta information about theme author and return HTML.
	 *
	 * @param bool   $has_avatar Whether to show author avatar.
	 * @param int    $avatar_size Avatar size.
	 * @param bool   $has_prefix Whether to show $prefix before author name.
	 * @param string $prefix What should appear before author names. Has no effects if $has_prefix is false.
	 * @param bool   $has_link Whether author avatar and name are wrapped in a link.
	 *
	 * @return string The author information HTML
	 */
	public function get_posted_by( $has_avatar = false, int $avatar_size = 25, $has_prefix = true, $prefix = '', $has_link = true ) {

		// What should appear before author names. Defaults to 'by' if empty.
		if ( '' === $prefix ) {
			$prefix = __( 'by', 'themesetup' );
		}

		$avatar_size = absint( $avatar_size );

		// Co-authors Plus plugin is active.
		if ( function_exists( 'coauthors_posts_links' ) && ! empty( get_coauthors() ) ) {

			$authors        = get_coauthors();
			$author_count   = count( $authors );
			$i              = 1;
			$avatars_string = '';
			$authors_string = '';

			foreach ( $authors as $author ) {

				if ( $has_avatar ) {

					// Check whether it's a guest author post type.
					if ( 'guest-author' === get_post_type( $author->ID ) ) {

						// If guest author, make sure the author actually has an avatar set.
						if ( get_post_thumbnail_id( $author->ID ) ) {
							// If there is an avatar, use it.
							$author_avatar = coauthors_get_avatar( $author, ( 2 * $avatar_size ) );
						} else {
							// Otherwise, force it to return the current fallback image.
							$author_avatar = get_avatar( ' ' );
						}
					} else {
						$author_avatar = coauthors_get_avatar( $author, ( 2 * $avatar_size ) );
					}

					if ( $has_link ) {
						$avatars_string .= sprintf(
							'<span class="author-avatar"><a class="author-avatar-link" href="%1$s">%2$s</a></span>',
							esc_url( get_author_posts_url( $author->ID, $author->user_nicename ) ),
							wp_kses( $author_avatar, themesetup()->sanitize_avatars() )
						);
					} else {
						$avatars_string .= sprintf( '<span class="author-avatar">%1$s</span>', wp_kses( $author_avatar, themesetup()->sanitize_avatars() ) );
					}
				}

				$i++;

				// Authors sep.
				if ( $author_count === $i ) {
					// Separates last two authors, needs a space on either side.
					$sep = esc_html__( ' and ', 'themesetup' );
				} elseif ( $author_count > $i ) {
					// separates all but the last two authors, needs a space at the end.
					$sep = esc_html( ', ' );
				} else {
					$sep = '';
				};

				if ( $has_link ) {
					$authors_string .= sprintf(
						'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>%3$s',
						esc_url( get_author_posts_url( $author->ID, $author->user_nicename ) ),
						esc_html( $author->display_name ),
						esc_html( $sep )
					);
				} else {
					$authors_string .= sprintf(
						'<span class="author vcard"><span class="fn n">%1$s</span></span>%2$s',
						esc_html( $author->display_name ),
						esc_html( $sep )
					);
				}
			}

			$avatars_css = '
				<style>
					.author-avatar {
						display: inline-block;
						width: ' . $avatar_size . 'px;
						height: ' . $avatar_size . 'px;
					}
				</style>';

			$output = sprintf(
				'%1$s%2$s<span class="byline"><span>%3$s</span>%4$s</span>',
				$has_avatar ? $avatars_css : '',
				$has_avatar ? $avatars_string : '',
				$has_prefix ? esc_html( $prefix ) : '',
				$authors_string
			);

			// No Co-authors Plus plugin.
		} else {

			$avatar_string = '';
			$author_string = '';

			if ( $has_avatar ) {

				if ( $has_link ) {
					$avatar_string = sprintf(
						'<span class="author-avatar"><a class="author-avatar-link" href="%1$s">%2$s</a></span>',
						esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
						get_avatar( get_the_author_meta( 'ID' ), ( 2 * $avatar_size ) )
					);
				} else {
					$avatar_string = sprintf( '<span class="author-avatar">%1$s</span>', get_avatar( get_the_author_meta( 'ID' ), ( 2 * $avatar_size ) ) );
				}
			}

			if ( $has_link ) {
				$author_string = sprintf(
					'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_html( get_the_author() )
				);
			} else {
				$author_string = sprintf(
					'<span class="author vcard"><span class="fn n">%1$s</span></span>',
					esc_html( get_the_author() )
				);
			}

			$avatar_css = '
				<style>
					.author-avatar {
						display: inline-block;
						width: ' . $avatar_size . 'px;
						height: ' . $avatar_size . 'px;
					}
				</style>';

			$output = sprintf(
				'%1$s%2$s<span class="byline"><span>%3$s</span> %4$s</span>',
				$has_avatar ? $avatar_css : '',
				$has_avatar ? $avatar_string : '',
				$has_prefix ? esc_html( $prefix ) : '',
				$author_string
			);

		}

		return $output;
	}

	/**
	 * Prints HTML with meta information about theme author.
	 *
	 * @param bool   $has_avatar Whether to show author avatar.
	 * @param int    $avatar_size Avatar size.
	 * @param bool   $has_prefix Whether to show $prefix before author name.
	 * @param string $prefix What should appear before author names (defaults to 'by'). Has no effects if $show_prefix is false.
	 * @param bool   $has_link Whether author avatar and name are wrapped in a link.
	 */
	public function posted_by( $has_avatar = false, int $avatar_size = 25, $has_prefix = true, $prefix = '', $has_link = true ) {

		$author = themesetup()->get_posted_by( $has_avatar, $avatar_size, $has_prefix, $prefix, $has_link );

		echo $author; // phpcs:ignore WordPress.Security.EscapeOutput

	}

	/**
	 * Get meta information for the current post-date/time.
	 *
	 * @param bool $has_icon Whether to show icon before date.
	 * @param bool $has_prefix Whether to show $prefix before date.
	 * @param string $prefix What should appear before date. Has no effects if $has_prefix is false.
	 */
	public function get_posted_on( $has_icon = false, $has_prefix = false, $prefix = '' ) {

		// What should appear before date. Defaults to 'Posted on' if empty.
		if ( '' === $prefix ) {
			$prefix = __( 'Posted on', 'themesetup' );
		}

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$span = '';

		$span = sprintf(
			'%1$s<span class="posted-on">%2$s%3$s</span>',
			$has_icon ? wp_kses( themesetup()->get_svg( 'ui', 'hours', 20 ), themesetup()->sanitize_svgs() ) : '',
			$has_prefix ? '<span>' . esc_html( $prefix ) . '</span>' : '',
			wp_kses(
				$time_string,
				[
					'time' => [
						'class'    => [],
						'datetime' => [],
					],
				]
			)
		);

		return $span;
	}

	/**
	 * Prints HTML with meta information for the current post-date/time.
	 *
	 * @param bool $has_icon Whether to show icon before date.
	 * @param bool $has_prefix Whether to show $prefix before date.
	 * @param string $prefix What should appear before date. Has no effects if $has_prefix is false.
	 */
	public function posted_on( $has_icon = false, $has_prefix = false, $prefix = '' ) {

		$date = themesetup()->get_posted_on( $has_icon, $has_prefix, $prefix );

		echo $date; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Get the current post's categories.
	 *
	 * @param bool $has_icon Whether to show icon before categories.
	 * @param bool $has_prefix Whether to show $prefix before categories.
	 * @param string $prefix What should appear before categoris. Has no effects if $has_prefix is false.
	 */
	public function get_categories( $has_icon = false, $has_prefix = false, $prefix = '' ) {

		// What should appear before categories. Defaults to 'Posted in' if empty.
		if ( '' === $prefix ) {
			$prefix = __( 'Posted in', 'themesetup' );
		}

		$categories_list = '';

		// Display Yoast primary category if Yoast plugin active and the customizer option is enabled (default: true).
		if ( class_exists( WPSEO_Primary_Term::class ) && true === themesetup()->get_setting( 'themesetup_yoast_primary_category_display' ) ) {

			if ( is_single() && true === themesetup()->get_setting( 'themesetup_yoast_primary_category_not_single' ) ) {
				$categories_list = '';
			} else {

				$primary_term     = new WPSEO_Primary_Term( 'category', get_the_ID() );
				$prim_category_id = $primary_term->get_primary_term();

				if ( $prim_category_id ) {
					$category_obj = get_term( $prim_category_id );
					if ( $category_obj ) {
						$categories_list = '<a href="' . esc_url( get_category_link( $category_obj->term_id ) ) . '" rel="category tag">' . $category_obj->name . '</a>';
					}
				}
			}
		}

		$span = '';

		if ( ! $categories_list ) {
			/* translators: used between list items; followed by a space. */
			$span = get_the_category_list( '<span class="sep">' . esc_html__( ',', 'themesetup' ) . '&nbsp;</span>' );
		} else {
			$span = sprintf(
				'%1$s<span class="cat-links">%2$s%3$s</span>',
				$has_icon ? wp_kses( themesetup()->get_svg( 'ui', 'folder', 20 ), themesetup()->sanitize_svgs() ) : '',
				$has_prefix ? '<span>' . esc_html( $prefix ) . '</span>' : '',
				$categories_list
			);
		}

		return $span;
	}

	/**
	 * Prints HTML with meta information for the current post's categories.
	 *
	 * @param bool $has_icon Whether to show icon before categories.
	 * @param bool $has_prefix Whether to show $prefix before categoris.
	 * @param string $prefix What should appear before categoris. Has no effects if $has_prefix is false.
	 */
	public function categories( $has_icon = false, $has_prefix = false, $prefix = '' ) {

		$categories = themesetup()->get_categories( $has_icon, $has_prefix, $prefix );

		echo $categories; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Get the comments count for the current post.
	 *
	 * @param bool $has_icon Whether to show icon before comments count.
	 */
	public function get_comments_count( $has_icon = false ) {

		$count_string = '';

		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {

			if ( $has_icon ) {
				$count_string .= wp_kses( themesetup()->get_svg( 'ui', 'comment', 20 ), themesetup()->sanitize_svgs() );
			}

			$count_string .= '<span class="comments-count">';

			ob_start();
			comments_popup_link(
				sprintf(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'themesetup' ),
					get_the_title()
				),
				false,
				false,
				'comments-count-link'
			);
			$count_string .= ob_get_contents();
			ob_end_clean();

			$count_string .= '</span>';
		}

		return $count_string;

	}

	/**
	 * Print HTML for the comments count for the current post.
	 *
	 * @param bool $has_icon Whether to show icon before comments count.
	 */
	public function comments_count( $has_icon = false ) {

		$count = themesetup()->get_comments_count( $has_icon );

		echo $count; // phpcs:ignore WordPress.Security.EscapeOutput

	}

	/**
	 * Get the reading time for the current post.
	 *
	 * @param boolean $has_icon Whether the icon should be displayed.
	 */
	public function get_post_read_time( $has_icon = false ) {

		$rtime_string = '';

		$rtime = $this->reading_time( get_post_field( 'post_content', get_the_ID() ) );

		if ( ! empty( $rtime ) ) {
			$rtime_string = sprintf(
				'%1$s<span class="rtime">%2$s%3$s%4$s</span>',
				$has_icon ? wp_kses( themesetup()->get_svg( 'ui', 'timer', 20 ), themesetup()->sanitize_svgs() ) : '',
				esc_html( themesetup()->get_setting( 'themesetup_reading_time_text_before' ) ),
				esc_html( $rtime ),
				esc_html( themesetup()->get_setting( 'themesetup_reading_time_text_after' ) )
			);
		}

		return $rtime_string;

	}

	/**
	 * Print HTML for the reading time for the current post.
	 *
	 * @param boolean $has_icon Whether the icon should be displayed.
	 */
	public function post_read_time( $has_icon = false ) {

		$rtime = themesetup()->get_post_read_time( $has_icon );

		echo $rtime; // phpcs:ignore WordPress.Security.EscapeOutput

	}

	/**
	 * Calculate reading time in minutes.
	 *
	 * @param string $text Text to calculate the reading time.
	 *
	 * @return int Number of minutes.
	 */
	public function reading_time( $text ) {

		$words            = count( preg_split( "/[\n\r\t ]+/", wp_strip_all_tags( $text ) ) );
		$words_per_minute = themesetup()->get_setting( 'themesetup_reading_time_words' );
		$words_per_minute = ! empty( $words_per_minute ) ? absint( $words_per_minute ) : 200;

		if ( ! empty( $words ) ) {
			$time_in_minutes = ceil( $words / $words_per_minute );
			return $time_in_minutes;
		}

		return false;

	}

	/**
	 * Display a list of post metas.
	 *
	 * @param array $metas An array of meta to display.
	 * @param array $author_args Post author meta arguments.
	 * @param array $date_args Post date meta arguments.
	 * @param array $categories_args Post categories meta arguments.
	 * @param array $comments_args Post comments meta arguments.
	 * @param array $rtime_args Post reading time meta arguments.
	 */
	public function metas( $metas = [], $author_args = [], $date_args = [], $categories_args = [], $comments_args = [], $rtime_args = [] ) {

		if ( empty( $metas ) ) {
			return '';
		}

		$author_defaults = [ 'has_prefix' => true, 'prefix' => '', 'has_link' => true ];
		$author_args = wp_parse_args( $author_args, $author_defaults );

		$date_defaults = [ 'has_icon' => false, 'has_prefix' => false, 'prefix' => '' ];
		$date_args = wp_parse_args( $date_args, $date_defaults );

		$categories_defaults = [ 'has_icon' => false, 'has_prefix' => false, 'prefix' => '' ];
		$categories_args = wp_parse_args( $categories_args, $categories_defaults );

		$comments_defaults = [ 'has_icon' => false ];
		$comments_args = wp_parse_args( $comments_args, $comments_defaults );

		$rtime_defaults = [ 'has_icon' => false ];
		$rtime_args = wp_parse_args( $rtime_args, $rtime_defaults );

		$metas_string = '';

		// Encapsulate each meta in span with a class meta-item.
		foreach ( $metas as $meta ) {

			$meta_string = '';

			switch ( $meta ) {

				case 'author':
					// Authors can't have an avatar in the metas wrap.
					$meta_string = themesetup()->get_posted_by( false, 20, $author_args['has_prefix'], $author_args['prefix'], $author_args['has_link'] );
					break;

				case 'date':
					$meta_string = themesetup()->get_posted_on( $date_args['has_icon'], $date_args['has_prefix'], $date_args['prefix'] );
					break;

				case 'categories':
					if ( 'post' === get_post_type() ) {
						$meta_string = themesetup()->get_categories( $categories_args['has_icon'], $categories_args['has_prefix'], $categories_args['prefix'] );
					}
					break;

				case 'comments':
					$meta_string = themesetup()->get_comments_count( $comments_args['has_icon'] );
					break;

				case 'rtime':
					$meta_string = themesetup()->get_post_read_time( $rtime_args['has_icon'] );
					break;

				default:
					break;
			}

			if ( ! empty( $meta_string ) ) {

				$metas_string .= sprintf(
					'<li class="meta-item meta-%1$s">%2$s</li>',
					$meta,
					$meta_string
				);
			}
		}

		// Display metas inside a div with class meta-items.
		printf(
			'<ul class="meta-items">%1$s</ul>',
			$metas_string
		); // phpcs:ignore WP.Security.EscapeOutput -- WPCS: XSS ok

	}

	/**
	 * Trim down a text without breaking a word.
	 *
	 * @param string  $string Content to trim.
	 * @param integer $limit Number of characters to limit.
	 * @param string  $after Chars to append after trimed string.
	 *
	 * @return string Trimmed part of the string.
	 */
	public function trim_down_text( $string, $limit, $after = '...' ): string {

		// If the string is already shorter than the limit, return it.
		if ( strlen( $string ) <= $limit ) {
			return $string;
		}

		// Find the first space after the desired length.
		$breakpoint = strpos( $string, ' ', $limit );

		// Make sure $breakpoint isn't returning false, and is less than length of the string.
		if ( false !== $breakpoint && $breakpoint < strlen( $string ) - 1 ) {
			$string = substr( $string, 0, $breakpoint ) . $after;
		}

		return $string;
	}

	/**
	 * Prints a post's summary.
	 *
	 * @param integer $limit Max number of characters.
	 * @param string  $post_ID Post ID.
	 */
	public function the_summary( $limit = 250, $post_ID = null ) {

		// If an excerpt is manually added, use it.
		if ( has_excerpt( $post_ID ) ) {

			$excerpt = get_the_excerpt( $post_ID );

			// Otherwise use a trimmed-down version of the content.
		} else {
			$content = get_the_content( $post_ID );
			$content = strip_shortcodes( $content );
			$content = apply_filters( 'the_content', $content );
			$excerpt = str_replace( ']]>', ']]&gt;', $content );

			if ( ! empty( $excerpt ) ) {
				$excerpt = wp_strip_all_tags( $excerpt );
				$excerpt = preg_replace( '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $excerpt );
				$excerpt = themesetup()->trim_down_text( $excerpt, $limit );
			} else {
				return '';
			}
		}

		echo wp_kses_post( wpautop( $excerpt ) );
	}

	/**
	 * Prints HTML for edit post link.
	 */
	public function edit_post() {
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post; only visible to screen readers. */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'themesetup' ),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				get_the_title()
			),
			'<span class="edit-link">' . themesetup()->get_svg( 'ui', 'edit', 16 ),
			'</span>'
		);
	}

}
