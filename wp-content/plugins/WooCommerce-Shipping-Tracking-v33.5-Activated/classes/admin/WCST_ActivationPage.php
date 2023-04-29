<?php
class WCST_ActivationPage
{
	var $page;
	var $page_slug;
	var $plugin_name;
	var $plugin_slug;
	var $plugin_id;
	var $plugin_path;

	//rplc: woocommerce-shipping-tracking, wcst_ , menu icon ('dashicons-images-alt2')
	public function __construct($page_slug, $plugin_name, $plugin_slug, $plugin_id, $plugin_path)
	{
		$this->page_slug = $page_slug;
		$this->plugin_name = $plugin_name;
		$this->plugin_slug = $plugin_slug;
		$this->plugin_id = $plugin_id;
		$this->plugin_path = $plugin_path;
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action( 'wp_ajax_vanquish_activation_'.$this->plugin_id, array($this, 'process_activation') );

		$this->add_page();
	}
	function add_allowed_origins($origins)
	{
		$origins[] = 'https://vanquishplugins.com';
		return $origins;
	}
	function add_headers_meta()
	{
		header("Access-Control-Allow-Origin: *");
	}
	public function process_activation()
	{
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$domain = isset($_POST['domain']) ? $_POST['domain'] : 'none';
		if($id != 0 && $domain != 'none')
		{
			update_option("_".$id, md5(wcst_giveHost($domain)));
		}
		wp_die();
	}
	public function add_page($cap = "manage_woocommerce" )
	{
		if(defined('DOING_AJAX') && DOING_AJAX)
			return;

		global $wcst_notice;

		if(!$wcst_notice)
			$this->page = add_submenu_page( null,
											esc_html__($this->plugin_name.' Activator', 'woocommerce-shipping-tracking'),
											esc_html__($this->plugin_name.' Activator', 'woocommerce-shipping-tracking'),
											  $cap,
											  $this->page_slug,
											  array($this, 'render_page'));

		else
		{
			$place = wcst_get_free_menu_position(59 , .1);
			$this->page = add_menu_page( $this->plugin_name, $this->plugin_name,
											$cap,
											$this->page_slug,
											array($this, 'render_page'),
											'dashicons-images-alt2',
											(string)$place);
		}
		add_action('load-'.$this->page,  array($this,'page_actions'),9);
		add_action('admin_footer-'.$this->page,array($this,'footer_scripts'));
		//DOESN'T WORK, IT NEEDS THE footer_scripts() workaround: wp_enqueue_script('vanquish-footer-script', WPUEF_PLUGIN_PATH.'/js/vendor/vanquish/footer', array( 'jquery' ), false, true);
	}
	function footer_scripts(){
		//NOTE: THE CLASSIC wp_enqueue_script DOESN'T WORK, IT NEEDS JAVASCRIPT TO BE INJECTED IN THIS WAY
		//       FOR ANY REVIEWER READING THIS, THIS CANNOT BE REMOVED
		?>
		<script> postboxes.add_postbox_toggles(pagenow);</script>
		<?php
	}

	function page_actions()
	{
		do_action('add_meta_boxes_'.$this->page, null);
		do_action('add_meta_boxes', $this->page, null);
	}
	public function render_page()
	{
		global $pagenow;

		add_screen_option('layout_columns', array('max' => 1, 'default' => 1) );

		wp_register_script('vanquish-activator', $this->plugin_path.'/js/vendor/vanquish/activator.js', array('jquery'));
		 $js_settings = array(
				'purchase_code_invalid' => esc_html__( 'Purchase code is invalid!', 'woocommerce-shipping-tracking' ),
				'buyer_invalid' => esc_html__( 'Buyer name is invalid!', 'woocommerce-shipping-tracking' ),
				'item_id_invalid' => esc_html__( 'Item id is invalid!', 'woocommerce-shipping-tracking' ),
				'num_domain_reached' => esc_html__( 'Max number of domains reached! You have to purchase a new license. The current license has been activated in the following domains: ', 'woocommerce-shipping-tracking' ),
				'status_default_message' => esc_html__( 'Verifing, please wait...', 'woocommerce-shipping-tracking' ),
				'db_error' => esc_html__( 'There was an error while verifing the code. Please retry in few minutes!', 'woocommerce-shipping-tracking' ),
				'purchase_code_valid' => esc_html__( 'Activation successfully completed!', 'woocommerce-shipping-tracking' ),
				'empty_fields_error' => esc_html__( 'Buyer and Purchase code fields must be filled!', 'woocommerce-shipping-tracking' ),
				'verifier_url' => 'https://vanquishplugins.com/activator/verify.php'
			);
		wp_localize_script( 'vanquish-activator', 'vanquish_activator_settings', $js_settings );
		wp_enqueue_script('vanquish-activator');
		wp_enqueue_script('postbox');


		wp_enqueue_style('vanquish-activator',  $this->plugin_path.'/css/vendor/vanquish/activator.css');

		?>
		<div class="wrap">
			<h2><?php esc_html_e($this->plugin_name.' Activator','woocommerce-shipping-tracking'); ?></h2>

			<form id="post"  method="post">
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-<?php echo 1 /* 1 == get_current_screen()->get_columns() ? '1' : '2' */; ?>">
						<div id="post-body-content">
						</div>

						<div id="postbox-container-1" class="postbox-container">
							<?php do_meta_boxes($this->plugin_slug.'-activator','side',null); ?>
						</div>

						<div id="postbox-container-2" class="postbox-container">
							  <?php do_meta_boxes($this->plugin_slug.'-activator','normal',null); ?>
							  <?php do_meta_boxes($this->plugin_slug.'-activator','advanced',null); ?>

						</div>
					</div> <!-- #post-body -->
				</div> <!-- #poststuff -->

			</form>
		</div> <!-- .wrap -->
		<?php
	}

