<?php
/**
 *    LeggeroController
 *    Base controller
 */
class LeggeroController
{
  private $controller_name;
  private $controller_classname;

  protected $extension_view = 'phtml';

  function __construct($name)
  {
    $this->controller_name = $name;
    $this->controller_classname = get_class($this);
  }

  protected function View($view_name, $parameter=null)
  {
    LeggeroMVC::RenderView(
                        sprintf("%s/%s.%s",
                            $this->controller_name,
                            $view_name,
                            $this->extension_view)
                  , $parameter);
  }

  protected function Redirect($url){
    header("Location: $url");
  }
}
