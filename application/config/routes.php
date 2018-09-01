<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller']   					= 'welcome';
$route['404_override']         					= '';
$route['translate_uri_dashes'] 					= FALSE;

// change language
$route['language/(:any)']						= 'ajax/change_language/$1';

// Admin Routes
$route['logout']               					= 'auth/logout';
$route['admin']                					= 'admin/dashboard';

// Frontend Routes
$route['cms/faq'] 								= 'cms/faq';
$route['cms/(:any)'] 							= 'cms/index/$1';

$route['blogs'] 								= 'blogs';
$route['blogs/(:any)'] 							= 'blogs/blog/$1';

$route['myblogs'] 								= 'myblogs';
$route['myblogs/add_new_blog'] 					= 'myblogs/form';
$route['myblogs/edit_blog/(:any)'] 				= 'myblogs/form/$1';

$route['courses'] 								= 'courses';
$route['courses/search_categories'] 			= 'courses/search_categories';
$route['courses/(:any)'] 						= 'courses/index/$1';
$route['courses/detail/(:any)']					= 'courses/detail/$1';

$route['events'] 								= 'events';
$route['events/search_categories'] 				= 'events/search_categories';
$route['events/(:any)'] 						= 'events/index/$1';
$route['events/detail/(:any)']					= 'events/detail/$1';

$route['tutors'] 								= 'tutors';
$route['tutors/(:any)'] 						= 'tutors/tutor/$1';

$route['bbooking/get_batches']					= 'bbooking/get_batches';
$route['bbooking/get_net_fees']					= 'bbooking/get_net_fees';
$route['bbooking/get_booked_seats']				= 'bbooking/get_booked_seats';
$route['bbooking/initiate_booking']				= 'bbooking/initiate_booking';
$route['bbooking/payment_method']				= 'bbooking/payment_method';
$route['bbooking/pay_with_paypal']				= 'bbooking/pay_with_paypal';
$route['bbooking/pay_with_stripe']				= 'bbooking/pay_with_stripe';
$route['bbooking/finish_booking']				= 'bbooking/finish_booking';
$route['bbooking/booking_complete']				= 'bbooking/booking_complete';
$route['bbooking/(:any)']						= 'bbooking/index/$1';

$route['ebooking/get_events']					= 'ebooking/get_events';
$route['ebooking/get_net_fees']					= 'ebooking/get_net_fees';
$route['ebooking/get_booked_seats']				= 'ebooking/get_booked_seats';
$route['ebooking/initiate_booking']				= 'ebooking/initiate_booking';
$route['ebooking/payment_method']				= 'ebooking/payment_method';
$route['ebooking/pay_with_paypal']				= 'ebooking/pay_with_paypal';
$route['ebooking/pay_with_stripe']				= 'ebooking/pay_with_stripe';
$route['ebooking/finish_booking']				= 'ebooking/finish_booking';
$route['ebooking/booking_complete']				= 'ebooking/booking_complete';
$route['ebooking/(:any)']						= 'ebooking/index/$1';
