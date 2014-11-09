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

defined('_JEXEC') || define('_JEXEC', 1);
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// Load system defines
if (file_exists(dirname(dirname(__FILE__)) . '/defines.php'))
{
	require_once dirname(dirname(__FILE__)) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	defined('JPATH_BASE') || define('JPATH_BASE', dirname(dirname(__FILE__)));
	require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_LIBRARIES . '/import.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Force library to be in JError legacy mode
require_once JPATH_LIBRARIES . '/import.legacy.php';
JError::$legacy = true;

// Import necessary classes not handled by the autoloaders
jimport('joomla.application.component.helper');

// Import the configuration.
require_once JPATH_CONFIGURATION . '/configuration.php';

// System configuration.
$config = new JConfig;

$app = JFactory::getApplication('administrator',array('session'=>false));
require_once JPATH_BASE . '/administrator/includes/framework.php';

//lib_joomla language necesary for templates name isntalation
$lang = JFactory::getLanguage();
$lang->load('lib_joomla', JPATH_SITE);
$lang->load('lib_joomla', JPATH_ADMINISTRATOR);

if (! defined('APS_EXTENSION_INSTALL')) {
	if(count($argv) < 2)
	{
    	print "Usage: cli/install_package_cli.php <plugin zip archive>\n";
    	exit(1);
	}
}

class InstallJoomlaExtensionFromAPS
{
	public function install($plugin_distr)
	{
		$package = $this->_getPackageFromDistributives($plugin_distr);
		if(!$package)
		{
			return false;
		}

		// Get an installer instance
		$installer = new JInstaller;
		//$installer = JInstaller::getInstance();

		// Install the package
		if (!$installer->install($package['dir']))
		{
			// There was an error installing the package
			$result = false;
		}
		else
		{
			// Package installed sucessfully
			$result = true;
		}

		// Clear install
		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

		return $result;
	}


	private	function _getPackageFromDistributives($plugin_distr)
	{
		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib'))
		{
			return false;
		}

		//Check file exist
		if(!file_exists ($plugin_distr))
		{
			return false;
		}

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($plugin_distr);

		return $package;
	}
}
?>
