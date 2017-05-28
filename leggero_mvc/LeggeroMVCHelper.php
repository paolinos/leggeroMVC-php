<?php
/**
 *
 */
class LeggeroMVCHelper
{
  // Layout
  private $layout;
  private $layoutProperties;

  private $view;
  private $viewProperties;

  public function __construct( $_layout )
  {
    $this->layout =  $_layout;
    $this->layoutProperties = [];
  }

  public function Render(){
    ob_start();

    extract($this->layoutProperties);
    require_once $this->layout;

    ob_get_flush();
  }

  public function AddView($_view, $_properties=null)
  {
    $this->view = $_view;
    $this->viewProperties = $_properties;
  }

  public function RenderBody(){
    $props = [];
    //$props['model'] = $this->viewProperties;
    extract($this->viewProperties);
    require_once $this->view;
  }

  public function RenderScripts(){
    
  }

  public function ToDo()
  {
    return "Todo something";
  }
}
