<?php

//-----
//          Routing
/**
 *
 */
class BaseRouting
{
  private $root_path;

  private $controller = "";
  private $action = "index";
  private $parameters = [];

  function __construct()
  {
    $root_app = $_SERVER['SCRIPT_NAME'];
    $this->root_path = str_replace("index.php","",$root_app);

    $fullUrl = $_SERVER['REQUEST_URI'];
    $url_params = explode("?", str_replace($this->root_path,"",$fullUrl));

    if(count($url_params) == 0){
      return;
    }

    $url_structure = explode("/", $url_params[0]);


    $count = count($url_structure);
    $tmpVal = null;
    for ($iStruc=0; $iStruc < $count; $iStruc++) {
      $tmpVal = $this->getVal($url_structure[$iStruc]);
      if($tmpVal != null){
        if($iStruc == 0){
          $this->controller = $tmpVal;
        }
        else if($iStruc == 1){
          $this->action = $tmpVal;
        }
        else{
          $this->parameters[] = $tmpVal;
        }
      }
      else{
        // Break!!!! //TODO: Add a better comment
        break;
      }
    }

    /*
    echo('- Controller:' . $this->controller . '<br>');
    echo('- Action:' . $this->action . '<br>');
    echo('- Parameters:');
    print_r($this->parameters);
    */
  }

  public function getController(){
    return $this->controller;
  }
  public function getAction(){
    return $this->action;
  }
  public function getParameters(){
    return $this->parameters;
  }

  //TODO: Add regular expression to check only allowed characters
  private function getVal($val)
  {
    if(empty($val))
      return null;

    return strtolower($val);
  }


}

/**
 *
 */
class AutoLoad
{
  private $base_path = '';
  function __construct()
  {
    $this->base_path = __DIR__;
  }

  function load($class_path){
    $url = $this->base_path .'/'. $class_path .'.php';
    //if(file_exists($url)){
      require_once $url;
    //}
  }


}



/**
 *
 */
class LeggeroMVC
{
  private static $routing;
  private static $auto_load;

  private static $render_items;
  //function __construct() {}

  public static function init()
  {
    //TODO: Fix problems with   event handler!!
    //error_reporting(0);
    //ini_set('display_errors', 1);
    //set_error_handler("self::ErrorHandler",E_ALL);
    //register_shutdown_function("self::ShutdownHandler");


    self::$render_items = [];
    self::$routing = new BaseRouting();
    self::$auto_load = new AutoLoad();


    $controllerName = ucfirst(self::$routing->getController()) . 'Controller';
    self::$auto_load->load(
      'app/controllers/' . $controllerName
    );

    $current_controller = new $controllerName();
    //echo('Action: ' . self::$routing->getAction());
    call_user_func_array( array($current_controller, self::$routing->getAction() ), self::$routing->getParameters() );

    self::Render();

    try {


    } catch (Exception $e) {
      print_r($e);
    }

  }


  public static function RenderView($path, $parameters){
    self::$render_items[] = array(
        'type' => 'view',
        'path' => $path,
        'params' => $parameters
    );
  }

  public static function RenderString($val){
    self::$render_items[] = array('type' => 'string', 'val' => $val );
  }


  private static function Render()
  {
    $total = count(self::$render_items);
    for ($i=0; $i < $total; $i++) {
      $item = self::$render_items[$i];
      echo($item['val']);
    }
  }

  private static function ShutdownHandler(){
    echo('ShutdownHandler');
  }

  private static function ErrorHandler($errno, $errstr, $errfile, $errline){
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

LeggeroMVC::init(
                /*
                array(
                  'controllers' => 'api/controllers',
                  'views' => 'api/views',
                  'services' => 'api/services',
                  'repositories' => 'api/repositories',
                  'helpers' => 'api/helpers',
                  )
                  */
                );




/*
$fullUrl = $_SERVER['REQUEST_URI'];
$url_structure = explode("/", str_replace($root_app,"",$fullUrl));

$controller = "";
$action = "";
$parameters = [];






if($count == 1){
  $controller = $url_structure[0];
  $action = "index";
}
if($count == 2){
  $controller = $url_structure[0];
  $action = $url_structure[1];
}
if($count >= 3){
  $controller = $url_structure[0];
  $action = $url_structure[1];

  //$url_structure
  for ($parPos=2; $parPos < $count; $parPos++) {
    $tmpVal = $url_structure[$parPos];
    if(!empty($tmpVal)){
        $parameters[] = $tmpVal;
    }
  }
}

echo('controller name: '. $controller  );echo('<br>');
echo('action name: '. $action  );echo('<br>');
echo('parameters name: ');  print_r($parameters);echo('<br>');
*/






/*
echo('REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . '<br>' );
echo('SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME'] . '<br>');

echo('<br>');



print_r($_SERVER);
echo("<br>");
print_r(__DIR__);
echo("<br>");
print_r($_GET);
echo("<br>");


echo("ACA");
*/
 ?>
