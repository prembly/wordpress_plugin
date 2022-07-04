 <?php
/**
 * Plugin Name: Identitypass Verification
 * Description: Handle the basics checkout for the Identitypass widget
 * Version:      1.0.0
 * License:      GPL-2.0+
 * Author:       Identitypass, Os-prog
 * Author URI:   https://myidentitypass.com
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('IDX_PLUGIN_BASENAME', plugin_basename(__FILE__));

add_action('init', 'plugin_init');

// run_checkout_form();


function plugin_init() {
    require_once plugin_dir_path( __FILE__ ) . 'core/includes/checkout.php';
    
    $plugin = new Idx_pfd_checkout( 'Identitypass checkout', '1.0.0' );
    
    $plugin->run();
}

// function run_checkout_form() {
//     // $plugin = new Idx_pfd_checkout( 'Identitypass checkout', '1.0.0' );
    
//     // $plugin->run();
// }