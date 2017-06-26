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

  // Dynamic Properties
  private $dynamic;

  //  is prod or not
  private $isProd;
  private $useMinFiles;

  // Array with name of scripts js
  private $script_list;


  public function __construct( $_layout, $_dynamicProps, $debug=false, $usemin = false )
  {
    $this->isProd = !$debug;
    $this->useMinFiles = $usemin;
    $this->dynamic = $_dynamicProps;

    $this->layout =  $_layout;
    $this->layoutProperties = [];
    $this->absolutePath = '';

    $this->script_list = [];
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
    * @return path
    */
  public function GetPath($path=''){
    return $this->absolutePath . $path;
  }

  /**
    * Get properties in the view using $this->
    * @param $name: name of the property to get the value
    * @return value or null if not exist.
    */
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

  /**
   *  Add path script without extension
   */
  public function AddScript($url)
  {
    if($this->useMinFiles){
      $url .= ".min";
    }
    $this->script_list[] = $url . ".js";
  }

  public function RenderScripts(){
    $total = count($this->script_list);
    $scriptRendering = '';
    for ($i=0; $i < $total; $i++) {
      $src = $this->script_list[$i];
      $scriptRendering .= '<script type="text/javascript" src="'.$src.'"></script>';
    }
    echo($scriptRendering);
  }
}
