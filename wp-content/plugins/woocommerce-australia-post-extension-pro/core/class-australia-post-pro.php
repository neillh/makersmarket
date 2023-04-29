<?php

namespace AustraliaPost\Core;


use AustraliaPost\BoxPacker\Australia_Post_Box;
use AustraliaPost\Core\Data\Zone_Scope;
use AustraliaPost\Core\Settings\Product_Shipping_Fields;
use AustraliaPost\Extensions\Business\API\Business;
use AustraliaPost\Extensions\Business\Helpers\Utilities as LabelsProUtilities;
use AustraliaPost\Extensions\Extensions_Loader;
use AustraliaPost\Helpers\Utilities;
use Exception;
use WC_Product;
use WC_Shipping_Method;
use AustraliaPost\BoxPacker\Australia_Post_Item;
use WPRuby\AustraliaPost\DVDoug\BoxPacker\PackedBox;
use WPRuby\AustraliaPost\DVDoug\BoxPacker\Packer;

/**
 * WPRuby_Australia_Post_Pro
 * @author Waseem Senjer
 * @since 1.0.0
 *
 * */
class Australia_Post_Pro extends WC_Shipping_Method
{
	private $package;

	private $only_letters;

	private $api_key;
	private $shop_post_code;
	private $handling_fee;
	private $default_weight;
	private $default_size;
	private $domestic_options;
	private $rates_option;
	private $auspost_key;
	private $customer_email;
	private $intl_options;
	private $debug_mode;
	private $custom_titles;
	private $custom_boxes;
	private $tracked_letters;
	private $satchels;
	private $show_duration;
	private $enable_letters;
	private $enabled_domestic_letters;
	private $enabled_intl_letters;
	private $signature_on_delivery;
	private $enable_extra_cover;
	private $seperate_extracover_sod;
	private $signature_on_delivery_label;
	private $extra_cover_label;
	private $fallback_price;
	private $enable_stripping_tax;
	private $deemphasize_satchels_dimensions;
	/** @var Zone_Scope */
	private $zone_scope;

	/**
	 * @var Extensions_Loader
	 */
	private $extensions;

	/**
	 * WPRuby_Australia_Post_Pro constructor.
	 *
	 * @param int $instance_id
	 */
	public function __construct($instance_id = 0)
	{
		$this->id = 'instance_auspost';
		$this->zone_scope = Utilities::get_zone_scope($instance_id);

		$this->extensions = Extensions_Loader::get_instance();

		$this->instance_id = absint($instance_id);
		$this->method_title = __('Australia Post Pro', 'woocommerce-australia-post-pro');
		$this->title = __('Australia Post', 'woocommerce-australia-post-pro');
		$this->supports  = array(
			'shipping-zones',
			'instance-settings',
			'settings',
		);


		$this->init_form_fields();
		$this->init_settings();


		$this->tax_status = 'taxable';

		$this->title = $this->get_option('title');
		$this->api_key = ($this->get_option('api_key') != '') ? $this->get_option('api_key') : Constants::default_api_key;

		$this->shop_post_code = $this->get_option('shop_post_code');
		$this->handling_fee = trim($this->get_option('handling_fee'));

		$this->default_weight = $this->get_option('default_weight');
		$this->default_size = $this->get_option('default_size');

		$this->domestic_options = $this->get_option('domestic_options');
		$this->rates_option = $this->get_option('rates_option');

		$this->auspost_key = $this->get_option('auspost_key');
		$this->customer_email = $this->get_option('customer_email');

		$this->intl_options = $this->get_option('intl_options');

		$this->debug_mode = $this->get_option('debug_mode');
		$this->custom_titles = $this->get_option('custom_titles');
		$this->custom_boxes = $this->get_option('custom_boxes');
		$this->tracked_letters = $this->get_option('tracked_letters');
		$this->satchels = $this->get_option('satchels');
		$this->show_duration = $this->get_option('show_duration');
		$this->enable_letters = $this->get_option('enable_letters');
		$this->enabled_domestic_letters = $this->get_option('enabled_domestic_letters');
		$this->enabled_intl_letters = $this->get_option('enabled_intl_letters');
		$this->signature_on_delivery = $this->get_option('signature_on_delivery');
		$this->enable_extra_cover = $this->get_option('enable_extra_cover');
		$this->seperate_extracover_sod = $this->get_option('seperate_extracover_sod');
		$this->fallback_price = $this->get_option('fallback_price');
		$this->enable_stripping_tax = $this->get_option('enable_stripping_tax');
		$this->deemphasize_satchels_dimensions = $this->get_option('deemphasize_satchels_dimensions');
		$this->signature_on_delivery_label = $this->get_option('signature_on_delivery_label');
		$this->extra_cover_label = $this->get_option('extra_cover_label');

		foreach ($this->extensions->extra_settings_keys() as $key){
			$this->$key = $this->get_option($key);
		}

		add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
		parent::__construct($instance_id);
	}

