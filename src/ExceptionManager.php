<?php
namespace Goma\Error;
use ErrorException;
use Goma\ENV\GomaENV;
use Throwable;

defined("IN_GOMA") OR die();

/**
 * ExceptionManager provides codes for exceptions.
 * In addition it adds default exception and error handler.
 *
 * @package	goma/error
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */
class ExceptionManager
{
    /**
     * used when cache could not be created cause directory does not exist,
     * is not creatable or autoloader_exclude does not exist and can't be created.
     */
    const ERR_CACHE_NOT_INITED = -250;

    /**
     * db connect error.
     */
    const DB_CONNECT_ERROR = -25;
    const SQL_EXCEPTION = -26;

    /**
     * if permissions are not enough to view this page.
     */
    const PERMISSION_ERROR = -5;

    /**
     * normal exception.
     */
    const EXCEPTION = -1;

    /**
     * unknown PHP-Error.
     */
    const PHP_ERROR = -6;

    /**
     * application version missmatch.
     */
    const APPLICATION_FRAMEWORK_VERSION_MISMATCH = -10;

    /**
     * security-error.
     */
    const SECURITY_ERROR = -1;

    /**
     * error when classinfo could not be written.
     */
    const CLASSINFO_WRITE_ERROR = -8;

    /**
     * class not found.
     */
    const CLASS_NOT_FOUND = -7;

    /**
     * called when version.php cant be written.
     */
    const SOFTWARE_UPGRADE_WRITE_ERROR = -12;

    /**
     * called when expansion with name wasnt found, but data were requested.
     */
    const EXPANSION_NOT_FOUND = -15;

    /**
     * email invalid.
     */
    const EMAIL_INVALID = -20;

    /**
     * login invalid.
     */
    const LOGIN_INVALID = -16;

    /**
     * invalid state transition.
     */
    const INVALID_STATE = -19;

    /**
     * user locked.
     */
    const LOGIN_USER_LOCKED = -17;

    /**
     * user not unlocked yet.
     */
    const LOGIN_USER_MUST_UNLOCK = -18;

    /**
     * store connection failed.
     */
    const STORE_CONNECTION_FAIL = -31;

    /**
     * 503
     */
    const SERVICE_UNAVAILABLE = -503;

    /**
     * invalid indexes
     */
    const INDEX_INVALID = -60;

    /**
     * db field invalid.
     */
    const DB_FIELD_INVALID = -61;

    /**
     * no inverse found.
     */
    const RELATIONSHIP_INVERSE_REQUIRED = -62;

    /**
     * data
     */
    const BAD_REQUEST = -400;
    const DATA_NOT_FOUND = -404;

    /**
     * gd.
     */
    const GD_EXCEPTION = -700;
    const GD_FILE_MALFORMED = -701;
    const GD_TYPE_NOT_SUPPORTED = -701;

    /**
     * file.
     */
    const FILE_EXCEPTION = -800;
    const FILE_NOT_PERMITTED = -803;
    const FILE_NOT_FOUND = -804;
    const FILE_ALREADY_EXISTING = -810;
    const FILE_COPY_FAIL = -820;
    const PROJECT_CONFIG_WRITE_ERROR = -825;

    /**
     * form
     */
    const FORM_INVALID = -900;
    const FORM_NOT_SUBMITTED = -901;

    /**
     * file upload
     */
    const FILEUPLOAD_FAIL = -601;
    const FILEUPLOAD_SIZE_FAIL = -602;
    const FILEUPLOAD_TYPE_FAIL = -603;
    const FILEUPLOAD_DISK_SPACE_FAIL = -604;

    const TPL_COMPILE_ERROR = -10;
    const TPL_NOT_FOUND = -650;

    /**
     * lists
     */
    const ITEM_NOT_FOUND = -944;

    /**
     * model.
     */
    const DATAOBJECTSET_COMMIT = -1105;

