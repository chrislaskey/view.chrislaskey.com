<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

//CI Defaults
$route['default_controller'] = "site/pages";
$route['404_override'] = '';

//Admin
$route['login(.*)'] = 'admin/login';
$route['logout(.*)'] = 'admin/login';
$route['admin/capture(.*)'] = 'admin/capture';
$route['admin/remove(.*)'] = 'admin/remove';
$route['admin/test(.*)'] = 'admin/test';

//Admin Catch All
$route['admin(.*)'] = 'admin/pages';

//API Catch All
$route['api(.*)'] = 'api/api';

//Site
$route['set/(.*)'] = 'site/set';
$route['sets(.*)'] = 'site/sets';
$route['sources(.*)'] = 'site/sources';
$route['ajax(.*)'] = 'site/ajax';

//Catch Alls
$route['admin(.*)'] = 'admin/pages';
$route['(.*)'] = 'site/pages';

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */
