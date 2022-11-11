<?php
/**
 * Quote requested email template
 * @package storefront-blocks
 */

/** @var SFPBK_Request_Quote_Email $email instance */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$table_attributes = 'width="100%" cellspacing="0" cellpadding="16" border="1" bordercolor="#ccc" style="border-collapse: collapse"';
?>

<?php do_action( 'woocommerce_email_header', 'Quote Requested', $email ); ?>

<p>
	<?php printf(
		__( 'You’ve received quote request from %s:', 'woocommerce' ),
		$email->get_from_name()
	); ?>
</p>

<h3>Products</h3>

<table <?php echo $table_attributes ?>>
	<tr>
		<th style="text-align:left">Product</th>
		<th style="text-align:left">Variation</th>
		<th style="text-align:left">Quantity</th>
	</tr>
	<?php
	$added = [];

	foreach ( $_POST['sfpbk-pt-prods'] as $prod_id => $qty ) {
		if ( $qty ) {
			if ( ! empty( $_POST['sfpbk-pt-variations'][ $prod_id ] ) ) {
				$prod_id = $_POST['sfpbk-pt-variations'][ $prod_id ];
			}

			$product   = wc_get_product( $prod_id );

			if ( $product->get_sku() ) {
				$prod_id = $product->get_sku();
			}

			if ( ! $product ) continue;

			$variation = '';
			if ( $product instanceof WC_Product_Variation ) {
				$variation = wc_get_formatted_variation( $product, true );
			}


			?>
			<tr>
				<td>
					<a href="<?php echo $product->get_permalink() ?>">
					<?php echo "#$prod_id " . $product->get_title() ?></a>
				</td>
				<td><?php echo $variation ?></td>
				<td><?php echo $qty ?></td>
			</tr>
			<?php
		}
	}
	?>
</table>

<?php if ( ! empty( $_POST['requester_message'] ) ): ?>
	<h3>Customer message:</h3>
	<?php echo esc_html( $_POST['requester_message'] ); ?>
<?php endif; ?>

<h3>Customer details:</h3>

<table <?php echo $table_attributes ?>>
	<tr>
		<th style="text-align:left">Name:</th>
		<td><?php echo $email->get_from_name() ?></td>
	</tr>
	<tr>
		<th style="text-align:left">Email:</th>
		<td><?php echo $email->get_from_address() ?></td>
	</tr>
</table>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
