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
  public $status;

  function __construct($_url, $_controller, $_action, $_name=null)
  {
    $this->status = 200;

    $this->url = $_url;
    $this->controller = $_controller;
    $this->action = $_action;
  }
}
