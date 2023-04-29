<h3><?php _e( 'Australia Post Settings', 'woocommerce-australia-post-pro' ); ?></h3>
<?php if ( $this->debug_mode == 'yes' ): ?>
    <div class="updated woocommerce-message">
        <p><?php _e( 'Australia Post debug mode is activated, only administrators can use it.', 'woocommerce-australia-post-pro' ); ?></p>
    </div>
<?php endif; ?>
<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <table class="form-table">
				<?php echo $this->get_admin_options_html(); ?>
			</table><!--/.form-table-->
		</div>
		<div id="postbox-container-1" class="postbox-container wpruby-widgets">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                    <?php if(!class_exists(\AustraliaPost\Extensions\Business\API\Business::class)): ?>
                        <a href="https://bit.ly/mpbpartner" target="_blank">
                            <img style="width: 100%;" src="https://cdn.wpruby.com/wp-content/uploads/2016/03/11094511/newsletter.png" alt="MyPost Business Offer">
                        </a>
                        <br>
                        <br>
                    <?php endif; ?>
                    <?php if (! isset($_GET['instance_id'])): ?>
                    <div class="postbox note">
                        <h3 class="hndle"><span class="dashicons dashicons-info-outline"></span>&nbsp;&nbsp;Note!</h3>
                        <hr>
                        <div class="inside">
                            <div class="support-widget">
                                <p>This is the global settings page for Australia Post, to enable Australia Post shipping method, please assign it to one or more shipping zones.</p>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>

				<?php do_action( 'wpruby_australia_post_promotion_widget_before' ); ?>

                <div class="postbox ">
                    <h3 class="hndle"><span class="dashicons dashicons-editor-help"></span>&nbsp;&nbsp;<?php _e( 'Plugin Support', 'woocommerce-australia-post-pro' ); ?></h3>
                    <hr>
                    <div class="inside">
                        <div class="support-widget">
                            <p style="text-align: center">
                                <a target="_blank" href="https://wpruby.com">
                                    <img alt="WPRuby" style="width:60%;" src="https://wpruby.com/wp-content/uploads/2016/03/wpruby_logo_with_ruby_color-300x88.png">
                                </a>
                            </p>
                            <p>
								<?php _e( 'Top Notch WordPress Plugins!', 'woocommerce-australia-post-pro' ); ?>
                            </p>

                            <ul>
                                <li>» <a href="<?php echo admin_url( 'admin.php?page=australia-post-woocommerce-extension-pro-activation' ); ?>" target="_blank"><?php _e( 'Add/Update Your license', 'woocommerce-australia-post-pro' ); ?></a>
                                </li>
                                <li>» <a href="https://wpruby.com/submit-ticket/" target="_blank"><?php _e( 'Support Request', 'woocommerce-australia-post-pro' ); ?></a></li>
                                <li>» <a href="https://wpruby.com/knowledgebase/how-to-renew-your-license/" target="_blank"><?php _e( 'How to renew your license', 'woocommerce-australia-post-pro' ); ?></a></li>
                                <li>» <a href="https://wpruby.com/knowledgebase/how-to-upgrade-you-license/" target="_blank"><?php _e( 'How to upgrade my license', 'woocommerce-australia-post-pro' ); ?></a></li>
                                <li>» <a href="https://wpruby.com/knowledgebase/change-plugins-license-domain/" target="_blank"><?php _e( 'How to change your license’s domain', 'woocommerce-australia-post-pro' ); ?></a></li>
                                <li>» <a href="https://wpruby.com/knowledgebase/woocommerce-australia-post-troubleshooting-common-issues-troubleshooting-common-issues/"
                                         target="_blank"><?php _e( 'Troubleshooting and Common issues', 'woocommerce-australia-post-pro' ); ?></a></li>
                                <li>» <a href="https://wpruby.com/knowledgebase_category/woocommerce-australia-post-shipping-method-pro/" target="_blank"><?php _e( 'Documentation', 'woocommerce-australia-post-pro' ); ?></a></li>
                                <li>» <a href="https://wpruby.com/plugins/" target="_blank"><?php _e( 'Our Plugins Shop', 'woocommerce-australia-post-pro' ); ?></a></li>
                            </ul>

                        </div>
                    </div>
                </div>


                <div class="postbox rss-postbox">
                    <h3 class="hndle"><span class="dashicons dashicons-rss"></span>&nbsp;&nbsp;<?php _e( 'WPRuby Blog', 'woocommerce-australia-post-pro' ); ?></h3>
                    <hr>
                    <div class="inside">
                        <div class="rss-widget">
							<?php
							wp_widget_rss_output( array(
								'url'          => 'https://wpruby.com/feed/',
								'title'        => 'WPRuby Blog',
								'items'        => 3,
								'show_summary' => 0,
								'show_author'  => 0,
								'show_date'    => 1,
							) );
							?>
                        </div>
                    </div>
                </div>

				<?php do_action( 'wpruby_australia_post_promotion_widget_after' ); ?>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<style type="text/css">
    #postbox-container-1 .note {
        background: #ffba00;
        color: #ffffe0;
    }

    .wpruby-widgets .hndle {
        padding: 10px 0 5px 10px !important;
    }

    .support-widget p {
        text-align: center;
    }
</style>
