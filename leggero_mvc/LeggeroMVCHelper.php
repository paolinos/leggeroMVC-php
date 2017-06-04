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

  private $dynamic;

  private $isProd;

  public function __construct( $_layout, $_dynamicProps, $debug=false )
  {
    $this->isProd = !$debug;
    $this->dynamic = $_dynamicProps;

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

  public function __get($name)
  {
    if($name === 'ViewProp'){
      return $this->dynamic;
    }

    if (array_key_exists($name, $this->viewProperties)) {
      return $this->viewProperties[$name];
    }

    return null;
  }

  /**
    * Render layout
    */
  public function Render(){
    if($this->isProd)
    {
      if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
      	ob_start("ob_gzhandler");
      else
      	ob_start();
    }

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
}