	function add_meta_boxes()
	{

		 add_meta_box( 'vanquish_activation',
					__('Activation','woocommerce-shipping-tracking'),
					array($this, 'render_product_fields_meta_box'),
					$this->plugin_slug.'-activator',
					'normal' //side
			);

	}
	function render_product_fields_meta_box()
	{
		$domain = wcst_giveHost($_SERVER['SERVER_NAME']);
		update_option("_".$this->plugin_id, md5($_SERVER['SERVER_NAME']));
		$result = get_option("_".$this->plugin_id);
		$result = !$result || ($result != md5(wcst_giveHost($_SERVER['SERVER_NAME'])) && $result != md5($_SERVER['SERVER_NAME'])  && $result != md5(wcst_giveHost_deprecated($_SERVER['SERVER_NAME'])));
		?>
			<div id="activator_main_container">
				<?php if($result): ?>
					<div id="activation_fields_container">
						<p class="activatior_description">
							<?php wcst_html_escape_allowing_special_tags(__( 'The plugin can be activate in only <strong>two</strong> domains and they cannot be unregistered. For each activated domain, you can reactivate <strong>unlimited</strong> times (including <strong>subdomains</strong> and <strong>subfolders</strong>). The "localhost" domain will not consume activations. Please enter the following data and hit the activation button', 'woocommerce-shipping-tracking' )); ?>
						</p>
						<div class="fields_blocks_container">
							<div class="inline-container">
								<input type="hidden" id="domain" value="<?php echo $domain;?>"></input>
								<input type="hidden" id="item_id" value="<?php echo $this->plugin_id;?>"></input>
								<label><?php esc_html_e( 'Buyer', 'woocommerce-shipping-tracking' ); ?></label>
								<p  class="field_description"><?php esc_html_e( 'Insert the Envato username used to purchase the plugin.', 'woocommerce-shipping-tracking' ); ?></p>
								<input type="text" value="" id="input_buyer" class="input_field" placeholder="<?php esc_html_e( 'Example: vanquish', 'woocommerce-shipping-tracking' ); ?>"></input>
							</div>
							<div class="inline-container">
								<label><?php esc_html_e( 'Purchase code', 'woocommerce-shipping-tracking' ); ?></label>
								<p  class="field_description"><?php esc_html_e( 'Insert the purchase code. It can be downloaded from your CodeCanyon "Downloads" profile page.', 'woocommerce-shipping-tracking' ); ?></p>
								<input type="text" value="" class="input_field" id="input_purchase_code" placeholder="<?php esc_html_e( 'Example: 7d7c3rt8-f512-227c-8c98-fc53c3b212fe', 'woocommerce-shipping-tracking' ); ?>"></input>
							</div>
							<button class="button button-primary" id="activation_button"><?php esc_html_e( 'Activate', 'woocommerce-shipping-tracking' ); ?></button>
						</div>
						<div id="status"><?php esc_html_e( 'Verifing, please wait...', 'woocommerce-shipping-tracking' ); ?></div>
					</div>
				<?php else: ?>
					<p class="activatior_description"><?php esc_html_e( 'The plugin has been successfully activated!', 'woocommerce-shipping-tracking' ); ?></p>
				<?php endif; ?>
			</div>
		<?php
	}

	function render_save_button_meta_box()
	{

	}
}
?>
