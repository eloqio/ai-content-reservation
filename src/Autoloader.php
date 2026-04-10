<?php
/**
 * PSR-4 autoloader for ELOQIO\AiContentReservation.
 */

namespace ELOQIO\AiContentReservation;

defined( 'ABSPATH' ) || exit;

final class Autoloader {

	private const PREFIX  = 'ELOQIO\\AiContentReservation\\';
	private const BASEDIR = __DIR__ . '/';

	public static function register(): void {
		spl_autoload_register( [ self::class, 'load' ] );
	}

	public static function load( string $class ): void {
		if ( strncmp( self::PREFIX, $class, strlen( self::PREFIX ) ) !== 0 ) {
			return;
		}

		$relative = substr( $class, strlen( self::PREFIX ) );
		$file     = self::BASEDIR . str_replace( '\\', '/', $relative ) . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
}
