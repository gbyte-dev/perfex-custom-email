<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Custom Email & SMS Notifications
Description: Contact your Customers' contacts or Leads, using Emails/SMSes (templetized or custom ones)
Version: 2.3.2
Requires at least: 2.3.*
Author: Themesic Interactive
Author URI: https://codecanyon.net/user/themesic/portfolio
*/

define('CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME', 'custom_email_and_sms_notifications');

$CI = &get_instance();

hooks()->add_filter('sidebar_custom_email_and_sms_notifications_items', 'app_admin_sidebar_custom_options', 999);
hooks()->add_filter('sidebar_custom_email_and_sms_notifications_items', 'app_admin_sidebar_custom_positions', 998);
hooks()->add_filter('setup_custom_email_and_sms_notifications_items', 'app_admin_custom_email_and_sms_notifications_custom_options', 999);
hooks()->add_filter('setup_custom_email_and_sms_notifications_items', 'app_admin_custom_email_and_sms_notifications_custom_positions', 998);
hooks()->add_filter('module_custom_email_and_sms_notifications_action_links', 'module_custom_email_and_sms_notifications_action_links');
hooks()->add_action('app_admin_footer', 'sms_and_email_assets');
hooks()->add_action('admin_init', 'add_csrf_support');

/**
 * Add CSRF Exclusion Support
 * @return stylesheet / script
 */
function add_csrf_support()
{
	$configfile = FCPATH . 'application/config/config.php';
	$searchforit = file_get_contents($configfile);
	$csrfstring = 'admin/custom_email_and_sms_notifications/email_sms/sendEmailSms';
	
	if(strpos($searchforit,$csrfstring) == false) {
		file_put_contents($configfile, str_replace('$config[\'csrf_exclude_uris\'] = [', '$config[\'csrf_exclude_uris\'] = [\'admin/custom_email_and_sms_notifications/email_sms/sendEmailSms\', ', $searchforit)); 
	}
}

/**
 * Staff login includes
 * @return stylesheet / script
 */
function sms_and_email_assets()
{
    echo '<link href="' . base_url('modules/custom_email_and_sms_notifications/assets/style.css') . '"  rel="stylesheet" type="text/css" >';
	echo '<script src="' . base_url('/modules/custom_email_and_sms_notifications/assets/check.js') . '"></script>';
}

/**
* Add additional settings for this module in the module list area
* @param  array $actions current actions
* @return array
*/
function module_custom_email_and_sms_notifications_action_links($actions)
{
    return $actions;
}
/**
* Load the module helper
*/
$CI->load->helper(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME . '/custom_email_and_sms_notifications');

/**
* Register activation module hook
*/
register_activation_hook(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME, 'custom_email_and_sms_notifications_activation_hook');

function custom_email_and_sms_notifications_activation_hook()
{
	$CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME, [CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME]);

//inject permissions Feature and Capabilities for module
hooks()->add_filter('staff_permissions', 'custom_email_and_sms_notifications_permissions_for_staff');
function custom_email_and_sms_notifications_permissions_for_staff($permissions)
{
    $viewGlobalName      = _l('permission_view').'('._l('permission_global').')';
    $allPermissionsArray = [
        'view'     => $viewGlobalName,
        'create'   => _l('permission_create'),
    ];
    $permissions['custom_email_and_sms_notifications'] = [
                'name'         => _l('sms_module_title'),
                'capabilities' => $allPermissionsArray,
            ];

    return $permissions;
}

hooks()->add_action('admin_init', 'custom_email_and_sms_menuitem');

function custom_email_and_sms_menuitem()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('custom-email-and-sms', [
        'collapse' => true,
            'slug'     => 'main-menu-options',
            'name'     => 'Custom Email/SMS',
            'href'     => admin_url('custom_email_and_sms_notifications/email_sms/email_or_sms'),
            'position' => 65,
            'icon'     => 'fa fa-envelope',
            'badge'    => [],
    ]);


    $CI->app_menu->add_sidebar_children_item('custom-email-and-sms', [
        'slug'     => 'main-menu-options',
        'name'     => 'Send a Notification',
        'href'     => admin_url('custom_email_and_sms_notifications/email_sms/email_or_sms'),
        'position' => 8,
        'badge'    => [],
    ]);


    if (has_permission('custom_email_and_sms_notifications', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('custom-email-and-sms', [
            'slug'     => 'add_edit_templates',
            'name'     => _l('templates'),
            'href'     => admin_url('custom_email_and_sms_notifications/template'),
            'position' => 16,
            'badge'    => [],
        ]);
    }
     $CI->app_menu->add_sidebar_children_item('custom-email-and-sms', [
            'slug'     => 'templates_group',
            'name'     => _l('templates').' Group',
            'href'     => admin_url('custom_email_and_sms_notifications/template/template_group'),
            'position' => 24,
            'badge'    => [],
        ]);
           

}


hooks()->add_action('app_init',CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'_actLib');
function custom_email_and_sms_notifications_actLib()
{
    $CI = & get_instance();
    $CI->load->library(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'/Envapi');
    $envato_res = $CI->envapi->validatePurchase(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME);
    if (!$envato_res) {
        set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
        redirect(admin_url('modules'));
    }
}

hooks()->add_action('pre_activate_module', CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'_sidecheck');
function custom_email_and_sms_notifications_sidecheck($module_name)
{
    if ($module_name['system_name'] == CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME) {
        if (!option_exists(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'_verified') && empty(get_option(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'_verified')) && !option_exists(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'_verification_id') && empty(get_option(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'_verification_id'))) {
            $CI = & get_instance();
            $data['submit_url'] = $module_name['system_name'].'/env_ver/activate'; 
            $data['original_url'] = admin_url('modules/activate/'.CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME); 
            $data['module_name'] = CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME; 
            $data['title'] = "Module activation"; 
            echo $CI->load->view($module_name['system_name'].'/activate', $data, true);
            exit();
        }
    }
}

hooks()->add_action('pre_deactivate_module', CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME.'_deregister');
function custom_email_and_sms_notifications_deregister($module_name)
{
    if ($module_name['system_name'] == CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME) {
        delete_option(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME."_verified");
        delete_option(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME."_verification_id");
        delete_option(CUSTOM_EMAIL_AND_SMS_NOTIFICATIONS_MODULE_NAME."_last_verification");
        if(file_exists(__DIR__."/config/token.php")){
            unlink(__DIR__."/config/token.php");
        }
    }
}