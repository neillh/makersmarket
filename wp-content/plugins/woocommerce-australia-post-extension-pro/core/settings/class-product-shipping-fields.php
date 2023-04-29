<?php
namespace AustraliaPost\Core\Settings;

class Product_Shipping_Fields
{
	protected static $_instance = null;
	const SHIPPED_INDIVIDUALLY_KEY = '_auspost_shipped_individually';

	private $fields = [
		[
			'id' => self::SHIPPED_INDIVIDUALLY_KEY,
			'label' => 'Australia Post: Shipping Individually',
			'description' => 'If the item is marked as to be shipped individually, The Australia Post plugin will pack each quantity of the item on its own.',
			'type'      => 'checkbox',
			'default'   => false,
			'desc_tip'  => true,
		],
	];


	/**
	 * @return self
	 */
	public static function get_instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
		add_action('woocommerce_product_options_shipping', [$this, 'add_fields']);
		add_action('woocommerce_process_product_meta', [$this, 'save_fields']);
	}

	public function add_fields()
	{
		foreach ($this->fields as $field) {
			switch ($field['type']){
				case 'select':
					woocommerce_wp_select($field);
					break;
				case 'checkbox':
					woocommerce_wp_checkbox($field);
					break;
				default:
					woocommerce_wp_text_input($field);
			}
		}
	}

	public function save_fields($product_id)
	{
		foreach ($this->fields as $field) {
			$woocommerce_text_field = (isset($_POST[$field['id']]))?$_POST[$field['id']]:'';
			update_post_meta($product_id, $field['id'], esc_attr($woocommerce_text_field));
		}
	}
}
