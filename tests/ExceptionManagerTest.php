<?php
defined("IN_GOMA") or die();
use Goma\Error\ExceptionManager;
use PHPUnit\Framework\TestCase;
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
        ExceptionManager::Goma_ExceptionHandler(new \Goma\Error\NoticeException());
        $this->assertTrue(true);
    }

    /**
     * tests implicit php error throwing.
     */
    public function testPHPNotice() {
        define("MOTICE_1", 1);
        define("MOTICE_1", 2);
        $this->assertTrue(true);
    }
}