    /**
     * gfs
     */
    const GFSException = -4000;
    const GFSVersionException = -4001;
    const GFSFileException = -4002;
    const GFSDBException = -4003;
    const GFSReadOnlyException = -4004;
    const GFSFileNotFoundException = -4005;
    const GFSFileNotValidException = -4006;
    const GFSFileExistsException = -4007;
    const GFSRealFileNotExistsException = -4008;
    const GFSRealFilePermissionException = -4009;
    const GD_FILE_TOO_BIG = -4010;

    /**
     * third-party exception-handlers.
     */
    protected static $exceptionHandlers = array();

    /**
     * This method registers a new exception-handler.
     * Exception-Handlers get all exceptions in advance. By returning true exception-handling of other handlers is stopped.
     * @param string $exceptionHandlerClass class of type ExceptionHandler
     * @param bool $prepend
     */
    public static function registerExceptionHandler($exceptionHandlerClass, $prepend = false) {
        if(!is_string($exceptionHandlerClass)) {
            throw new \InvalidArgumentException("\$exceptionHandlerClass must be a valid classname for registerExceptionHandler.");
        }

        if($prepend) {
            array_unshift(self::$exceptionHandlers, $exceptionHandlerClass);
        } else {
            array_push(self::$exceptionHandlers, $exceptionHandlerClass);
        }
    }

    /**
     * PHP-Error-Handling
     *
     * @param $err_severity
     * @param $err_msg
     * @param $err_file
     * @param $err_line
     * @param null $errcontext
     * @return bool
     * @throws \Exception
     */
    public static function handleError($err_severity, $err_msg, $err_file, $err_line, $errcontext = null)
    {
        if (0 === error_reporting()) {
            return false;
        }

        try {
            switch ($err_severity) {
                case E_ERROR:
                    throw new ErrorException            ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_WARNING:
                    throw new WarningException          ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_PARSE:
                    throw new ParseException            ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_NOTICE:
                    throw new NoticeException           ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_CORE_ERROR:
                    throw new CoreErrorException        ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_CORE_WARNING:
                    throw new CoreWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_COMPILE_ERROR:
                    throw new CompileErrorException     ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_COMPILE_WARNING:
                    throw new CoreWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_ERROR:
                    throw new UserErrorException        ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_WARNING:
                    throw new UserWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_NOTICE:
                    throw new UserNoticeException       ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_STRICT:
                    throw new StrictException           ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_RECOVERABLE_ERROR:
                    throw new RecoverableErrorException ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_DEPRECATED:
                    throw new DeprecatedException       ($err_msg, 0, $err_severity, $err_file, $err_line);
                case E_USER_DEPRECATED:
                    throw new UserDeprecatedException   ($err_msg, 0, $err_severity, $err_file, $err_line);
            }
        } catch (\Exception $e) {
            if (isset($e->isIgnorable) && $e->isIgnorable) {
                self::handleException($e);
            } else {
                throw $e;
            }
        }

        // block PHP's internal Error-Handler
        return true;
    }

    /**
     * Ignorable exceptions are exceptions, which are not leading to a crash of the system, for example: TaskCancelledException
     * By default all exceptions are *not* ignorable.
     *
     * @param Throwable $exception
     * @return bool
     */
    public static function isIgnorable($exception) {
        // check for other error-handlers.
        foreach(self::$exceptionHandlers as $exceptionHandler) {
            $return = call_user_func_array(array($exceptionHandler, "isIgnorableException"), array($exception)) ;
            if($return !== null) {
                return $return;
            }
        }

        if(isset($exception->isIgnorable) && $exception->isIgnorable) {
            return true;
        }

        return false;
    }

    /**
     * Developer presentable exceptions are exceptions, which will be printed in development mode even if ignorable.
     * By default all exceptions are developer-presentable.
     *
     * @param Throwable $exception
     * @return bool
     */
    public static function isDeveloperPresentable($exception) {
        // check for other error-handlers.
        foreach(self::$exceptionHandlers as $exceptionHandler) {
            $return = call_user_func_array(array($exceptionHandler, "isDeveloperPresentableException"), array($exception)) ;
            if($return !== null) {
                return $return;
            }
        }

        if(isset($exception->isDeveloperPresentable) && $exception->isDeveloperPresentable == false) {
            return false;
        }

        return true;
    }

