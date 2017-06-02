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

  private $absolutePath;

  public function __construct( $_layout )
  {
    $this->layout =  $_layout;
    $this->layoutProperties = [];
    $this->absolutePath = '';
  }

  /**
    * Set Absolute Path
    *  @param $_absolutePath, with slash at the end!!
    */
  public function SetAbsolutePath($_absolutePath){
    $this->absolutePath = $_absolutePath;
  }
  /**
    * Get absolute path
    * @param $path is optional
    */
  public function GetPath($path=''){
    return $this->absolutePath . $path;
  }

  /**
    * Render layout
    */
  public function Render(){
    ob_start();

    extract($this->layoutProperties);
    require_once $this->layout;

    ob_get_flush();
  }

  public function SetView($_view, $_properties=null)
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
