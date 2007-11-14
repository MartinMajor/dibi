<?php

/**
 * dibi - tiny'n'smart database abstraction layer
 * ----------------------------------------------
 *
 * Copyright (c) 2005, 2007 David Grudl aka -dgx- (http://www.dgx.cz)
 *
 * This source file is subject to the "dibi license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://php7.org/dibi/
 *
 * @copyright  Copyright (c) 2004, 2007 David Grudl
 * @license    http://php7.org/nette/license  Nette license
 * @link       http://php7.org/nette/
 * @package    Nette
 */



/**
 * Nette Exception base class
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2007 David Grudl
 * @package    Nette-Exception
 */
class NException extends Exception
{
    /** @var callback */
    private static $oldHandler;

    /** @var string */
    private static $handlerClass;


    /**
     * Enables converting all PHP errors to exceptions
     *
     * @param  Exception class to be thrown
     * @return void
     */
    public static function catchError($class = __CLASS__)
    {
        self::$oldHandler = set_error_handler(array(__CLASS__, '_errorHandler'), E_ALL);
        self::$handlerClass = $class;
    }



    /**
     * Disables converting errors to exceptions
     *
     * @return void
     */
    public static function restore()
    {
        if (self::$oldHandler !== NULL) {
            set_error_handler(self::$oldHandler);
            self::$oldHandler = NULL;
        } else {
            restore_error_handler();
        }
    }


    /**
     * Internal error handler
     */
    public static function _errorHandler($code, $message, $file, $line, $context)
    {
        self::restore();

        if (ini_get('html_errors')) {
            $message = strip_tags($message);
        }

        throw new self::$handlerClass($message, $code);
    }

}