<?php
/**
 * Main plugin orchestrator.
 */

namespace ELOQIO\AiContentReservation;

defined( 'ABSPATH' ) || exit;

final class Plugin {

	public const OPTION_KEY = 'eloqio_acr_settings';

	public const DEFAULTS = [
		'enabled'         => true,
		'tdm_reservation' => 1,
		'tdm_policy'      => '',
	];

	private static ?Plugin $instance = null;

	public static function instance(): Plugin {
		return self::$instance ??= new self();
	}

	private function __construct() {}

	public function boot(): void {
		( new Endpoint( $this ) )->register();
		( new Headers( $this ) )->register();
		( new Settings( $this ) )->register();
		( new SiteHealth( $this ) )->register();
	}

	/**
	 * Returns merged settings with defaults.
	 *
	 * @return array{enabled:bool, tdm_reservation:int, tdm_policy:string}
	 */
	public function settings(): array {
		$stored = get_option( self::OPTION_KEY, [] );
		$merged = wp_parse_args( is_array( $stored ) ? $stored : [], self::DEFAULTS );

		return [
			'enabled'         => (bool) $merged['enabled'],
			'tdm_reservation' => (int) $merged['tdm_reservation'] === 0 ? 0 : 1,
			'tdm_policy'      => (string) $merged['tdm_policy'],
		];
	}

	public function is_enabled(): bool {
		return $this->settings()['enabled'];
	}
}
