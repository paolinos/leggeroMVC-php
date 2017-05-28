<?php

/**
 *
 */
class RoutingLeggero
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

  public function addCustomRoute($name,$url,$controller,$action){

  }

  //TODO: Add regular expression to check only allowed characters
  private function getVal($val)
  {
    if(empty($val))
      return null;

    return strtolower($val);
  }
}
