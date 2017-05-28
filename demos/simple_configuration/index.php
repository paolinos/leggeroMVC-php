<?php
/**
 *    LeggeroMVC framework
 *
 *    Simple configuration of LeggeroMVC.
 */
require_once '../../leggero_mvc/LeggeroMVC.php';

//  Set path
LeggeroMVC::SetPath(
  array(
    'controller' => 'app/controllers/',
    'view' => 'app/views/',
    'model' => 'app/models/',
    'helper' => 'app/helpers/',
    'others' => [
      'app/services/',
      'app/repositories/'
      ]
  )
);
//TODO:
//LeggeroMVC::SetRouting();

//  Run Leggero MVC :D
LeggeroMVC::Run();

?>
