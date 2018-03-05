<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //

if (file_exists(dirname(__FILE__) . '/local.php')) {
	// local database settings
	define('DB_NAME', 'dbname');

	define('DB_USER', 'dbuser');
	
	define('DB_PASSWORD', '123');
	
	define('DB_HOST', 'localhost');
} else {
	// live database settings
	define('DB_NAME', 'amrn1479_universitydata');

	define('DB_USER', 'amrn1479_wp140');

	define('DB_PASSWORD', '123123');

	define('DB_HOST', 'localhost');
}



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
define('AUTH_KEY', 'THl)L,bQ39]p:$2Ob34z|m|<-wv&gpLLBgu3|Xv]hWll-ZEYeQnu=;dp@anO8?u&');
define('SECURE_AUTH_KEY', 'f.Z,-7a. L16OoJuR{NJKf=jDn+Vv,Ec$yAhpY=?vDb@ev)V19eN;.CS4[V]9-PR');
define('LOGGED_IN_KEY', '2540EEW^F3{|eZzY;$rT|k6i|}.CPuJd`8*l_+|hZ-;M}*i7Upz){x`56>4uy!@t');
define('NONCE_KEY', 'E?tq?3wp961&%?AogM_Z}RTI[87!Q6?`=N@iGireP_d4eb7O>HnkXOspwIjVJD2q');
define('AUTH_SALT', 'jpP(&hW-RA)+4-+Wl?sch >(2YA9/ DJbM5IMVlgat=1r_/^u^o!r0R$6pB:%9 f');
define('SECURE_AUTH_SALT', '+&e d#ez|sq@LBR=8-lOYU?$a|. $j+<_+%+Q`ZL%O1x;2xu6JQ_EWNc?BqE^G^Q');
define('LOGGED_IN_SALT', '(%q^#io S~$LRHs1?r[_8mNdf0Z-pa_)[t}A(+Kb?:*|0nQFR(;+Hq Oj[<D>#({');
define('NONCE_SALT', 'iYwd(fC-c_UZXX)OO|T>O4v/4s Z[1Mj+M,C~LI^:D~9Y!eaX|Rt4m))Uyoc1C`N');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