	/**
	 *
	 */
	public function init_form_fields()
	{
		$weight_unit = strtolower(get_option('woocommerce_weight_unit'));

		foreach ($this->extensions->extra_settings() as $key => $extension_setting){
			$this->form_fields[$key] = $extension_setting;
		}

		$global_settings = [
			'api_key' => [
				'title' => __('API Key', 'woocommerce-australia-post-pro'),
				'type' => 'text',
				'description' => __('You can get your own key from <a href="https://developers.auspost.com.au/apis/pacpcs-registration" target="_blank">here</a>, or you can keep using this one. If you have a business account with Australia Post, you must enter your own API key.', 'woocommerce-australia-post-pro'),
				'default' => Constants::default_api_key,
			],
			'debug_mode' => [
				'title' => __('Enable Debug Mode', 'woocommerce'),
				'type' => 'checkbox',
				'label' => __('Enable ', 'woocommerce'),
		        'description' => __('If debug mode is enabled, the shipping method will be activated just for 
		        the administrator. The debug mode will display all the debugging data at the cart and the checkout pages.
		         <strong>Also, if you have a business account, all of API operations will be performed in the testbed.</strong>',
			        'woocommerce-australia-post-pro'),
			],
			'title_shipping_settings'   => [
				'title'       => __( 'Shipping settings<hr>', 'woocommerce-australia-post-pro' ),
				'type'        => 'title',
				'description' => '',
			],
			'shop_post_code' => [
				'title' => __('Shop Origin Post Code', 'woocommerce-australia-post-pro'),
				'type' => 'text',
				'css' => 'width:95px',
				'description' => __('Enter your Shop postcode.', 'woocommerce-australia-post-pro'),
				'default' => '2000',
			],
			'default_weight' => [
				'title' => __('Default Package Weight', 'woocommerce-australia-post-pro'),
				'type' => 'text',
				'default' => '0.5',
				'css' => 'width:75px',
				'description' => __("Weight unit: ".$weight_unit."<br> This weight will only be used if the product\s weight are not set in the edit product's page.", 'woocommerce-australia-post-pro'),
			],
			'default_size' => [
				'type' => 'default_size',
				'default'=> 'default',
			],
			'signature_on_delivery_label' => [
				'title' => __('Signature on Delivery Label', 'woocommerce-australia-post-pro'),
				'type' => 'text',
				'description' => __('Customize the <b>Signature on Delivery</b> label text at the Checkout page.', 'woocommerce-australia-post-pro'),
				'default' => 'Signature on Delivery',
			],
			'extra_cover_label' => [
				'title' => __('Extra Cover Label', 'woocommerce-australia-post-pro'),
				'type' => 'text',
				'description' => __('Customize the <b>Extra Cover</b> label text at the Checkout page.', 'woocommerce-australia-post-pro'),
				'default' => 'Extra Cover',
			],
			'enable_stripping_tax' => [
				'title' => __('Remove GST', 'woocommerce-australia-post-pro'),
				'type' => 'checkbox',
				'default' => 'no',
				'label' => __('Enable', 'woocommerce-australia-post-pro'),
				'description' => __('Hint: Enabling this option will strip the GST(tax) value (10%) from the shipping prices coming from Australia Post.', 'woocommerce-australia-post-pro'),
			],
			'custom_titles' => [
				'type' => 'custom_titles',
				'default' => '',
			],
		];

		foreach ($global_settings as $key => $extension_setting) {
			$this->form_fields[$key] = $extension_setting;
		}

		$this->instance_form_fields = [
			'title' => [
				'title' => __('Method Title', 'woocommerce'),
				'type' => 'text',
				'description' => __('This controls the title', 'woocommerce'),
				'default' => __('Australia Post Shipping', 'woocommerce'),
				'desc_tip' => true,
			],
			'handling_fee' => [
				'title' => __('Handling Fees', 'woocommerce-australia-post-pro'),
				'type' => 'text',
				'css' => 'width:75px',
				'description' => __('(Optional) Enter an amount e.g. 3.5 or a percentage e.g. 3% PS: you can use negative values e.g -3.5', 'woocommerce-australia-post-pro'),
				'default' => '',
			],
			'fallback_price' => [
				'title' => __('Fallback Price', 'woocommerce-australia-post-pro'),
				'type' => 'text',
				'default' => '',
				'css' => 'width:75px',
				'description' => __("The plugin will display this price in case Australia Post service doesn't return any prices. Leave it blank to disable the fallback price functionality.", 'woocommerce-australia-post-pro'),
			],
			'domestic_options' => [
				'title' => __('Domestic Options', 'woocommerce-australia-post-pro'),
				'type' => 'multiselect',
				'default' => ['AUS_PARCEL_REGULAR', 'AUS_PARCEL_EXPRESS'],
				'class' => 'availability wc-enhanced-select',
				'options' => Constants::supported_services,
			],
			'intl_options' => [
				'title' => __('International Options', 'woocommerce-australia-post-pro'),
				'type' => 'multiselect',
				'default' => ['INT_PARCEL_AIR_OWN_PACKAGING', 'INT_PARCEL_STD_OWN_PACKAGING'],
				'class' => 'availability wc-enhanced-select',
				'options' => Constants::supported_international_services,
			],
			'rates_option' => [
				'title' => __('Rates ', 'woocommerce'),
				'type' => 'select',
				'default' => 'all',
				'description' => __('Choose whether the plugin shows the cheapest rate or all rates to the customer.', 'woocommerce-australia-post-pro'),
				'options' => [
					'all' => __('Show All The Options', 'woocommerce'),
					'cheapest' => __('Show only the cheapest option', 'woocommerce'),
				],
			],
			'satchels' => [
				'type' => 'satchels',
				'default' => '',
			],
			'deemphasize_satchels_dimensions' => [
				'title' => __('Weight Only Shipping', 'woocommerce-australia-post-pro'),
				'type' => 'checkbox',
				'label' => __('Enable ', 'woocommerce-australia-post-pro'),
				'default' => 'no',
				'description' => __('Enable this option if you think the plugin should ignore the dimensions in order to make shipping based on weight only.', 'woocommerce-australia-post-pro'),
			],
			'show_duration' => [
				'title' => __('Delivery Time', 'woocommerce'),
				'type' => 'checkbox',
				'label' => __('Enable ', 'woocommerce'),
				'default' => 'no',
				'description' => __('Show Delivery Time Estimation on the Cart and Checkout pages. PS: Delivery time is only available for domestic shipping.', 'woocommerce'),
			],
			'enable_letters' => [
				'title' => __('Letters Shipping', 'woocommerce'),
				'type' => 'checkbox',
				'label' => __('Enable', 'woocommerce'),
				'default' => 'no',
				'description' => 'To be considered a letter, your item must:
											<ul><li>- weigh less than 500g</li>
											<li>- contain flexible items</li>
											<li>- have a rectangular shape</li>
											<li>- be no larger than a B4 envelope (260mm x 360mm x 20mm)</li>
											<li>- be no thicker than 20mm</li></ul>',
			],
			'enabled_domestic_letters' => [
				'title' => __('Domestic Letters Options', 'woocommerce-australia-post-pro'),
				'type' => 'multiselect',
				'default' => array_keys(Constants::domestic_letters_services),
				'class' => 'availability wc-enhanced-select',
				'options' => Constants::domestic_letters_services,
			],
			'tracked_letters' => [
				'type' => 'tracked_letters',
				'default' => '',
			],
			'enabled_intl_letters' => [
				'title' => __('International Letters Options', 'woocommerce-australia-post-pro'),
				'type' => 'multiselect',
				'default' => array_keys(Constants::intl_letters_services),
				'class' => 'availability wc-enhanced-select',
				'options' => Constants::intl_letters_services,
			],

			'signature_on_delivery' => [
				'title' => __('Signature on Delivery', 'woocommerce-australia-post-pro'),
				'type' => 'checkbox',
				'default' => 'no',
				'label' => __('Enable', 'woocommerce-australia-post-pro'),
				'description' => __('Hint: Enabling this option will charge the extra shipping cost to the customer', 'woocommerce-australia-post-pro'),
			],
			'enable_extra_cover' => [
				'title' => __('Extra Cover', 'woocommerce-australia-post-pro'),
				'type' => 'checkbox',
				'default' => 'no',
				'label' => __('Enable', 'woocommerce-australia-post-pro'),
				'description' => __('Hint: Enabling this option will charge the extra shipping cost to the customer', 'woocommerce-australia-post-pro'),
			],
			'seperate_extracover_sod' => [
				'title' => __('Separate Extra Services', 'woocommerce-australia-post-pro'),
				'type' => 'checkbox',
				'default' => 'no',
				'label' => __('Enable', 'woocommerce-australia-post-pro'),
				'description' => __('Hint: Enabling this option will separate the Extra Cover and Signature on Delivery costs. Users will be able to choose whether they want this extra services or not.', 'woocommerce-australia-post-pro'),
			],
			'custom_boxes' => [
				'type' => 'custom_boxes',
				'default' => '',
			],
		];

		if ( $this->zone_scope->is_only_local() ) {
			unset($this->instance_form_fields['intl_options']);
			unset($this->instance_form_fields['enabled_intl_letters']);
		}

		if ( $this->zone_scope->is_only_international() ){
			unset($this->instance_form_fields['domestic_options']);
			unset($this->instance_form_fields['enabled_domestic_letters']);
			unset($this->instance_form_fields['satchels']);
			unset($this->instance_form_fields['tracked_letters']);
		}

	}

