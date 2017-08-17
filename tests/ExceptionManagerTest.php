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

    protected $exceptionHandlers;

    /**
     * saves exceptionHandlers to $this->exceptionHandlers and
     * clears reflection property exceptionHandlers.
     */
    protected function clearExceptionHandlers()
    {
        $reflectionProperty = new \ReflectionProperty(ExceptionManager::class, "exceptionHandlers");
        $reflectionProperty->setAccessible(true);
        $this->exceptionHandlers = $reflectionProperty->getValue();
        $reflectionProperty->setValue(array());
    }

    /**
     * restores exceptionHandlers from $this->exceptionHandlers.
     */
    protected function restoreExceptionHandlers() {
        $reflectionProperty = new \ReflectionProperty(ExceptionManager::class, "exceptionHandlers");
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->exceptionHandlers);
    }

    /**
     * Tests up ExceptionManager with MockExceptionHandler class.
     * It clears all others to not have problems with other handlers.
     */
    public function setUp() {
        $this->clearExceptionHandlers();

        ExceptionManager::registerExceptionHandler(MockExceptionHandler::class);
    }

    /**
     * Restores exception handlers.
     */
    public function tearDown() {
        $this->restoreExceptionHandlers();
    }

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
        MockExceptionHandler::$handleExceptionCalled  = 0;
        MockExceptionHandler::$handle = true;

        ExceptionManager::handleException(new ParseException());

        $this->assertEqual(1, MockExceptionHandler::$handleExceptionCalled);
    }

    //region isIgnorable

    /**
     * tests if isIgnorable is called.
     */
    public function testIgnoreOnHandlerCalled() {
        MockExceptionHandler::$isIgnorableCalled  = 0;

        ExceptionManager::isIgnorable(new NoticeException());

        $this->assertEqual(1, MockExceptionHandler::$isIgnorableCalled);
    }

    /**
     * tests if isIgnorable of handler is more important for true than on exception itself.
     */
    public function testIgnoreOnHandlerHasPriorityTrue() {
        MockExceptionHandler::$isIgnorableCalled  = 0;
        MockExceptionHandler::$ignorable = true;

        $this->assertTrue(ExceptionManager::isIgnorable(new ParseException()));
        $this->assertEqual(1, MockExceptionHandler::$isIgnorableCalled);
    }

    /**
     * tests if isIgnorable of handler is more important for false than on exception itself.
     */
    public function testIgnoreOnHandlerHasPriorityFalse() {
        MockExceptionHandler::$isIgnorableCalled  = 0;
        MockExceptionHandler::$ignorable = false;

        $this->assertFalse(ExceptionManager::isIgnorable(new NoticeException()));
        $this->assertEqual(1, MockExceptionHandler::$isIgnorableCalled);
    }

    /**
     * tests if property $exception->isIgnorable is used by isIgnorable if no ExceptionHandler overrides it.
     * $exception->isIgnorable = false
     */
    public function testIgnorablePropertyFalse() {
        MockExceptionHandler::$ignorable = null;

        $exception = new ParseException();
        $exception->isIgnorable = false;
        $this->assertFalse(ExceptionManager::isIgnorable($exception));
    }

    /**
     * tests if property $exception->isIgnorable is used by isIgnorable if no ExceptionHandler overrides it.
     * $exception->isIgnorable = true
     */
    public function testIgnorablePropertyTrue() {
        MockExceptionHandler::$ignorable = null;

        $exception = new ParseException();
        $exception->isIgnorable = true;
        $this->assertTrue(ExceptionManager::isIgnorable($exception));
    }

    //endregion

    //region isDeveloperPresentable

    /**
     * tests if property $exception->isDeveloperPresentable is used by isDeveloperPresentable if no ExceptionHandler overrides it.
     * $exception->isIgnorable = false
     */
    public function testDevPresentablePropertyFalse() {
        MockExceptionHandler::$devPresentable = null;

        $exception = new ParseException();
        $exception->isDeveloperPresentable = false;
        $this->assertFalse(ExceptionManager::isDeveloperPresentable($exception));
    }

    /**
     * tests if property $exception->isDeveloperPresentable is used by isDeveloperPresentable if no ExceptionHandler overrides it.
     * $exception->isIgnorable = true
     */
    public function testDevPresentablePropertyTrue() {
        MockExceptionHandler::$devPresentable = null;

        $exception = new ParseException();
        $exception->isDeveloperPresentable = true;
        $this->assertTrue(ExceptionManager::isDeveloperPresentable($exception));
    }

    /**
     * tests if DevPresentable of handler is more important for true than on exception itself.
     */
    public function testDevPresentableOnHandlerHasPriorityTrue() {
        MockExceptionHandler::$isDevPresentableCalled  = 0;
        MockExceptionHandler::$devPresentable = true;

        $exception = new ParseException();
        $exception->isDeveloperPresentable = false;
        $this->assertTrue(ExceptionManager::isDeveloperPresentable($exception));
        $this->assertEqual(1, MockExceptionHandler::$isDevPresentableCalled);
    }

    /**
     * tests if DevPresentable of handler is more important for false than on exception itself.
     */
    public function testDevPresentableOnHandlerHasPriorityFalse() {
        MockExceptionHandler::$isDevPresentableCalled  = 0;
        MockExceptionHandler::$devPresentable = false;

        $this->assertFalse(ExceptionManager::isDeveloperPresentable(new NoticeException()));
        $this->assertEqual(1, MockExceptionHandler::$isDevPresentableCalled);
    }

    /**
     * tests if isDevPresentable is called.
     */
    public function testDevPresentableOnHandlerCalled() {
        MockExceptionHandler::$isDevPresentableCalled  = 0;

        ExceptionManager::isDeveloperPresentable(new NoticeException());

        $this->assertEqual(1, MockExceptionHandler::$isDevPresentableCalled);
    }

    //endregion
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
