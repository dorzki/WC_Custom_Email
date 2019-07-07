<?php
/**
 * Class Custom_Email
 *
 * @package    dorzki\WooCommerce\Custom_Email\Emails
 * @subpackage Custom_Email
 * @author     Dor Zuberi <webmaster@dorzki.co.il>
 * @link       https://www.dorzki.co.il
 * @version    1.0.0
 */

namespace dorzki\WooCommerce\Custom_Email\Emails;

use WC_Email;
use WC_Order;

// Block if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class Custom_Email
 *
 * @package dorzki\WooCommerce\Custom_Email\Emails
 */
class Custom_Email extends WC_Email {

	/**
	 * Custom_Email constructor.
	 */
	public function __construct() {

		// Email Details.
		$this->id = 'dorzki_wc_custom_email';
		$this->title = __( 'Custom Email', 'dorzki-wc-custom-email' );
		$this->description = __( 'Custom email template for WooCommerce.', 'dorzki-wc-custom-email' );

		$this->subject = __( 'Custom Email Subject', 'dorzki-wc-custom-email' );
		$this->customer_email = true;

		$this->template_html = 'views/custom-email.php';
		$this->template_plain = 'views/plain/custom-email.php';
		$this->template_base = DZ_WC_CEMAIL_PATH . 'emails/';

		$this->heading = __( 'Custom Email Title', 'dorzki-wc-custom-email' );

		parent::__construct();

	}


	/* ------------------------------------------ */


	/**
	 * Triggered on event.
	 *
	 * @param WC_Order $order current order object.
	 *
	 * @return void
	 */
	public function trigger( $order ) {

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$this->object = $order;
		$this->recipient = $this->object->get_billing_email();

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

	}


	/* ------------------------------------------ */


	/**
	 * Get content html.
	 *
	 * @return string
	 */
	public function get_content_html() {

		return wc_get_template_html( $this->template_html, [
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false,
			'email'         => $this,
		], '', $this->template_base );

	}


	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {

		return wc_get_template_html( $this->template_plain, [
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true,
			'email'         => $this,
		], '', $this->template_base );

	}

}