<?php
/**
 * Get awesome product styles working
 * @since 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $sfp_layout, $sfp_template;

?>

<style>
	.single-product .site-main,
	.sfp-nav-styleleft-vertical #content {
		margin: 0;
	}
</style>
<link href="<?php echo plugin_dir_url( __FILE__ ) . "/product/$sfp_layout.css" ?>" rel="stylesheet">

<?php
if ( file_exists( dirname( __FILE__ ) . "/product/$sfp_layout.php" ) ) {
	include dirname( __FILE__ ) . "/product/$sfp_layout.php";
} else {
	include $sfp_template;
}
