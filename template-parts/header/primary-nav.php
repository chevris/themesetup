<?php
/**
 * Template to display the header primary nav template.
 *
 * @package themesetup
 */

use function Themesetup\themesetup;
?>

<nav class="primary-nav mobile-vis-false tablet-vis-false" aria-label="<?php esc_attr_e( 'Primary', 'themesetup' ); ?>" role="navigation">

	<?php themesetup()->display_header_primary_nav_menu(); ?>

</nav>
