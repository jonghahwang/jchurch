<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
*/

// public
$route[''] = '';
//$route['board/(:any)'] = 'board/index/$1';

/**
 * here :any would be board name such as 'praise'
 * in order to create a new post in a particular board
 * original uri would be /board/post/create
 */
$route['board/(:any)/category/(:any)'] = 'category/$2';
$route['board/(:any)/view/(:any)'] = 'post/view/$2';
$route['board/(:any)/edit/(:any)'] = 'post/edit/$2';
$route['board/(:any)/(:any)'] = 'post/$2';




// admin
//$route['board/(:any)/([a-zA-Z0-9_-]+)']     = 'board/post/$1';


/*
$route['blog/admin/categories(/:any)?']		= 'admin_categories$1';
$route['admin/help/([a-zA-Z0-9_-]+)']       = 'admin/help/$1';
$route['admin/([a-zA-Z0-9_-]+)/(:any)']	    = '$1/admin/$2';

$route['admin/(login|logout)']			    = 'admin/$1';
$route['admin/([a-zA-Z0-9_-]+)']            = '$1/admin/index';


// public
$route['(blog)/(:num)/(:num)/(:any)']	= 'blog/view/$4';
$route['(blog)/page(/:num)?']			    = 'blog/index$2';
$route['(blog)/rss/all.rss']			    = 'rss/index';
$route['(blog)/rss/(:any).rss']		    = 'rss/category/$2';
// admin
$route['blog/admin/categories(/:any)?']		= 'admin_categories$1';


*/