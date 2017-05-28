<?php
/**
 *
 */
class LeggeroHandlingErrors
{

  function __construct()
  {
    /*
    define('DEBUG_APP',true);
    if(DEBUG_APP){
      ini_set('display_errors','On');
      error_reporting(E_ALL);
    }else{
      // Turn off all error reporting
      ini_set('display_errors','Off');
      error_reporting(0);
    }
    */
    error_reporting(0);
    ini_set('display_errors', 1);
    set_error_handler("ErrorHandler",E_ALL);
    register_shutdown_function("ShutdownHandler");
  }

  private function ShutdownHandler(){
    echo('ShutdownHandler');
  }

  private function ErrorHandler($errno, $errstr, $errfile, $errline){
    echo('ErrorHandler');
    print_r($errno);
    return false;
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
  }
}
