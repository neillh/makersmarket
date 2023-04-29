<?php

namespace Objectiv\Plugins\Checkout\Features;

use Exception;
use Objectiv\Plugins\Checkout\Admin\Pages\PageAbstract;
use Objectiv\Plugins\Checkout\Factories\BumpFactory;
use Objectiv\Plugins\Checkout\Interfaces\SettingsGetterInterface;
use Objectiv\Plugins\Checkout\Model\Bumps\BumpAbstract;
use WC_Cart;

class OrderBumps extends FeaturesAbstract {
	public function __construct( bool $available, string $required_plans_list, SettingsGetterInterface $settings_getter ) {
		parent::__construct( true, $available, $required_plans_list, $settings_getter );
	}

	public function init() {
		parent::init();

		BumpAbstract::init( PageAbstract::get_parent_slug() );
	}

	protected function run_if_cfw_is_enabled() {
		// Store line item bump information and record order stats
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'handle_order_meta' ) );
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_bump_meta_to_order_items' ), 10, 4 );

		// Output bumps
		add_action( 'cfw_checkout_cart_summary', array( $this, 'output_cart_summary_bumps' ), 41 );
		add_action( 'cfw_checkout_payment_method_tab', array( $this, 'output_payment_tab_bumps' ), 38 );
		add_action( 'cfw_checkout_payment_method_tab', array( $this, 'output_mobile_bumps' ), 38 );
		add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'add_bumps_to_update_checkout' ) );

		// Add to Cart
		add_action( 'cfw_checkout_update_order_review', array( $this, 'handle_adding_order_bump_to_cart' ) );

		// Pricing overrides
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'sync_bump_cart_prices' ), 100000 );
		add_filter( 'cfw_cart_item_discount', array( $this, 'show_bump_discount_on_cart_item' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_product', array( $this, 'correct_cart_bump_subtotals' ), 10, 2 );

		// Admin filters
		add_action( 'restrict_manage_posts', array( $this, 'admin_filter_select' ), 60 );

		// Handle invalidations
		add_action( 'woocommerce_cart_item_removed', array( $this, 'maybe_remove_bump_from_cart' ), 10 );

		// Prevent quantity adjustments (maybe)
		add_filter( 'cfw_disable_cart_editing', array( $this, 'maybe_disable_cart_editing' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_product', array( $this, 'maybe_prevent_quantity_changes' ), 10, 2 );

		// Add filter to queries on admin orders screen to filter on order type. To avoid WC overriding our query args, we have to hook at 11+
		add_filter( 'request', array( $this, 'filter_orders_query' ), 11 );
	}

	public function unhook_order_bumps_output() {
		remove_action( 'cfw_checkout_cart_summary', array( $this, 'output_cart_summary_bumps' ), 41 );
		remove_action( 'cfw_checkout_payment_method_tab', array( $this, 'output_payment_tab_bumps' ), 38 );
		remove_action( 'cfw_checkout_payment_method_tab', array( $this, 'output_mobile_bumps' ), 38 );
		remove_action( 'woocommerce_update_order_review_fragments', array( $this, 'add_bumps_to_update_checkout' ) );
	}

	/**
	 * Handle order meta
	 *
	 * @param int $order_id
	 * @throws Exception
	 */
	public function handle_order_meta( int $order_id ) {
		$purchased_bump_ids = $this->get_purchased_bump_ids( $order_id );

		if ( ! empty( $purchased_bump_ids ) ) {
			$order = \wc_get_order( $order_id );

			$order->add_meta_data( 'cfw_has_bump', true );

			foreach ( $purchased_bump_ids as $purchased_bump_id ) {
				$order->add_meta_data( 'cfw_bump_' . $purchased_bump_id, true );
			}

			$order->save();
		}

		$this->record_bump_stats( $purchased_bump_ids );
	}

	/**
	 * Record bump stats
	 *
	 * @throws Exception
	 */
	public function record_bump_stats( array $purchased_bump_ids ) {
		foreach ( $purchased_bump_ids as $purchased_bump_id ) {
			BumpFactory::get( $purchased_bump_id )->record_purchased();
		}

		$raw_displayed_bump_ids = $_POST['cfw_displayed_order_bump'] ?? array();
		$displayed_bump_ids     = array_unique( $raw_displayed_bump_ids );

		foreach ( $displayed_bump_ids as $displayed_bump_id ) {
			BumpFactory::get( (int) $displayed_bump_id )->record_displayed();
		}
	}

	protected function get_purchased_bump_ids( $order_id ): array {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return array();
		}

		$items = $order->get_items();

		if ( empty( $items ) ) {
			return array();
		}

		$ids = array();

		foreach ( $items as $item ) {
			$bump_id = $item->get_meta( '_cfw_order_bump_id', true );

			if ( ! $bump_id ) {
				continue;
			}

			$ids[] = $bump_id;
		}

		return $ids;
	}

	/**
	 * Output cart summary bumps
	 *
	 * @throws Exception
	 */
	public function output_cart_summary_bumps() {
		$this->output_bumps( 'below_cart_items' );
	}

	/**
	 * Output payment tab bumps
	 *
	 * @throws Exception
	 */
	public function output_payment_tab_bumps() {
		$this->output_bumps( 'above_terms_and_conditions' );
	}

	/**
	 * Output mobile bumps
	 *
	 * @throws Exception
	 */
	public function output_mobile_bumps() {
		$this->output_bumps( 'all', 'cfw-order-bumps-mobile' );
	}

	/**
	 * Output bumps
	 *
	 * @throws Exception
	 */
	public function output_bumps( string $location = 'all', string $container_class = '' ) {
		$bumps = BumpFactory::get_all();

		ob_start();
		foreach ( $bumps as $bump ) {
			$bump->display( $location );
		}

		$bump_content    = ob_get_clean();
		$has_bumps_class = ! empty( $bump_content ) ? 'cfw-has-bumps' : '';

		// Output a div whether or not we have content since it's dynamically refreshed with fragments
		echo "<div id=\"cfw_order_bumps_{$location}\" class=\"cfw-order-bumps {$container_class} {$has_bumps_class}\">";
		echo $bump_content;
		echo '</div>';
	}

	/**
	 * Add bumps to update checkout
	 *
	 * @param mixed $fragments
	 *
	 * @return array
	 * @throws Exception
	 */
	public function add_bumps_to_update_checkout( $fragments ): array {
		// We can't really trust WordPress filters to give us the correct data type, so we'll just make sure it's an array
		if ( ! array( $fragments ) ) {
			$fragments = array();
		}

		ob_start();
		$this->output_cart_summary_bumps();
		$fragments['#cfw_order_bumps_below_cart_items'] = ob_get_clean();

		ob_start();
		$this->output_payment_tab_bumps();
		$fragments['#cfw_order_bumps_above_terms_and_conditions'] = ob_get_clean();

		ob_start();
		$this->output_mobile_bumps();
		$fragments['#cfw_order_bumps_all'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Handle adding order bump to cart
	 *
	 * @param $post_data
	 *
	 * @return bool
	 */
	public function handle_adding_order_bump_to_cart( $post_data ): bool {
		// turn the string of post data into an array
		// We don't use the $_POST object because $post_data here is preprocessed for us.
		if ( ! is_array( $post_data ) ) {
			parse_str( $post_data, $post_data );
		}

		$bump_ids = $post_data['cfw_order_bump'] ?? array();

		if ( empty( $bump_ids ) ) {
			return false;
		}

		foreach ( $bump_ids as $bump_id ) {
			BumpFactory::get( $bump_id )->add_to_cart( WC()->cart );
		}

		return true;
	}

	/**
	 * Maybe override cart item subtotal
	 *
	 * @param $subtotal
	 * @param $cart_item
	 * @return string
	 * @throws Exception
	 */
	public function maybe_override_cart_item_subtotal( $subtotal, $cart_item ): string {
		$bump = BumpFactory::get( $cart_item['_cfw_order_bump_id'] ?? 0 );

		if ( ! $bump->is_cart_bump_valid() || ! $this->is_published() ) {
			return $subtotal;
		}
		// This was added for TM Extra Product Options
		// Which aggressively tries to override the display of the cart item price
		// We are operating on the assumption that there are other, equally evil plugins out there
		// But we don't know for sure - so we should circle back to this
		// TODO: Should we specifically solve this in a compatibility class or leave this as protective code for n+1 situations?
		return isset( $cart_item['cfw_order_bump_price'] ) ? wc_price( $cart_item['cfw_order_bump_price'] * $cart_item['quantity'] ) : $subtotal;
	}

	/**
	 * Sync bump cart prices
	 *
	 * @param WC_Cart $cart
	 * @throws Exception
	 */
	public function sync_bump_cart_prices( WC_Cart $cart ) {
		foreach ( $cart->get_cart_contents() as $cart_item ) {
			$bump = BumpFactory::get( $cart_item['_cfw_order_bump_id'] ?? 0 );

			if ( ! $bump->is_cart_bump_valid() || ! $bump->is_published() ) {
				continue;
			}

			$bump_price = $bump->get_price( 'cart' );

			$cart_product = $cart_item['data'] ?? false;

			if ( ! ( $cart_product instanceof \WC_Product ) ) {
				continue;
			}

			$cart_product->set_price( $bump_price );
		}

		WC()->cart->set_session();
	}

	/**
	 * Save bump meta to order items
	 *
	 * @param $item
	 * @param $cart_item_key
	 * @param array $values
	 * @throws Exception
	 */
	public function save_bump_meta_to_order_items( $item, $cart_item_key, array $values ) {
		$bump = BumpFactory::get( $values['_cfw_order_bump_id'] ?? 0 );

		$bump->add_bump_meta_to_order_item( $item, $values );
	}

	/**
	 * Show bump discount on cart item
	 *
	 * @param string $price_html
	 * @param array $cart_item
	 *
	 * @return string
	 */
	public function show_bump_discount_on_cart_item( string $price_html, array $cart_item ): string {
		$bump = BumpFactory::get( $cart_item['_cfw_order_bump_id'] ?? 0 );

		return $bump->get_cfw_cart_item_discount( $price_html );
	}

	public function admin_filter_select() {
		global $typenow;

		if ( 'shop_order' !== $typenow ) {
			return;
		}

		// TODO: get_all() only returns published bumps. This should probably get them all.
		$all_bumps = BumpFactory::get_all();

		if ( count( $all_bumps ) === 0 ) {
			return;
		}

		?>
		<select name="cfw_order_bump_filter" id="cfw_order_bump_filter">
			<option value=""><?php cfw_esc_html_e( 'All orders', 'woocommerce-subscriptions' ); ?></option>
			<?php
			$bump_filters = array(
				'any' => cfw__( 'Contains Any Order Bump', 'checkout-wc' ),
			);

			foreach ( $all_bumps as $bump ) {
				$bump_filters[ $bump->get_id() ] = sprintf( cfw__( 'Has Bump: %s' ), $bump->get_title() );
			}

			foreach ( $bump_filters as $bump_key => $bump_filter_description ) {
				echo '<option value="' . esc_attr( $bump_key ) . '"';

				if ( isset( $_GET['cfw_order_bump_filter'] ) && $_GET['cfw_order_bump_filter'] ) {
					selected( $bump_key, $_GET['cfw_order_bump_filter'] );
				}

				echo '>' . esc_html( $bump_filter_description ) . '</option>';
			}
			?>
		</select>
		<?php
	}

	/**
	 * Filter orders query
	 *
	 * @param $vars
	 * @return array
	 */
	public static function filter_orders_query( $vars ): array {
		global $typenow;

		$filter_setting = $_GET['cfw_order_bump_filter'] ?? '';
		$should_filter  = 'shop_order' === $typenow && ! empty( $filter_setting );

		if ( ! $should_filter ) {
			return $vars;
		}

		$vars['meta_query']['relation'] = 'AND';

		$key = 'any' === $filter_setting ? 'cfw_has_bump' : 'cfw_bump_' . (int) $filter_setting;

		$vars['meta_query'][] = array(
			'key'     => $key,
			'compare' => 'EXISTS',
		);

		return $vars;
	}

	public function maybe_remove_bump_from_cart() {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( empty( $cart_item['_cfw_order_bump_id'] ) ) {
				continue;
			}

			$bump = BumpFactory::get( $cart_item['_cfw_order_bump_id'] );

			if ( ( ! $bump->is_cart_bump_valid() || ! $bump->is_published() ) && $bump->get_item_removal_behavior() !== 'keep' ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}

		WC()->cart->set_session();
	}

	public function maybe_disable_cart_editing( $result, $cart_item ): bool {
		if ( empty( $cart_item['_cfw_order_bump_id'] ) ) {
			return $result;
		}

		$bump = BumpFactory::get( $cart_item['_cfw_order_bump_id'] );

		return ! $bump->can_quantity_be_updated();
	}

	public function maybe_prevent_quantity_changes( $product, $cart_item ) {
		$bump = BumpFactory::get( $cart_item['_cfw_order_bump_id'] ?? 0 );

		if ( ! $bump->is_cart_bump_valid() || ! $bump->is_published() ) {
			return $product;
		}

		if ( $bump->can_quantity_be_updated() ) {
			return $product;
		}

		$product->set_sold_individually( true );

		return $product;
	}

	public function correct_cart_bump_subtotals( $product, $cart_item ) {
		$bump = BumpFactory::get( $cart_item['_cfw_order_bump_id'] ?? 0 );

		if ( ! $bump->is_cart_bump_valid() || ! $bump->is_published() ) {
			return $product;
		}

		$product->set_price( $bump->get_price( 'cart' ) );

		return $product;
	}
}
