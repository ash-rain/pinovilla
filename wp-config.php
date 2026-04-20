<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pinovilla' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'xG+]1dp.rdL;u!R9@D29l4JgjzECHAd3T~WGa~/1$In<9HJJ8SO~OR4+FG_0!+4y' );
define( 'SECURE_AUTH_KEY',  'N8dArQ2yk}b~Xs$,T==LXzkzdPvQ/0y{3)1K%^^E4I=WIAE:W}~n$7zO4gp1uPG;' );
define( 'LOGGED_IN_KEY',    'P?n_[3E)O@Hm@|<VPs?k};IWE@1%l^R]lu~MBz*SKFkG}.v6!Z=Y6siH(T=UaFeW' );
define( 'NONCE_KEY',        'VoKCk9/N;83`WX(7p`euLYIPF8i#h^:Gn09l6rxE{JNxIH&LhuGN9_ug`s<s@^G:' );
define( 'AUTH_SALT',        'Q%xVW9t.+4S1zPiUCP:!+s]c?$XJyEwyr`Kd|CbI6BnRKHfYoODp*`u:jE{]i~GQ' );
define( 'SECURE_AUTH_SALT', '(i:LO#~ve>BT?>Y`pZy/f#6j_f4LH 6A>tT^Ri_hT#+:2T0#MpMDY?Rn9{M:VL]S' );
define( 'LOGGED_IN_SALT',   'fuB6]pmC%}H-9<?XWkIs<H*`2~UfUZrw%&x%?fQuf]0gftfF1s}NAjSnWKsoy=dt' );
define( 'NONCE_SALT',       '92@<y:#/Aq]9_c$( %]@i@A--92`#dUjx@1w.-!05fx1{E#T^Ksqc;;V$m-iw]{8' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
