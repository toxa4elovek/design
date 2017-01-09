<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2011 EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 2.0
 * @filesource	
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter APC Caching Class 
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		ExpressionEngine Dev Team
 * @link		
 */

class CI_Cache_apc extends CI_Driver
{

    /**
     * Get 
     *
     * Look for a value in the cache.  If it exists, return the data 
     * if not, return FALSE
     *
     * @param 	string	
     * @return 	mixed		value that is stored/FALSE on failure
     */
    public function get($id)
    {
        $data = apc_fetch($id);

        return (is_array($data)) ? $data[0] : false;
    }

    // ------------------------------------------------------------------------	

    /**
     * Cache Save
     *
     * @param 	string		Unique Key
     * @param 	mixed		Data to store
     * @param 	int			Length of time (in seconds) to cache the data
     *
     * @return 	boolean		true on success/false on failure
     */
    public function save($id, $data, $ttl = 60)
    {
        return apc_store($id, [$data, time(), $ttl], $ttl);
    }
    
    // ------------------------------------------------------------------------

    /**
     * Delete from Cache
     *
     * @param 	mixed		unique identifier of the item in the cache
     * @param 	boolean		true on success/false on failure
     */
    public function delete($id)
    {
        return apc_delete($id);
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the cache
     *
     * @return 	boolean		false on failure/true on success
     */
    public function clean()
    {
        return apc_clear_cache('user');
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     *
     * @param 	string		user/filehits
     * @return 	mixed		array on success, false on failure	
     */
     public function cache_info($type = null)
     {
         return apc_cache_info($type);
     }

    // ------------------------------------------------------------------------

    /**
     * Get Cache Metadata
     *
     * @param 	mixed		key to get cache metadata on
     * @return 	mixed		array on success/false on failure
     */
    public function get_metadata($id)
    {
        $stored = apc_fetch($id);

        if (count($stored) !== 3) {
            return false;
        }

        list($data, $time, $ttl) = $stored;

        return [
            'expire'    => $time + $ttl,
            'mtime'        => $time,
            'data'        => $data
        ];
    }

    // ------------------------------------------------------------------------

    /**
     * is_supported()
     *
     * Check to see if APC is available on this system, bail if it isn't.
     */
    public function is_supported()
    {
        if (! extension_loaded('apc') or ! function_exists('apc_store')) {
            log_message('error', 'The APC PHP extension must be loaded to use APC Cache.');
            return false;
        }
        
        return true;
    }

    // ------------------------------------------------------------------------
}
// End Class

/* End of file Cache_apc.php */
/* Location: ./system/libraries/Cache/drivers/Cache_apc.php */
