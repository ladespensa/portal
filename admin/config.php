<?php
/**
 * Config
 *
 * Core system configuration file
 *
 * @package		App
 * @author		Ruiz Garcia Jose Carlos
 * @copyright	(c) 2020 GESDES
 * @license		https://www.gesdes.com/license
 *******************************************************************
 */

date_default_timezone_set('America/Mexico_City');


define('ROOT', '/');
define('DIRECTORIO', 'admin');
define('NAME', 'AC MARKET');
define('RAIZ', dirname(__FILE__).'\\');


define('URL_PRODUCTOS','https://acmarket.expressmyapp.com/admin/asociados/productos/');

define('URL_ASOCIADOS','https://acmarket.expressmyapp.com/admin/asociados/');

define('URL_CATEGORIAS','https://acmarket.expressmyapp.com/admin/asociados/categorias/');

define('URL_CATEGORIAS_PRODUCTOS','https://acmarket.expressmyapp.com/admin/asociados/productos/categorias/');

define('URL_PROMOS','https://acmarket.expressmyapp.com/admin/promociones/');




/*********----DATA BASE--------******/
define('HOST','');
define('DB', '');
define('USERDB', '');
define('PWDDB', '');
define('TYPE', 'mssql');


/********* FIREBASE*/


define('KEY_FIREBASE', 'AAAAzWD1XjY:APA91bF00qEhAlcdgb4lkdz4kqhWr-6Bu-VjriFxMBpMf0-5xpSsgVzLbPvQZScx8s-hAuqAgO5GmSJa_qtAfT1J8YRA3Dnx93VtmHs8YKylUXkTZtbra18EFYwW8t092D8_1dgzRE7W');
define('KEY_FIREBASE_REPARTIDOR', 'AAAAzWD1XjY:APA91bF00qEhAlcdgb4lkdz4kqhWr-6Bu-VjriFxMBpMf0-5xpSsgVzLbPvQZScx8s-hAuqAgO5GmSJa_qtAfT1J8YRA3Dnx93VtmHs8YKylUXkTZtbra18EFYwW8t092D8_1dgzRE7W');


?>