	/**
	 * @param array $package
	 *
	 * @return bool
	 */
	public function calculate_shipping( $package = [] ) {

		$this->package = $package;

		if ($this->debug_mode === 'yes' && !current_user_can('manage_options')) {
			return false;
		}

		$this->rates = [];

		if ($this->should_use_boxpacker($package)) {
			$package_details = $this->get_package_details_by_boxpacker($package);
			// if one item is too large to fit in any box
			if(FALSE === $package_details){
				$package_details = $this->get_package_details($package);
			}
		}else{
			$package_details = $this->get_package_details($package);
		}

		$method_settings = array_merge($this->instance_settings, $this->settings, array('instance_id' => $this->instance_id));
		$ratesCalculator = $this->extensions->calculator($method_settings);
		$rates = $ratesCalculator->calculate($package_details, $package);

		if (!empty($rates)) {
			uasort($rates, array( $this, 'sort_rates' ));
			if ($this->rates_option == 'cheapest') {
				// Add the first element which is the cheapest.
				$this->add_rate(reset($rates));
			} else {
				foreach ($rates as $key => $rate) {

					if (isset($rate['cost']) && $rate['cost'] > -1) {
						$rate['package'] = $package;
						$rate['cost'] = apply_filters('australia_post_shipping_rate', $rate['cost']);
						if (class_exists(Business::class)) {
							$rate['meta_data'] = [WPRUBY_PACKAGING_DETAILS_KEY => json_encode($package_details) ];
                        }
                        $rate = $this->add_boxes_names($rate, $package_details);
						$this->add_rate($rate);
					}
				}
			}
		} else {
			if (isset($this->fallback_price) && is_numeric($this->fallback_price) && $this->fallback_price > -1) {
			    $rate = [
				    'id' => 'fallback_price',
				    'label' => 'Australia Post',
				    'cost'	=> $this->fallback_price,
			    ];
			    if (class_exists(Business::class)) {
				    $rate['meta_data'] = [ WPRUBY_PACKAGING_DETAILS_KEY   => json_encode($package_details)];
                }
				$rate = $this->add_boxes_names($rate, $package_details);
				$this->add_rate($rate);
			}
		}

		return true;
	}

	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.3.0
	 * @return void
	 */
	public function admin_options()
	{
		require_once( dirname( __FILE__ ) . '/views/admin-options.php' );
	}

	public function generate_satchels_html()
	{
		ob_start();
		include_once( dirname( __FILE__ ) . '/views/satchels_html.php' );
		return ob_get_clean();
	}

	/**
	 * validate_satchels_field function.
	 *
	 * @access public
	 * @return array
	 * @internal param mixed $key
	 */
	public function validate_satchels_field()
	{
		$satchels = [];
		$posted_satchels = [];

		if (isset($_POST['woocommerce_satchels'])) {
			$posted_satchels = $_POST['woocommerce_satchels'];
		}
		if (!empty($posted_satchels)) {
			foreach ($posted_satchels as $type => $values) {
				if (isset($values['small']) && $values['small'] === 'on') {
					$satchels[$type]['small'] = true;
				}

				if (isset($values['1kg']) && $values['1kg'] === 'on') {
					$satchels[$type]['1kg'] = true;
				}

				if (isset($values['medium']) && $values['medium'] === 'on') {
					$satchels[$type]['medium'] = true;
				}

				if (isset($values['large']) && $values['large'] === 'on') {
					$satchels[$type]['large'] = true;
				}
			}
		}
		/// To use the stachels in the order's metabox
		update_option('austpost_stachels', $satchels);
		return $satchels;
	}


	/**
	 * get_min_dimension function.
	 * get the minimum dimension of the package, so we multiply it with the quantity
	 * @access private
	 * @param number $width
	 * @param number $length
	 * @param number $height
	 * @return string $result
	 */
	private static function get_min_dimension($width, $length, $height)
	{
		$dimensions = array('width' => $width, 'length' => $length, 'height' => $height);
		$result = array_keys($dimensions, min($dimensions));
		return $result[0];
	}

	/**
	 * get_package_details function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return array
	 */
	private function get_package_details($package)
	{
		$default_length = isset($this->default_size['length'])?$this->default_size['length']:1;
		$default_width =  isset($this->default_size['width'])?$this->default_size['width']:1;
		$default_height = isset($this->default_size['height'])?$this->default_size['height']:1;

		$weight = 0;
		$volume = 0;
		$all_products_fit_satchels = true;
		$shipped_individually_items = [];
		$products = [];
		// Get weight of order
		foreach ($package['contents'] as $item_id => $values) {
			/** @var WC_Product $_product */
			$_product = $values['data'];
			//info: since 1.9.2 skipp virtual products
			if ($_product->is_virtual()) {
				continue;
			}

			$final_weight = wc_get_weight((floatval($_product->get_weight()) <= 0) ? $this->default_weight : $_product->get_weight(), 'kg');
			$weight += $final_weight * $values['quantity'];
			$value = $_product->get_price();

			$length = wc_get_dimension((floatval($_product->get_length()) <= 0) ? $default_length : $_product->get_length(), 'cm');
			$height = wc_get_dimension((floatval($_product->get_height()) <= 0) ? $default_height : $_product->get_height(), 'cm');
			$width = wc_get_dimension((floatval($_product->get_width()) <= 0) ? $default_width : $_product->get_width(), 'cm');
			$product_dimensions = $this->normalize_dimensions([ 'length' => $length, 'height' => $height, 'width' => $width]);

			if( $this->deemphasize_satchels_dimensions === 'yes'){
				$product_dimensions['length'] = wc_get_dimension(5, 'cm');
				$product_dimensions['width'] = wc_get_dimension(5, 'cm');
				$product_dimensions['height'] = wc_get_dimension(5, 'cm');
			}

			$product_dimensions = array( 'length' => $length, 'height' => $height, 'width' => $width);
			if (!Utilities::fit_satchels(array('large' => true), $product_dimensions)) {
				$all_products_fit_satchels = false;
			}

            $variant_id = $_product->get_parent_id() === 0? $_product->get_id(): $_product->get_parent_id();
            $variant_product = wc_get_product($variant_id);
            if ($variant_product instanceof WC_Product) {
                if ('yes' === $variant_product->get_meta(Product_Shipping_Fields::SHIPPED_INDIVIDUALLY_KEY, true)) {
                    $shipped_individually_items[] =  [
                        'shipped_individually' => true,
                        'weight' => $final_weight,
                        'length' => $product_dimensions['length'],
                        'width' => $product_dimensions['width'],
                        'height' => $product_dimensions['height'],
                        'quantity' => $values['quantity'],
                        'postcode' => $this->get_postcode_for_shipping($_product->get_id()),
                        'item_id' => $item_id,

                    ];
                    continue;
                }
            }

			$min_dimension = self::get_min_dimension($width, $length, $height);
			$product_weight = wc_get_weight((floatval($_product->get_weight()) <= 0) ? $this->default_weight : $_product->get_weight(), 'kg');
			$products[] = array(
				'weight' => round(($product_weight < 0.01)? 0.01: $product_weight, 2),
				'quantity' => $values['quantity'],
				'length' => $product_dimensions['length'],
				'width' => $product_dimensions['width'],
				'height' => $product_dimensions['height'],
				'item_id' => $item_id,
				'postcode' => $this->get_postcode_for_shipping($values['product_id']),
				'value' => $value,
				'min_dimension' => $min_dimension,
			);

			$volume += ($length * $height * $width);
		}
		$max_weights = $this->get_max_weight($package, $products);
		// @since 1.5 order the products by their postcodes
		array_multisort($products, SORT_ASC, $products);
		$pack = [];
		$packs_count = 1;
		$pack[$packs_count]['weight'] = 0;
		$pack[$packs_count]['length'] = 0;
		$pack[$packs_count]['height'] = 0;
		$pack[$packs_count]['width'] = 0;
		$pack[$packs_count]['quantity'] = 0;
		$pack[$packs_count]['value'] = 0;
		$i = 0;

		foreach ($products as $product) {
			$max_weight = ($product['weight'] < $max_weights['satchel'] && !empty($this->satchels)) ? $max_weights['satchel'] : $max_weights['own_package'];
			// since 1.6 letters support
			if (!$this->is_letter($pack[$packs_count])) {
				$this->only_letters = false;
			}

			$next_postcode = isset($products[$i + 1]['postcode']) ? $products[$i + 1]['postcode'] : $product['postcode'];
			while ($product['quantity'] > 0) {
				if (!isset($pack[$packs_count]['weight'])) {
					$pack[$packs_count]['weight'] = 0;
				}
				if (!isset($pack[$packs_count]['quantity'])) {
					$pack[$packs_count]['quantity'] = 0;
				}

				$pack[$packs_count]['weight'] += round($product['weight'], 2);
				$pack[$packs_count]['length'] = ('length' == $product['min_dimension'] && $this->deemphasize_satchels_dimensions !== 'yes') ? $pack[$packs_count]['length'] + $product['length'] : $product['length'];
				$pack[$packs_count]['height'] = ('height' == $product['min_dimension'] && $this->deemphasize_satchels_dimensions !== 'yes') ? $pack[$packs_count]['height'] + $product['height'] : $product['height'];
				$pack[$packs_count]['width'] = ('width' == $product['min_dimension'] && $this->deemphasize_satchels_dimensions !== 'yes') ? $pack[$packs_count]['width'] + $product['width'] : $product['width'];
				$pack[$packs_count]['postcode'] = $product['postcode'];
				$pack[$packs_count]['item_id'] = $product['item_id'];
				$pack[$packs_count]['quantity'] += 1;
				$pack[$packs_count]['value'] += round($product['value'], 2);
				$package_height = self::get_min_dimension($pack[$packs_count]['width'], $pack[$packs_count]['length'], $pack[$packs_count]['height']);
				// since 1.7.1
				//INFO this was causing a bug when letters enabled and two products of 0.04kg and height of 20mm each.
				if (!$this->is_letter($pack[$packs_count])) {
					$this->only_letters = false;
				}
				if ($pack[$packs_count]['weight'] > $max_weight
				    || ($next_postcode != $product['postcode'])
				    || (!empty($this->satchels) && $pack[$packs_count][$package_height] > 30)
				    || ($this->enable_letters != 'no' && (!$this->is_letter($pack[$packs_count]) && $this->is_letter($product) && $this->only_letters === true))
				    || !$this->fit_with_guides($pack[$packs_count][$product['min_dimension']])
				    || ( $all_products_fit_satchels && !$this->is_still_fit_satchels($pack[$packs_count], $package['destination']['country'])) // this can be an option by it's own, to only use satchels
				) {
				    if (($pack[$packs_count]['weight'] - $product['weight']) != 0) {
					    $pack[$packs_count]['value'] -= round($product['value'], 2);

					    $pack[$packs_count]['length'] = ('length' == $product['min_dimension'] && $this->deemphasize_satchels_dimensions !== 'yes') ? $pack[$packs_count]['length'] - $product['length'] : $product['length'];
					    $pack[$packs_count]['height'] = ('height' == $product['min_dimension'] && $this->deemphasize_satchels_dimensions !== 'yes') ? $pack[$packs_count]['height'] - $product['height'] : $product['height'];
					    $pack[$packs_count]['width'] =  ('width' == $product['min_dimension'] && $this->deemphasize_satchels_dimensions !== 'yes') ? $pack[$packs_count]['width'] - $product['width'] : $product['width'];

					    $pack[$packs_count]['quantity'] -= 1;
					    $pack[$packs_count]['weight'] -= round($product['weight'], 2);
					    $pack[$packs_count]['postcode'] = $product['postcode'];
                    }

					// since 1.7.1
					if (!$this->is_letter($pack[$packs_count])) {
						$this->only_letters = false;
					}

					$packs_count++;

					$pack[$packs_count]['weight'] = round($product['weight'], 2);
					$pack[$packs_count]['length'] = $product['length'];
					$pack[$packs_count]['height'] = $product['height'];
					$pack[$packs_count]['width'] = $product['width'];
					$pack[$packs_count]['postcode'] = $product['postcode'];
					$pack[$packs_count]['item_id'] = $product['item_id'];
					$pack[$packs_count]['quantity'] = 1;
					$pack[$packs_count]['value'] = round($product['value'], 2);
				}
				$product['quantity']--;
			}
			$i++;
		}

		foreach ($shipped_individually_items as $shippedIndividuallyItem) {
			for ($i = 0 ; $i < $shippedIndividuallyItem['quantity']; $i++) {
				array_push($pack, $shippedIndividuallyItem);
			}
		}

		foreach ($pack as $key => $p) {
			if ($p['weight'] == 0) {
				unset($pack[$key]);
			}
		}

		return $pack;
	}
	/**
	 * get_package_details_by_boxpacker function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return mixed
	 */
	private function get_package_details_by_boxpacker($package)
	{
		$default_length = isset($this->default_size['length'])?$this->default_size['length']:1;
		$default_width 	=  isset($this->default_size['width'])?$this->default_size['width']:1;
		$default_height = isset($this->default_size['height'])?$this->default_size['height']:1;

		//1. adding boxes
		$boxes = [];
		if (!empty($this->satchels)) {
			$boxes = $this->add_satchels_as_boxes($package);
		}

		if (!empty($this->custom_boxes)) {
			foreach ($this->custom_boxes as $key => $box) {
				$boxes[] = new Australia_Post_Box($box['box_name'], $box['box_outer_length'], $box['box_outer_width'], $box['box_outer_height'], $box['box_empty_weight'], $box['box_inner_length'], $box['box_inner_width'], $box['box_inner_height'], intval($box['box_maximum_weight']) +  intval($box['box_empty_weight']));
			}
		} else {
            /** @var Australia_Post_Box $box */
			foreach ($this->add_boxes_iterations($package['destination']['country']) as $box) {
                $boxes[] = $box;
            }
		}

	    if ($this->enable_letters === 'yes') {
            if ($this->enabled_domestic_letters != '') {
	            $boxes[] = new Australia_Post_Box('Small Envelope', 110, 220, 20,0, 105, 215, 20, 500);
	            $boxes[] = new Australia_Post_Box('Medium Envelope', 162, 229, 20,0, 160, 225, 20, 500);
	            $boxes[] = new Australia_Post_Box('Large Envelope', 250, 350, 20,0, 245, 345, 20, 500);
            }
		    $boxes = $this->add_tracked_letters_as_boxes($boxes);
	    }


		$packer = new Packer();
		foreach ($boxes as $box) {
			$packer->addBox($box);
		}

		$value = 0;
		$shipped_individually_items = [];
		// Get weight of order
		foreach ($package['contents'] as $item_id => $values) {
			/** @var WC_Product $_product */
			$_product = $values['data'];
            $postcode = $this->get_postcode_for_shipping($_product->get_id());
			if ($_product->is_virtual()) {
				continue;
			}

	        $weight = wc_get_weight((floatval($_product->get_weight()) <= 0) ? $this->default_weight : $_product->get_weight(), 'g');
            $value = $_product->get_price() * $values['quantity'];

			if ($this->deemphasize_satchels_dimensions !== 'yes') {
				$length = wc_get_dimension((floatval($_product->get_length()) <= 0) ? $default_length : $_product->get_length(), 'mm');
				$width = wc_get_dimension((floatval($_product->get_width()) <= 0) ? $default_width : $_product->get_width(), 'mm');
				$height = wc_get_dimension((floatval($_product->get_height()) <= 0) ? $default_height : $_product->get_height(), 'mm');
			} else {
				$length = wc_get_dimension(5, 'mm');
				$height = wc_get_dimension(5, 'mm');
				$width = wc_get_dimension(5, 'mm');
			}

			$dimensions = $this->normalize_dimensions([
				'length' => $length,
				'width' => $width,
				'height' => $height,
			]);
			$variant_id = $_product->get_parent_id() === 0? $_product->get_id(): $_product->get_parent_id();
			$variant_product = wc_get_product($variant_id);
			if ($variant_product instanceof WC_Product) {
                if ('yes' === $variant_product->get_meta(Product_Shipping_Fields::SHIPPED_INDIVIDUALLY_KEY, true)) {
                    $shipped_individually_items[] =  [
                        'shipped_individually' => true,
                        'weight' => $weight / 1000,
                        'length' => $dimensions['length'] / 10,
                        'width' => $dimensions['width'] / 10,
                        'height' => $dimensions['height'] / 10,
                        'quantity' => $values['quantity'],
                        'postcode' => $postcode,
                        'item_id' => $item_id,

                    ];
                    continue;
                }
			}
			//adding the packer code
			//2. adding items
			for ($i = 0 ; $i < $values['quantity']; $i++) {
				$item = new Australia_Post_Item('Product', $dimensions['width'], $dimensions['length'], $dimensions['height'], $weight, true, $postcode, $value);
				$packer->addItem($item);
			}
			//end of the packer code

		}

        //adding the packer code
        //3. packing
        try {
            $packedBoxes = $packer->pack();
        } catch (Exception $e) {
            return false;
        }
        $pack = [];
        $packs_count = 1;
        $pack[$packs_count]['weight'] = 0;
        $pack[$packs_count]['length'] = 0;
        $pack[$packs_count]['height'] = 0;
        $pack[$packs_count]['width'] = 0;
        $pack[$packs_count]['quantity'] = 0;
        $pack[$packs_count]['value'] = 0;
        $pack[$packs_count]['item_id'] = '';
        $pack[$packs_count]['box_name'] = '';
        /** @var PackedBox $packedBox */
        foreach ($packedBoxes as $packedBox) {
            /** @var Australia_Post_Box $boxType */
            $boxType = $packedBox->getBox(); // your own box object, in this case TestBox
	        $pack[$packs_count]['weight'] = round($packedBox->getWeight() / 1000, 3);
	        if(strpos(strtolower($boxType->getReference()), 'envelope') !== false) {
		        $pack[$packs_count]['length'] =  $boxType->getOuterLength() / 10;
		        $pack[$packs_count]['width'] =$boxType->getOuterWidth() / 10;
		        $pack[$packs_count]['height'] =  $boxType->getOuterDepth() / 10;
	        } else {
		        $pack[$packs_count]['length'] =  $packedBox->getUsedLength() / 10;
		        $pack[$packs_count]['width']  =  $packedBox->getUsedWidth()/ 10;
		        $pack[$packs_count]['height'] = $boxType->isSatchel()? 5: $packedBox->getUsedDepth()/ 10;
                if (!$boxType->isFake()) {
	                $pack[$packs_count]['real_length'] =  $boxType->getOuterLength() / 10;
	                $pack[$packs_count]['real_width']  =  $boxType->getOuterWidth() / 10;
	                $pack[$packs_count]['real_height'] = $boxType->getOuterDepth() / 10;
                }

	        }
            $pack[$packs_count]['quantity'] = count($packedBox->getItems()->asArray());
            $pack[$packs_count]['postcode'] = $packedBox->getItems()->asArray()[0]->getPostcode();
            $pack[$packs_count]['value'] = array_reduce($packedBox->getItems()->asArray(), function ($carry, $item) {return $item->getValue() + $carry;}, 0);
            $pack[$packs_count]['item_id'] = md5(microtime());
            $pack[$packs_count]['box_name'] = $boxType->getReference();
			$packs_count++;
		}


        foreach ($shipped_individually_items as $shippedIndividuallyItem) {
	        for ($i = 0 ; $i < $shippedIndividuallyItem['quantity']; $i++) {
	            $pack[$packs_count] = $shippedIndividuallyItem;
		        $packs_count++;
	        }
        }
		return $pack;
	}

