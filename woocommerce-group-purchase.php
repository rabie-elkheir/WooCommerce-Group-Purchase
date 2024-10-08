<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/rabie-elkheir
 * @since             1.0.0
 * @package           Woocommerce_Group_Purchase
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Group Purchase
 * Plugin URI:        https://github.com/rabie-elkheir/WooCommerce-Group-Purchase
 * Description:       WooCommerce Group Purchase is a simple plugin that allows store owners to offer special discounts to customers when they participate in group purchases. 
 * Version:           1.0.0
 * Author:            Rabie Alkheir
 * Author URI:        https://github.com/rabie-elkheir/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-group-purchase
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WOOCOMMERCE_GROUP_PURCHASE_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-group-purchase-activator.php
 */
function activate_woocommerce_group_purchase()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-group-purchase-activator.php';
	Woocommerce_Group_Purchase_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-group-purchase-deactivator.php
 */
function deactivate_woocommerce_group_purchase()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-group-purchase-deactivator.php';
	Woocommerce_Group_Purchase_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_woocommerce_group_purchase');
register_deactivation_hook(__FILE__, 'deactivate_woocommerce_group_purchase');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-woocommerce-group-purchase.php';


// إضافة ملف إعدادات شراء المجموعة
require_once plugin_dir_path(__FILE__) . 'includes/group-purchase-settings.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_group_purchase()
{

	$plugin = new Woocommerce_Group_Purchase();
	$plugin->run();
}
run_woocommerce_group_purchase();
