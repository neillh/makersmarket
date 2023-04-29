<?php
/**
 * Item image
 *
 * @package cartflows
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// Ignoring below rule as we have included these template file in class based file and 'self' refering to same class.
//phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.SelfOutsideClass
?>

<?php if ( 'yes' === self::$product_option_data['product_images'] ) { ?>
	<div class="wcf-item-image" style=""><?php echo $rc_product_obj->get_image(); ?></div>
<?php } ?>