	/**
	 * @param $package
	 * @param array $products
	 *
	 * @return array
	 */
	private function get_max_weight($package, $products = [])
	{
		$country = $package['destination']['country'];
		if ($this->enable_letters != 'no') {
			$all_letters = true;
			//check if all are fit letters
			if (isset($products)) {
				if (is_array($products)) {
					foreach ($products as $product) {
						if (!$this->is_letter($product)) {
							$all_letters = false;
						}
					}
				}
			}
			if ($all_letters === true) {
				return array('own_package' => 500, 'satchel' => 0);
			}
		}

		$max_weights = [];
		$max_weights['satchel'] = 0;
		$satchels = $this->satchels;
		if ($country == 'AU') {
			if ($satchels !== false) {
				// from 30.09.2019 all satchels have a max weight of 5 kg.
				$max_weights['satchel'] = wc_get_weight(5, 'kg', 'kg');
			}
		}

		// if seller not using any satchels.

        $domesticMaxWeight = 22;
		// eParcel has a maximum weight of 32 kg
		if (class_exists(Business::class)) {
		    $domesticMaxWeight = ( LabelsProUtilities::isEParcel() && LabelsProUtilities::get_method_setting('enabled_contract_mode') === 'yes')? 32:22;
        }

		$max_weights['own_package'] = ($country == 'AU') ? $domesticMaxWeight : 20;
		return [
			'own_package' => $max_weights['own_package'],
			'satchel' => $max_weights['satchel'],
		];
	}
	/**
	 * get_postcode_for_shipping function.
	 *
	 * @access private
	 * @param int $product_id
	 * @return int $post_code
	 */
	private function get_postcode_for_shipping($product_id)
	{
		$dropshipper_postcode = get_post_meta($product_id, '_dropshipping_postcode', true);
		if (is_numeric($dropshipper_postcode)) {
			return $dropshipper_postcode;
		} else {
			return $this->shop_post_code;
		}
	}


