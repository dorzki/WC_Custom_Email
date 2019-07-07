<?php
/**
 * Main plugin file.
 *
 * @package    dorzki\WooCommerce\Custom_Email
 * @subpackage Plugin
 * @author     Dor Zuberi <webmaster@dorzki.co.il>
 * @link       https://www.dorzki.co.il
 * @version    1.0.0
 */

namespace dorzki\WooCommerce\Custom_Email;

use dorzki\WooCommerce\Custom_Email\Emails\Custom_Email;

// Block if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class Plugin
 *
 * @package dorzki\WooCommerce\Custom_Email
 */
class Plugin {

	/**
	 * Plugin instance.
	 *
	 * @var null|Plugin
	 */
	private static $instance = null;


	/* ------------------------------------------ */


	/**
	 * Plugin constructor.
	 */
	public function __construct() {

		add_filter( 'woocommerce_email_classes', [ $this, 'register_emails' ] );
		add_filter( 'woocommerce_order_actions', [ $this, 'register_actions' ] );

		add_action( 'woocommerce_order_action_dorzki_wc_send_custom_email', [ $this, 'send_custom_email' ] );

	}


	/* ------------------------------------------ */

	/**
	 * Retrieve plugin instance.
	 *
	 * @return Plugin|null
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();

		}

		return self::$instance;

	}


	/* ------------------------------------------ */


	/**
	 * Register custom emails.
	 *
	 * @param array $emails WooCommerce registered emails.
	 *
	 * @return array
	 */
	public function register_emails( $emails ) {

		require_once 'emails/class-custom-email.php';

		$emails['custom_email'] = new Custom_Email();

		return $emails;

	}


	/* ------------------------------------------ */

	/**
	 * Register custom order actions.
	 *
	 * @param array $actions WooCommerce registered order actions.
	 *
	 * @return array
	 */
	public function register_actions( $actions ) {

		$actions['dorzki_wc_send_custom_email'] = __( 'Send Custom Email', 'dorzki-wc-custom-email' );

		return $actions;

	}


	/* ------------------------------------------ */

	/**
	 * Send custom email on request.
	 *
	 * @param WC_Order $order current order.
	 */
	public function send_custom_email( $order ) {

		WC()->mailer()->emails['custom_email']->trigger( $order );

		// Optional: Add order note.
		$order->add_order_note( __( 'Custom Email Sent!', 'dorzki-wc-custom-email' ), false, false );

	}

}

// initiate plugin.
Plugin::get_instance();
