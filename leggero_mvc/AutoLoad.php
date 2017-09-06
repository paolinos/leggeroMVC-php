<?php
/**
 *
 */
class AutoLoad
{
  private $base_path;
  private $class_paths;

  /**
   *  Add base path
   */
  function __construct($path)
  {
    $this->base_path = $path;
    $this->class_paths = [];
    //$this->base_path = __DIR__;
    spl_autoload_register(function($class_name){
      $this->AutoLoadEvent($class_name);
    });
  }

  /**
   *    Add Path
   */
  public function AddPath($path){
    $this->class_paths[] = $path;
  }

  public function AddArrayPath($arrayPath){
    $count = count($arrayPath);
    for ($i=0; $i < $count; $i++) {
      $this->class_paths[] = $arrayPath[$i];
    }
  }

  /**
   *   Set Paths.
   *  @param $paths should be an array that contain 'controller', 'model', 'helper' values,
   *            and also 'others' that should be and array, but is optional.
   */
  public function SetPath($paths)
  {
    $this->class_paths = $paths;
  }

  private function AutoLoadEvent($class_name)
  {
    //TODO: optimize this code
    // We need to check for controllers, models, helpers, and then foreach in other
    $included = false;
    foreach ($this->class_paths as $key => $value) {
      $url = $this->base_path . $value . $class_name .'.php';
      if($this->IncludeFile($url)){
        $included = true;
        break;
      }
    }

    if($included === false){
      //echo("Non exist");
    }
  }

  /**
  * Include the file if exist
  */
  private function IncludeFile($path){
    if(file_exists($path)){
      require_once $path;
      return true;
    }
    return false;
  }

}
