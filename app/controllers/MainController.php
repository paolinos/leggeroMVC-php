<?php


/**
 *
 */
class MainController
{
  function __construct()
  {
  }

  public function index(){
    LeggeroMVC::RenderString('index');
  }

  public function test(){
    LeggeroMVC::RenderString('test action');
  }

  public function testparams($param1, $param2){
    echo('echo:' . $param1 . '<br>'); 
    echo('echo:' . $param2 . '<br>');
    LeggeroMVC::RenderString('testparams');
  }

}
