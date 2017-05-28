<?php
/**
 *    LeggeroMVC framework
 *
 *    Simple configuration of LeggeroMVC.
 */
require_once '../../leggero_mvc/LeggeroMVC.php';

//  Set
LeggeroMVC::SetPath(
  array(
    'controller' => '/app/controllers/',
    'view' => 'app/views/',
    'model' => '/app/models/',
    'helper' => '/app/helpers/',
    'others' => []
  )
);
//TODO:
//LeggeroMVC::SetRouting();

//  Run Leggero MVC :D
LeggeroMVC::Run();

?>
