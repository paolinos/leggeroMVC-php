<?php
/**
 *
 */
class MainController extends LeggeroController
{
  public function index(){

    $model = new MainModel();
    $model->name = "name here";

    $this->View('index', $model);
    //LeggeroMVC::RenderString($model->name);
  }

  public function test(){
    $service = new MainService();

    $ser = new MainService();
    $service->CallService();
    
    LeggeroMVC::RenderString('test action');
  }

  public function testparams($param1, $param2){
    echo('echo:' . $param1 . '<br>');
    echo('echo:' . $param2 . '<br>');
    LeggeroMVC::RenderString('testparams');
  }

}
