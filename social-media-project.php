<?php
/* 
Plugin Name: Social Media Project 
Description: Study Social Media Project.
Version: 1.0 
Author: Sana - Azza
*/

define('PROJECT_PATH', plugin_dir_path(__FILE__));

if (!function_exists('project_init')) {
    function project_init()
    {
        require_once(PROJECT_PATH . 'sql-project/init.php');  // Load sql project
        require_once(PROJECT_PATH . 'json-project/init.php'); // Load json project
    }
}
add_action('plugin_loaded', 'project_init');
