<?php

namespace AustraliaPost\License;

class Handler
{
    private $redirect_url = '';
    private $return_url = '';
    private $plugin_name = '';
    private $store_url = 'https://wpruby.com';
    private $licence_option_key = '';

	/**
	 * WPRuby_AustraliaPost_Licence_Handler constructor.
	 *
	 * @param $licence_option_key
	 */
	public function __construct($licence_option_key)
    {
        $this->licence_option_key = $licence_option_key;
        $this->store_url = WPRUBY_AUPOST_STORE_URL;
        $this->setReturnUrl();
        add_action('admin_menu', array($this,'register_activation_page'));
        if (get_option($this->licence_option_key . '_license_status') != 'valid') {
            update_option($this->licence_option_key, '123456789');
            update_option($this->licence_option_key . '_license_status', 'valid');
        }
    }


	/**
	 *
	 */
	public function register_activation_page()
    {
        $license_page_slug = str_replace(' ', '-', strtolower($this->plugin_name)). '-activation';
        add_submenu_page(
                  null,   //or 'options.php'
            $this->plugin_name . ' Activation',
            $this->plugin_name . ' Activation',
            'manage_options',
            $license_page_slug,
            array($this,'activation_page_callback')
        );
        add_action('admin_init', array($this,'register_activation_settings'));
    }



    public function register_activation_settings()
    {
        register_setting('wpruby-settings-group', $this->licence_option_key);
    }

    public function setPluginName($plugin_name='')
    {
        $this->plugin_name = $plugin_name;
        $this->redirect_url = admin_url('admin.php?page=' . str_replace(' ', '-', strtolower($this->plugin_name)). '-activation');
    }

	/**
	 * @param string $return_url
	 */
	private function setReturnUrl($return_url='')
    {
        if (function_exists("WC")) {
            if (version_compare(WC()->version, '2.6.0', 'lt')) {
                $this->return_url = $return_url;
            } else {
                if (isset($_GET['instance_id'])) {
                    $this->return_url = admin_url('admin.php?page=wc-settings&tab=shipping&instance_id=' . $_GET['instance_id']);
                } else {
                    $this->return_url = $return_url;
                }
            }
        } else {
            $this->return_url = $return_url;
        }
    }

	/**
	 * @return bool
	 */
	public function verify_key()
    {
        $license = esc_attr(get_option($this->licence_option_key));

        // data to send in our API request
        $api_params = array(
            'edd_action'=> 'activate_license',
            'license' 	=> $license,
            'item_name' => urlencode($this->plugin_name), // the name of our product in EDD,
            'url'       => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post($this->store_url, array(
            'timeout'   => 15,
            'sslverify' => false,
            'body'      => $api_params
        ));
        // make sure the response came back okay
        if (is_wp_error($response)) {
            return false;
        }
        // decode the license data
        $license_data = json_decode(wp_remote_retrieve_body($response));
        // $license_data->license will be either "active" or "inactive"
        update_option($this->licence_option_key .'_license_status', $license_data->license);
        if ($license_data->license == 'valid') {
            echo '<div id="message" class="updated fade"><p><strong>' . 'The plugin has been activated, Thank you :) <br>You can return to the <a href="'. $this->return_url .'">'.$this->plugin_name.' Settings</a>' . '</strong></p></div>';
	        return true;
        } else {
            echo '<div id="message" class="error fade"><p><strong>' . 'Your licence Key is not valid. If there is any problem, please contact the <a href="https://wpruby.com/submit-ticket/">WPRuby support</a>.' . '</strong></p></div>';
        }

	    return false;
    }
    public function activation_page_callback()
    {
        ?>
			<div class="wrap columns-2">
				<h2><?php echo $this->plugin_name; ?> Activation</h2>
				<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div class="postbox">
					<h3 class="hndle">Licence Activation</h3>
                        <hr>
						<div class="inside">
							<form method="post" id="mainform" action="options.php">
							<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']==true) {
            $this->verify_key();
        } ?>
							 	<?php settings_fields('wpruby-settings-group'); ?>
								<table class="form-table">
									<tbody>
									<tr>
										<td valign="top" style="width:150px;">Licence Key</td>
										<td valign="top">
											<fieldset>
												<input class="input-text regular-input" name="<?php echo $this->licence_option_key; ?>" autocomplete="off" style="width: 450px;height: 35px;padding: 10px;" value="<?php echo esc_attr(get_option($this->licence_option_key)); ?>">
												<p class="description">If you purchased the plugin, you'll find the key in the confirmation email, If you lost it, you can <a href="https://wpruby.com/checkout/purchase-history/">restore your license keys</a>.</p>
											</fieldset>
										</td>
									</tr>
									</tbody>
								</table>
								<?php submit_button(); ?>
							</form>
						</div>
					</div>
				</div>
				<div id="postbox-container-1" class="postbox-container">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
                            <div class="postbox ">
                                <h3 class="hndle"><span><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Plugin Support</span></h3>
                                <hr>
                                <div class="inside">
                                    <div class="support-widget">
                                        <p>
                                        <img style="width:100%;" src="https://wpruby.com/wp-content/uploads/2016/03/wpruby_logo_with_ruby_color-300x88.png">
                                        <br/>
                                            Top Notch WordPress Plugins!</p>
                                        <ul>
                                            <li>» <a href="https://wpruby.com/submit-ticket/" target="_blank">Support Request</a></li>
                                            <li>» <a href="https://wpruby.com/knowledgebase_category/<?php echo str_replace(' ', '-', strtolower($this->plugin_name)); ?>/" target="_blank">Documentation and Common issues.</a></li>
                                            <li>» <a href="https://wpruby.com/plugins/" target="_blank">Our Plugins Shop</a></li>
                                        </ul>

                                    </div>
                                </div>
                            </div>

                            <div class="postbox rss-postbox">
    								<h3 class="hndle"><span><i class="fa fa-wordpress"></i>&nbsp;&nbsp;WPRuby Blog</span></h3>
                                <hr>
                                <div class="inside">
										<div class="rss-widget">
											<?php
                                                wp_widget_rss_output(array(
                                                        'url' => 'https://wpruby.com/feed/',
                                                        'title' => 'WPRuby Blog',
                                                        'items' => 3,
                                                        'show_summary' => 0,
                                                        'show_author' => 0,
                                                        'show_date' => 1,
                                                )); ?>
    									</div>
    								</div>
    						</div>

                        </div>
                    </div>
                    </div>
				</div>

			</div>
		<?php
    }
}
