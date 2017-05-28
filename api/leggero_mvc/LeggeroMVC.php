<?php
/**
 *
 */
require_once 'AutoLoad.php';
require_once 'RoutingLeggero.php';

class LeggeroMVC
{
  private static $routing;
  private static $auto_load;

  private static $render_items;
  //function __construct() {}


  private static $paths = array(
    'controller' => './app/controllers/',
    'view' => './app/views/',
    'model' => './app/models/',
    'helper' => './app/helpers/',
    'others' => []
  );

  private static $default_routing = array(
    'name' => 'Default',
    'url' => '{controller}/{action}/{parameters}',
    'controller' => 'Main',
    'action' => 'Index'
  );


  public static function SetPath($list){

  }
  public static function SetRouting($list){

  }

  private static $base_path = '';

  public static function Run($path=null)
  {
    //TODO: Fix problems with Error event handler!!

    // Set default path
    if( $path == null){
      self::$base_path = dirname($_SERVER['SCRIPT_FILENAME']);
    }else{
      self::$base_path = $path;
    }
    self::$base_path .= '/';

    // Initialize default variables
    //TODO: Check if we need to move this..
    self::$render_items = [];
    self::$routing = new RoutingLeggero();
    self::$auto_load = new AutoLoad();

    // Get Controller and Action
    $controllerName = self::$routing->getController();
    $actionName = self::$routing->getAction();

    //  Get default routing, if it's empty
    if(empty($controllerName)){
      $controllerName = self::$default_routing['controller'];
      $actionName = self::$default_routing['action'];
    }


    // Get Controller
    $controllerName = ucfirst($controllerName) . 'Controller';
    self::$auto_load->load(
      self::$base_path . 'app/controllers/' . $controllerName
    );

    // Create controller
    $current_controller = new $controllerName();
    // Call action
    call_user_func_array(
      array($current_controller, $actionName),
      self::$routing->getParameters()
    );

    self::Render();
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
}