	/**
	 * to split packages when minimum dimension reach the max.
	 * @since 1.6.4
	 * @param $min_dimension
	 *
	 * @return bool
	 */
	private function fit_with_guides($min_dimension)
	{
		if ($min_dimension > 105) {
			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function generate_default_size_html()
	{
		$dimensions_unit = strtolower(get_option('woocommerce_dimension_unit'));
		$length = (isset($this->default_size['length']))?$this->default_size['length']:wc_get_dimension(250, $dimensions_unit, 'mm');
		$width  = (isset($this->default_size['width']))?$this->default_size['width']:wc_get_dimension(200, $dimensions_unit, 'mm');
		$height  = (isset($this->default_size['height']))?$this->default_size['height']:wc_get_dimension(100, $dimensions_unit, 'mm');
		ob_start(); ?>
        <tr style="vertical-align: top;">
            <th class="titledesc">
                <label><?php _e('Default Package Size', 'woocommerce-australia-post-pro') ?></label>
            </th>
            <td class="forminp">
                <fieldset id="aupost_default_dimensions">
                    <label for="woocommerce_auspost_default_length"><?php _e('Length', 'woocommerce-australia-post-pro'); ?></label> <input type="text" class="input-text regular-input" id="woocommerce_auspost_default_length" name="woocommerce_auspost_default_length" value="<?php echo esc_attr($length); ?>" style="width:70px" />
                    <label for="woocommerce_auspost_default_width"><?php _e('Width', 'woocommerce-australia-post-pro'); ?></label>   <input type="text" class="input-text regular-input" id="woocommerce_auspost_default_width" name="woocommerce_auspost_default_width" value="<?php echo esc_attr($width); ?>" style="width:70px" />
                    <label for="woocommerce_auspost_default_height"><?php _e('Height', 'woocommerce-australia-post-pro'); ?></label> <input type="text" class="input-text regular-input" id="woocommerce_auspost_default_height" name="woocommerce_auspost_default_height" value="<?php echo esc_attr($height); ?>" style="width:70px" />
                    <p class="description">Size unit: <?php echo $dimensions_unit; ?><br> This dimension will only be used if the product\s dimensions are not set in the edit product's page.</p>
                </fieldset>
            </td>
        </tr>

		<?php
		return ob_get_clean();
	}

	/**
	 * validate_default_size_field function.
	 *
	 * @access public
	 * @return array
	 * @internal param mixed $key
	 */
	public function validate_default_size_field()
	{
		$dimensions = [];
		if (is_numeric($_POST['woocommerce_auspost_default_length']) && $_POST['woocommerce_auspost_default_length'] > 0) {
			$dimensions['length'] = $_POST['woocommerce_auspost_default_length'];
		}
		if (is_numeric($_POST['woocommerce_auspost_default_width']) && $_POST['woocommerce_auspost_default_width'] > 0) {
			$dimensions['width']  = $_POST['woocommerce_auspost_default_width'];
		}
		if (is_numeric($_POST['woocommerce_auspost_default_height']) && $_POST['woocommerce_auspost_default_height'] > 0) {
			$dimensions['height'] = $_POST['woocommerce_auspost_default_height'];
		}
		return $dimensions;
	}


	/** sort rates based on cost *
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	private function sort_rates($a, $b)
	{
		if(!isset($a['cost']) || !isset($b['cost'])) return 1;
		if ($a['cost'] == $b['cost']) {
			return 0;
		}
		return ($a['cost'] < $b['cost']) ? -1 : 1;
	}

	/**
	 * @since 1.8.6
	 * Generates the HTML for custom shipping methods label
	 **/
	public function generate_custom_titles_html()
	{

		ob_start();
		require_once( dirname( __FILE__ ) . '/views/custom_titles_html.php' );
		return ob_get_clean();
	}

	/**
	 * validate_custom_titles_field function.
	 *
	 * @access public
	 * @return array
	 * @internal param mixed $key
	 */
	public function validate_custom_titles_field()
	{
		$custom_titles = [];
		$posted_custom_titles = [];
		if (isset($_POST['woocommerce_custom_titles'])) {
			$posted_custom_titles = $_POST['woocommerce_custom_titles'];
		}
		if (!empty($posted_custom_titles)) {
			foreach ($posted_custom_titles as $type => $value) {
				$custom_titles[$type] = $value;
			}
		}
		return $custom_titles;
	}

	/**
	 * @since 2.0.0
	 * Generates the HTML for custom shipping methods label
	 **/
	public function generate_custom_boxes_html()
	{
		ob_start();
		require_once( dirname( __FILE__ ) . '/views/custom_boxes_html.php' );
		return ob_get_clean();
	}

	/**
	 * @since 2.0.0
	 * Generates the HTML for custom shipping methods label
	 **/
	public function generate_tracked_letters_html()
	{
		ob_start();
		require_once( dirname( __FILE__ ) . '/views/tracked_letters_html.php' );
		return ob_get_clean();
	}

	/**
	 * validate_custom_boxes_field function.
	 *
	 * @access public
	 * @return array
	 * @internal param mixed $key
	 */
	public function validate_custom_boxes_field()
	{
		$custom_boxes = [];
		$posted_custom_boxes = [];
		if (isset($_POST['woocommerce_custom_boxes'])) {
			$posted_custom_boxes = $_POST['woocommerce_custom_boxes'];
		}

		if (!empty($posted_custom_boxes)) {
			foreach ($posted_custom_boxes as $key => $value) {
				foreach ($value as $i => $attr) {
					$custom_boxes[$i][$key] = $attr;
				}
			}
		}
		return $custom_boxes;
	}

	/**
	 * validate_tracked_letters_field function.
	 *
	 * @access public
	 * @return array
	 * @internal param mixed $key
	 */
	public function validate_tracked_letters_field()
	{
		$tracked_letters = [];
		$posted_tracked_letters = [];
		if (isset($_POST['woocommerce_tracked_letters'])) {
			$posted_tracked_letters = $_POST['woocommerce_tracked_letters'];
		}
		if (!empty($posted_tracked_letters)) {
			foreach ($posted_tracked_letters as $type => $value) {
				$tracked_letters[$type] = $value;
			}
		}
		return $tracked_letters;
	}

	/**
	 * @param $pack
	 * @param $country
	 *
	 * @return bool
	 */
	private function is_still_fit_satchels($pack, $country){
		if ( $country !== 'AU') {
			return true;
		}

		if(isset($this->satchels['regular'])){
			return Utilities::fit_satchels($this->satchels['regular'], $pack);
		}elseif(isset($this->satchels['express'])){
			return Utilities::fit_satchels($this->satchels['express'], $pack);
		}

		return true;

	}

	public function has_settings() {
		return true;
	}

	/**
	 * @param array $package
	 *
	 * @return bool
	 */
	private function should_use_boxpacker(array $package): bool
    {
        return true;
	}

	/**
	 * @param array $package
	 *
	 * @return float
	 */
	private function get_order_total_weight( $package ) {
		$total_weight = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			/** @var WC_Product $_product */
			$_product = $values['data'];
			//info: since 1.9.2 skipp virtual products
			if ( $_product->is_virtual() ) {
				continue;
			}
			$final_weight = $values['quantity'] * wc_get_weight( ( floatval( $_product->get_weight() ) <= 0 ) ? $this->default_weight : $_product->get_weight(), 'g' );
			$total_weight += $final_weight;
		}
		return $total_weight;
	}



	private function add_satchels_as_boxes($package)
	{

		$country = $package['destination']['country'];
		if ($country !== 'AU') {
			return [];
		}

		foreach ($package['contents'] as $item){
			/** @var WC_Product $product */
			$product = $item['data'];
			$dimensions = [
				'length' => wc_get_dimension(floatval($product->get_length()), 'cm'),
				'width' => wc_get_dimension(floatval($product->get_width()), 'cm'),
				'height' => wc_get_dimension(floatval($product->get_height()), 'cm'),
			];
			if (!Utilities::fit_satchels(['large' => 1], $dimensions)) {
				return [];
			}
		}

		$satchels = [
			'small' => (new Australia_Post_Box('Small Satchel', 355, 220 , 125, 100, 345, 210 , 120, 5000))->setIsSatchel(true),
			'medium' => (new Australia_Post_Box('Medium Satchel', 385, 265, 135, 100, 375, 245, 130, 5000))->setIsSatchel(true),
			'large' => (new Australia_Post_Box('Large Satchel', 405, 310, 135, 100, 395, 300, 130, 5000))->setIsSatchel(true),
			'extra_large' => (new Australia_Post_Box('Extra Large Satchel', 510, 435, 135, 100, 500, 425, 130, 5000))->setIsSatchel(true)
		];

		$boxes = [];
		foreach ($this->satchels as $key => $value) {
			if (isset($value['small'])) {
				$boxes['small'] = $satchels['small'];
			}
			if (isset($value['1kg'])) {
				$boxes['medium'] = $satchels['medium'];
			}
			if (isset($value['medium'])) {
				$boxes['large'] = $satchels['large'];
			}
			if (isset($value['large'])) {
				$boxes['extra_large'] = $satchels['extra_large'];
			}
		}

		return $boxes;
	}

	/**
	 * get_postcode_for_shipping function.
	 *
	 * @access private
	 *
	 * @param $params
	 *
	 * @return bool $is_letter
	 * @internal param array $dimensions
	 */
	private function is_letter($params)
	{
		if ($this->enable_letters == 'no') {
			return false;
		}

		$slug = "aupost_not_letter";
		foreach ($this->package['contents'] as $item_id => $values) {
			/** @var WC_Product $_product */
			$_product = $values['data'];
			$terms = get_the_terms($_product->get_id(), 'product_shipping_class');

			if ($terms) {
				foreach ($terms as $term) {
					$shipping_class = $term->slug;
					if ($slug === $shipping_class) {
						return false;
					}
				}
			}
		}

		$width = $params['width'];
		$length =   $params['length'];
		$thickness = $params['height'];
		$weight = $params['weight'] * 1000;
		$max_weight = 500;
		/*
		To be considered a letter, your item must:
		- weigh less than 500g
		- contain flexible items
		- have a rectangular shape
		- be no larger than a B4 envelope (260mm x 360mm x 20mm)
		- be no thicker than 20mm
		 */
		if ($weight > $max_weight) {
			return false;
		}

		if ($thickness > (2)) {
			return false;
		}

		if ($width > (36)) {
			return false;
		}

		if ($length > (26)) {
			return false;
		}

		return true;
	}

	private function normalize_dimensions($dimensions) {
		$dimensions = [
			$dimensions['length'],
			$dimensions['width'],
			$dimensions['height'],
		];
		sort($dimensions);
		$dimensions = array_reverse($dimensions);

		$params = [];
		$params['length'] = floatval($dimensions[0]);
		$params['width'] = floatval($dimensions[1]);
		$params['height'] = floatval($dimensions[2]);

		return $params;
	}

	private function add_boxes_names($rate, $package_details)
    {
        if (!is_array($package_details)) {
            return $rate;
        }

        $boxes = array_map(function($package) {
            if (isset($package['box_name'])) {
                return $package['box_name'];
            }
            return false;
        }, $package_details);

	    $boxes = array_filter($boxes, function($box){
		    return $box;
	    });

        $rate['meta_data']['Boxes'] = implode(', ', $boxes);

        return $rate;
	}

	/**
	 * @param array<Australia_Post_Box> $boxes
	 */
	private function add_tracked_letters_as_boxes( $boxes )
    {
        $tracked_services = Constants::domestic_tracked_letters;
        foreach ($this->tracked_letters as $key => $letter) {
            if ($letter['enabled'] !== 'on') {
                continue;
            }
	        $letter_info = $tracked_services[$key];
	        $boxes[] = new Australia_Post_Box(
                $key,
                $letter_info['dimensions']['l'],
                $letter_info['dimensions']['w'],
		        $letter_info['dimensions']['h'],
            0,
		        $letter_info['dimensions']['l'],
		        $letter_info['dimensions']['w'],
		        $letter_info['dimensions']['h'],
		        $letter_info['max_weight']
            );

        }

        return $boxes;
	}


	protected function add_boxes_iterations(string $country): array
	{
		$boxes = [];
		$length = 100;
		$width = 50;
		$height = 20;
		$emptyWeight = 0;
		$maxWeight = 1000;
        $maxIteration = ($country === 'AU')? 22: 20;

		foreach (range(1, $maxIteration) as $box) {
			$boxes[] = (new Australia_Post_Box( 'Box #' . $box, $length, $width, $height, $emptyWeight, $length - 10, $width - 10, $height - 10, $maxWeight ))
                        ->setIsFake(true);
			$length += 45;
			$width += 25;
			$height += 20;
			$maxWeight += 1000;
			if (round(($length * $width * $height) / 1000000000, 2) > 0.25) {
				break;
			}
		}

		return $boxes;
	}

}
