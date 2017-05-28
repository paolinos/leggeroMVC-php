<?php
/**
 *
 */
class AutoLoad
{
  private $base_path = '';
  function __construct()
  {
    //$this->base_path = __DIR__;
  }

  function load($class_path){
    //$url = $this->base_path .'/'. $class_path .'.php';
    $url = $class_path .'.php';
    //if(file_exists($url)){
      require_once $url;
    //}
  }

}
