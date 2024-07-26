<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$module_name = explode("/", str_replace("ci4_app",'',$_SERVER['REQUEST_URI']));
// echo"<pre>";
// print_r($module_name);
// exit;
if(!empty($module_name[2]) && !empty($module_name[3])){
    $controller = !empty($module_name[2]) ? $module_name[2] : '';
    $action = !empty($module_name[3]) ? $module_name[3] : '';



    if(strpos($module_name[3],'?') !==false){
        $action_arr = explode("?", $module_name[3]);
        $action = $action_arr[0];
        
    }

    $routes->get('/'.$controller.'/'.$action.'', ''.ucfirst($controller).'::'.$action.'');
    $routes->post('/'.$controller.'/'.$action.'', ''.ucfirst($controller).'::'.$action.'');
}
else{
    $routes->get('/', 'Home::index');
}





// $routes->get('/user/list', 'User::list');
// $routes->get('/user/add', 'User::add');
// $routes->post('/user/add', 'User::add');
// $routes->get('/user/manageData', 'User::manageData');
// $routes->post('/user/manageData', 'User::manageData');
