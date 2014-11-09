<?php
/**
 * @package     Joomla.CLI
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// Make sure we're being called from the command line, not a web interface
if (array_key_exists('REQUEST_METHOD', $_SERVER)) die();


/**
 * Finder CLI Bootstrap
 *
 * Run the framework bootstrap with a couple of mods based on the script's needs
 */

// We are a valid entry point.
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

// Load system defines
if (file_exists(dirname(dirname(__FILE__)) . '/defines.php'))
{
	require_once dirname(dirname(__FILE__)) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(dirname(__FILE__)));
	require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_LIBRARIES . '/import.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Force library to be in JError legacy mode
JError::$legacy = true;

// Import necessary classes not handled by the autoloaders
jimport('joomla.application.menu');
jimport('joomla.environment.uri');
jimport('joomla.event.dispatcher');
jimport('joomla.utilities.utility');
jimport('joomla.utilities.arrayhelper');

// Import the configuration.
require_once JPATH_CONFIGURATION . '/configuration.php';

// System configuration.
$config = new JConfig;

// Configure error reporting to maximum for CLI output.
error_reporting(E_ALL);
ini_set('display_errors', 1);

defined('STDIN') && define('STDIN', fopen('php://stdin', 'r')); 
defined('STDOUT') && define('STDOUT', fopen('php://stdout', 'w')); 
defined('STDERR')  && define('STDERR', fopen('php://stderr', 'w')); 

/**
 * A command line cron job to run the Finder indexer.
 *
 * @package     Joomla.CLI
 * @subpackage  com_installer
 * @since       2.5
 */
class UpdateDBcli extends JApplicationCli
{
	/**
	 * Start time for the index process
	 *
	 * @var    string
	 * @since  2.5
	 */
	private $_time = null;

	/**
	 * Start time for each batch
	 *
	 * @var    string
	 * @since  2.5
	 */
	private $_qtime = null;

	/**
	 * Entry point for Finder CLI script
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function execute()
	{
		// import library dependencies
               //jimport('joomla.application.component.helper');
                //$installer = JComponentHelper::getComponent('com_installer');

		require_once JPATH_ADMINISTRATOR . '/components/com_installer/controller.php';

		//jimport('joomla.application.component.installer');

		// fool the system into thinking we are running as JSite with Finder as the active component
		JFactory::getApplication('site');
		$_SERVER['HTTP_HOST'] = 'domain.com';
		define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_installer');
		define('JPATH_COMPONENT', JPATH_BASE . '/administrator/components/com_installer');

		// Disable caching.
		$config = JFactory::getConfig();
		$config->set('caching', 0);
		$config->set('cache_handler', 'file');

		// Import the installer plugins.
		JPluginHelper::importPlugin('installer');

		$updater = new InstallerController();

                // APS. Fix DB
                echo "\nFixing database...\n";
                $model = $updater->getModel('database');
                $model->fix();
                echo "Done\n";
	}
}

// Instantiate the application object, passing the class name to JCli::getInstance
// and use chaining to execute the application.
JApplicationCli::getInstance('UpdateDBcli')->execute();
