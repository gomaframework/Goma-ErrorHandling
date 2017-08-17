<?php
namespace Goma\Error;

use Throwable;

defined("IN_GOMA") or die();

/**
 * Extension-Interface for ExceptionManager extensions.
 * Register Extensions via ExceptionManager::registerExceptionHandler($class)
 *
 * @package	goma/error
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */
interface ExceptionHandler
{
    /**
     * @param Throwable $exception
     * @return bool|null
     */
    public static function handleException($exception);

    /**
     * @param Throwable $exception
     * @return bool|null
     */
    public static function isIgnorableException($exception);

    /**
     * @param Throwable $exception
     * @return bool|null
     */
    public static function isDeveloperPresentableException($exception);
}
