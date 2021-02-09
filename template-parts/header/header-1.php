<?php
/**
 * Header-1
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<div class="header-1">

	<?php get_template_part( 'template-parts/header/branding' ); ?>

	<?php if ( themesetup()->is_primary_nav_menu_active() ) : ?>
		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'themesetup' ); ?>" role="navigation">
			<?php themesetup()->display_primary_nav_menu(); ?>
		</nav>
	<?php endif; ?>

	<?php if ( themesetup()->is_social_nav_menu_active() ) : ?>
		<nav class="social-nav hide-label" aria-label="<?php esc_attr_e( 'Social Links Menu', 'themesetup' ); ?>">
			<?php themesetup()->display_social_nav_menu(); ?>
		</nav>
	<?php endif; ?>

	<button class="slideout-menu-toggle"
		<?php if ( themesetup()->is_amp() ) { ?>
			on="tap:slideout-menu-amp.toggle"
		<?php } ?>
	>
		<?php echo wp_kses( themesetup()->get_svg( 'ui', 'menu', 20 ), themesetup()->sanitize_svgs() ); ?>
		<span><?php esc_html_e( 'Menu', 'themesetup' ); ?></span>
	</button>

</div><!-- .header-1 -->
