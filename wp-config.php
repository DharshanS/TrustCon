<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
//define( 'WPCACHEHOME', '/home/clickmybooking/public_html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'v1');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'MHhGKcFNCBvrvvi0y66kKU1L2uddk1aku2bbPLodnA1I484KgRlpYzTq3DO2FkUn');
define('SECURE_AUTH_KEY',  'WsC0OxCKOQfydJ2mysbjZkCK98BTMATBXigF8YxXQZ6X73u66ndMwDUf26kmhwYy');
define('LOGGED_IN_KEY',    'Msg0svdvyMpFiWZMksRIo4ytc7nsbB6JlCSRd9ZSH2GiXwBii4hcrglw0OsR34oK');
define('NONCE_KEY',        'g7srYLCuLioyFOEsaSjutKu6Wsq9LBu05kHm2mX6XJTW0nv5nFkoefJ1AJKSeHhc');
define('AUTH_SALT',        '0vhZh7m6uNcCRKNzA8nDn0zH5jO7pCGc4md2fJSIJHBZBtpHJmgdYELDT5Kx57Wl');
define('SECURE_AUTH_SALT', 'jmoKvnfvRbZ2jHGsjUMPtmrT8ThGv1voQ2UltWIUjo0gFAqbo3UkcaCybND3EFRF');
define('LOGGED_IN_SALT',   'I49VOtJhD2ppHSwxN5ILhPwPefKUEVFXs8DDBym9KJLBTIfttDcJ6rZkDG7CHsPt');
define('NONCE_SALT',       '09VZiXdWXIDsFlAgdMwzph7qngtk4L21FCILbkqy6aZy1p1iE06J7NAQXHjcfABt');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', false);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */


 // Enable WP_DEBUG mode
define( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings 


define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', true );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');



