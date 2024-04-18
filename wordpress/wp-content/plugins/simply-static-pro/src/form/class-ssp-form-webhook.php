<?php

namespace simply_static_pro;

use Simply_Static;

/**
 * Class to handle form webhooks.
 */
class Form_Webhook {

	/**
	 * Contains instance or null
	 *
	 * @var object|null
	 */
	private static $instance = null;

	/**
	 * Returns instance of Webhook.
	 *
	 * @return object
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for Form_Webhook.
	 */
	public function __construct() {
		$options = get_option( 'simply-static' );

		if ( isset( $options['use_forms'] ) && $options['use_forms'] ) {
			add_action( 'init', array( $this, 'form_handler' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_webhook_scripts' ) );
		}
	}

	/**
	 * Enqueue scripts for webhooks.
	 *
	 * @return void
	 */
	public function add_webhook_scripts() {
		$options = get_option( 'simply-static' );

		if ( $options['use_forms'] ) {
			wp_enqueue_script( 'ssp-form-webhook-public', SIMPLY_STATIC_PRO_URL . '/assets/ssp-form-webhook-public.js', array(), SIMPLY_STATIC_PRO_VERSION, true );
		}
	}

	/**
	 * Handles the HTTP Request sent to your site's webhook
	 */
	public function form_handler() {
		$options   = get_option( 'simply-static' );
		$send_mail = apply_filters( 'ssp_send_webhook_mail', true );

		if ( ! empty( $options['destination_host'] ) ) {
			// Check request first.
			$allowed_hosts = array( $options['destination_host'] );

			if ( ! isset( $_SERVER['HTTP_HOST'] ) || ! in_array( $_SERVER['HTTP_HOST'], $allowed_hosts ) ) {
				return;
			}
		}

		// Check if the webhook should be fired.
		if ( ! isset( $_GET['mailme'] ) ) {
			return;
		}

		// Check if there is POST request.
		if ( empty( $_POST ) ) {
			return;
		}

		// Check if Simply Static is allowed to send mails.
		if ( $send_mail !== true ) {
			return;
		}

		// Get all form object ids.
		$args = array(
			'numberposts' => -1,
			'post_type'   => 'ssp-form',
			'fields'      => 'ids',
		);

		$forms = get_posts( $args );

		foreach ( $forms as $form_id ) {
			$meta_fields = array(
				'tool'               => get_post_meta( $form_id, 'tool', true ),
				'email'              => get_post_meta( $form_id, 'email', true ),
				'form-id'            => get_post_meta( $form_id, 'form_id', true ),
				'subject'            => get_post_meta( $form_id, 'subject', true ),
				'name-attributes'    => get_post_meta( $form_id, 'name_attributes', true ),
				'message'            => get_post_meta( $form_id, 'message', true ),
				'additional-headers' => get_post_meta( $form_id, 'additional_headers', true ),
			);

			if ( 'cf7' == $meta_fields['tool'] && ! empty( $_POST['_wpcf7'] ) && $_POST['_wpcf7'] == $meta_fields['form-id'] || 'gravity_forms' == $meta_fields['tool'] && ! empty( $_POST['gform_submit'] ) && $_POST['gform_submit'] == $meta_fields['form-id'] ) {
				// Preparing the headers.
				$headers            = array( 'Content-Type: text/html; charset=UTF-8' );
				$additional_headers = $meta_fields['additional-headers'];

				// Preparing mail content.
				$subject    = esc_html( $_POST[ $meta_fields['subject'] ] );
				$attributes = explode( ',', $meta_fields['name-attributes'] );
				$body       = $meta_fields['message'];

				foreach ( $attributes as $attribute ) {
					$body = str_replace( esc_html( $attribute ), esc_html( $_POST[ $attribute ] ), $body );

					if ( ! empty( $additional_headers ) ) {
						$additional_headers = str_replace( esc_html( $attribute ), esc_html( $_POST[ $attribute ] ), $additional_headers );
					}
				}

				$additional_headers = explode( ',', $additional_headers );

				if ( ! empty( $additional_headers ) && is_array( $additional_headers ) ) {
					foreach ( $additional_headers as $header ) {
						$headers[] = $header;
					}
				}

				// Now we can send the mail.
				wp_mail( $meta_fields['email'], $subject, $body, $headers );
			}
		}
	}
}

