<?php
/**
 * Uninstall cleanup.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'eloqio_acr_settings' );
