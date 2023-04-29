<?php

class BWFAN_WC_Cart_Items extends Merge_Tag_Abstract_Product_Display {

	private static $instance = null;

	public $supports_cart_table = true;

	public function __construct() {
		$this->tag_name        = 'cart_items';
		$this->tag_description = __( 'Cart Items', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_cart_items', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = false;
		$this->priority = 2;
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Parse the merge tag and return its value.
	 *
	 * @param $attr
	 *
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		if ( false !== BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			$args = array(
				'posts_per_page' => 1,
				'orderby'        => 'rand',
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'fields'         => 'ids',
			);

			$random_products = get_posts( $args );
			$products        = [];
			foreach ( $random_products as $product ) {
				if ( absint( $product ) > 0 ) {
					$products[] = wc_get_product( $product );
				}
			}
			$this->products = $products;
			$result         = $this->process_shortcode( $attr );

			return $this->parse_shortcode_output( $result, $attr );
		}

		$cart_details = BWFAN_Merge_Tag_Loader::get_data( 'cart_details' );

		if ( empty( $cart_details ) ) {
			$abandoned_id = BWFAN_Merge_Tag_Loader::get_data( 'cart_abandoned_id' );
			$cart_details = BWFAN_Model_Abandonedcarts::get( $abandoned_id );
		}

		if ( empty( $cart_details ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}
		
		$checkout_data    = isset( $cart_details['checkout_data'] ) ? $cart_details['checkout_data'] : '';
		$checkout_data    = json_decode( $checkout_data, true );
		$lang             = is_array( $checkout_data ) && isset( $checkout_data['lang'] ) ? $checkout_data['lang'] : '';
		$items            = apply_filters( 'bwfan_abandoned_cart_items_visibility', maybe_unserialize( $cart_details['items'] ) );
		$products         = [];
		$product_quantity = [];
		foreach ( $items as $item ) {
			if ( ! $item['data'] instanceof WC_Product ) {
				continue;
			}
			$products[] = $item['data'];

			$product_quantity[ $item['data']->get_id() ] = $item['quantity'];
		}

		$this->cart              = $items;
		$this->products_quantity = $product_quantity;
		$this->data              = [
			'coupons'            => maybe_unserialize( $cart_details['coupons'] ),
			'fees'               => maybe_unserialize( $cart_details['fees'] ),
			'shipping_total'     => maybe_unserialize( $cart_details['shipping_total'] ),
			'shipping_tax_total' => maybe_unserialize( $cart_details['shipping_tax_total'] ),
			'total'              => maybe_unserialize( $cart_details['total'] ),
			'currency'           => maybe_unserialize( $cart_details['currency'] ),
			'lang'               => $lang
		];
		$this->products          = $products;

		$result = $this->process_shortcode( $attr );

		return $this->parse_shortcode_output( $result, $attr );
	}

	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {

		$options = [
			[
				'value' =>  '',
				'label' => __( 'Product Grid - 2 Column', 'wp-marketing-automations' ),
			],
			[
				'value' =>  'product-grid-3-col',
				'label' => __( 'Product Grid - 3 Column', 'wp-marketing-automations' ),
			],
			[
				'value' =>  'product-rows',
				'label' => __( 'Product Rows', 'wp-marketing-automations' ),
			],
			[
				'value' =>  'review-rows',
				'label' => __( 'Product Rows (With Review Button)', 'wp-marketing-automations' ),
			],
			[
				'value' =>  'order-table',
				'label' => __( 'WooCommerce Order Summary Layout', 'wp-marketing-automations' ),
			],
			[
				'value' =>  'cart-table',
				'label' => __( 'Cart Table Layout', 'wp-marketing-automations' ),
			],
			[
				'value' =>  'list-comma-separated',
				'label' => __( 'List - Comma Separated (Product Names only)', 'wp-marketing-automations' ),
			],
			[
				'value' =>  'list-comma-separated-with-quantity',
				'label' => __( 'List - Comma Separated (Product Names with Quantity)', 'wp-marketing-automations' ),
			]
		];

		return [
			[
				'id'          => 'template',
				'type'        => 'select',
				'options'     => $options,
				'label'       => __( 'Select Template', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Product Grid - 2 Column',
				"required"    => false,
				"description" => ""
			],
		];
	}
}

/**
 * Register this merge tag to a group.
 *
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_ab_cart', 'BWFAN_WC_Cart_Items', null, 'Abandoned Cart' );
}