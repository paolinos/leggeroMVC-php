<?php
/**
 *
 */
class RoutingLeggero
{
  private $root_path;

  private $currentRoute;

  private $parameters = [];

  private $query_string;

  //  Setting Routes
  private $routesList;
  private $defaultRoute;

  private $excludeList;

  function __construct()
  {
    //  TODO: receive server parameter in the constructor
    $root_app = $_SERVER['SCRIPT_NAME'];

    $this->root_path = str_replace("index.php","",$root_app);

    $this->routesList = [];
    $this->defaultRoute = null;

    $this->excludeList = [];

    $this->currentRoute = new Route('','','','');
  }

  public function AddRoute($route, $isDefault=false){
    if($isDefault){
      $this->defaultRoute = $route;
    }else{
      $this->routesList[$route->url] = $route;
    }
  }
  public function AddExcludeRoute($route){
    $this->excludeList[] = $route;
  }

  public function GetRoute()
  {
    //  TODO: receive server parameter in the constructor
    $fullUrl = $_SERVER['REQUEST_URI'];

    // Remove all get parameters
    $url_params = explode("?", str_replace($this->root_path,"",$fullUrl));

    $urlParamTotal = count($url_params);
    if($urlParamTotal == 0){ return; }

    $url = $url_params[0];
    $url_structure = explode("/", $url);

    /*
    $this->query_string = "";
    if($urlParamTotal == 2){
      $this->query_string = $url_params[1];
    }*/
    $this->query_string = $_SERVER['QUERY_STRING'];
    $this->currentRoute->url = $url;

    // Check if there is in the exclude list.
    foreach($this->excludeList as $excludePath) {
        $tmpPos = strpos ($url, $excludePath);
        //echo("<br>url:[$url] - exclude:[$excludePath] - [" . $tmpPos . "]<br>" );
        if ($tmpPos !== false ){
          $this->currentRoute->status = 400;
          return $this->currentRoute;
        }
    }

    //  ---
    $count = count($url_structure);
    $tmpVal = null;

    for ($iStruc=0; $iStruc < $count; $iStruc++) {
      $tmpVal = $this->getVal($url_structure[$iStruc]);
      if($tmpVal != null){
        if($iStruc == 0){
          $this->currentRoute->controller = $tmpVal;
        }
        else if($iStruc == 1){
          $this->currentRoute->action = $tmpVal;
        }
        else{
          $this->parameters[] = $tmpVal;
        }
      }else{
        // Break!!!! //TODO: Add a better comment
        break;
      }
    }

    //  Search in the route list
    if(array_key_exists($url, $this->routesList)){
      $this->currentRoute = $this->routesList[$url];
      return $this->currentRoute;
    }else{
      // Check the default route
      if($this->defaultRoute != null){
        if($this->defaultRoute->url === $url){
          $this->currentRoute = $this->defaultRoute;
          return $this->currentRoute;
        }
      }
    }

    // Set default action
    if(empty($this->currentRoute->action)){
      $this->currentRoute->action = 'index';
    }
    return $this->currentRoute;
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
  public function getQueryString(){
    return $this->query_string;
  }

  public function addCustomRoute($name,$url,$controller,$action){

  }

  //TODO: Add regular expression to check only allowed characters
  private function getVal($val)
  {

    if(isset($val))
      return strtolower($val);

    return null;
  }
}
