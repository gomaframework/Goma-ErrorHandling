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
     * At this point exceptions can be handled.
     * Return true if exception was handled and default handling or handling by others should be stopped.
     *
     * @param Throwable $exception
     * @return bool|null
     */
    public static function handleException($exception);

    /**
     * Ignorable exceptions are exceptions, which are not leading to a crash of the system, default: false
     *
     * Return null if no decision can be made.
     * Return boolean true or false to decide if ignorable or not.
     *
     * @param Throwable $exception
     * @return bool|null
     */
    public static function isIgnorableException($exception);

    /**
     * Developer presentable exceptions are exceptions, which will be printed in development mode even if ignorable.
     * Return null if no decision can be made.
     * Return boolean true or false to decide if developer-presentable or not.
     *
     * @param Throwable $exception
     * @return bool|null
     */
    public static function isDeveloperPresentableException($exception);
}
