<?php

if ( ! is_user_logged_in() ) {
		wp_redirect( home_url() );
		exit;
}

$current_user = wp_get_current_user();
$user_orders = wc_get_orders( array(
		'customer_id' => $current_user->ID,
		'status'      => array( 'processing', 'completed' ),
		'orderby'     => 'date',
		'order'       => 'DESC',
) );

get_header();
?>

<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
				<?php if ( $user_orders ) : ?>
						<h2><?php _e( 'My Orders', 'your-text-domain' ); ?></h2>
						<table>
								<thead>
										<tr>
												<th><?php _e( 'Order Number', 'your-text-domain' ); ?></th>
												<th><?php _e( 'Date', 'your-text-domain' ); ?></th>
												<th><?php _e( 'Total', 'your-text-domain' ); ?></th>
												<th><?php _e( 'Status', 'your-text-domain' ); ?></th>
										</tr>
								</thead>
								<tbody>
										<?php foreach ( $user_orders as $order ) : ?>
												<tr>
														<td><a href="<?php echo esc_url( $order->get_view_order_url() ); ?>"><?php echo esc_html( $order->get_order_number() ); ?></a></td>
														<td><?php echo esc_html( $order->get_date_created()->date( 'Y-m-d' ) ); ?></td>
														<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
														<td><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></td>
												</tr>
										<?php endforeach; ?>
								</tbody>
						</table>
				<?php else : ?>
						<p><?php _e( 'You have no orders.', 'your-text-domain' ); ?></p>
				<?php endif; ?>
		</main>
</div>

<?php
get_footer();
