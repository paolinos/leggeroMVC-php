<?php
/**
 *    LeggeroController
 *    Base controller
 */
class LeggeroController
{
  private $path;
  private $controller_name;
  private $controller_classname;

  protected $extension_view = 'phtml';

  private $dynamic;

  function __construct($name,$_path)
  {
    $this->dynamic = new Dynamic();

    $this->path = $_path;
    $this->controller_name = $name;
    $this->controller_classname = get_class($this);
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
                  , $parameter);
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
}
