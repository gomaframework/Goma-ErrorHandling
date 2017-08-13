<?php

namespace Goma\Error;

use Exception;

defined("IN_GOMA") OR die();

/**
 * BaseClass for all Goma-Exceptions. Goma-Exceptions have to additional features:
 *
 * - HTTP-Status
 * - Developer-Message for internal logging purpose
 *
 * @package	goma/error
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */
class GomaException extends Exception
{
    /**
     * @var int
     */
    protected $standardCode = ExceptionManager::EXCEPTION;

    /**
     * GomaException constructor.
     *
     * @param string $message
     * @param null|int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = null, Exception $previous = null) {
        if(!isset($code)) {
            $code =  $this->standardCode;
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * returns http-status, which is connected to this exception.
     *
     * @return int
     */
    public function http_status() {
        return 500;
    }

    /**
     * returns internal developer-message.
     *
     * @return string
     */
    public function getDeveloperMessage() {
        return $this->http_status() != 200 ? " Status: " . $this->http_status() : "";
    }
}
