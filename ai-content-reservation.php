<?php
/**
 * Plugin Name:       AI Content Reservation (TDMRep)
 * Plugin URI:        https://github.com/eloqio/ai-content-reservation
 * Description:       Implements the W3C TDM Reservation Protocol to signal AI training opt-out on your WordPress content. Exposes /.well-known/tdmrep.json, a tdm-reservation HTTP header, and a matching HTML meta tag.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Author:            ELOQIO
 * Author URI:        https://eloq.io
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ai-content-reservation
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'ELOQIO_ACR_VERSION', '1.0.0' );
define( 'ELOQIO_ACR_FILE', __FILE__ );
define( 'ELOQIO_ACR_DIR', plugin_dir_path( __FILE__ ) );
define( 'ELOQIO_ACR_URL', plugin_dir_url( __FILE__ ) );

require_once ELOQIO_ACR_DIR . 'src/Autoloader.php';
\ELOQIO\AiContentReservation\Autoloader::register();

add_action( 'plugins_loaded', static function () {
	\ELOQIO\AiContentReservation\Plugin::instance()->boot();
} );
