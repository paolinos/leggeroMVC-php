<?php
/**
 *    LeggeroController
 *    Base controller
 */
class LeggeroController
{
  // Default view extension
  protected $extension_view = 'phtml';

  //  layout to load
  protected $layout = '';


  private $path;
  private $controller_name;
  private $controller_classname;

  private $dynamic;

  private $query_string;


  function __construct($name,$_path, $query_string = "")
  {
    $this->dynamic = new Dynamic();

    $this->path = $_path;
    $this->controller_name = $name;
    $this->controller_classname = get_class($this);
    $this->query_string = $query_string;
  }

  public function __get($name){
    if($name === 'ViewProp'){
      return $this->dynamic;
    }
    return null;
  }

  protected function IsPost(){
    return !empty($_POST);
  }

  protected function View($view_name, $parameter=null)
  {
    LeggeroMVC::RenderView(
                        sprintf("%s/%s.%s",
                            $this->controller_name,
                            $view_name,
                            $this->extension_view)
                  , $parameter , $this->layout, $this->extension_view);
  }

  protected function Json($data)
  {
    LeggeroMVC::RenderJson(json_encode($data));
  }


  protected function Redirect($url, $http_Code=302){
    header("Location: " . $this->path . $url,true,$http_Code);
    exit;
    //echo("<br>path: " . $this->path . " - url: $url <br>");
  }
  protected function RedirectToExternalUrl($url){
    header("Location: $url");
  }

  protected function GetPostValue($name){
    $result = null;
    if(isset($_POST[$name])){
      $result = $_POST[$name];
      if(!is_array($result)){
        return trim($result);
      }
    }
    return $result;
  }

  protected function GetQueryString(){
    return $this->query_string;
  }
}
