<?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 1/28/2016
     * Time: 6:11 AM
     */



    // We are a valid entry point.
    const _JEXEC = 1;

    // Load system defines
    if (file_exists(dirname(__DIR__) . '/defines.php'))
    {
        require_once dirname(__DIR__) . '/defines.php';
    }

    if (!defined('_JDEFINES'))
    {
        define('JPATH_BASE', dirname(__DIR__));
        require_once JPATH_BASE . '/includes/defines.php';
    }

    // Get the framework.
    require_once JPATH_LIBRARIES . '/import.legacy.php';

    // Bootstrap the CMS libraries.
    require_once JPATH_LIBRARIES . '/cms.php';

    require_once JPATH_CONFIGURATION . '/configuration.php';

// System configuration.
    $config = new JConfig;

// Configure error reporting to maximum for CLI output.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Library language
$lang = JFactory::getLanguage();

// Try the files_joomla file in the current language (without allowing the loading of the file in the default language)
$lang->load('files_joomla.sys', JPATH_SITE, null, false, false)
// Fallback to the files_joomla file in the default language
    || $lang->load('files_joomla.sys', JPATH_SITE, null, true);

/**
 * A command line cron job to attempt to remove files that should have been deleted at update.
 *
 * @since  3.0
 */
class Uidincrement extends JApplicationCli
{
    /**
     * Entry point for CLI script
     *
     * @return  void
     *
     * @since   3.0
     */
    public function doExecute()
    {

        $currentid = getopt("i:")['i'];

        var_dump($currentid);
        echo 'ping';
        $newid = $currentid+234;

        //update joomla user tables
        $sql = "UPDATE z_users SET id = $newid WHERE id = $currentid";
        JFactory::getDbo()->setQuery($sql)->execute();

        $sql = "UPDATE z_user_usergroup_map SET z_user_usergroup_map.user_id = $newid WHERE user_id = $currentid";
        JFactory::getDbo()->setQuery($sql)->execute();


        //update sp4k user table
        $sql = "UPDATE z_sp4k_parent_items SET juser_id = $newid WHERE juser_id = $currentid";
        JFactory::getDbo()->setQuery($sql)->execute();
    }
}

// Instantiate the application object, passing the class name to JCli::getInstance
// and use chaining to execute the application.
JApplicationCli::getInstance('Uidincrement')->execute();
