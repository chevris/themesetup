<?php
/**
 * Template part for displaying the site branding.
 *
 * @package themesetup
 */

?>

<div class="branding">

	<?php
	if ( has_custom_logo() ) {
		the_custom_logo();
	}
	?>

	<div class="branding-identity">

		<?php if ( ! empty( get_bloginfo( 'name' ) ) ) { ?>
			<p class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</p>
		<?php } ?>

		<?php
		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) :
			?>
			<p class="site-description">
				<?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</p>
		<?php endif; ?>

	</div><!-- .branding-identity -->

</div><!-- .branding -->