    /**
     * @param Throwable $exception
     */
    public static function handleException($exception) {
        $uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : (isset($_SERVER["argv"]) ? implode(" ", $_SERVER["argv"]) : null);

        // check for other error-handlers.
        foreach(self::$exceptionHandlers as $exceptionHandler) {
            if(call_user_func_array(array($exceptionHandler, "handleException"), array($exception)) === true) {
                return;
            }
        }

        if(self::isDeveloperPresentable($exception) && (GomaENV::isDevMode() || GomaENV::isDevModeCLI() || GomaENV::isPHPUnit())) {
            echo $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
        }

        if(self::isIgnorable($exception)) {
            return;
        }

        $details = static::getUserDetailsFromException($exception);
        $current = $exception;
        while($current = $current->getPrevious()) {
            $details .= static::getUserDetailsFromException($current);
        }

        $content = file_get_contents(static::getTemplateForException());
        $content = str_replace('{BASE_URI}', "", $content);
        $content = str_replace('{$errcode}', $exception->getCode(), $content);
        $content = str_replace('{$errname}', get_class($exception), $content);
        $content = str_replace('{$errdetails}', $details, $content);
        $content = str_replace('$uri', $uri, $content);

        if(!GomaENV::isCommandLineInterface()) {
            /*if (method_exists($exception, "http_status")) {
                HTTPResponse::setResHeader($exception->http_status());
            } else {
                HTTPResponse::setResHeader(500);
            }
            HTTPResponse::sendHeader();*/
            // TODO: Replace with HTTP-Package
            echo $content;
        } else {
            echo $exception->getCode() . ":" . $exception->getMessage() . "\n" . $details . "\n";
        }

        exit($exception->getCode() != 0 ? $exception->getCode() : 8);
    }

    /**
     * @return string
     */
    protected static function getTemplateForException() {
        if(file_exists(GomaENV::getRoot() . "templates/phperror.html")) {
            return GomaENV::getRoot() . "templates/phperror.html";
        }

        return dirname(__FILE__) . "/../templates/phperror.html";
    }

    /**
     * @param Throwable $e
     * @return string
     */
    public static function getUserDetailsFromException($e) {
        $trace = method_exists($e, "getTraceForUser") ? $e->getTraceForUser() : $e->getTraceAsString();
        return static::getExceptionMessageOrClass($e) .
            "\n<br />\nin " . $e->getFile() . " on line ".$e->getLine() . "<br />\n<textarea style=\"width: 100%; height: 300px;\">" . $trace . "</textarea>";
    }

    /**
     * @param Throwable $e
     * @return string
     */
    public static function getExceptionMessageOrClass($e) {
        return $e->getMessage() ? $e->getMessage() : get_class($e) . ": " . $e->getCode();
    }
}

class WarningException              extends ErrorException {
    public $isIgnorable = true;
}
class ParseException                extends ErrorException {}
class NoticeException               extends ErrorException {
    public $isIgnorable = true;
}
class CoreErrorException            extends ErrorException {}
class CoreWarningException          extends ErrorException {}
class CompileErrorException         extends ErrorException {}
class CompileWarningException       extends ErrorException {}
class UserErrorException            extends ErrorException {}
class UserWarningException          extends ErrorException {
    public $isIgnorable = true;
    public $isDeveloperPresentable = true;
}
class UserNoticeException           extends ErrorException {
    public $isIgnorable = true;
}
class StrictException               extends ErrorException {}
class RecoverableErrorException     extends ErrorException {}
class DeprecatedException           extends ErrorException {
    public $isIgnorable = true;
}
class UserDeprecatedException       extends ErrorException {
    public $isIgnorable = true;
}
