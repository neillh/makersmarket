<?php

// Exit if this file is accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * An instance of this class represents the Simple Shipping Labels plugin.
 * This plugin adds a column of buttons in WooCommerce orders page,
 * to generate a single or bulk of editable shipping labels.
 */
class dimap_SimpleShippingLabels {

	  //////////////////////////////////////////////////
	 /// Default label settings and class constants ///
	//////////////////////////////////////////////////
	// Plugin and settings slugs/names/ids/... used in the functions below.
	const PLUGIN_VERSION = '1.0.6';
	const PLUGIN_NAME = 'Simple Shipping Labels';
	const OPTION_GROUP_NAME = 'sslabels_plugin_options';
	const OPTION_NAME = 'simple_shipping_labels';
	const SETTINGS_PAGE_SLUG = 'sslabels_settings';
	const SETTINGS_PAGE_SECTION_SLUG = 'general';
	
	// Settings sections slugs.
	const LABEL_SETTINGS_SECTION_SLUG = 'label-settings';
	const BRAND_SETTINGS_SECTION_SLUG = 'brand-settings';
	const RECIPIENT_SETTINGS_SECTION_SLUG = 'recipient-settings';
	const ORDER_SETTINGS_SECTION_SLUG = 'order-settings';

	/*** Plugin settings ***/
	public $settings = array();
	public $default_settings = array(
		'label_height'				=>	53,		// mm
		'label_width'				=>	101,	// mm
		'label_padding_vertical'	=>	2,		// mm
		'label_padding_horizontal'	=>	2,		// mm
		'test_labels'				=>	'',	// list of order ids to generate labels for preview
		'recipient_details_layout'	=>	'default',	// selected radio button
		'recipient_align_fields'	=>	'center',	// selected dropdown option
		'use_billing_details'		=>	'on',	// checked checkbox
		'show_company'				=> 	'on',	// checked checkbox
		'display_state_full_name'	=>	'on',	// checked checkbox
		'hide_base_country'			=>	'on',	// checked checkbox
		'show_phone'				=> 	'on',	// checked checkbox
		'show_order_total'			=>	'on',	// checked checkbox
		'show_order_id'				=> 	'on',	// checked checkbox
		'auto_open_print_dialog'	=> 	'off',	// unchecked checkbox
		'plugin_version'			=> 	self::PLUGIN_VERSION	// currently not used in plugin
	);

	public $recipient_align_fields_options = ['center','left','right'];
	public $recipient_details_layout_options = array(
		'default' => "<br><span class='label-layout-title'><b>DEFAULT</b></span><br>first_name last_name<br>company<br>address_1 address_2<br>city state post_code<br>country<br>phone",
		'separate_lines' => "<br><span class='label-layout-title'><b>SEPARATE LINES:</b></span><br>first_name last_name<br>company<br>address_1 address_2<br>city<br>state<br>post_code<br>country<br>phone",
		'post_code_first' => "<br><span class='label-layout-title'><b>POST CODE FIRST</b></span><br>first_name last_name<br>company<br>address_1 address_2<br>post_code city state<br>country<br>phone",
		'post_code_first_separate_lines' => "<br><span class='label-layout-title'><b>POST CODE FIRST<br>SEPARATE LINES</b></span><br>first_name last_name<br>company<br>address_1 address_2<br>post_code<br>city<br>state<br>country<br>phone",
	);


