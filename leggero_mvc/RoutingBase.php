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

class Dynamic
{
  private $properties = array();

  public function __get($name)
  {
    if (array_key_exists($name, $this->properties)) {
        return $this->properties[$name];
    }
  }
  public function __set($name, $value)
  {
      $this->properties[$name] = $value;
  }
}
