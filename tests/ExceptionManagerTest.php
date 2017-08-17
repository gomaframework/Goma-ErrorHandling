<?php
namespace Goma\Error\Test;

defined("IN_GOMA") or die();

use Goma\Error\ExceptionHandler;
use Goma\Error\ExceptionManager;
use Goma\Error\NoticeException;
use Goma\Error\ParseException;
use GomaUnitTest;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Tests for ExceptionManager.
 *
 * @package	goma/error
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */

class ExceptionManagerTest extends GomaUnitTest
{
    /**
     * throw ignorable exception should not interrupt.
     */
    public function testthrowIgnorableException() {
        ExceptionManager::handleException(new NoticeException());
        $this->assertTrue(true);
    }

    /**
     * tests if ExceptionManager::isIgnorable returns true for NoticeException.
     */
    public function testIsIgnorableTrue() {
        $this->assertTrue(ExceptionManager::isIgnorable(new NoticeException()));
    }

    /**
     * tests if ExceptionManager::isIgnorable returns false for NoticeException.
     */
    public function testIsIgnorableFalse() {
        $this->assertFalse(ExceptionManager::isIgnorable(new ParseException()));
    }

    /**
     * tests implicit php error throwing.
     */
    public function testPHPNotice() {
        define("MOTICE_1", 1);
        define("MOTICE_1", 2);
        $this->assertTrue(true);
    }

    /**
     * tests if exception-handler is called.
     */
    public function testExceptionHandlerCalled() {
        ExceptionManager::registerExceptionHandler(MockExceptionHandler::class);

        MockExceptionHandler::$handleExceptionCalled  = 0;
        MockExceptionHandler::$handle = false;

        ExceptionManager::handleException(new NoticeException());

        $this->assertEqual(1, MockExceptionHandler::$handleExceptionCalled);
    }

    /**
     * tests if exception-handler's true stops exception-handling.
     *
     * This test will fail if not handled, since ExceptionHandler will exit.
     */
    public function testExceptionHandlerStopHandling() {
        ExceptionManager::registerExceptionHandler(MockExceptionHandler::class);

        MockExceptionHandler::$handleExceptionCalled  = 0;
        MockExceptionHandler::$handle = true;

        ExceptionManager::handleException(new ParseException());

        $this->assertEqual(1, MockExceptionHandler::$handleExceptionCalled);
    }
}

class MockExceptionHandler implements ExceptionHandler {

    /**
     * indicates whether to handle or not exceptions.
     *
     * @var bool
     */
    static $handle = false;

    /**
     * defines what to return for isIgnorable.
     * @var bool|null
     */
    static $ignorable = null;

    /**
     * defines what to return for developerPresentable.
     * @var bool|null
     */
    static $devPresentable = null;

    /**
     * @var int
     */
    static $handleExceptionCalled = 0;

    /**
     * @var int
     */
    static $isIgnorableCalled = 0;

    /**
     * @var int
     */
    static $isDevPresentableCalled = 0;

    /**
     * @param Throwable $exception
     * @return bool|null
     */
    public static function handleException($exception)
    {
        self::$handleExceptionCalled++;
        return self::$handle;
    }

    /**
     * @param Throwable $exception
     * @return bool|null
     */
    public static function isIgnorableException($exception)
    {
        self::$isIgnorableCalled++;
        return self::$ignorable;
    }

    /**
     * @param Throwable $exception
     * @return bool|null
     */
    public static function isDeveloperPresentableException($exception)
    {
        self::$isDevPresentableCalled++;
        return self::$devPresentable;
    }
}
