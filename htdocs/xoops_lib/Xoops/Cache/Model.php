<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Cache engine For XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      cache
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Database Storage engine for cache
 * PHP versions 4 and 5
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *                                     1785 E. Sahara Avenue, Suite 490-204
 *                                     Las Vegas, Nevada 89104
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright  Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link       http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package    cake
 * @subpackage cake.cake.libs.cache
 * @since      CakePHP(tm) v 1.2.0.4933
 * @version    $Revision$
 * @modifiedby $LastChangedBy$
 * @lastmodified $Date$
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Xoops_Cache_Model extends Xoops_Cache_Abstract
{
    /**
     * settings
     *              className = name of the model to use, default => Cache
     *              fields = database fields that hold data and ttl, default => data, expires
     *
     * @var array
     * @access public
     */
    public $settings = array();

    /**
     * Model instance.
     *
     * @var XoopsCachemodelHandler
     * @access private
     */
    private $model = null;

    /**
     * Model instance.
     *
     * @var object
     * @access private
     */
    private $fields = array();

    /**
     * Initialize the Cache Engine
     * Called automatically by the cache frontend
     * To reinitialize the settings call Cache::engine('EngineName', [optional] settings = array());
     *
     * @param array $settings array of setting for the engine
     *
     * @return boolean True if the engine has been successfully initialized, false if not
     * @access public
     */
    public function init($settings = array())
    {
        parent::init($settings);
        $defaults = array('fields' => array('cache_data', 'cache_expires'));
        $this->settings = array_merge($defaults, $this->settings);
        $this->fields = $this->settings['fields'];
        $this->model = Xoops::getInstance()->getHandlerCachemodel();
        return true;
    }

    /**
     * Garbage collection. Permanently remove all expired and deleted data
     *
     * @param null $expires
     *
     * @return boolean
     */
    public function gc($expires = null)
    {
        return $this->clear(true);
    }

    /**
     * Write data for key into cache
     *
     * @param string  $key      Identifier for the data
     * @param mixed   $value    Data to be cached
     * @param integer $duration How long to cache the data, in seconds
     *
     * @return boolean True if the data was successfully cached, false on failure
     * @access public
     */
    public function write($key, $value, $duration)
    {
        $value = serialize($value);
        if (empty($value)) {
            return false;
        }
        if (!$cache_obj = $this->model->get($key)) {
            $cache_obj = $this->model->create();
        }
        $cache_obj->setVar($this->model->keyName, $key);
        $cache_obj->setVar($this->fields[0], $value);
        $cache_obj->setVar($this->fields[1], time() + $duration);
        return $this->model->insert($cache_obj);
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return mixed The cached data, or false if the data doesn't exist, has expired, or if there was an error fetching it
     * @access public
     */
    public function read($key)
    {
        $criteria = new CriteriaCompo(new Criteria($this->model->keyName, $key));
        //$criteria->add(new Xoops_Criteria($this->fields[1], time(), ">"));
        $criteria->setLimit(1);
        $data = $this->model->getAll($criteria, null, true, false);

        if (!$data) {
            return null;
        }
        //Did cache expired
        if ($data[0]->getVar($this->fields[1]) < time()) {
            $this->delete($key);
            return null;
        }
        return unserialize($data[0]->getVar($this->fields[0]));
    }

    /**
     * Delete a key from the cache
     *
     * @param string $key Identifier for the data
     *
     * @return boolean True if the value was successfully deleted, false if it didn't exist or couldn't be removed
     * @access public
     */
    public function delete($key)
    {
        $criteria = new CriteriaCompo(new Criteria($this->model->keyName, $key));
        return $this->model->deleteAll($criteria);
    }

    /**
     * Delete all keys from the cache
     *
     * @param boolean $check if true will check expiration, otherwise delete all
     *
     * @return boolean True if the cache was successfully cleared, false otherwise
     * @access public
     */
    public function clear($check)
    {
        if ($check) {
            return $this->model->deleteAll(new Criteria($this->fields[1], time(), '<= '));
        }
        return $this->model->deleteAll(new Criteria(''));
    }

    /**
     * Increments the value of an integer cached key
     *
     * @param string  $key    Identifier for the data
     * @param integer $offset How much to increment
     *
     * @return mixed New incremented value, false otherwise
     */
    public function increment($key, $offset = 1)
    {
        trigger_error(sprintf('Method increment() not implemented in class %s', __CLASS__));
    }

    /**
     * Decrements the value of an integer cached key
     *
     * @param string  $key    Identifier for the data
     * @param integer $offset How much to subtract
     *
     * @return mixed New decremented value, false otherwise
     */
    public function decrement($key, $offset = 1)
    {
        trigger_error(sprintf('Method decrement() not implemented in class %s', __CLASS__));
    }
}