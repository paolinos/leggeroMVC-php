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

//  Add default Route. Only required
LeggeroMVC::AddRoute( '', 'Main', 'Index', 'Default Routing', true );
//  Routing to tests
LeggeroMVC::AddRoute( 'admin/{action}', 'Admin', 'Index' );
LeggeroMVC::AddRoute( 'logout', 'Main', 'test' );


//  Run Leggero MVC :D
LeggeroMVC::Run();

?>
