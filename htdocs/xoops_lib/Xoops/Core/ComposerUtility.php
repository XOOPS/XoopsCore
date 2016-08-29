<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\PhpProcess;

/**
 * ComposerUtility
 *
 * @category  Xoops\Core\ComposerUtility
 * @package   ComposerUtility
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
class ComposerUtility
{
    private $output = array();
    private $exe = null;
    private $exeOptions = ' --no-ansi --no-interaction ';
    private $errors = array();

    /**
     * __construct
     */
    public function __construct()
    {

    }

    /**
     * composerExecute - execute a command using composer
     *
     * @param string $command_line command to pass to composer, i.e. 'update'
     *
     * @return boolean true on success, false if command failed or could not execute
     */
    public function composerExecute($command_line)
    {
        $this->output = array();
        $this->errors = array();

        $options = ' ' . trim($this->exeOptions) . ' ';

        if (empty($this->exe)) {
            $exeFinder = new PhpExecutableFinder();
            $foundExe = $exeFinder->find();
            if ($foundExe) {
                $this->exe = $foundExe . ' composer.phar';
            } else {
                $this->errors[] = 'Cannot find PHP executable';
                return false;
            }
        }

        if (!chdir(\XoopsBaseConfig::get('lib-path'))) {
            $this->errors[] = 'Cannot change directory to lib-path';
            return false;
        }

        set_time_limit(300); // don't want this script to timeout;
        $command = $this->exe . $options . $command_line;
        putenv('COMPOSER_HOME=' . \XoopsBaseConfig::get('var-path').'/composer');
        $process = new Process($command);
        //$process->setEnv(array('COMPOSER_HOME' => \XoopsBaseConfig::get('var-path').'/composer'));
        $process->setTimeout(120);
        try {
            $process->run(
                function ($type, $buffer) use (&$errors, &$output) {
                    if (Process::ERR === $type) {
                        $errors[] = $buffer;
                    } else {
                        $this->output[] = $buffer;
                    }
                }
            );
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        if ($process->isSuccessful()) {
            array_unshift($this->output, $process->getErrorOutput());
            return true;
        } else {
            $this->errors[] = 'Failed: ' . $command;
            $this->errors[] = sprintf(
                "Process exit code: %s, '%s'",
                $process->getExitCode(),
                $process->getExitCodeText()
            );
        }
        return false;
    }

    /**
     * getLastOutput - return output from last composerExecute()
     *
     * @return array
     */
    public function getLastOutput()
    {
        return $this->output;
    }

    /**
     * getLastError - return errors from last composerExecute()
     *
     * @return array
     */
    public function getLastError()
    {
        return $this->errors;
    }

    /**
     * setComposerExe - set a specific executable for Composer
     *
     * By default symfony/process looks for a PHP executable, and it is passed an
     * argument of a local copy of composer.phar. This method allows an override
     * for these defaults to be specified. Invoke this method before composerExecute().
     *
     * @param string $overrideExe command line to invoke composer
     *
     * @return void
     */
    public function setComposerExe($overrideExe)
    {
        $this->exe = $overrideExe;
    }
}
