<?php
// file contain const
const _MODULE_DEFAULT = 'home';
const _ACTION_DEFAULT = 'lists';

const _INCODE = true;
// config host
define('_WEB_HOST_ROOT', 'http://' . $_SERVER['HTTP_HOST'] . '/php-learning/module5/users_manager');
define('_WEB_HOST_TEMPLATE', _WEB_HOST_ROOT . '/templates');

// confgi path
define('_WEB_PATH_ROOT', __DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH_ROOT . '/templates');
// database connect
const _HOST = 'localhost';
const _USER = 'root';
const _PASS = '';
const _DB = 'phponline';
const _DRIVER = 'mysql';
