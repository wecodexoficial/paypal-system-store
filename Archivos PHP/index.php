<?php
 
date_default_timezone_set('America/Mexico_City');

/**
 * Load The Fat Free Framework version 3.6 core
 */
require_once('vendor/Autoload.php');
$f3 = Base::instance();

/**
 * Load the configs file
 * If your application needs to be user-configurable,
 * F3 provides a handy method for reading configuration files to set up your application.
 * This way, you and your users can tweak the application without altering any PHP code.
 */
$f3->config('config/core/core.ini');

/**
 * Security module load the permission of all application
 */

Permissions::securityModule(true);



/**
 * Error Handler
 */
ErrorHandler::error();

Locales::getLocale();

/**
 * This method load the @Globals to send global parameters to al views
 */
Globals::globasVariablesLoader();

/** Run APPLICATIONS CORE */
$f3->run();
