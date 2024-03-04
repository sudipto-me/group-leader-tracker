<?php

/**
 * Plugin Name: Group Leader Tracker
 * Plugin URI: https://group-leader-tracker.com
 * Text Domain: gpld-tracker
 * Description: Plugin that tracks learndash group leaders activity in WordPress and then presents those events in a very nice GUI.
 * Version: 1.0.0
 * Requires at least: 6.1
 * Requires PHP: 7.4
 * Author: The Wunderkind Company
 * Author URI: https://thewunderkindcompany.com/
 * License: GPL2
 */

defined('ABSPATH') || exit;

class Group_Leader_Tracker
{
	/**
	 * This plugin's instance
	 *
	 * @var Group_Leader_Tracker The one true WFG_Scheduled_Event
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * Group_Leader_Tracker version.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $version = '1.0.0';

	/**
	 * Group_Leader_Tracker constructor.
	 */
	public function __construct()
	{
		$this->define_constants();
		add_action('plugins_loaded', array($this, 'init_plugin'));
	}

	/**
	 * Define all constants.
	 * 
	 * @return void
	 * @since 1.0.0
	 */
	public function define_constants()
	{
		$this->define('Group_Leader_Tracker_PLUGIN_VERSION', $this->version);
		$this->define('Group_Leader_Tracker_PLUGIN_FILE', __FILE__);
		$this->define('Group_Leader_Tracker_PLUGIN_DIR', dirname(__FILE__));
		//$this->define('WFG_Scheduled_Event_PLUGIN_INC_DIR', dirname(__FILE__) . '/includes');
	}

	/**
	 * Define constants if not already defined.
	 * 
	 * @param string $name Name of the constants.
	 * @param string $value Value of the constants.
	 * 
	 * @return void
	 * @since 1.0.0
	 */
	public function define($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	/**
	 * Main Group_Leader_Tracker Instance
	 *
	 * Insures that only one instance of Group_Leader_Tracker exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @return Group_Leader_Tracker The one true Group_Leader_Tracker
	 * @since 1.0.0
	 * @static var array $instance
	 */
	public static function init()
	{
		if (!isset(self::$instance) && !(self::$instance instanceof Group_Leader_Tracker)) {
			self::$instance = new Self();
		}
		return self::$instance;
	}

	/**
	 * Return plugin version.
	 * 
	 * @return string
	 * @since 1.0.0
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Return plugin name.
	 * 
	 * @return string
	 * @since 1.0.0
	 */
	public function get_name()
	{
		return 'Group Leader Tracker';
	}

	/**
	 * Plugin URL getter.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_url()
	{
		return untrailingslashit(plugins_url('/', __FILE__));
	}

	/**
	 * Plugin path getter.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_path()
	{
		return untrailingslashit(plugin_dir_path(__FILE__));
	}

	/**
	 * Plugin base path name getter.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function plugin_basename()
	{
		return plugin_basename(__FILE__);
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function localization_setup()
	{
		load_plugin_textdomain('gpld-tracker', false, plugin_basename(dirname(__FILE__)) . '/i18n/languages');
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access protected
	 * @return void
	 */

	public function __clone()
	{
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'gpld-tracker'), '1.0.0');
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access protected
	 * @return void
	 */

	public function __wakeup()
	{
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'gpld-tracker'), '1.0.0');
	}

	/**
	 * Load the plugin when WooCommerce loaded.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init_plugin()
	{
		//$this->includes();
		$this->init_hooks();
	}

	/**
	 * Hook into action and filters.
	 * 
	 * @since 1.0.0
	 * @return void.
	 */
	public function init_hooks()
	{
		add_action('admin_notices', array($this, 'dependecies_notices'));
		add_action('plugins_loaded', array($this, 'localization_setup'));
	}

	/**
	 * Missing dependency Notice.
	 * 
	 * @return void.
	 */
	public function dependecies_notices()
	{
		if (is_plugin_active('sfwd-lms/sfwd_lms.php')) {
			return;
		}
		$notice = sprintf(
			__('%1$s requires %2$s to be installed and active', 'gpld-tracker'),
			'<strong>' . esc_html($this->get_name()) . '</strong>',
			'<strong>' . esc_html__('Learndash', '') . '</strong>'
		);

		echo '<div class="notice notice-error"><p>' . wp_kses_post($notice) . '</p></div>';
	}
}

/**
 * The main function responsible for returning the one true WC Serial Numbers
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @return Group_Leader_Tracker
 * @since 1.0.0
 */
function Group_Leader_Tracker()
{
	return Group_Leader_Tracker::init();
}

Group_Leader_Tracker();
