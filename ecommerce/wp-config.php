<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('WORDPRESS_DB_NAME') );

/** Database username */
define( 'DB_USER', getenv('WORDPRESS_DB_USER') );

/** Database password */
define( 'DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') );

/** Database hostname */
define( 'DB_HOST', getenv('WORDPRESS_DB_HOST') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         '*>IUC?vr#)yz4JK6!x(AnIDsY!<[xC ;Iv9C#1I$@S96qdrD)@My`~p!_xa:gJ[2' );
define( 'SECURE_AUTH_KEY',  '/vO{rSqQ9j*Cu%rQ5@>y,uk)c#+[h+Fiy/-w9am;y<LtNN?IeJ[rxYg]F)p}4zul' );
define( 'LOGGED_IN_KEY',    'B6:jD8 xN Ae3.<A:whePq7k=!C!;Yc]FcTo?He<1=Dg@RoPP+ XqVQi>A5)LK#,' );
define( 'NONCE_KEY',        '0Lz+P }NEIs-aQk&>cXl}CYX9)Q[!t_{g>k8$YM0x]QNfTFyf%G{kp_Bl/VF1vLq' );
define( 'AUTH_SALT',        'cQGoapt|YdAikJ!TlzIjc6vb/JR6oc<sLjsOAY^NF(sdX=^:;RZ{#.VZFa:3BN+t' );
define( 'SECURE_AUTH_SALT', '^xrb,5VQvwfxj:)a.W=07Tu!/N3u1|qX-],iqh*y;o5K=t^&=sH7ED-2LJpQgFF$' );
define( 'LOGGED_IN_SALT',   'c9e#w62+F(SS+VZy*D<AH8<yFmrC2vD<zFB-bt(ly86a310Hkh4z|@N7;R7u-ZVU' );
define( 'NONCE_SALT',       'P4TG:$rH Dj|HJzTNP$Q~_1fL.jC#{r]urCqwIn&PFiAC%F0$QgzJS5{C!su=._~' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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