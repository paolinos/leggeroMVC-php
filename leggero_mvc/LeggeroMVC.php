<?php
/**
 * @version    0.1
 * @author     Pablo
 * @license    MIT License
 * @copyright  Pablo
 * @link
 */

require_once 'AutoLoad.php';
require_once 'RoutingLeggero.php';
require_once 'LeggeroController.php';
require_once 'RoutingBase.php';
require_once 'LeggeroMVCHelper.php';

class LeggeroMVC
{
  private static $routing;
  private static $auto_load;

  //  Base application path
  private static $base_path;

  //  Items to render
  private static $render_items;

  private static $paths = array(
    'controller' => './app/controllers/',
    'view' => './app/views/',
    'model' => './app/models/',
    'helper' => './app/helpers/',
    'others' => []
  );

  /*
  //TODO: Not implemented correclty Yet!
  private static $default_routing = array(
    'name' => 'Default',
    'url' => '{controller}/{action}/{parameters}',
    'controller' => 'Main',
    'action' => 'Index'
  );
  */

  /**
   *    Set paths
   */
  public static function SetPath($pathArray){
    if(is_array(self::$paths)) self::$paths = (object)self::$paths;

    foreach ($pathArray as $key => $value) {
      if($key === 'controller'){
        self::$paths->controller = $value;
      }else if($key === 'view'){
        self::$paths->view = $value;
      }else if($key === 'model'){
        self::$paths->model = $value;
      }else if($key === 'helper'){
        self::$paths->helper = $value;
      }else if($key === 'others'){
        self::$paths->others = $value;
      }
    }
  }

  public static function AddRoute($url, $controller, $action, $name=null, $is_default=false){
    if(self::$routing === null){
        self::$routing = new RoutingLeggero();
    }
    self::$routing->AddRoute( new Route($url, $controller, $action, $name), $is_default );
  }

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

    if(is_array(self::$paths)) self::$paths = (object)self::$paths;

    // Initialize default variables
    //TODO: Check if we need to move this..
    self::$render_items = [];

    if(self::$routing === null){
        self::$routing = new RoutingLeggero();
    }

    self::$auto_load = new AutoLoad(self::$base_path);
    self::$auto_load->AddPath( self::$paths->controller );
    self::$auto_load->AddPath( self::$paths->model );
    self::$auto_load->AddPath( self::$paths->helper );
    self::$auto_load->AddArrayPath( self::$paths->others );

    // Get Controller and Action
    $route = self::$routing->GetRoute();

    $controllerName = $route->controller;
    $actionName = $route->action;

    /*
    $controllerName = self::$routing->getController();
    $actionName = self::$routing->getAction();

    //  Get default routing, if it's empty
    if(empty($controllerName)){
      $controllerName = self::$default_routing['controller'];
      $actionName = self::$default_routing['action'];
    }
    */

    // Get Controller
    $controllerName = ucfirst($controllerName);
    $controllerClassName = $controllerName . 'Controller';

    // Create controller
    $current_controller = new $controllerClassName($controllerName);
    // Call action
    call_user_func_array(
      array($current_controller, $actionName),
      self::$routing->getParameters()
    );

    self::Render();
  }


  public static function RenderView($path, $parameters = null){
    self::$render_items[] = array(
        'type' => 'view',
        'path' => $path,
        'params' => $parameters
    );
  }

  public static function RenderString($val){
    self::$render_items[] = array('type' => 'string', 'val' => $val );
  }

  //TODO:  Move Render to class
  private static function Render()
  {
    // is this ok? could we have more than one render?? mmm....
    /*
    $total = count(self::$render_items);
    for ($i=0; $i < $total; $i++) {
      $item = self::$render_items[$i];
      //echo($item);
      print_r($item);
    }
    */
    $total = count(self::$render_items);
    if($total == 0) return;


    $rendereable_item = self::$render_items[0];
    if($rendereable_item['type'] === 'view'){
      $view = $rendereable_item['path'];
      $params = $rendereable_item['params'];
      $properties = [];


      $layoutViewPath = sprintf ("%s%s%s" , self::$base_path, self::$paths->view, 'layout.phtml' );
      $viewPath = sprintf ("%s%s%s" , self::$base_path, self::$paths->view, $view );

      $LeggeroMVCHelper = new LeggeroMVCHelper($layoutViewPath);

      $propertiesView = [];
      if($params != null){
        $propertiesView['model'] = $params;
      }
      $LeggeroMVCHelper->AddView($viewPath, $propertiesView);
      $LeggeroMVCHelper->Render();

      //$properties['LeggeroMVCHelper'] = $LeggeroMVCHelper;

      /*
      $propertiesView = [];
      if($params != null){
        $propertiesView['model'] = $params;
      }*/

      /*
      $viewPath = sprintf ("%s%s%s" , self::$base_path, self::$paths->view, $view );
      extract($propertiesView);

      //$LeggeroMVCHelper->AddView($viewPath, $params);
      //srequire_once $viewPath;
      //$properties['body'] =  $viewPath;

      $layoutViewPath = sprintf ("%s%s%s" , self::$base_path, self::$paths->view, 'layout.phtml' );

      extract($properties);

      require_once $layoutViewPath;

      */

    }else{
      echo($rendereable_item['val']);
    }

  }
}
