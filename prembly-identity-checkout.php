 <?php
/**
 * Plugin Name: Prembly Identity Checkout
 * Description: Handle the basics checkout for the Identitypass widget
 * Version:      2.0.1
 * License:      GPL-2.0+
 * Author:       Prembly
 * Author URI:   https://prembly.com
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('PREMIDX_PLUGIN_BASENAME', plugin_basename(__FILE__));

add_action('init', 'premidx_plugin_init');

function premidx_plugin_init() {
    require_once plugin_dir_path( __FILE__ ) . 'core/includes/checkout.php';
    
    $plugin = new Premidx_pfd_checkout( 'Prembly Identity Checkout', '2.0.1' );
    
    $plugin->run();
}