	  ////////////////////////////////////////////////
	 /// Class constructor, methods and callbacks ///
	////////////////////////////////////////////////
	/**
	 * The plugin class constructor is responsible for:
	 * - Getting saved plugin options from wp_options database table or using the $default_settings array above.
	 * - Registering plugin hooks:
	 *   - Run plugin update routine when plugin loads.
	 *   - Add settings page to the admin menu.
	 *   - Add buttons and JavaScript to WooCommerce orders page to generate labels.
	 *   - Register custom AJAX endpoint (a single function acts as plugin api) to generate labels page or reset plugin options.
	 */
	public function __construct( $plugin_basename )
    {
		// #######################
		// ### WordPress hooks ###
		// #######################
		// Add plugin version check and update routine.
		// This hook runs each time the plugin is loaded (wordpress environment loads),
		// because there are no other reliable hooks that run when plugin files are
		// updated from remote server.
		add_action('plugins_loaded', array( $this, 'load_plugin_settings' ) );

		// Add plugin settings page ("options page") and register settings.
		add_action( 'admin_menu', array( $this, 'add_plugin_options_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add plugin action link to the plugin settings page.
		// Action links displayed for a specific plugin in the Plugins list table.
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'my_plugin_action_links' ) );

		// Register AJAX endpoint as plugin API to generate labels or reset settings to default.
		add_action( 'wp_ajax_sslabels_api', array( $this, 'sslabels_api' ) );


		// #########################
		// ### WooCommerce hooks ###
		// #########################
		// Add custom column to WooCommerce orders table page.
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'add_print_buttons_column' ) );
		
		// Populate the column with print buttons.
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'add_print_buttons' ) );

		// Add JavaScript functionality to the buttons.
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_pages_scripts_and_styles' ) );
		
		// Add shipping label meta box for print button to all order types (based on WooCommerce plugin source code woocommerce/includes/admin/class-wc-admin-meta-boxes.php file).
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
	}

	// Add plugin action link to plugin settings page.
	function my_plugin_action_links( $actions ) {
		$myActions = array( '<a href="'. esc_url( admin_url('admin.php?page=' . self::SETTINGS_PAGE_SLUG) ) .'">' . __('Settings') . '</a>',);
		return array_merge( $myActions, $actions );
	}

	  /////////////////////////////
	 /// ADMIN EDIT ORDER PAGE ///
	/////////////////////////////
	function add_meta_boxes() {
		foreach ( wc_get_order_types( ['order-meta-boxes'] ) as $type ) {
			$order_type_object = get_post_type_object( $type );
			add_meta_box( 'woocommerce-order-shipping-label', __( 'Shipping Labels', 'woocommerce' ) . wc_help_tip( 'Generate shipping label page for printing' ), array( $this, 'add_admin_order_page_meta_box' ), $type, 'side', 'high' );
		}
	}

	function add_admin_order_page_meta_box() {
		/*
		// Check which admin screen we are on.
		$current_screen = get_current_screen();
		echo $current_screen->post_type;
		*/

		global $post;	// based on https://stackoverflow.com/a/52633638/8587533

		// Get an instance of the WC_Order Object (if needed).
		//$order = wc_get_order( $post->ID );

		// Metabox button uses the same page enqued script.
		$shipping_label_print_button_html = '<button type="button" class="button button-sslabels-print" ' 
		. 'value="' . $post->ID . '">'. __( 'Label' ) .'</button>';
		echo $shipping_label_print_button_html;
	}
	
	  ////////////////////////////
	 /// PLUGIN SETTINGS PAGE ///
	////////////////////////////
	/**
	 * Add plugin settings page in WooCommerce plugin submenu.
	 */
	function add_plugin_options_page() {
		add_submenu_page(
			'woocommerce',									// $parent_slug
			self::PLUGIN_NAME,								// $page_title
			self::PLUGIN_NAME,								// $menu_title
			'manage_options',								// $capability
			self::SETTINGS_PAGE_SLUG,						// $menu_slug
			array( $this, 'render_plugin_settings_page' )	// callable $function
		);
	}

	/**
	 * Output the plugin settings page HTML.
	 * The settings page is a simple form, which is submitted to options.php.
	 * The form fields rendered via do_settings_sections() callback.
	 * The settings are saved in wp_options database table under a single serialized
	 * option with a name self::OPTION_NAME.
	 * 
	 * Based on:
	 * https://nimblewebdeveloper.com/blog/add-tabs-to-wordpress-plugin-admin-page
	 * and Elementor pure JavaScript settings tabs (without refreshing page or query string parameters).
	 */
	function render_plugin_settings_page() {
		?>
		<!-- Admin page content should all be inside .wrap class for some reason -->
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		
		<div class="wrap">
			<!-- Echo the page title -->
			<p>Set the generated simple shipping labels parameters for <b>WooCommerce</b> orders.</p>
			<nav class="nav-tab-wrapper">
				<a href="#tab-label" id="sslabels-settings-tab-label" class="nav-tab nav-tab-active">Label</a>
				<a href="#tab-recipient" id="sslabels-settings-tab-recipient" class="nav-tab">Recipient</a>
				<a href="#tab-order" id="sslabels-settings-tab-order" class="nav-tab">Order</a>
			</nav>
			<form action="options.php" method="post">
				<?php
					settings_fields( self::OPTION_GROUP_NAME );			// Output nonce, action, and option_page fields for a settings page.
					
					// Output all sections at once via WordPress native settings output funciton.
					// do_settings_sections( self::SETTINGS_PAGE_SLUG );	// Prints out all settings sections added to a particular settings page.

					// Label settings tab.
					echo '<div id="tab-label" class="sslabels-settings-form-tab sslabels-active"><table class="form-table">';
					do_settings_fields( self::SETTINGS_PAGE_SLUG, self::LABEL_SETTINGS_SECTION_SLUG );
					echo '</table></div>';

					// Recipient settings tab.
					echo '<div id="tab-recipient" class="sslabels-settings-form-tab"><table class="form-table">';
					do_settings_fields( self::SETTINGS_PAGE_SLUG, self::RECIPIENT_SETTINGS_SECTION_SLUG );
					echo '</table></div>';

					// Order settings tab.
					echo '<div id="tab-order" class="sslabels-settings-form-tab"><table class="form-table">';
					do_settings_fields( self::SETTINGS_PAGE_SLUG, self::ORDER_SETTINGS_SECTION_SLUG );
					echo '</table></div>';
				?>
				<input type="submit" name="submit" class="button button-primary"  value="Save"/>
				<button type="button" id="button-reset-settings" class="button">Reset settings</button>
			</form>
		</div>
		<script>
			var delete_settings_button = document.querySelector("#button-reset-settings");
			delete_settings_button.addEventListener('click', function() {
				
				// Make sure the user wants to delete saved plugin settings.
				if (!confirm("Delete saved plugin options and reset settings?")) {
					return;
				}

				// Show progress in button text.
				this.disabled = true;
				this.innerHTML = "Deleting saved plugin options...";
				
				// Send AJAX request to plugin API to reset plugin settings.
				var data = {
					'action': 'sslabels_api',
					'api_action': 'reset_options',
					'_ajax_nonce': '<?php echo wp_create_nonce('reset_options') ?>'
				};

				// Since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php.
				jQuery.post(ajaxurl, data, function(response) {
					location.reload();
				}).fail(function() {
					alert( "Could not delete saved plugin options. Please try again later or contact plugin support." );
					console.error(response);
					delete_settings_button.innerHTML = "Reset settings";
					delete_settings_button.disabled = false;
				});
			});
		</script>

		<?php
	}


	/**
	 * Register a single setting/option to store all plugin settings.
	 */
	function register_settings() {
		
		// Register the setting and data validation filter function.
		register_setting( self::OPTION_GROUP_NAME, self::OPTION_NAME, array( $this, 'plugin_options_validate' ) );
		
		// Label general settings section.
		add_settings_section( self::LABEL_SETTINGS_SECTION_SLUG, 'Label settings', array( $this, 'settings_section_text' ), self::SETTINGS_PAGE_SLUG );
		add_settings_field( 'label-size', 'Label size', array( $this, 'setting_label_size'), self::SETTINGS_PAGE_SLUG, self::LABEL_SETTINGS_SECTION_SLUG );
		add_settings_field( 'label-padding', 'Label padding', array( $this, 'setting_label_padding' ), self::SETTINGS_PAGE_SLUG, self::LABEL_SETTINGS_SECTION_SLUG );
		add_settings_field( 'auto_open_print_dialog', 'Print dialog', array( $this, 'setting_auto_open_print_dialog'), self::SETTINGS_PAGE_SLUG, self::LABEL_SETTINGS_SECTION_SLUG );
		add_settings_field( 'test_labels', 'Test labels', array( $this, 'setting_test_labels'), self::SETTINGS_PAGE_SLUG, self::LABEL_SETTINGS_SECTION_SLUG );


		// Recipient address settings tab.
		add_settings_section( self::RECIPIENT_SETTINGS_SECTION_SLUG, 'Recipient/order address section', array( $this, 'settings_section_text' ), self::SETTINGS_PAGE_SLUG );
		add_settings_field( 'recipient_details_layout', 'Details layout', array( $this, 'setting_recipient_details_layout'), self::SETTINGS_PAGE_SLUG, self::RECIPIENT_SETTINGS_SECTION_SLUG );
		add_settings_field( 'recipient_details_align', 'Details text align', array( $this, 'setting_recipient_details_align'), self::SETTINGS_PAGE_SLUG, self::RECIPIENT_SETTINGS_SECTION_SLUG );
		add_settings_field( 'recipient_use_billing_details', 'Use billing details', array( $this, 'setting_recipient_use_billing_details'), self::SETTINGS_PAGE_SLUG, self::RECIPIENT_SETTINGS_SECTION_SLUG );
		add_settings_field( 'recipient_show_company', 'Company', array( $this, 'setting_recipient_show_company'), self::SETTINGS_PAGE_SLUG, self::RECIPIENT_SETTINGS_SECTION_SLUG );
		add_settings_field( 'recipient_display_state_full_name', 'State abbreviations', array( $this, 'setting_recipient_display_state_full_name'), self::SETTINGS_PAGE_SLUG, self::RECIPIENT_SETTINGS_SECTION_SLUG );
		add_settings_field( 'recipient_hide_base_country', 'Local shipping', array( $this, 'setting_recipient_hide_base_country'), self::SETTINGS_PAGE_SLUG, self::RECIPIENT_SETTINGS_SECTION_SLUG );
		add_settings_field( 'recipient_show_phone', 'Phone', array( $this, 'setting_recipient_show_phone'), self::SETTINGS_PAGE_SLUG, self::RECIPIENT_SETTINGS_SECTION_SLUG );
		

		// Order settings tab.
		add_settings_section( self::ORDER_SETTINGS_SECTION_SLUG, 'Order details section', array( $this, 'settings_section_text' ), self::SETTINGS_PAGE_SLUG );
		add_settings_field( 'order_show_id', 'Order id', array( $this, 'setting_order_show_id'), self::SETTINGS_PAGE_SLUG, self::ORDER_SETTINGS_SECTION_SLUG );
		add_settings_field( 'order_show_total', 'Order total', array( $this, 'setting_order_show_total'), self::SETTINGS_PAGE_SLUG, self::ORDER_SETTINGS_SECTION_SLUG );
	}


	/**
	 * Sanitize and validate plugin settings page form fields.
	 */
	function plugin_options_validate( $input ) {
		
		// Sanitize and validate user plugin settings.
		$sanitized_and_validated_input = array(
			'label_height'				=>	absint( $input['label_height'] ),
			'label_width'				=>	absint( $input['label_width'] ),
			'label_padding_vertical'	=>	absint( $input['label_padding_vertical'] ),
			'label_padding_horizontal'	=>	absint( $input['label_padding_horizontal'] ),
			'test_labels'				=>	wp_parse_id_list( $input['test_labels'] ),
			'plugin_version'			=> 	self::PLUGIN_VERSION,	// Add plugin version to the stored plugin settings option.
		);

		// If checkboxes are checked - they exist in $input array and we add them to $sanitized_input,
		// otherwise they were either turned off, or they didn't exist in previous plugin version.
		// We handle the later case by explicitly setting them to 'off' when the user saves settings, so if they don't exist
		// when plugin is loaded - the default settings are applied and stored in database.
		
		// Because of a known WordPress bug - this option sanitization callback is called twice if option doesn't exist.
		// https://core.trac.wordpress.org/ticket/21989
		// So if you count on using only isset() to check if some key exists in $input and then set it - this same
		// callback will be called again on the $sanitized_and_validated_input we return here, and run again! So isset()
		// will look like it always returns 'true' (because you set it in previous callback call).
		// To see it for yourself - add the following line:
		// $sanitized_and_validated_input['test'] = $input;
		// and observe the initial saved database option (or after resetting/removing the option) vs saving again and again.
		
		$checkboxes = [
			'use_billing_details',
			'show_company',
			'display_state_full_name',
			'hide_base_country',
			'show_phone',
			'show_order_total',
			'show_order_id',
			'auto_open_print_dialog'
		];

		foreach ($checkboxes as $checkbox) {
			$sanitized_and_validated_input[$checkbox] = (!empty($input[$checkbox]) && $input[$checkbox] == 'on') ? 'on' : 'off' ;
		}

		// Validate the recipient_details_layout selected radio option is one of the available options, otherwise use the default setting.
		if ( !empty($input['recipient_details_layout'])  && !empty($this->recipient_details_layout_options[$input['recipient_details_layout']]) )  {
			$sanitized_and_validated_input['recipient_details_layout'] = $input['recipient_details_layout'];
		} else {
			$sanitized_and_validated_input['recipient_details_layout'] = $this->$default_settings['recipient_details_layout'];
		}

		// Validate the recipient_align_fields option is one of the available options, otherwise use the default setting.
		if ( !empty( $input['recipient_align_fields'] ) && in_array( $input['recipient_align_fields'], $this->recipient_align_fields_options, true) ) {		// `true` enables strict type checking
			$sanitized_and_validated_input['recipient_align_fields'] = $input['recipient_align_fields'];
		} else {
			$sanitized_and_validated_input['recipient_align_fields'] = $this->$default_settings['recipient_align_fields'];
		}
		
		// Return the sanitized and validated input (plugin settings) to save as option in wp_options database table.
		return $sanitized_and_validated_input;
	}


	  ////////////////////////////////////////////////
	 /// PLUGIN SETTINGS PAGE SECTIONS AND FIELDS ///
	////////////////////////////////////////////////
	function settings_section_text() {
		// echo "<p>This is section text.</p>";
	}
	
	// ##########################
	// ### Label Settings Tab ###
	// ##########################
	function setting_label_size() {
		?>
		<p class="description">The horizontal and vertical sides lenghts of the label.</p>
		<ul>
			<li>
				<label for="label-height">Height :
				<input id="label-height" class="small-text" name="<?php echo self::OPTION_NAME; ?>[label_height]" type="number" min="10"  value="<?php echo esc_attr( $this->settings['label_height'] ); ?>" />
				mm</label>
			</li>
			<li>
				<label for="label-width">Width :
				<input id="label-width" class="small-text" name="<?php echo self::OPTION_NAME; ?>[label_width]" type="number" min="10"  value="<?php echo esc_attr( $this->settings['label_width'] ); ?>" />
				mm</label>
			</li>
		</ul>
		<p class="description"><b>Note:</b> if you find empty labels (page overflows) in print dialogue - use 1mm less height.</p>
		<?php
	}


	function setting_auto_open_print_dialog() {
		$checked_state = '';
		if ( !empty($this->settings['auto_open_print_dialog']) && $this->settings['auto_open_print_dialog'] == 'on' ) {
			$checked_state = ' checked';
		}
		?>
		<label for="auto-open-print-dialog">
		<input id="auto-open-print-dialog" name="<?php echo self::OPTION_NAME; ?>[auto_open_print_dialog]" type="checkbox"<?php echo $checked_state; ?>/>Automatically open print dialog on generated labels page</label>
		<?php
	}


	function setting_label_padding() {
		?>
			<p class="description">The inner margins of the label.</p>
			<ul>
				<li>
					<label for="label-padding-top-bottom">Top and bottom :
					<input id="label-padding-top-bottom" class="small-text" name="<?php echo self::OPTION_NAME; ?>[label_padding_vertical]" type="number" min="0"  value="<?php echo esc_attr( $this->settings['label_padding_vertical'] ); ?>" />
					mm</label>
				</li>
				<li>
					<label for="label-padding-left-right">Left and right :
					<input id="label-padding-left-right" class="small-text" name="<?php echo self::OPTION_NAME; ?>[label_padding_horizontal]" type="number" min="0"  value="<?php echo esc_attr( $this->settings['label_padding_horizontal'] ); ?>" />
					mm</label>
				</li>
			</ul>
		<?php
	}


	function setting_test_labels() {
		?>
		<p class="description">Enter a comma separated list of order ids to test your saved settings.</p>
		<ul>
			<li>
				<input type="text" id="test-labels-list" class="regular-text" name="<?php echo self::OPTION_NAME; ?>[test_labels]" value="<?php echo implode(",", wp_parse_id_list( $this->settings['test_labels'] ) ) ?>" pattern="^[0-9]+(,[1-8]+)*$"/>
				<button id="button-generate-labels" type="button" class="button">Generate test labels</button>
				<p class="description">(save changes before generating test labels)</p>
			</li>
		</ul>

		<script type="text/javascript" >
			var generate_labels_button = document.querySelector("#button-generate-labels");
			generate_labels_button.addEventListener('click', function() {
				
				// Show progress in button text.
				this.innerHTML = "Generating...";
				this.disabled = true;

				// Send AJAX request to plugin API to generate labels page.
				var data = {
					'action': 'sslabels_api',
					'orders': document.querySelector("#test-labels-list").value.split(","),
					'api_action': 'generate_labels',
					'_ajax_nonce': '<?php echo wp_create_nonce('generate_labels'); ?>'
				};


				// Since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php.
				jQuery.post(ajaxurl, data, function(response) {
					
					// Open new browser tab and set its content to the response labels page.
					var popup_window = window.open('', 'Shipping Labels');
					popup_window.document.write('<!DOCTYPE html><html>' + response + '</html>');		// Write the content of the document.
					popup_window.document.close();				// Finishes writing to a document.
					
					// Reset button to default text.
					generate_labels_button.innerHTML = "Generate test labels";
					generate_labels_button.disabled = false;

					// Try displaying the new window, but it might fail due to some pop-up blocker.
					try {
						popup_window.focus();
					} catch (e) {
						alert("Browser or extension pop-up blocker is enabled. Please add this site to your exceptions list for the generated labels page pop-up window.");
					}

				}).fail(function() {
					generate_labels_button.innerHTML = "Generate test labels";
					generate_labels_button.disabled = false;
					alert( "Could not generate labels." );
				});
				
			});
		</script>
		<?php
	}




	// ##############################
	// ### Recipient Settings Tab ###
	// ##############################
	function setting_recipient_details_layout() {
		$selected_details_layout = esc_html( $this->settings['recipient_details_layout'] );
		?>
		<div class="recipient-details-layout-label-samples-container">
		<?php
		// Output the radio buttons field, its options and set the selected option.
		foreach ($this->recipient_details_layout_options as $key => $value) {
			if ($key == $selected_details_layout) {
				echo '<div class="recipient-details-layout-label-sample"><input type="radio" id="' . $key . '-recipient-details-layout-option" name="' . self::OPTION_NAME . '[recipient_details_layout]" value="' . $key . '" checked="checked"><label for="' . $key . '-recipient-details-layout-option">' . $value . '</label></div>';
			} else {
				echo '<div class="recipient-details-layout-label-sample"><input type="radio" id="' . $key . '-recipient-details-layout-option" name="' . self::OPTION_NAME . '[recipient_details_layout]" value="' . $key . '"><label for="' . $key . '-recipient-details-layout-option">' . $value . '</label></div>';
			}
		}
		?>
		</div>
		<?php
	}


	function setting_recipient_details_align() {
		$selected_align_option = esc_html( $this->settings['recipient_align_fields'] );
		// Output the select field, its options and set the selected option.
		?>
		<select name="<?php echo self::OPTION_NAME; ?>[recipient_align_fields]">
			<?php
			foreach ($this->recipient_align_fields_options as $value) {
				if ($value == $selected_align_option) {
					echo '<option selected="selected" value="' . $value . '">' . $value. '</option>';
				} else {
					echo '<option value="' . $value . '">' . $value. '</option>';
				}
			}
			?>
		</select>
		<?php
	}

	function setting_recipient_show_company() {
		$checked_state = '';
		if ( !empty($this->settings['show_company']) && $this->settings['show_company'] == 'on' ) {
			$checked_state = ' checked';
		}
		?>
		<label for="show-company">
		<input id="show-company" name="<?php echo self::OPTION_NAME; ?>[show_company]" type="checkbox"<?php echo $checked_state; ?>/>Show company field if not empty</label>
		<p class="description"><b>Note:</b> this field might confuse the shipping carrier, if the company employee ships it to his personal address.</p>
		<?php
	}

	function setting_recipient_display_state_full_name() {
		$checked_state = '';
		if ( !empty($this->settings['display_state_full_name'] )  && $this->settings['display_state_full_name'] == 'on'  ) {
			$checked_state = ' checked';
		}
		?>
		<label for="display-state-full-name">
		<input id="display-state-full-name" name="<?php echo self::OPTION_NAME; ?>[display_state_full_name]" type="checkbox"<?php echo $checked_state; ?>/>Display state full name</label>
		<?php
	}

	function setting_recipient_hide_base_country() {
		$checked_state = '';
		if ( !empty($this->settings['hide_base_country'] )  && $this->settings['hide_base_country'] == 'on'  ) {
			$checked_state = ' checked';
		}
		?>
		<label for="hide-base-country">
		<input id="hide-base-country" name="<?php echo self::OPTION_NAME; ?>[hide_base_country]" type="checkbox"<?php echo $checked_state; ?>/>Hide country field when shipping to store base country</label>
		<p class="description">Store base country is a setting set in <b>WooCommerce</b> > <b>Settings</b> > <b>General</b> > <b>Store Address</b></p>
		<?php
	}

	function setting_recipient_show_phone() {
		$checked_state = '';
		if ( !empty($this->settings['show_phone']) && $this->settings['show_phone'] == 'on' ) {
			$checked_state = ' checked';
		}
		?>
		<label for="show-phone">
		<input id="show-phone" name="<?php echo self::OPTION_NAME; ?>[show_phone]" type="checkbox"<?php echo $checked_state; ?>/>Show phone field if not empty</label>
		<?php
	}

	function setting_recipient_use_billing_details() {
		$checked_state = '';
		if ( !empty($this->settings['use_billing_details']) && $this->settings['use_billing_details'] == 'on' ) {
			$checked_state = ' checked';
		}
		?>
		<label for="use-billing-details">
		<input id="use-billing-details" name="<?php echo self::OPTION_NAME; ?>[use_billing_details]" type="checkbox"<?php echo $checked_state; ?>/>Use billing details if shipping details are empty</label>
		<p class="description">Some themes hide the shipping details section of checkout form, leaving them empty in order details.</p>
		<?php
	}

	function setting_order_show_total() {
		$checked_state = '';
		if ( !empty($this->settings['show_order_total']) && $this->settings['show_order_total'] == 'on' ) {
			$checked_state = ' checked';
		}
		?>
		<label for="show-order-total">
		<input id="show-order-total" name="<?php echo self::OPTION_NAME; ?>[show_order_total]" type="checkbox"<?php echo $checked_state; ?>/>Show order total (some shipping carriers require this info)</label>
		<?php
	}


	// ##########################
	// ### Order Settings Tab ###
	// ##########################

	function setting_order_show_id() {
		$checked_state = '';
		if ( !empty($this->settings['show_order_id']) && $this->settings['show_order_id'] == 'on' ) {
			$checked_state = ' checked';
		}
		?>
		<label for="show-order-id">
		<input id="show-order-id" name="<?php echo self::OPTION_NAME; ?>[show_order_id]" type="checkbox"<?php echo $checked_state; ?>/>Show order id</label>
		<?php
	}


	  ///////////////////////////////
	 /// WooCommerce orders page ///
	///////////////////////////////
	/**
	 * Add custom column to WooCommerce orders table page.
	 */
	function add_print_buttons_column( $columns ) {
		$columns['shipping_label'] = __('Shipping Labels');
		return $columns;
	}

	/**
	 * Populate the column with print buttons.
	 */
	function add_print_buttons( $column ) {
		global $post;
		
		if ( $column === 'shipping_label' ) {
			$shipping_label_print_button_html = '<button type="button" class="button button-sslabels-print" ' 
			. 'value="' . $post->ID . '">'. __( 'Label' ) .'</button>';
			echo $shipping_label_print_button_html;
		}
	}


	/**
	 * Add JavaScript functionality to the "Print" buttons on the WooCommerce orders page.
	 * We enqueue the JavaScript script on that specific page (also known as the 'shop_order' screen).
	 * Then we pass it the plugin settings and additional style and script files paths to apply
	 * to the generated page of labels.
	 * We use wp_localize_script() to pass PHP variables to the enqueued JavaScript
	 */
	function add_admin_pages_scripts_and_styles( $hook ) {
		if ( ! is_user_logged_in() ) exit;
		
		$current_screen = get_current_screen();

		// Here we pass the stored options or default values to the orders page JavaScript,
		// for it to generate a shipping labels page with correct settings.
		if ( ($hook == 'edit.php' || $hook == 'post.php' ) && $current_screen->post_type == 'shop_order' ) {
			
			// Add JavaScript functionality to the main orders page,
			// for the Shipping Labels column 'Print' buttons to use.
			wp_enqueue_script( 'sslabels-script', plugins_url( '/js/woocommerce-orders.js', __FILE__ ) );

			// Get the plugin settings options for JavaScript to generate customized labels, and pass it
			// to the enqueued JavaScript sctipt.
			$labels_page_localized_js_object = array(
				'_ajax_nonce'					=> wp_create_nonce('generate_labels'),
				'button_name'			=> __( 'Label' ),
				'button_active_name'	=> __( 'Generating' ) . '...'
			);
			wp_localize_script( 'sslabels-script', 'sslabels', $labels_page_localized_js_object );
			
		// Enqueue settings page scripts and stylesheet.
		} else if ( !empty($_GET['page']) && $_GET['page'] == self::SETTINGS_PAGE_SLUG ) {
			wp_enqueue_style( 'sslabels-stylesheet', plugins_url( '/css/options-page-style.css', __FILE__ ) );
			wp_enqueue_script( 'sslabels-pro-settings-script',  plugins_url( '/js/settings-page.js', __FILE__ ) );
		}
	}


	  ///////////////////////////////////
	 /// AJAX callbacks (plugin API) ///
	///////////////////////////////////
	/**
	 * Plugin AJAX endpoint that acts as the plugin API for the following actions:
	 * - Generate and return labels page for a list of order ids.
	 * - Reset plugin setting - remove store plugin settings option for wp_options database table and set the $settings field to $default_settings.
	 */
	function sslabels_api() {

		// Security checklist:
		// 1) API action exists.
		// 2) Verify nonce.
		// 3) Check user capabilities.
		// 4) Sanitize and Validate input.
		// 5) Escape output.
		$allowed_api_actions = [ 'generate_labels', 'reset_options' ];

		// If no 'api_action' or nonces exist in requesy - exit immediately.
		if ( empty( $_REQUEST['api_action'] ) || empty( $_REQUEST['_ajax_nonce'] ) ) {
			exit;
		}

		// Do the correct api action.
		switch($_REQUEST['api_action']) {
			
			case 'generate_labels':
				if (wp_verify_nonce( $_REQUEST['_ajax_nonce'], 'generate_labels' ) && 
					current_user_can('edit_posts') && 
					!empty($_POST['orders']) ) {
					
					// Sanitize an array or comma/space-separated list of IDs (WooCommerce orders IDs in this case).
					$orders = wp_parse_id_list( $_POST['orders'] );		// Uses absint() to convert a value to non-negative integer.
					
					// Generate response HTML or catch internal exceptions.
					try {
					$this->generate_shipping_labels_page_html( $orders );
					} catch (Throwable $e) {
						?>
						<div class="error-container">
							<div class="error-title">&nbsp; &#x26A0; <b>Oops, cought Error or Exception</b> (• - • ) &nbsp;</div>
							<div class="error-content"><?php echo $e->getMessage(); ?></div>
						</div>
						<?php
					}
				}
				break;
			
			case 'reset_options':
				if (wp_verify_nonce( $_REQUEST['_ajax_nonce'], 'reset_options' ) && current_user_can('manage_options') ) {
					delete_option( self::OPTION_NAME );
				}
				break;
			default:
				wp_die();
		}
		
		wp_die(); // This is required to terminate immediately and return a proper response.
	}


	  //////////////////////////////////////////////////////
	 /// Plugin activation, version control and updates ///
	//////////////////////////////////////////////////////

	/**
	 * Load plugin option stored in dastabase (array of settings), check plugin version and run update procedures if necessary.
	 */
	function load_plugin_settings() {
		
		// Look for a stored plugin option in database (this option is an array/dictionary of plugin settings).
		$stored_option = get_option( self::OPTION_NAME );
		
		// If option exists in database - check the version.
		if ($stored_option) {
			
			// Compare the default/required plugin settings to the stored settings.
			if (!empty(array_diff_key($this->default_settings, $stored_option))) {
				// We reach this section only if the stored plugin option found in database doesn't have all the keys as the default settings array.
				// So in this section we can run any version related update procedures.

				// We create a new options array to store in database, by looping over the default settings (which current plugin version requires),
				// add the ones that exist in stored options, then add the ones that don't from current version default settings.
				$new_option_to_save = array();
				foreach ($this->default_settings as $key => $value) {
					if (array_key_exists($key, $stored_option)) {
						$new_option_to_save[$key] = $stored_option[$key];
					} else {
						$new_option_to_save[$key] = $value;
					}
				}
				
				// Since 'plugin_version' exists in $stored_option - it is set in foreach loop above, so we update the value here.
				$new_option_to_save['plugin_version'] = self::PLUGIN_VERSION;

				// Set the settings variable for the plugin to use the updated option.
				$this->settings = $new_option_to_save;

				// Update the database option.
				update_option( 'self::OPTION_NAME', $new_option_to_save);
			
			// If all required settings are present - set the settings variable for the plugin to use the stored option.
			} else {
				$this->settings = $stored_option;
			}

		// Else - no option found in database, so just use the plugin default settings.
		} else {
			$this->settings = $this->default_settings;
		}
	}


	  /////////////////////////////////////////
	 /// Client labels page pop-up content ///
	/////////////////////////////////////////
	/**
	 * If a string contains Hebrew/Arabic characters - return a CSS class that is
	 * used to style rtl fields.
	 */
	function rtl_css_class($str) {
		$pattern = '/[\x{0591}-\x{07FF}\x{FB1D}-\x{FDFD}\x{FE70}-\x{FEFC}]/u';

		if ( preg_match($pattern, $str) )
		{
			return " rtl";
		} else {
			return "";
		}
	}


	/**
	 * Output HTML for shipping labels page.
	 * The HTML is used as the content of a client pop-up tab/window, 
	 * created by JavaScript in client browser, for the client to print.
	 */
	function generate_shipping_labels_page_html( $orders ) {

		// SVG favicon and light/dark theme handling based on: https://medium.com/swlh/are-you-using-svg-favicons-yet-a-guide-for-modern-browsers-836a6aace3df
		$labels_page_favicon_url = plugins_url( '/assets/favicon.svg', __FILE__ );
		?>
		<head>
		<title>Shipping Labels</title>
		<link rel="icon" href="<?php echo $labels_page_favicon_url ?>">
		<style>
		/* Page style */
		body {
			background-color: rgb(210, 214, 220);
			margin: 0px;
			padding: 0px;
		}

		/* Label style */
		.shipping-label {
			position: relative;
			box-sizing: border-box;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			width: <?php echo esc_html( $this->settings['label_width'] ) ?>mm;
			height: <?php echo esc_html( $this->settings['label_height'] ) ?>mm;
			padding: <?php echo esc_html( $this->settings['label_padding_vertical'] ) . "mm " . esc_html( $this->settings['label_padding_horizontal'] ) ."mm" ?>;
			margin: 20px;
			border-radius: 3mm;
			background-color: white;
			box-shadow: 0 1px 2px 0 rgba(60,64,67,.3), 0 1px 3px 1px rgba(60,64,67,.15);
			font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
		}

		/* Support for WordPress image alignment classes */
		.alignleft {
			float: left;
		}

		.alignright {
			float: right;
		}

		.aligncenter {
			display: block;
			margin-left: auto;
			margin-right: auto;
		}

		.recipient-container {
			flex: 1;
			width: 100%;
			display: flex;
			flex-direction: column;
			align-items: center;
			overflow: hidden;
		}

		.detail-container {
			flex: 1;
			box-sizing: border-box;
			display: flex;
			align-items: center;
			width: 100%;
			border: none;
			padding: 0px;
			margin: 0px;
			background-color: transparent;
			overflow: hidden;
		}

		.detail {
			background-color: transparent;
			box-sizing: border-box;
			overflow: hidden;
			resize: none;
		}

		[contenteditable]:focus {
			outline: none;
			background-color: rgb(195,207,221);
			color: black;
		}

		/* Customer details style */
		<?php
		// Customer fields alignment.
		$selected_align = $this->settings['recipient_align_fields'];
		if ($selected_align == "left") { 
			echo '.detail { text-align: left; } .detail-container { justify-content: flex-start; }';
		} else if ($selected_align == "right") { 
			echo '.detail { text-align: right; } .detail-container { justify-content: flex-end; }';
		} else {
			echo '.detail { text-align: center; } .detail-container { justify-content: center; }';
		}
		?>

		.rtl {
			direction: rtl;
		}

		.name {
			flex: 1.5;
			font-weight: bold;
		}

		.address {
			flex: 1.5;
		}

		.country {
			font-weight: bold;
		}

		.phone {
			align-items: baseline;
		}

		.order-id {
			vertical-align: text-top;
			height: fit-content;
			width: fit-content;
			position: absolute;
			right: 15px;
			bottom: 10px;
			font-size: 16px;
		}

		.error-container {
			width: 100%;
			overflow: hidden;
			background-color: #d52b57;
			border-radius: 15px;
		}

		.error-title {
			padding: 3px;
			background-color: #811a41;
			text-align: center;
			color: white;
		}

		.error-content {
			padding: 5px;
			color: white;
		}

		/*** Print style - hide redundant elements and styles for print ***/
		@media print {
			body {
				background-color: initial;
			}

			.shipping-label {
				page-break-after: always;
				border: none;
				border-radius: initial;
				box-shadow: none;
				margin: 0px;
			}
		}
		</style>
		</head>
		<body>
		<?php
		
		// Get store base country to check whether a given order shipping is local or international.
		$store_base_country = WC()->countries->get_base_country();

		// Declare localized and international field labels dictionaries, to be used based on each order shipping destination.

		// Translated labels to current site locale.
		$field_to_localized_label = array(
			'order_total'	=> __("Order Total", 'woocommerce')
		);

		// International (original/English) labels.
		$field_to_international_label = array(
			'order_total'	=> "Order Total",
		);

		$field_to_label = null;


		// Generate shipping label HTML for each order_id.
		foreach ($orders as $order_id)
		{
			// Try to get the WooCommerce order corresponding to the current order id (instance of WC_Order object).
			$order = wc_get_order( $order_id );
			
			// If current order id doesn't exist - continue to generating next order label.
			if (!$order) {
				continue;
			}

			// Get the customer shipping information details.
			$order_number		= $order->get_order_number();
			$order_first_name	= $order->get_shipping_first_name();
			$order_last_name	= $order->get_shipping_last_name();
			$order_company		= $order->get_shipping_company();
			$order_address_1	= $order->get_shipping_address_1();
			$order_address_2	= $order->get_shipping_address_2();
			$order_city			= $order->get_shipping_city();
			$order_state		= $order->get_shipping_state();
			$order_postcode		= $order->get_shipping_postcode();
			$order_country		= $order->get_shipping_country();
			$order_phone		= $order->get_shipping_phone();

			// Temporary fix - WooCommerce 5.6 introduced get_shipping_phone() method, but doesn't duplicate billing phone field to shipping phone field
			// (as it does for other fields) and themes don't yet expose the shipping phone field when "Ship to a different address?" checkbox in chechout is checked.
			// So we still use the billing phone if shipping phone is empty.
			if (empty($order_phone)) {
				$order_phone = $order->get_billing_phone();
			}

			if ( !empty($this->settings['use_billing_details']) && $this->settings['use_billing_details'] == 'on' && empty($order_first_name) ) {
				$order_first_name	= $order->get_billing_first_name();
				$order_last_name	= $order->get_billing_last_name();
				$order_company		= $order->get_billing_company();
				$order_address_1	= $order->get_billing_address_1();
				$order_address_2	= $order->get_billing_address_2();
				$order_city			= $order->get_billing_city();
				$order_state		= $order->get_billing_state();
				$order_postcode		= $order->get_billing_postcode();
				$order_country		= $order->get_billing_country();
				$order_phone		= $order->get_billing_phone();
			}
			
			// For international shipping (shipping country != store base country)
			// get shipping country and state in English, by temporary switching WordPress environment locale.
			$is_shipping_to_base_country = $store_base_country === $order_country;
			
			// Declare result containers.
			$country;
			$state;

			// If shipping to local address - use default WordPress $locale='default' language for local country, state and "Order total" field string.
			if ( $is_shipping_to_base_country ) {
				
				// Get full country name.
				$country = WC()->countries->countries[$order_country];
				
				// Get full state name (if option 'display_state_full_name' in on), otherwise use order state abbreviation.
				if ( !empty($this->settings['display_state_full_name']) && $this->settings['display_state_full_name'] == 'on' ) {
					$state  = !empty(WC()->countries->get_states($order_country)) ? WC()->countries->get_states($order_country)[$order_state] : '';
				} else {
					$state = $order_state;
				}
				
				// Use localized field labels.
				$field_to_label = $field_to_localized_label;

			// Else - switch to $locale='en_US' to generate international shipping labels in English. 
			} else {
				// Switch locale (WordPress language for translations) to get countries list in English, using WordPress locale_switcher.
				$locale_switched = switch_to_locale( 'en_US' );
				
				// Get translated full country name, using WooCommerce countries.php file array.
				$countries = include WC()->plugin_path() . '/i18n/countries.php';
				$country = $countries[$order_country];

				// Get translated full state name (if option 'display_state_full_name' in on) using WooCommerce states.php file array,
				// otherwise use order state abbreviation.
				if ( !empty($this->settings['display_state_full_name']) && $this->settings['display_state_full_name'] == 'on' ) {
					$state  = !empty(WC()->countries->get_states($order_country)) ? WC()->countries->get_states($order_country)[$order_state] : '';
					
					// Alternative way to get states:
					//$states = include WC()->plugin_path() . '/i18n/states.php';
					//$state = !empty($states[$order_country]) ? $states[$order_country][$order_state] : '';
				} else {
					$state = $order_state;
				}
				
				// When we are done, restore the initial WordPress locale.
				restore_current_locale();

				// Use international field labels.
				$field_to_label = $field_to_international_label;
			}


			/* Collect label CSS classes to set */
			// Very useful for custom CSS per local/international shipping, conditional display based on payment/shipping methods, etc.

			// Get shipping method id.
			// Note: not sure why it's a list, as if order can have multiple shipping methods.
			$shipping_methods_names = array();
			foreach ( $order->get_shipping_methods() as $shipping_method ) {
				$shipping_methods_names[] = $shipping_method->get_method_id();
			}
			$shipping_method_css_class = implode( '-', $shipping_methods_names );
			
			// Check if the order destination is local or international to set a discriminator CSS class.
			$destination_css_class = ( $is_shipping_to_base_country )? "local-shipping-label" : "international-shipping-label";

			// Define label CSS classes.
			$label_css_classes = array(
				"shipping-label",	// Core label CSS class.
				$destination_css_class,		// Local vs international label discriminator CSS class.
				$order->get_payment_method() . "-payment-method-label",		// Payment method discriminator CSS class.
				$shipping_method_css_class. "-shipping-method-label"	// Shipping method discriminator CSS class.
			);

			// Generate current order shipping label element and append to the HTML result string.
			// To support Right-to-Left data, we asign ".rtl" class (for css direction/aligning styles) to all fields
			// containing RTL characters. To find those characters, we use Regex (regular expression) patterns in
			// $this->rtl_css_class() function.
			?>
			<div class="<?php echo implode(" ", $label_css_classes) ?>">
				<div class="recipient-container">
					<div class="detail-container name"><div contenteditable="true" class="detail<?php echo $this->rtl_css_class($order_first_name) ?>"><?php echo esc_html( $order_first_name ) . '&nbsp;' . $order_last_name ?></div></div>
					<?php
					// Echo company if option is set to show and not empty.
					if ( $this->settings['show_company'] == "on" && !empty($order_company) ) {
						echo '<div class="detail-container company"><div contenteditable="true" class="detail' . $this->rtl_css_class($order_company) . '">' . esc_html( $order_company ) . '</div></div>';
					}
					?>
					<div class="detail-container address"><div contenteditable="true" class="detail<?php echo $this->rtl_css_class($order_address_1) ?>"><?php echo esc_html( $order_address_1 ) . ( (strlen($order_address_2) == 0) ? "" : "&nbsp;" . esc_html( $order_address_2 ) ) ?></div></div>
					<?php 
					
					// Output address in one of the available layouts options.
					switch ( $this->settings['recipient_details_layout'] ) {
						case "default":
							echo '<div class="detail-container city"><div contenteditable="true" class="detail' . $this->rtl_css_class($order_city) . '">' . esc_html( $order_city ) . ( (strlen($state) == 0) ? '' : ',&nbsp;' . esc_html( $state ) ) . '&nbsp;' . esc_html( $order_postcode ) . '</div></div>';
							break;
						case "separate_lines":
							echo '<div class="detail-container city"><div contenteditable="true" class="detail' . $this->rtl_css_class($order_city) . '">' . esc_html( $order_city ) . '</div></div>';
							if ( !empty($state) ) {
								echo '<div class="detail-container state"><div contenteditable="true" class="detail' . $this->rtl_css_class($state) . '">' . $state . '</div></div>';
							}
							echo '<div class="detail-container postcode"><div contenteditable="true" class="detail">' . esc_html( $order_postcode )  . '</div></div>';
							break;
						case "post_code_first":
							echo '<div class="detail-container city"><div contenteditable="true" class="detail' . $this->rtl_css_class($order_city) . '">' . esc_html( $order_postcode ) . '&nbsp;' . esc_html( $order_city ) . ( (strlen($state) == 0) ? '' : ',&nbsp;' . esc_html( $state ) ) . '</div></div>';
							break;
						case "post_code_first_separate_lines":
							echo '<div class="detail-container postcode"><div contenteditable="true" class="detail">' . esc_html( $order_postcode )  . '</div></div>';
							echo '<div class="detail-container city"><div contenteditable="true" class="detail' . $this->rtl_css_class($order_city) . '">' . esc_html( $order_city ) . '</div></div>';
							if ( !empty($state) ) {
								echo '<div class="detail-container state"><div contenteditable="true" class="detail' . $this->rtl_css_class($state) . '">' . $state . '</div></div>';
							}
							break;
					}

					// Echo country if option is not set to hide base country (in case it's a base country).
					if (!$is_shipping_to_base_country || ($is_shipping_to_base_country && !empty($this->settings['hide_base_country']) && $this->settings['hide_base_country'] == 'off') ) {
						echo '<div class="detail-container country' . $this->rtl_css_class($country) . '"><div contenteditable="true" class="detail">' . esc_html( $country ) . '</div></div>';
					}

					// Echo phone if option is set to show and not empty.
					if ( $this->settings['show_phone'] == "on" && !empty($order_phone) ) {
						echo '<div class="detail-container phone"><div contenteditable="true" class="detail"> &#9742; ' . esc_html( $order_phone ) . '</div></div>';
					}

					// Echo order total if option is set to show.
					if ( $this->settings['show_order_total'] == "on" ) {
						echo '<div class="detail-container total"><div contenteditable="true" class="detail' . $this->rtl_css_class($field_to_label['order_total']) . '"> ' . $field_to_label['order_total'] . ': ' . $order->get_formatted_order_total() . '</div></div>';
					}

					?>
				</div>
				<?php

				// Order id.
					if ($this->settings['show_order_id'] == "on") {
						echo '<div class="order-id" contenteditable="true">' . esc_html( $order_number ) . '</div>';
					}
				?>
			</div>
			<?php
		}
		?>
		<script>
			document.addEventListener('DOMContentLoaded', simpleShippingLabelsPageOnLoaded);
			
			// Workaround for Chrome to display icon in this new blank tab.
			// Note: assign the attribute to itself works as well, the idea is to force refresh the attribute
			// once page is loaded, but it only works with timeout for some reason. Maybe running function as
			// delegate will work as well.
			setTimeout(function () {
				document.querySelector("link[rel~='icon']").href = "<?php echo $labels_page_favicon_url ?>";
			}, 0);

			/**
			 * This function adds custom 'input' event to trigger auto text fitting script
			 * when user edits any label field. It also triggers the attached event to intiaily 
			 * fit text in each detail field.
			 */
			function simpleShippingLabelsPageOnLoaded() {
				var details = document.querySelectorAll('.detail');
				var event = new Event('input', {
					bubbles: true,
					cancelable: true,
				});
				for (let i = 0; i < details.length; i++) {
					details[i].addEventListener('paste', simpleShippingLabelsPasteHandler);
					details[i].addEventListener('input', simpleShippingLabelsFitInput);
					details[i].dispatchEvent(event);
				}

				var orderId = document.querySelector('.order-id');
				if (orderId != null) {
					orderId.addEventListener('paste', simpleShippingLabelsPasteHandler);
				}

				<?php
				if ( !empty($this->settings['auto_open_print_dialog']) && $this->settings['auto_open_print_dialog'] == 'on' ) {
					echo 'window.print();';
				}
				?>
			}


			/**
			 * Handle pasting formatted text by pasting as plain text.
			 * This prevents the fit input function from freaking out and freezing the window.
			 */
			function simpleShippingLabelsPasteHandler(e) {
				
				// Prevent default paste (might by formatted text).
				e.preventDefault();

				// Get text representation of clipboard.
				let pasted_text = (event.clipboardData || window.clipboardData).getData('text');

				// Insert text manually (this command maintaince input history).
				document.execCommand("insertText", true, pasted_text);
			}

			/**
			 * This function tests elements content overflow/available space to grow and adjusts
			 * the font size accordingly.
			 */
			function simpleShippingLabelsFitInput() {
				while ( (this.innerText.length > 0) && (this.clientWidth < this.parentElement.clientWidth) && (this.clientHeight < this.parentElement.clientHeight) ) {
					this.style.fontSize = parseFloat(window.getComputedStyle(this).fontSize) + 0.3 + 'px';
				}
				while ( (this.clientWidth > this.parentElement.clientWidth) || (this.clientHeight > this.parentElement.clientHeight) ) {
					this.style.fontSize = parseFloat(window.getComputedStyle(this).fontSize) - 0.3 + 'px';
				}
			}
		</script>
		</body>
		<?php
	}

}
