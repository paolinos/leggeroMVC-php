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

  //
  private static $isDebug = false;

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

  public static function EnableDebug(){
    self::$isDebug = true;
  }

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

  /**
    * Add Route, to configure your route.
    */
  public static function AddRoute($url, $controller, $action, $name=null, $is_default=false){
    if(self::$routing === null){
        self::$routing = new RoutingLeggero();
    }
    self::$routing->AddRoute( new Route($url, $controller, $action, $name), $is_default );
  }

  /**
    * Exclude Paths
    * @param $excludes, array with name and path.
    */
  public static function ExcludePaths($excludes){
    if(self::$routing === null){
        self::$routing = new RoutingLeggero();
    }
    //  TODO: why I need the key? is it less performance??
    foreach ($excludes as $key => $value) {
      self::$routing->AddExcludeRoute($value);
    }
  }


  private static $RootPath;
  private static $AbsolutePath;
  private static $RelativePath;

  public static function Run($path=null)
  {
    try {
      $internal_error = false;
      // Set default path
      if( $path == null){
        self::$base_path = dirname($_SERVER['SCRIPT_FILENAME']);
      }else{
        self::$base_path = $path;
      }
      self::$base_path .= '/';

      //   [REQUEST_SCHEME] => http
      //  [SERVER_NAME] => localhost

      //print_r($_SERVER);
      $documentRoot = $_SERVER['DOCUMENT_ROOT'];
      self::$RootPath = str_replace($documentRoot,"", self::$base_path);
      //echo("<br>root Path:".self::$RootPath."<br>");

      if(array_key_exists('REQUEST_SCHEME',$_SERVER)){
          self::$AbsolutePath = $_SERVER['REQUEST_SCHEME'] . '://'  . $_SERVER['SERVER_NAME'] .self::$RootPath;
      }else{
          self::$AbsolutePath = 'http://'.$_SERVER['SERVER_NAME'] .self::$RootPath;
      }
      //echo("<br>Absolute Path:".self::$AbsolutePath."</br>");

      //
      $requestUri = $_SERVER['REQUEST_URI'];
      self::$RelativePath = str_replace(self::$RootPath,"", $requestUri);
      //echo("<br>current Path File:".self::$RelativePath."<br>");

      //  TODO: Check mimetype or extension, to only run framework when is valid
      /*
      $content_type_allowed = ['text/html','application/xhtml+xml','application/xml'];
      $content_type_allowed_total = count($content_type_allowed);
      $current_content_type = $_SERVER['HTTP_ACCEPT'];
      print_r($_SERVER);
      $contentTypeValid = false;
      for ($i=0; $i < $content_type_allowed_total; $i++) {
        $tmpContent = $content_type_allowed[$i];
        if (strpos($current_content_type, $tmpContent)) {
            $contentTypeValid = true;
            break;
        }
      }

      if($contentTypeValid == false){
        //print_r($_SERVER);
        //$uri = $_SERVER['REQUEST_URI'];
        //echo($uri . "</br>");
        //echo(self::$base_path. "</br>");
        //require_once
        return;
      }
      */

      //TODO: Fix problems with Error event handler!!
      //  Add Error handler here!


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
      if($route->status !== 200){
        http_response_code($route->status);
        return;
      }


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
      if (class_exists($controllerClassName)) {
        $current_controller = new $controllerClassName($controllerName, self::$RootPath, self::$routing->getQueryString());

        $callableData = array($current_controller, $actionName);
        if(is_callable($callableData))
        {
          // Call action
          call_user_func_array(
            $callableData,
            self::$routing->getParameters()
          );
          self::Render($current_controller);
        }else{
          $internal_error = true;
        }
      }else{
        $internal_error = true;
      }

    } catch (Exception $e) {
      $internal_error = true;
    }

    if($internal_error){
      //TODO: Create Error template and display the page
      header("HTTP/1.0 404 Not Found");
      echo "<h1>Ups....Page Not Found</h1>";
      echo "The page that you have requested could not be found.";
      exit();
    }

  }


  public static function RenderView($path, $parameters = null, $layoutName='layout', $viewExtension='phtml'){
    self::$render_items[] = array(
        'type' => 'view',
        'path' => $path,
        'params' => $parameters,
        'layout' => $layoutName,
        'view_ext' => $viewExtension,
    );
  }

  public static function RenderJson($val){
    //self::$render_items[] = array('type' => 'string', 'val' => $val );
    header('Content-type: application/json');
    echo($val);
  }

  //TODO:  Move Render to class
  private static function Render($current_controller)
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
    // Take the first view and render it
    $total = count(self::$render_items);
    if($total == 0) return;

    $rendereable_item = self::$render_items[0];
    if($rendereable_item['type'] === 'view'){
      $view = $rendereable_item['path'];
      $params = $rendereable_item['params'];
      $properties = [];

      $viewPath = "";

      $layout = $rendereable_item['layout'];
      if($layout === null){
        // No Layout
        $layoutViewPath = sprintf ("%s%s%s" , self::$base_path, self::$paths->view, $view);

      }else
      {
        if($layout === ""){
          //  Load defautl layout
          $layout = "layout";
        }
        $view_ext = $rendereable_item['view_ext'];

        $layoutViewPath = sprintf ("%s%s%s" , self::$base_path, self::$paths->view, $layout . '.' . $view_ext);
        $viewPath = sprintf ("%s%s%s" , self::$base_path, self::$paths->view, $view );
      }

      $LeggeroMVCHelper = new LeggeroMVCHelper($layoutViewPath, $current_controller->ViewProp, self::$isDebug);
      $LeggeroMVCHelper->SetAbsolutePath(self::$AbsolutePath);

      $propertiesView = [];
      if($params != null){
        $propertiesView['model'] = $params;
      }
      $LeggeroMVCHelper->SetView($viewPath, $propertiesView);
      $LeggeroMVCHelper->Render();

      $current_controller = null;

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
