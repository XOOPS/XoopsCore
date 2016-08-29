<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Key;

/**
 * Xmf\Key\StorageInterface
 *
 * load a database table
 *
 * @category  Xmf\Key\FileStorage
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class FileStorage implements StorageInterface
{

    /**
     * Fetch key data by name
     *
     * @param string $name key name
     *
     * @return string file name
     */
    protected function fileName($name)
    {
        $syssec = $this->systemSecret();
        return XOOPS_VAR_PATH . "/data/{$syssec}-key-{$name}.php";
    }

    /**
     * Construct a string related to the system to make name less predictable
     *
     * @return string
     */
    protected function systemSecret()
    {
        $db = \XoopsDatabaseFactory::getDatabaseConnection();
        $prefix = $db->prefix();
        $secret = md5($prefix);
        $secret = substr($secret, 8, 8);
        return $secret;
    }

    /**
     * Save key data by name
     *
     * @param string $name key name
     * @param string $data key data, serialized to string if required
     *
     * @return boolean true if key saved, otherwise false
     */
    public function save($name, $data)
    {
        if (empty($data) || !is_string($data)) {
            throw new \DomainException('Invalid key data');
        }
        $fileContents = "<?php\n//**Warning** modifying this file will break things!\n"
            . "return '{$data}';\n";
        return (false !== file_put_contents($this->fileName($name), $fileContents));
    }

    /**
     * Fetch key data by name
     *
     * @param string $name key name
     *
     * @return string|false key data (possibly serialized) or false on error
     */
    public function fetch($name)
    {
        return include $this->fileName($name);
    }

    /**
     * Check if key data exists
     *
     * @param string $name key name
     *
     * @return boolean true if key exists, otherwise false
     */
    public function exists($name)
    {
        return file_exists($this->fileName($name));
    }

    /**
     * Delete a key
     *
     * @param string $name key name
     *
     * @return boolean true if key deleted, otherwise false
     */
    public function delete($name)
    {
        return unlink($this->fileName($name));
    }
}
