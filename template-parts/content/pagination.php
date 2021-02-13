<?php
/**
 * Template part for displaying pagination
 *
 * @package themesetup
 */

use function Themesetup\themesetup;

the_posts_pagination(
	apply_filters(
		'themesetup_filter_posts_pagination_args',
		[
			'mid_size'           => 2,
			'prev_text'          => sprintf(
				'%s <span class="nav-prev-text">%s</span>',
				themesetup()->get_svg( 'ui', 'chevron_left', 22 ),
				__( 'Newer archive results', 'themesetup' )
			),
			'next_text'          => sprintf(
				'<span class="nav-next-text">%s</span> %s',
				__( 'Older archive results', 'themesetup' ),
				themesetup()->get_svg( 'ui', 'chevron_right', 22 )
			),
			'screen_reader_text' => __( 'Page navigation', 'themesetup' ),
		]
	)
);
