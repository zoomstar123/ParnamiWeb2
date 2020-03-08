<?php
define('DB_NAME', 'parnamiweb2-wp-B536WECf');
define('DB_USER', 'LvNe4NPe0NFH');
define('DB_PASSWORD', 'GkqaMKYYGGoGS8Xe');

define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('AUTH_KEY',         '1Nl1b05aIJ4Qb1u0TWy7FMPiFQ2VGeDYVbzOnz77');
define('SECURE_AUTH_KEY',  'jGphJCaNSujREKO2ZkNUtjJL2F0ZFR4KNDO7WmAH');
define('LOGGED_IN_KEY',    'VIflAMul9NSrLjQP2ahAhffXP2txQVVzDXp7CR9D');
define('NONCE_KEY',        'WrxD76ybXAQOL80SuieoQSuFkGe2tGPq8Ub7XURI');
define('AUTH_SALT',        'RifUTWKymxILCAYkW9zVCpR3d5KsdTZanIPO6S77');
define('SECURE_AUTH_SALT', 'mxutn2p5LUOt9DrqjPpYidHKklCAd0yMLJcr8Vpu');
define('LOGGED_IN_SALT',   'uDMHY7WMC4jtJ1qPDfPy0WgFswOAn5hgz5q3PhXO');
define('NONCE_SALT',       'QFTy0bVmTfYSvLMAaw7PgeeXug75HGqXDDGU7bdX');

$table_prefix  = 'wp_7fcd6591b6_';

define('SP_REQUEST_URL', ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);

define('WP_SITEURL', SP_REQUEST_URL);
define('WP_HOME', SP_REQUEST_URL);

/* Change WP_MEMORY_LIMIT to increase the memory limit for public pages. */
define('WP_MEMORY_LIMIT', '256M');

/* Uncomment and change WP_MAX_MEMORY_LIMIT to increase the memory limit for admin pages. */
//define('WP_MAX_MEMORY_LIMIT', '256M');

/* That's all, stop editing! Happy blogging. */

if ( !defined('ABSPATH') )
        define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'wp-settings.php');
