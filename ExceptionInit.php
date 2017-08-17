<?php
use Goma\Error\ExceptionManager;

/**
 * Registers error and exception-handler.
 *
 * @package	goma/error
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */

set_error_handler(array(ExceptionManager::class, "handleError"));
set_exception_handler(array(ExceptionManager::class, "handleException"));
