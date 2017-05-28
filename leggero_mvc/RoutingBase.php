<?php
/**
 *
 */
class Route
{
  public $name;
  public $url;
  public $controller;
  public $action;

  function __construct($_url, $_controller, $_action, $_name=null)
  {
    $this->url = $_url;
    $this->controller = $_controller;
    $this->action = $_action;
  }
}
