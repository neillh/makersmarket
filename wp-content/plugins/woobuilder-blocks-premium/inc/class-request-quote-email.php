<?php
/**
 * Class SFPBK_Request_Quote_Email file.
 *
 * @package WooCommerce\Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SFPBK_Request_Quote_Email', false ) ) :

	/**
	 * Customer Completed Order Email.
	 *
	 * Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.
	 *
	 * @class       SFPBK_Request_Quote_Email
	 * @version     2.0.0
	 * @package     WooCommerce/Classes/Emails
	 * @extends     WC_Email
	 */
	class SFPBK_Request_Quote_Email extends WC_Email {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id             = 'request_quote';
			$this->title          = __( 'Quote Request', 'woocommerce' );
			$this->description    = __( 'Storefront Blocks: Sent to admin when a quote is requested.', 'woocommerce' );
			$this->template_base  = dirname( __FILE__ ) . '/tpl/';
			$this->template_html  = 'emails/requested-quote.php';
			$this->placeholders   = array(
				'{site_title}'   => $this->get_blogname(),
				'{order_date}'   => '',
				'{order_number}' => '',
			);
			$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );

			// Call parent constructor.
			parent::__construct();
			$this->email_type     = 'html';
		}

		/**
		 * Get template.
		 *
		 * @param  string $type Template type. Can be either 'template_html' or 'template_plain'.
		 * @return string
		 */
		public function get_template( $type ) {
			if ( $type === 'template_html' ) {
				return $this->template_html;
			}

			return '';
		}

		/**
		 * Trigger the sending of this email.
		 * @return string Notice to output
		 */
		public function trigger() {

			if ( ! filter_var( $this->get_from_address(), FILTER_VALIDATE_EMAIL ) ) {
				return '<div class="woocommerce-error">Proper email address is required to send the quote to.</div>';
			}

			if ( ! $this->get_from_name() ) {
				return '<div class="woocommerce-error">Requester name is required.</div>';
			}

			$this->setup_locale();

			if ( $this->get_recipient() ) {
				$this->send(
					$this->get_recipient(),
					$this->get_subject(),
					$this->get_content(),
					$this->get_headers(),
					$this->get_attachments()
				);
			}

			$this->restore_locale();

			return '<div class="woocommerce-message">Quote request sent.</div>';
		}

		/**
		 * Get email subject.
		 *
		 * @return string
		 */
		public function get_subject() {
			return apply_filters( 'woocommerce_email_subject_' . $this->id, 'New Quote request' );
		}

		/**
		 * Get content html.
		 *
		 * @return string
		 */
		public function get_content() {
			return wc_get_template_html(
				$this->template_html,
				[
					'email' => $this,
				],
				'',
				$this->template_base
			);
		}

		/**
		 * Initialise settings form fields.
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'    => array(
					'title'   => __( 'Enable/Disable', 'woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this email notification', 'woocommerce' ),
					'default' => 'yes',
				),
				'recipient'  => array(
					'title'       => __( 'Recipient(s)', 'woocommerce' ),
					'type'        => 'text',
					/* translators: %s: WP admin email */
					'description' => sprintf( __( 'Enter recipient for quote request emails. Defaults to %s.', 'woocommerce' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
					'placeholder' => '',
					'default'     => get_option( 'admin_email' ),
					'desc_tip'    => true,
				),
			);
		}


		public function get_from_name( $unused = '' ) {
			return esc_html( $_POST['requester_name'] );
		}

		public function get_from_address( $unused = '' ) {
			return esc_html( $_POST['requester_email'] );
		}
	}

endif;