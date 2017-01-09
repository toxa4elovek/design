<?php  if (! defined('BASEPATH')) {
     exit('No direct script access allowed');
 }
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Initialize the database
 *
 * @category	Database
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/database/
 */
function &DB($params = '', $active_record_override = null)
{
    // Load the DB config file if a DSN string wasn't passed
    if (is_string($params) and strpos($params, '://') === false) {
        // Is the config file in the environment folder?
        if (! defined('ENVIRONMENT') or ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database'.EXT)) {
            if (! file_exists($file_path = APPPATH.'config/database'.EXT)) {
                show_error('The configuration file database'.EXT.' does not exist.');
            }
        }
        
        include($file_path);

        if (! isset($db) or count($db) == 0) {
            show_error('No database connection settings were found in the database config file.');
        }

        if ($params != '') {
            $active_group = $params;
        }

        if (! isset($active_group) or ! isset($db[$active_group])) {
            show_error('You have specified an invalid database connection group.');
        }

        $params = $db[$active_group];
    } elseif (is_string($params)) {

        /* parse the URL from the DSN string
         *  Database settings can be passed as discreet
         *  parameters or as a data source name in the first
         *  parameter. DSNs must have this prototype:
         *  $dsn = 'driver://username:password@hostname/database';
         */

        if (($dns = @parse_url($params)) === false) {
            show_error('Invalid DB Connection String');
        }

        $params = [
                            'dbdriver'    => $dns['scheme'],
                            'hostname'    => (isset($dns['host'])) ? rawurldecode($dns['host']) : '',
                            'username'    => (isset($dns['user'])) ? rawurldecode($dns['user']) : '',
                            'password'    => (isset($dns['pass'])) ? rawurldecode($dns['pass']) : '',
                            'database'    => (isset($dns['path'])) ? rawurldecode(substr($dns['path'], 1)) : ''
                        ];

        // were additional config items set?
        if (isset($dns['query'])) {
            parse_str($dns['query'], $extra);

            foreach ($extra as $key => $val) {
                // booleans please
                if (strtoupper($val) == "TRUE") {
                    $val = true;
                } elseif (strtoupper($val) == "FALSE") {
                    $val = false;
                }

                $params[$key] = $val;
            }
        }
    }

    // No DB specified yet?  Beat them senseless...
    if (! isset($params['dbdriver']) or $params['dbdriver'] == '') {
        show_error('You have not selected a database type to connect to.');
    }

    // Load the DB classes.  Note: Since the active record class is optional
    // we need to dynamically create a class that extends proper parent class
    // based on whether we're using the active record class or not.
    // Kudos to Paul for discovering this clever use of eval()

    if ($active_record_override !== null) {
        $active_record = $active_record_override;
    }

    require_once(BASEPATH.'database/DB_driver'.EXT);

    if (! isset($active_record) or $active_record == true) {
        require_once(BASEPATH.'database/DB_active_rec'.EXT);

        if (! class_exists('CI_DB')) {
            eval('class CI_DB extends CI_DB_active_record { }');
        }
    } else {
        if (! class_exists('CI_DB')) {
            eval('class CI_DB extends CI_DB_driver { }');
        }
    }

    require_once(BASEPATH.'database/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_driver'.EXT);

    // Instantiate the DB adapter
    $driver = 'CI_DB_'.$params['dbdriver'].'_driver';
    $DB = new $driver($params);

    if ($DB->autoinit == true) {
        $DB->initialize();
    }

    if (isset($params['stricton']) && $params['stricton'] == true) {
        $DB->query('SET SESSION sql_mode="STRICT_ALL_TABLES"');
    }

    return $DB;
}



/* End of file DB.php */
/* Location: ./system/database/DB.php */
