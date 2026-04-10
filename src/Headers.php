<?php
/**
 * Emits the tdm-reservation HTTP header and the matching HTML meta tag.
 */

namespace ELOQIO\AiContentReservation;

defined( 'ABSPATH' ) || exit;

final class Headers {

	private Plugin $plugin;

	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	public function register(): void {
		add_action( 'send_headers', [ $this, 'send_http_header' ] );
		add_action( 'wp_head', [ $this, 'print_meta_tag' ], 1 );
	}

	public function send_http_header(): void {
		if ( is_admin() || ! $this->plugin->is_enabled() ) {
			return;
		}

		$settings = $this->plugin->settings();

		header( 'tdm-reservation: ' . (string) $settings['tdm_reservation'] );

		if ( '' !== $settings['tdm_policy'] ) {
			header( 'tdm-policy: ' . $settings['tdm_policy'] );
		}
	}

	public function print_meta_tag(): void {
		if ( ! $this->plugin->is_enabled() ) {
			return;
		}

		$settings = $this->plugin->settings();

		printf(
			'<meta name="tdm-reservation" content="%s" />' . "\n",
			esc_attr( (string) $settings['tdm_reservation'] )
		);

		if ( '' !== $settings['tdm_policy'] ) {
			printf(
				'<meta name="tdm-policy" content="%s" />' . "\n",
				esc_attr( $settings['tdm_policy'] )
			);
		}
	}
}
