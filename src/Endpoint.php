<?php
/**
 * Serves /.well-known/tdmrep.json directly, without writing to disk.
 */

namespace ELOQIO\AiContentReservation;

defined( 'ABSPATH' ) || exit;

final class Endpoint {

	public const PATH         = '/.well-known/tdmrep.json';
	public const CONTENT_TYPE = 'application/tdmrep+json; charset=utf-8';

	private Plugin $plugin;

	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	public function register(): void {
		add_action( 'init', [ $this, 'maybe_serve' ], 0 );
	}

	public function maybe_serve(): void {
		if ( ! $this->plugin->is_enabled() ) {
			return;
		}

		$request_uri = isset( $_SERVER['REQUEST_URI'] )
			? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) )
			: '';

		$path = wp_parse_url( $request_uri, PHP_URL_PATH );

		if ( self::PATH !== $path ) {
			return;
		}

		nocache_headers();
		header( 'Content-Type: ' . self::CONTENT_TYPE );
		header( 'Access-Control-Allow-Origin: *' );

		echo wp_json_encode( $this->build_payload(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		exit;
	}

	/**
	 * Builds the TDMRep JSON payload.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function build_payload(): array {
		$settings = $this->plugin->settings();

		$entry = [
			'location'        => '/',
			'tdm-reservation' => $settings['tdm_reservation'],
		];

		if ( '' !== $settings['tdm_policy'] ) {
			$entry['tdm-policy'] = $settings['tdm_policy'];
		}

		return [ $entry ];
	}
}
